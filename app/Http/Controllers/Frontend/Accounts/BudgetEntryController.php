<?php

namespace App\Http\Controllers\Frontend\Accounts;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Profession;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\DataStore\BudgetAction;
use App\Actions\DataStore\BusinessPlanAction;

class BudgetEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.accounts.budget.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create(Request $request, TrialBalance $trialBalance)
    public function create(Request $request, BusinessPlanAction $trialBalance)
    {
        // return substr(123456, -6, 1);
        $request->validate([
            'profession_id' => 'required',
            'year'          => 'required',
        ]);
        $client          = Client::findOrFail(client()->id);
        $profession      = Profession::findOrFail($request->profession_id);
        // $date            = makeBackendCompatibleDate($request->date)->subYear();
        $date            = Carbon::parse('30-Jun-'. $request->year);

        $existingBudgets = BudgetEntry::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '>', $date->format('Y-m-d'))
            ->count();

        if ($existingBudgets > 0) {
            toast('You have been selected the date under existing Budget for update,please select last budget date', 'error');
            return redirect()->back();
        }

        $currentBudgets = BudgetEntry::with(['chart' => fn($q) => $q->where('client_id', $client->id)])->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', $date->format('Y-m-d'))
            ->get()
            ->groupBy(fn($item) => substr($item->chart_id, -6, 1));

        if ($currentBudgets->count() > 0) {
            $CRetains =  pl($client, $profession, $date);
            return view('frontend.accounts.budget.current', compact('currentBudgets', 'client', 'profession', 'date', 'CRetains'));
            return 'Current budget ';
        }
        // return
        $data = $trialBalance->budgetEntry($request, $client, $profession);

        if (isset($data['ledgers'][1]) || isset($data['ledgers'][2])) {
            return view('frontend.accounts.budget.create', $data);
        }
        return redirect()->back()->with('error', 'No data found for this client and profession');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BudgetAction $action)
    {
        // return $request;
        $request->validate([
            'client_id'     => 'required',
            'profession_id' => 'required',
            'date'          => 'required|date',
            'entries.*'     => 'required|array',
        ]);
        try {
            $action->store($request);
            toast('Budget Entry Created Successfully', 'success');
            return redirect()->route('client-budget.index');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
            return redirect()->back();
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BudgetEntry  $budgetEntry
     * @return \Illuminate\Http\Response
     */
    public function show(BudgetEntry $budgetEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BudgetEntry  $budgetEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(BudgetEntry $budgetEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $budgetEntry, BudgetAction $action)
    {
        // return
        // $request;
        $request->validate([
            'client_id'                => 'required',
            'profession_id'            => 'required',
            'date'                     => 'required|date',
            'entries.*'                  => 'required|array',
        ]);

        try {
            $action->update($request);
            toast('Budget Entry Updated Successfully', 'success');
            return redirect()->route('client-budget.index');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
            return redirect()->back();
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BudgetEntry  $budgetEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(BudgetEntry $budgetEntry)
    {
        //
    }
}
