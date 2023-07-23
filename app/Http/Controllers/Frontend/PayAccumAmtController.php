<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\StandardWages;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\ClientWages;
use App\Models\Frontend\PayAccumAmt;
use App\Models\Frontend\PayslipDeduc;
use App\Models\Frontend\PayslipLeave;
use App\Models\Frontend\PayslipSuper;
use App\Models\Frontend\PayslipWages;
use App\Models\Frontend\PreparePayroll;
use RealRashid\SweetAlert\Facades\Alert;

class PayAccumAmtController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // return PayslipWages::where('tran_id',$request->tran_id)->get();
        // return $request;
        $data = $request->validate([
            "client_id"           => "required",
            "tran_id"           => "required",
            "profession_id"       => "required",
            "employee_card_id"    => "required",
            "gross_pay"           => "required",
            "withholdingpay"      => "required",
            "deduc"               => "required",
            "personal_leave_ac"   => "required",
            "annual_leave_acrual" => "required",
            "super"               => 'required',
            // "pay_serial"          => "required",
            "payment_date"        => "required",
            "payperiod_start"     => "required",
            "payperiod_end"       => "required",
            "pay_period"          => "required",
            "statement"           => "sometimes",
            "memo"                => "sometimes",
            "net_pay"             => "required",
            // "wagesid"             => 'required',
            // "typeofages"          => "required",
            // "emphour"             => 'required',
            // "hourlyamount"        => 'required',
            // "deduc_amt"           => "required",
            // "deduc_rate"          => 'required',
            // "leave_rate"          => 'required',
            // "leave_acrual"        => 'required',
        ]);
        DB::beginTransaction();
        $tran_id = $request->tran_id;
        $salary = $overtime=$bonus=$allowence=$director=$lump=$ETP=$CDEP=$secriface=$exempt= 0;
        foreach ($request->wagesid as $i=>$wage) {
            $payProcessWages = PayslipWages::where('name',$wage)->where('tran_id',$request->tran_id)->first();
            $stanWages = StandardWages::where('name',$wage)->first();
            if($stanWages == null){
               $stanWages = ClientWages::where('name',$wage)->first();
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
                'name'    => $wage,
                'hours'   => $request->emphour[$i],
                'amount'  => $request->hourly_amt[$i],
                'rate'    => $request->hourlyamount[$i],
                'tran_id' => $tran_id,
                'status'  => 1,
            ];
            $payProcessWages->update($wages);
        }
        $j =0;
        foreach ($request->deduc_id as $i=>$dedc) {
          $payProcessDeduc = PayslipDeduc::where('name', $dedc)->where('tran_id', $request->tran_id)->first();
            $deduc = [
                'name'    => $dedc,
                'amount'  => $request->deduc_amt[$i],
                'rate'    => $request->deduc_rate[$i],
                'fix_amt' => $request->has('fix_amt')==true?$request->deduc_amt[$i]:'',
                'tran_id' => $tran_id,
                'status'  => 1,
            ];
            if ($payProcessDeduc != '') {
                $payProcessDeduc->update($deduc);
            }else{
                PayslipDeduc::create($deduc);
            }
        }
        foreach ($request->leave_id as $i=>$leaves) {
            $payProcessLeave = PayslipLeave::where('name', $leaves)->where('tran_id', $request->tran_id)->first();

            $leave = [
                'name'    => $leaves,
                'amount'  => $request->leave_acrual[$i],
                'rate'    => $request->leave_rate[$i],
                // 'fix_amt' => $request->fix_amt[$i],
                'tran_id' => $tran_id,
                'status'  => 1,
            ];
            $payProcessLeave->update($leave);
        }
        foreach ($request->super_id as $i=>$supers) {
            $payProcessSuper = PayslipSuper::where('name', $supers)->where('tran_id', $request->tran_id)->first();

            $super = [
                'name'    => $supers,
                'amount'  => $request->super[$i],
                'tran_id' => $tran_id,
                'status'  => 1,
            ];
            $payProcessSuper->update($super);

        }


        $emp = PayAccumAmt::where('employee_card_id',$request->employee_card_id)->get();
        $data['payment_date']   = $data['tran_date'] = makeBackendCompatibleDate($request->payment_date);
        $data['payperiod_start'] = makeBackendCompatibleDate($request->payperiod_start);
        $data['payperiod_end']   = makeBackendCompatibleDate($request->payperiod_end);
        if ($data['payment_date']->format('m') >= 07 & $data['payment_date']->format('m') <= 12) {
            $data['financial_year'] = $data['payment_date']->format('Y'); //01/07/2020 01/12/2020
        } else {
            $data['financial_year'] = $data['payment_date']->format('Y')-1;
        }
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
        }else{
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
            PreparePayroll::findOrFail($request->prepay_id)->update($data);
            DB::commit();
            toast('Pay Slip saved success!','success');
        } catch (\Exception $e) {
            DB::rollBack();
            toast($e->getMessage(),'error');
        }
        return redirect()->route('prepayroll.emplist_redirect');
    }

    public function show(PayAccumAmt $payaccumamt)
    {
        //
    }

    public function edit(PayAccumAmt $payaccumamt)
    {
        //
    }

    public function update(Request $request, PayAccumAmt $payaccumamt)
    {
        //
    }

    public function destroy(PayAccumAmt $payaccumamt)
    {
        //
    }
}
