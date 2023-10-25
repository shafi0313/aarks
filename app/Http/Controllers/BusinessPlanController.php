<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Profession;
use App\Models\BudgetEntry;
use App\Models\BusinessPlan;
use Illuminate\Http\Request;
use App\Models\ClientAccountCode;
use App\Models\AccountCodeCategory;
use App\Actions\Reports\TrialBalance;
use RealRashid\SweetAlert\Facades\Alert;
use App\Actions\DataStore\BusinessPlanAction;

class BusinessPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->sendPermissionError('admin.business_plan.index')) {
            return $error;
        }
        return view('admin.add-edit-entry.business-plan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, BusinessPlanAction $action)
    {
        // return substr(123456, -6, 1);
        if ($error = $this->sendPermissionError('admin.business_plan.create')) {
            return $error;
        }
        $request->validate([
            'client_id'     => 'required',
            'profession_id' => 'required',
            'year'          => 'required',
        ]);
        $date       = Carbon::parse('30-Jun-'. $request->year);
        $client     = Client::findOrFail($request->client_id);
        $profession = Profession::findOrFail($request->profession_id);

        $lastYear = makeBackendCompatibleDate('01/06/'.$request->year-1);
        $months = [];
        foreach (range(1, 12) as $month) {
            $lastYear->addMonth();
            $months[] = $lastYear->format('F') .' '. $lastYear->format('y') + 1 ;
        }
        // return $months;
        // return $months = array_map(function () { return 0; }, array_flip($months));

        $currentPlans = BusinessPlan::with(['chart' => fn ($q) => $q->where('client_id', $client->id)])->where('client_id', $client->id)
            ->where('profession_id', $profession->id)
            ->where('date', $date->format('Y-m-d'))
            ->get()
            ->groupBy(fn ($item) => substr($item->chart_id, -6, 1));
        if ($currentPlans->count() > 0) {
            $plan = $currentPlans->first()->first();
            if (!$plan) {
                Alert::error('We are unable to find a plan');
                return redirect()->back();
            }
            $pl =  pl($client, $profession, $date);
            return view('admin.add-edit-entry.business-plan.current', compact('currentPlans', 'client', 'profession', 'date', 'pl', 'months', 'plan'));
            return 'Current budget ';
        }
        // return
        $data       = $action->budget($request, $client, $profession);
        // return ($data['ledgers'][5]);
        if (isset($data['ledgers'][1]) || isset($data['ledgers'][2])) {
            return view('admin.add-edit-entry.business-plan.create', $data, ['months' => $months]);
        }
        $codes = ClientAccountCode::where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->where(function ($query) {
            $query->where('code', 'like', '1%')
                  ->orWhere('code', 'like', '2%');
        })
        ->orderBy('code')
        ->get()
        ->groupBy(fn ($item) => substr($item->code, -6, 1));
        return view('admin.add-edit-entry.business-plan.custom.create', compact('client', 'profession', 'date', 'codes', 'months'));
        return redirect()->back()->with('error', 'No data found for this client and profession');
    }

    public function createLikeMasterChart(Request $request, TrialBalance $trialBalance)
    {
        if ($error = $this->sendPermissionError('admin.business_plan.create')) {
            return $error;
        }

        $request->validate([
            'client_id'     => 'required',
            'profession_id' => 'required',
            'date'          => 'required|date_format:d/m/Y',
        ]);
        $client     = Client::findOrFail($request->client_id);
        $profession = Profession::findOrFail($request->profession_id);
        $date = makeBackendCompatibleDate($request->date)->subYear();

        $lastYear = makeBackendCompatibleDate('01/06/'.$date->subYear()->format('Y'));
        $months = [];
        foreach (range(1, 12) as $month) {
            $lastYear->addMonth();
            $months[] = $lastYear->format('F') .' '. $lastYear->format('y');
        }
        // return $months;

        // return
        $data       = $trialBalance->budget($request, $client, $profession);
        // return ($data['ledgers'][5]);
        if (isset($data['ledgers'][1]) || isset($data['ledgers'][2])) {
            return view('admin.add-edit-entry.business-plan.create', $data, ['months' => $months]);
        }
        $codes = ClientAccountCode::where('client_id', $client->id)
        ->where('profession_id', $profession->id)
        ->where(function ($query) {
            $query->where('code', 'like', '1%')
                  ->orWhere('code', 'like', '2%');
        })
        ->orderBy('code')
        ->get();
        // ->groupBy(fn ($item) => substr($item->code, -6, 1));
        $accountCategories = AccountCodeCategory::with('subCategory', 'industryCategories', 'subCategoryWithoutAdditional')
        ->where('type', 1)->where(function ($q) {
            $q->where('code', 'like', '1%')
                ->orWhere('code', 'like', '2%');
        })->whereNull('parent_id')->orderBy('code', 'asc')->get();

        return view('admin.add-edit-entry.business-plan.custom.create', compact('client', 'profession', 'date', 'codes', 'months', 'accountCategories'));
        return redirect()->back()->with('error', 'No data found for this client and profession');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BusinessPlanAction $action)
    {
        if ($error = $this->sendPermissionError('admin.business_plan.create')) {
            return $error;
        }
        $request->validate([
            'client_id'     => 'required',
            'profession_id' => 'required',
            'date'          => 'required|date',
            'entries'       => 'required|array',
            'entries.*.chart_id' => 'required|string|integer',
        ]);
        try {
            $action->store($request);
            return redirect()->route('business-plan.index')->with('success', 'Business Plan Created Successfully');
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
    public function update(Request $request, $plan, BusinessPlanAction $action)
    {
        if ($error = $this->sendPermissionError('admin.business_plan.edit')) {
            return $error;
        }
        $request->validate([
            'client_id'          => 'required',
            'profession_id'      => 'required',
            'date'               => 'required|date',
            'entries'            => 'required|array',
        ]);
        try {
            $plan = BusinessPlan::findOrFail($plan);
            $action->update($request, $plan);

            return redirect()->route('business-plan.index')->with('success', 'Business Plan Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
            return $e->getMessage();
        }
    }
}
