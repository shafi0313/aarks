<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Client;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\Frontend\Payslip;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\PayAccumAmt;
use App\Models\Frontend\EmployeeCard;
use App\Models\Frontend\PreparePayroll;

class PreparePayrollController extends Controller
{
    public function index()
    {
        $client = Client::findOrFail(client()->id);
        return view('frontend.payroll.prepare_payroll.select_activity', compact('client'));
    }

    public function empselect(Client $client, Profession $profession)
    {
        return view('frontend.payroll.prepare_payroll.select_date', compact('client', 'profession'));
    }


    public function payemp(Request $r) //Pay Emp selete base on Pay frequesncy
    {
        $emps = EmployeeCard::where('pay_frequency', $r->pay_period)->where('client_id', $r->client_id)->where('profession_id', $r->profession_id)->get();
        $html = '';
        $html .= '<select class="form-control employecss" name="employeid" id="employeid">';
        $html .=  '<option selected disabled>Select Employee</option>';
        foreach ($emps as $emp) {
            $html .=  '<option value="'.$emp->id.'">'.$emp->fullname.'</option>';
        }
        $html .=  '</select>';
        return json_encode(['status'=>200,'html'=>$html]);
    }

    public function emplist(Request $r)
    {
        DB::table('prepare_payrolls')->delete();
        if ($r->has('process')) {
            if ($r->get('process') == 'all') {
                $employees = EmployeeCard::where('pay_frequency', $r->pay_period)->where('client_id', $r->client_id)->where('profession_id', $r->profession_id)->get();
            } else {
                $employees = EmployeeCard::where('id', $r->employeid)->get();
            }

            foreach ($employees as $employee) {
                $tran_id = $r->client_id.$r->profession_id.$employee->id.Carbon::parse($r->payment_date)->format('dmy').rand(11, 99);
                DB::beginTransaction();

                foreach (json_decode($employee->wages) as $wage) {
                    $stanWages = StandardWages::where('name', $wage)->first();
                    if ($stanWages == null) {
                        $stanWages = ClientWages::where('name', $wage)->first();
                    }
                    $wages = [
                        'name'    => $wage,
                        'hours'   => $stanWages->hourly_rate,
                        'amount'  => 0,
                        'rate'    => $stanWages->regular_rate,
                        'tran_id' => $tran_id,
                    ];
                    PayslipWages::create($wages);
                }
                foreach (json_decode($employee->deduction) as $dedc) {
                    $deduc = [
                        'name'    => $dedc,
                        'amount'  => 0,
                        'tran_id' => $tran_id,
                    ];
                    PayslipDeduc::create($deduc);
                }
                foreach (json_decode($employee->leave) as $leaves) {
                    $leave = [
                        'name'    => $leaves,
                        'amount'  => 0,
                        'tran_id' => $tran_id,
                    ];
                    PayslipLeave::create($leave);
                }
                foreach (json_decode($employee->superannuation) as $supers) {
                    $super = [
                        'name'    => $supers,
                        'amount'  => 0,
                        'tran_id' => $tran_id,
                    ];
                    PayslipSuper::create($super);
                }
                // PayAccum Amount Table cal
                $data = [
                    "client_id"        => $r->client_id,
                    "profession_id"    => $r->profession_id,
                    "employee_card_id" => $employee->id,
                    "tran_id"          => $tran_id,
                    "source"           => 'PAY',
                    "pay_period"       => $r->pay_period,
                ];

                $data['payment_date']    = $data['tran_date'] = $r->payment_date;
                $data['payperiod_start'] = $r->pay_period_start;
                $data['payperiod_end']   = $r->pay_period_end;
                $payDate = Carbon::parse($r->payment_date);
                if ($payDate->format('m') >= 07 & $payDate->format('m') <= 12) {
                    $data['financial_year'] = $payDate->format('Y');
                } else {
                    $data['financial_year'] = $payDate->format('Y')-1;
                }
                $data['gross']        = $employee->hourly_rate * $employee->hour_pay_frequency;
                $data['director_fee'] = 0;
                $data['overtime']     = 0;
                $data['allowence']    = 0;
                $data['bonus']        = 0;
                $data['lump']         = 0;
                $data['etp']          = 0;
                $data['salary']       = 0;
                $data['exempt']       = 0;
                $data['cdep']         = 0;
                $data['secriface']    = 0;
                $data['payg']         = $this->paygCal($employee->pay_frequency, $data['gross'], $employee->tax_table);
                $data['wages']        = 0;
                $data['deduc']        = 0;
                $data['annual']       = 0;
                $data['personal']     = 0;
                $data['super']        = 0;
                $data['net_pay']      = $data['gross'] - $data['payg'];
                try {
                    PreparePayroll::create($data);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return $e->getMessage();
                }
            }
            $payProcess = PreparePayroll::with('employee_card')->get();
            return view('frontend.payroll.prepare_payroll.emplist', compact('payProcess', 'employees'));
        } else {
            toast('Please Select an employee', 'warning');
            return back();
        }
        // return $emp['process'];
    }
    public function preedit(EmployeeCard $empCard, PreparePayroll $prepay)
    {
        $coefficent = Coefficient::first();
        $client_id =client()->id;
        $payAmt = PayAccumAmt::select(DB::raw('sum(wages) as wages,sum(super) as super'))->whereMonth('tran_date', $prepay->payment_date->format('m'))->where('client_id', $client_id)->where('employee_card_id', $empCard->id)->first();
        $grossPayCal = 0;
        if ($payAmt->wages != null) {
            if ($payAmt->wages >= 450 && $payAmt->super > 0) {
                $grossPayAmt = 0;
                $grossPayCal = $payAmt->wages;
            } else {
                $grossPayAmt = $payAmt->wages;
            }
        } else {
            $grossPayAmt = 0;
        }
        // return $grossPayAmt;
        return view('frontend.payroll.prepare_payroll.employee_edit', compact('grossPayCal', 'grossPayAmt', 'empCard', 'prepay','coefficent'));
    }
    public function emplist_redirect()
    {
        $payProcess = PreparePayroll::with('employee_card')->get();
        return view('frontend.payroll.prepare_payroll.emplist', compact('payProcess'));
    }
    public function payg(Request $r)
    {
        // return $r;
        $r->validate([
            'amount'       => 'required|numeric',
            'payFrequency' => 'required|string',
            'taxTable'     => 'required|string'
        ]);
        if ($r->payFrequency == 'Weekly') {
            $coefficent =  $this->taxtable($r->taxTable, $r->amount);
            $payg = ceil(($r->amount* $coefficent->per_dollar_a) - $coefficent->per_dollar_b);
        } elseif ($r->payFrequency == 'Fortnightly') {
            $amount = $r->amount/2;
            $coefficent =  $this->taxtable($r->taxTable, $amount);
            $payg = ceil((($amount * $coefficent->per_dollar_a) - $coefficent->per_dollar_b)*2);
        } elseif ($r->payFrequency == 'Monthly') {
            $amount = ($r->amount*12)/52;
            $coefficent =  $this->taxtable($r->taxTable, $amount);
            $pay = ($amount * $coefficent->per_dollar_a) - $coefficent->per_dollar_b;
            $payg = ceil(($pay*52)/12);
        }
        return json_encode(['status'=>200,'payg'=>$payg,'coefficent'=>$coefficent]);
    }
    public function paygCal($payFrequency, $ramount, $taxTable)
    {
        if ($payFrequency == 'Weekly') {
            $coefficent =  $this->taxtable($taxTable, $ramount);
            return ceil(($ramount* $coefficent->per_dollar_a) - $coefficent->per_dollar_b);
        } elseif ($payFrequency == 'Fortnightly') {
            $amount = $ramount/2;
            $coefficent =  $this->taxtable($taxTable, $amount);
            return ceil((($amount * $coefficent->per_dollar_a) - $coefficent->per_dollar_b)*2);
        } elseif ($payFrequency == 'Monthly') {
            $amount = ($ramount*12)/52;
            $coefficent =  $this->taxtable($taxTable, $amount);
            $pay = ($amount * $coefficent->per_dollar_a) - $coefficent->per_dollar_b;
            return ceil(($pay*52)/12);
        }
    }
    public function taxtable($taxTable, $amount)
    {
        if ($taxTable == 'No tax-free threshold') {
            return  Coefficient::where('holding_type', 'Scale 1')->where('weekly_earning', '>=', $amount)->first();
        } elseif ($taxTable == 'Tax-free threshold') {
            return  Coefficient::where('holding_type', 'Scale 2')->where('weekly_earning', '>=', $amount)->first();
        } elseif ($taxTable == 'Non Resident/Foregin resident' || $taxTable == 'Tax file not decleared residence' || $taxTable == 'Tax file not decleared non residence') {
            return  Coefficient::where('holding_type', 'Scale 3')->where('weekly_earning', '>=', $amount)->first();
        } elseif ($taxTable == 'Full medicare levy Exemption') {
            return  Coefficient::where('holding_type', 'Scale 5')->where('weekly_earning', '>=', $amount)->first();
        } elseif ($taxTable == 'Half medicare levy Exemption') {
            return  Coefficient::where('holding_type', 'Scale 6')->where('weekly_earning', '>=', $amount)->first();
        } else {
            return "NULL";
        }
    }

    public function payslipStore(Request $request)
    {
        $payProcess = PreparePayroll::whereIn('id',$request->empid)->get();
        foreach ($payProcess as $process) {
            DB::beginTransaction();
            $payAccum =  PayAccumAmt::create($process->toArray());
            $data = [
                'pay_accum_amt_id' => $payAccum->id,
                'tran_id'          => $payAccum->tran_id,
                'tran_date'        => $payAccum->tran_date,
                'client_id'        => $payAccum->client_id,
                'employee_card_id' => $payAccum->employee_card_id,
            ];
            $employee = EmployeeCard::findOrFail($payAccum->employee_card_id);
            $chart_id = [
                '245101','551800','245103','912200','245200','913100'
            ];
            $codes = ClientAccountCode::whereIn('code', $chart_id)
                        ->where('client_id',$payAccum->client_id)
                        ->where('profession_id',$payAccum->profession_id)
                        ->get();
            $ledger['date']                   = $payAccum->tran_date;
            $ledger['narration']              = "PAYSLIP NARRATION";
            $ledger['source']                 = 'PAY';
            $ledger['client_id']              = $employee->client_id;
            $ledger['profession_id']          = $employee->profession_id;
            $ledger['transaction_id']         = $payAccum->tran_id;
            $ledger['balance'] = $ledger['gst'] = $ledger['debit'] = $ledger['credit'] = 0;
            foreach ($codes as $code) {
                $ledger['chart_id'] = $code->code;
                $ledger['client_account_code_id'] = $code->id;
                switch ($code->code) {
                    case '245101':
                        $ledger['balance']  = $ledger['debit'] = $payAccum->net_pay;
                        $ledger['credit'] = 0;
                        $ledger['balance_type']           = 1;
                        break;
                    case '551800':
                        $ledger['balance']  = $ledger['credit'] = $payAccum->net_pay;
                        $ledger['debit'] = 0;
                        $ledger['balance_type']           = 2;
                        break;
                    case '245103':
                        $ledger['balance']  = $ledger['debit'] = $payAccum->wages;
                        $ledger['credit'] = 0;
                        $ledger['balance_type']           = 1;
                        break;
                    case '912200':
                        $ledger['balance']  = $ledger['credit'] = $payAccum->wages;
                        $ledger['debit'] = 0;
                        $ledger['balance_type']           = 2;
                        break;
                    case '245200':
                        $ledger['balance']  = $ledger['debit'] = $payAccum->super;
                        $ledger['credit'] = 0;
                        $ledger['balance_type']           = 1;
                        break;
                    case '913100':
                        $ledger['balance']  = $ledger['credit'] = $payAccum->super;
                        $ledger['debit'] = 0;
                        $ledger['balance_type']           = 2;
                        break;
                    default:
                        $ledger['balance']  = $ledger['debit'] = 0;
                        break;
                }
                GeneralLedger::create($ledger);
            }
            // Retain Calculations
            $periodStartDate = $payAccum->financial_year.'-07-01';
            $periodEndDate   = $payAccum->financial_year+1 .'-06-30';

            $retainData = GeneralLedger::select(DB::raw('sum(balance) as balance'))
            ->where('date', '>=', $periodStartDate)
            ->where('date', '<=', $periodEndDate)
            ->where('chart_id', 'LIKE', '2%')
            ->where('client_id', $employee->client_id)
            ->where('profession_id', $employee->profession_id)
            ->where('transaction_id', $payAccum->tran_id)
            ->first();

            $ledger['chart_id'] = 999999;
            $ledger['client_account_code_id'] = 0;
            $ledger['balance_type']           = 1;
            $ledger['credit']           = 0;
            $ledger['balance'] = $ledger['debit'] =  $retainData->balance;
            GeneralLedger::create($ledger);

            //PL Retain Calculations

            $plData = GeneralLedger::select(DB::raw('sum(balance) as balance'))
            ->where('chart_id', 'LIKE', '2%')
            ->where('client_id', $employee->client_id)
            ->where('profession_id', $employee->profession_id)
            ->where('transaction_id', $payAccum->tran_id)
            ->first();

            $ledger['chart_id'] = 999998;
            $ledger['balance'] = $ledger['debit'] =  $plData->balance;

            GeneralLedger::create($ledger);
            // return $ledger;
            try {
                Payslip::create($data);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                toast($e->getMessage(), '');
            }
        }

        DB::table('prepare_payrolls')->delete();

        return redirect()->route('payslip.index');
    }

    public function payslipIndex(Request $request)
    {
        $client = client()->id;
        $payslips = Payslip::where('client_id', $client)->orderBy('id', 'desc')->get();
        return view('frontend.payroll.payslip.index', compact('payslips'));
    }
    public function payslipFilter(Request $request)
    {
        $date_from =  makeBackendCompatibleDate($request->date_from);
        $date_to =  makeBackendCompatibleDate($request->date_to);
        // return
        $payslips = Payslip::where('tran_date', '>=', $date_from)->where('tran_date', '<=', $date_to)->orderBy('id', 'desc')->get();
        return view('frontend.payroll.payslip.index', compact('payslips'));
    }

    public function payslipReport($tran_id, PayAccumAmt $pay_accum)
    {
        // return $pay_accum;
        $yearTo = PayAccumAmt::where('financial_year', $pay_accum->financial_year)
                ->where('tran_id', $pay_accum->tran_id)
                ->where('profession_id', $pay_accum->profession_id)
                ->where('client_id', $pay_accum->client_id)
                ->where('employee_card_id', $pay_accum->employee_card_id)->get();

        $payWages = PayslipWages::where('tran_id', $tran_id)->where('status', 1)->get();
        $payDeduc = PayslipDeduc::where('tran_id', $tran_id)->where('status', 1)->get();
        $payLeave = PayslipLeave::where('tran_id', $tran_id)->where('status', 1)->get();
        $paySuper = PayslipSuper::where('tran_id', $tran_id)->where('status', 1)->get();

        return view('frontend.payroll.payslip.report', compact('yearTo', 'pay_accum', 'payWages', 'payDeduc', 'payLeave', 'paySuper'));
    }
    public function payslipEdit(Payslip $payslip, $tran_id)
    {
        $payAmt = PayAccumAmt::select(DB::raw('sum(wages) as wages,sum(super) as super'))->whereMonth('tran_date', $payslip->tran_date->format('m'))->where('client_id', $payslip->client_id)->where('employee_card_id', $payslip->employee_card_id)->first();
        $grossPayCal = 0;
        if ($payAmt->wages != null) {
            if ($payAmt->wages >= 450 && $payAmt->super > 0) {
                $grossPayAmt = 0;
                $grossPayCal = $payAmt->wages;
            } else {
                $grossPayAmt = $payAmt->wages;
            }
        } else {
            $grossPayAmt = 0;
        }

        //   return
        $payWages = PayslipWages::where('tran_id', $tran_id)->where('status', 1)->get();
        $payDeduc = PayslipDeduc::where('tran_id', $tran_id)->where('status', 1)->get();
        $payLeave = PayslipLeave::where('tran_id', $tran_id)->where('status', 1)->get();
        $paySuper = PayslipSuper::where('tran_id', $tran_id)->where('status', 1)->get();
        return view('frontend.payroll.payslip.update', compact('payslip', 'payWages', 'payDeduc', 'payLeave', 'paySuper', 'grossPayAmt', 'grossPayCal', 'payAmt'));
    }
    public function payslipUpdate(Request $request, Payslip $payslip, $tran_id)
    {
        // return $request;
        $data = $request->validate([
            "gross_pay"           => "required",
            "withholdingpay"      => "required",
            "deduc"               => "required",
            "personal_leave_ac"   => "required",
            "annual_leave_acrual" => "required",
            "superAmt"            => 'required',
            "net_pay"             => "required",
        ]);
        DB::beginTransaction();
        $salary = $overtime=$bonus=$allowence=$director=$lump=$ETP=$CDEP=$secriface=$exempt= 0;
        foreach ($request->wagesid as $i=>$wage) {
            $payProcessWages = PayslipWages::where('name', $wage)->where('tran_id', $request->tran_id)->first();
            $stanWages = StandardWages::where('name', $wage)->first();
            if ($stanWages == null) {
                $stanWages = ClientWages::where('name', $wage)->first();
            }
            switch ($stanWages->link_group) {
                case 1:
                    $salary += $request->hourly_amt[$i];
                    break;
                case 2:
                    $overtime += $request->hourly_amt[$i];
                    break;
                case 3:
                    $bonus += $request->hourly_amt[$i];
                    break;
                case 4:
                    $allowence += $request->hourly_amt[$i];
                    break;
                case 5:
                    $director += $request->hourly_amt[$i];
                    break;
                case 6:
                    $lump += $request->hourly_amt[$i];
                    break;
                case 7:
                    $ETP += $request->hourly_amt[$i];
                    break;
                case 8:
                    $CDEP += $request->hourly_amt[$i];
                    break;
                case 9:
                    $secriface += $request->hourly_amt[$i];
                    break;
                default:
                    $exempt += $request->hourly_amt[$i];
                    break;
            }
            $wages = [
                'hours'   => $request->emphour[$i],
                'amount'  => $request->hourly_amt[$i],
                'rate'    => $request->hourlyamount[$i],
            ];
            $payProcessWages->update($wages);
        }
        foreach ($request->deduc_id as $i=>$dedc) {
            $payProcessDeduc = PayslipDeduc::where('name', $dedc)->where('tran_id', $request->tran_id)->first();
            $deduc = [
                'amount'  => $request->deduc_amt[$i],
                'rate'    => $request->deduc_rate[$i],
                'fix_amt' => $request->has('fix_amt')==true?$request->fix_amt[$i]:'',
            ];
            $payProcessDeduc->update($deduc);
        }
        foreach ($request->leave_id as $i=>$leaves) {
            $payProcessLeave = PayslipLeave::where('name', $leaves)->where('tran_id', $request->tran_id)->first();

            $leave = [
                'amount'  => $request->leave_acrual[$i],
                'rate'    => $request->leave_rate[$i],
            ];
            $payProcessLeave->update($leave);
        }
        foreach ($request->super_id as $i=>$supers) {
            $payProcessSuper = PayslipSuper::where('name', $supers)->where('tran_id', $request->tran_id)->first();

            $super = [
                'amount'  => $request->super[$i]
            ];
            $payProcessSuper->update($super);
        }


        $emp = PayAccumAmt::where('employee_card_id', $request->employee_card_id)->get();
        if ($emp->count() > 0) {
            $data['gross']              = $request->gross_pay;
            $data['accum_gross']        = $request->gross_pay + $emp->sum('gross');
            $data['director_fee']       = $director;
            $data['accum_director_fee'] = $director + $emp->sum('director_fee');
            $data['overtime']           = $overtime;
            $data['accum_overtime']     = $overtime + $emp->sum('overtime');
            $data['allowence']          = $allowence;
            $data['accum_allowence']    = $allowence + $emp->sum('allowence');
            $data['bonus']              = $bonus;
            $data['accum_bonus']        = $bonus + $emp->sum('bonus');
            $data['lump']               = $lump;
            $data['accum_lump']         = $lump + $emp->sum('lump');
            $data['etp']                = $ETP;
            $data['accum_etp']          = $ETP + $emp->sum('etp');
            $data['salary']             = $salary;
            $data['accum_salary']       = $salary + $emp->sum('salary');
            $data['exempt']             = $exempt;
            $data['accum_exempt']       = $exempt + $emp->sum('exempt');
            $data['cdep']               = $CDEP;
            $data['accum_cdep']         = $CDEP + $emp->sum('cdep');
            $data['secriface']          = $secriface;
            $data['accum_secriface']    = $secriface + $emp->sum('secriface');
            $data['payg']               = $request->withholdingpay;
            $data['accum_payg']         = $request->withholdingpay + $emp->sum('payg');
            $data['wages']              = $request->gross_pay;
            $data['accum_wages']        = $request->gross_pay + $emp->sum('wages');
            $data['deduc']              = $request->deduc;
            $data['accum_deduc']        = $request->deduc + $emp->sum('deduc');
            $data['annual']             = $request->annual_leave_acrual;
            $data['accum_annual']       = $request->annual_leave_acrual + $emp->sum('annual');
            $data['personal']           = $request->personal_leave_ac;
            $data['accum_personal']     = $request->personal_leave_ac + $emp->sum('personal');
            $data['super']              = $request->superAmt;
            $data['accum_super']        = $request->superAmt + $emp->sum('super');
        } else {
            $data['gross']        = $data['accum_gross']        = $request->gross_pay;
            $data['director_fee'] = $data['accum_director_fee'] = $director;
            $data['overtime']     = $data['accum_overtime']     = $overtime;
            $data['allowence']    = $data['accum_allowence']    = $allowence;
            $data['bonus']        = $data['accum_bonus']        = $bonus;
            $data['lump']         = $data['accum_lump']         = $lump ;
            $data['etp']          = $data['accum_etp']          = $ETP;
            $data['salary']       = $data['accum_salary']       = $salary ;
            $data['exempt']       = $data['accum_exempt']       = $exempt ;
            $data['cdep']         = $data['accum_cdep']         = $CDEP ;
            $data['secriface']    = $data['accum_secriface']    = $secriface ;
            $data['payg']         = $data['accum_payg']         = $request->withholdingpay ;
            $data['wages']        = $data['accum_wages']        = $request->gross_pay ;
            $data['deduc']        = $data['accum_deduc']        = $request->deduc ;
            $data['annual']       = $data['accum_annual']       = $request->annual_leave_acrual ;
            $data['personal']     = $data['accum_personal']     = $request->personal_leave_ac ;
            $data['super']        = $data['accum_super']        = $request->superAmt ;
        }
        try {
            $payslip->pay_accum->update($data);
            $this->updateLeadger($payslip->pay_accum);
            DB::commit();
            toast('Pay Slip Update successful!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
            toast($e->getMessage(), 'error');
        }
        return redirect()->route('payslip.report', [$payslip->tran_id,$payslip->pay_accum_amt_id]);
    }
    public function updateLeadger($payAccum)
    {
        $employee = $payAccum->employee;
        $chart_id = [
                '245101','551800','245103','912200','245200','913100'
            ];
        $codes = ClientAccountCode::whereIn('code', $chart_id)->get();
        $ledger['narration']              = "PAYSLIP NARRATION UP";
        $ledger['balance'] = $ledger['gst'] = $ledger['debit'] = $ledger['credit'] = 0;
        $glUpdateCheck = GeneralLedger::where('client_id', $employee->client_id)
                        ->where('profession_id', $employee->profession_id)
                        ->where('transaction_id', $payAccum->tran_id)
                        ->get();
        foreach ($codes as $code) {
            $glUpdate = $glUpdateCheck->where('chart_id',$code->code)->first();
            switch ($code->code) {
                case '245101':
                    $ledger['balance']  = $ledger['debit'] = $payAccum->net_pay;
                    $ledger['credit'] = 0;
                    break;
                case '551800':
                    $ledger['balance']  = $ledger['credit'] = $payAccum->net_pay;
                    $ledger['debit'] = 0;
                    break;
                case '245103':
                    $ledger['balance']  = $ledger['debit'] = $payAccum->wages;
                    $ledger['credit'] = 0;
                    break;
                case '912200':
                    $ledger['balance']  = $ledger['credit'] = $payAccum->wages;
                    $ledger['debit'] = 0;
                    break;
                case '245200':
                    $ledger['balance']  = $ledger['debit'] = $payAccum->super;
                    $ledger['credit'] = 0;
                    break;
                case '913100':
                    $ledger['balance']  = $ledger['credit'] = $payAccum->super;
                    $ledger['debit'] = 0;
                    break;
                default:
                    $ledger['balance']  = $ledger['debit'] = 0;
                    break;
            }
            $glUpdate->update($ledger);
        }
        // Retain Calculations
        $periodStartDate = $payAccum->financial_year.'-07-01';
        $periodEndDate   = $payAccum->financial_year+1 .'-06-30';

        $retainData = GeneralLedger::select(DB::raw('sum(balance) as balance'))
            ->where('date', '>=', $periodStartDate)
            ->where('date', '<=', $periodEndDate)
            ->where('chart_id', 'LIKE', '2%')
            ->where('client_id', $employee->client_id)
            ->where('profession_id', $employee->profession_id)
            ->where('transaction_id', $payAccum->tran_id)
            ->first();

        $ledger['chart_id'] = 999999;
        $ledger['client_account_code_id'] = 0;
        $ledger['balance_type']           = 1;
        $ledger['credit']           = 0;
        $ledger['balance'] = $ledger['debit'] =  $retainData->balance;
        $glUpdateCheck->where('chart_id', 999999)->first()->update($ledger);

        //PL Retain Calculations
        $plData = GeneralLedger::select(DB::raw('sum(balance) as balance'))
            ->where('chart_id', 'LIKE', '2%')
            ->where('client_id', $employee->client_id)
            ->where('profession_id', $employee->profession_id)
            ->where('transaction_id', $payAccum->tran_id)
            ->first();
        $ledger['chart_id'] = 999998;
        $ledger['balance'] = $ledger['debit'] =  $plData->balance;
        $glUpdateCheck->where('chart_id', 999998)->first()->update($ledger);
    }
    public function payslipDelete(Payslip $payslip, $tran_id)
    {
        try {
            $payslip->pay_accum->delete();
            $payslip->delete();
            PayslipWages::where('tran_id', $tran_id)->where('status', 1)->delete();
            PayslipDeduc::where('tran_id', $tran_id)->where('status', 1)->delete();
            PayslipLeave::where('tran_id', $tran_id)->where('status', 1)->delete();
            PayslipSuper::where('tran_id', $tran_id)->where('status', 1)->delete();
            GeneralLedger::where('transaction_id', $tran_id)->where('client_id',$payslip->client_id)->delete();
            toast('PaySlip Deleted!', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        return back();
    }
    public function payslipPrint(Request $request)
    {
        if ($request->has('payslip_id')) {
            $payslips = Payslip::whereIn('id', $request->payslip_id)->get();
            return view('frontend.payroll.payslip.print', compact('payslips'));
        }else{
            toast('Please select one item','warning');
            return back();
        }
    }
    public function store(Request $request)
    {
        //
    }

    public function show(PreparePayroll $prepayroll)
    {
        return view('frontend.payroll.prepare_payroll.employee_edit');
    }

    public function edit(EmployeeCard $prepayroll)
    {
        return $prepayroll;
    }

    public function update(Request $request, PreparePayroll $prepayroll)
    {
        //
    }

    public function destroy(PreparePayroll $prepayroll)
    {
        //
    }
}
