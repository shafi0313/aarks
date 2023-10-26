<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Profession;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;
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
        if ($error = $this->sendPermissionError('admin.budget.index')) {
            return $error;
        }
        return view('admin.add-edit-entry.budget.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, BusinessPlanAction $trialBalance)
    {
        if ($error = $this->sendPermissionError('admin.budget.create')) {
            return $error;
        }
        $request->validate([
            'client_id'     => 'required',
            'profession_id' => 'required',
            'year'          => 'required',
        ]);
        $client          = Client::findOrFail($request->client_id);
        $profession      = Profession::findOrFail($request->profession_id);
        // $date            = makeBackendCompatibleDate($request->date)->subYear();
        $date            = Carbon::parse('30-Jun-'. $request->year);

        $existingBudgets = BudgetEntry::where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', '>', $date->format('Y-m-d'))
            ->count();
        if ($existingBudgets > 0) {
            return redirect()->back()->with('error', 'You have been selected the date under existing Budget for update,please select last budget date');
        }
        $currentBudgets = BudgetEntry::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', $date->format('Y-m-d'))
            ->get()
            ->groupBy(fn ($item) => substr($item->chart_id, -6, 1));
        if ($currentBudgets->count() > 0) {
            $CRetains =  pl($client, $profession, $date);
            return view('admin.add-edit-entry.budget.current', compact('currentBudgets', 'client', 'profession', 'date', 'CRetains'));
            return 'Current budget ';
        }
        // return
        $data       = $trialBalance->budgetEntry($request, $client, $profession);
        // return isset($data['ledgers'][2]);
        if (isset($data['ledgers'][1]) || isset($data['ledgers'][2])) {
            return view('admin.add-edit-entry.budget.create', $data);
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
        if ($error = $this->sendPermissionError('admin.budget.create')) {
            return $error;
        }

        $request->validate([
            'client_id'     => 'required',
            'profession_id' => 'required',
            'date'          => 'required|date',
            'entries.*'     => 'required|array',
        ]);
        try {
            $action->store($request);
            return redirect()->route('budget.index')->with('success', 'Budget Entry Created Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $budgetEntry, BudgetAction $action)
    {
        if ($error = $this->sendPermissionError('admin.budget.edit')) {
            return $error;
        }
        $request->validate([
            'client_id'     => 'required',
            'profession_id' => 'required',
            'date'          => 'required|date',
            'entries.*'     => 'required|array',
        ]);

        try {
            $action->update($request);
            return redirect()->route('budget.index')->with('success', 'Budget Entry Created Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
            return $e->getMessage();
        }
    }
}
