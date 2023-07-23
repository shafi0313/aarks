<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Client;
use App\Models\Profession;
use App\Models\StandardLeave;
use App\Models\StandardWages;
use App\Models\ClientAccountCode;
use App\Models\StandardDeducation;
use App\Http\Controllers\Controller;
use App\Models\Frontend\ClientLeave;
use App\Models\Frontend\ClientWages;
use App\Models\Frontend\EmployeeCard;
use App\Models\StandardSuperannuation;
use App\Models\Frontend\ClientDeduction;
use App\Models\Frontend\EClassification;
use App\Http\Requests\UpdateEmployeeRequst;
use App\Models\Frontend\ClientSuperannuation;
use App\Http\Requests\AddEmployeeCardsRequest;

class EmployeeCardController extends Controller
{
    // add_card
    public function add_card_select_activity()
    {
        $client = Client::with('professions')->where('id', client()->id)->first();
        return view('frontend.add_card.select_activity', compact('client'));
    }
    public function add_card_select_type(Profession $profession)
    {
        return view('frontend.add_card.select_type', compact('profession'));
    }
    public function add_card_create(Profession $profession)
    {
        return view('frontend.add_card.create', compact('profession'));
    }
    public function add_card_employee(Profession $profession)
    {
        $client      = client()->id;
        $ECS         = EClassification::where('client_id', $client)->get();
        $wages       = ClientWages::where('client_id', $client)->get();
        $leaves      = ClientLeave::where('client_id', $client)->get();
        $sups        = ClientSuperannuation::where('client_id', $client)->get();
        $deducs      = ClientDeduction::where('client_id', $client)->get();
        $standWages  = StandardWages::all();
        $standLeaves = StandardLeave::all();
        $standDeducs = StandardDeducation::all();
        $standSups   = StandardSuperannuation::all();
        $codes       = ClientAccountCode::where('client_id', $client)->where('profession_id', $profession->id)->get();
        return view('frontend.add_card.employee', compact('client', 'ECS', 'wages', 'leaves', 'sups', 'standWages', 'standLeaves', 'standDeducs', 'standSups', 'deducs', 'profession', 'codes'));
    }
    public function store(AddEmployeeCardsRequest $request)
    {
        $data             = $request->validated();
        $data['wages']          = json_encode($data['wages']);
        $data['leave']          = json_encode($data['leave']);
        $data['deduction']      = json_encode($data['deduction']);
        $data['superannuation'] = json_encode($data['superannuation']);
        try {
            EmployeeCard::create($data);
            toast('Employee Created!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
    public function view($profession)
    {
        $client = client()->id;
        $employees = EmployeeCard::where('client_id', $client)->where('profession_id', $profession)->get();
        return view('frontend.card_list.employee', compact('employees'));
    }
    public function edit(EmployeeCard $employee)
    {
        $client      = client()->id;
        $ECS         = EClassification::where('client_id', $client)->get();
        $wages       = ClientWages::where('client_id', $client)->get();
        $leaves      = ClientLeave::where('client_id', $client)->get();
        $sups        = ClientSuperannuation::where('client_id', $client)->get();
        $deducs      = ClientDeduction::where('client_id', $client)->get();
        $standWages  = StandardWages::all();
        $standLeaves = StandardLeave::all();
        $standDeducs = StandardDeducation::all();
        $standSups   = StandardSuperannuation::all();

        return view('frontend.card_list.update_employee', compact('employee', 'client', 'ECS', 'wages', 'leaves', 'sups', 'standWages', 'standLeaves', 'standDeducs', 'standSups', 'deducs'));
    }
    public function update(UpdateEmployeeRequst $request, EmployeeCard $employee)
    {
        $data             = $request->validated();
        try {
            $employee->update($data);
            toast('Employee Updated!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('employee.view', $employee->profession_id);
    }
    public function delete(EmployeeCard $employee)
    {
        try {
            $employee->delete();
            toast('Employee Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
}
