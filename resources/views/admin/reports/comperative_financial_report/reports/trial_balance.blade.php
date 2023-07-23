<div class="reportH mt-5">
    <div class="text-center">
        <h5 class="company_name">{{$client->fullname}}</h5>
        <h5 class="report_name">Trial Balance</h5>
        <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the year ended
            {{$data['date']->format('d-M-Y')}}</h5>
    </div>

    <div class="row justify-content-center" style="margin-top: 35px;">
    <div class="col-md-8 col-lg-offset-2">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td colspan="2" class="text-center">
                        Particulars
                    </td>
                    <td colspan="2" class="text-center  t-right">
                        {{$date->format('Y')-1}}
                    </td>
                    <td colspan="2" class="text-center  t-right">
                        {{$date->format('Y')}}
                    </td>
                </tr>
                <tr>
                    <td class="center" style="width:10%">Account Code</td>
                    <td class="center" style="width:50%">Account Name</td>
                    <td class="center t-right" style="width:10%">Debit</td>
                    <td class="center t-right" style="width:10%">Credit</td>
                    <td class="center t-right" style="width:10%">Debit</td>
                    <td class="center t-right" style="width:10%">Credit</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $preBalance = $preDebit = $preCredit = $preTotalDebit = $preTotalCredit = $preRetain = $prePlRetain = $PRdebit = $PRcredit = 0;
                    $sum     = ['debit'=> 0, 'credit'=> 0, 'income' => 0, 'expense' => 0];
                    $Sdebit  = $Scredit = $debit  = $credit  = $retain
                             = $Rcredit = $Rdebit = $Ccredit = $Cdebit = $chart_id = $preBalance = 0;
                    $ac_code = '';
                @endphp
                @foreach($data['trial_codes'] as $code)
                @if ($ac_code != $code->code)
                @php
                    $ac_code   = $code->code;
                    $preLedger = $data['trial_preLedgers']->where('chart_id',$code->code)->first();
                    $ledger    = $data['trial_ledgers']->where('chart_id',$code->code)->first();
                    // if(!$ledger ){
                    //     continue;
                    // }
                    // Pre
                    if($preLedger){
                        if($preLedger->balance_type==1){
                            if($preLedger->trail_balance < 0){
                                $preCredit    = $preLedger->trail_balance;
                                $preDebit     = 0;
                                $preTotalCredit += abs($preCredit);
                            }else{
                                $preDebit    = $preLedger->trail_balance;
                                $preCredit   = 0;
                                $preTotalDebit += abs($preDebit);
                            }
                        }elseif($preLedger->balance_type==2){
                            if($preLedger->trail_balance < 0){
                                $preDebit    = $preLedger->trail_balance;
                                $preCredit   = 0;
                                $preTotalDebit += abs($preDebit);
                            }else{
                                $preCredit    = $preLedger->trail_balance;
                                $preDebit     = 0;
                                $preTotalCredit += abs($preCredit);
                            }
                        }
                    } else {
                        $preDebit = $preCredit = 0;
                    }
                    // Corrent
                    if($ledger){
                        if($ledger->balance_type==1){
                            if($ledger->trail_balance < 0){
                                $credit=$ledger->trail_balance;
                                $sum['credit'] += abs($credit);
                                $debit = 0;
                            }else{
                                $debit = $ledger->trail_balance;
                                $sum['debit'] += abs($debit);
                                $credit = 0;
                            }
                        }elseif($ledger->balance_type==2){
                            if($ledger->trail_balance < 0){ $debit=$ledger->trail_balance;
                                $sum['debit'] += abs($debit);
                                $credit = 0;
                            }else{
                                $credit = $ledger->trail_balance;
                                $sum['credit'] += abs($credit);
                                $debit = 0;
                            }
                        }
                    } else {
                        $debit = $credit = 0;
                    }

                @endphp
                @if($ledger || $preLedger)
                    <tr>
                        <td style="text-align: center">{{$ac_code}}</td>
                        <td>{{$code->name}}</td>
                        <td style="text-align: right">{{number_format(abs($preDebit),2)}}</td>
                        <td style="text-align: right">{{number_format(abs($preCredit),2)}}</td>
                        <td style="text-align: right">{{number_format(abs($debit),2)}}</td>
                        <td style="text-align: right">{{number_format(abs($credit),2)}}</td>
                    </tr>
                @endif
                @endif
                @endforeach

                <tr>
                    <td class="text-center">999999</td>
                    <td>Retain earning</td>
                    @if ($data['preRetain'])
                    @php
                    if($data['preRetain'] > 0 ){
                        $PRcredit = abs($data['preRetain']);
                        $PRdebit = 0;
                    } else {
                        $PRdebit = abs($data['preRetain']);
                        $PRcredit = 0;
                    }
                    @endphp
                    <td class="text-right">{{number_format($PRdebit,2)}} </td>
                    <td class="text-right">{{number_format($PRcredit,2)}} </td>
                    @else
                    <td class="text-right">{{number_format(0,2)}} </td>
                    <td class="text-right">{{number_format(0,2)}} </td>
                    @endif
                    @if ($data['retain'])
                    @php
                    if($data['retain'] > 0 ){
                        $Rcredit = abs($data['retain']);
                        $Rdebit = 0;
                    } else {
                        $Rdebit = abs($data['retain']);
                        $Rcredit = 0;
                    }
                    @endphp
                    <td class="text-right">{{number_format($Rdebit,2)}} </td>
                    <td class="text-right">{{number_format($Rcredit,2)}} </td>
                    @endif
                </tr>
                <tr style="text-align: right;  color:blue; font-weight:bold">
                    <td colspan="2" class="text-right;">Total</td>
                    <td>
                        <div style="float:right;border-top:1px solid blue !important; border-bottom-style: 1px solid;border-bottom-style:double">{{number_format($preTotalDebit += $PRdebit,2)}}</div>
                    </td>
                    <td>
                        <div style="float:right;border-top:1px solid blue !important; border-bottom-style: 1px solid;border-bottom-style:double">{{number_format($preTotalCredit += $PRcredit,2)}}</div>
                    </td>
                    <td>
                        <div style="float:right;border-top:1px solid blue !important; border-bottom-style: 1px solid;border-bottom-style:double">{{number_format($Sdebit = $sum['debit']+$Rdebit,2)}}</div>
                    </td>
                    <td>
                        <div style="float:right;border-top:1px solid blue !important; border-bottom-style: 1px solid;border-bottom-style:double">{{number_format($Scredit = $sum['credit']+$Rcredit,2)}}</div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="text-danger text-right">Difference between Dr. and
                        Cr.</td>
                    <td colspan="2" class="text-center text-danger">
                        @php $preDeffBlnc = $preTotalDebit - $preTotalCredit; @endphp
                        {{ number_format(abs($preDeffBlnc),2) . ($preDeffBlnc >0 ?' Cr':' Dr')}}

                    </td>
                    <td colspan="2" class="text-center text-danger">
                        @php $deffBlnc = $Scredit-$Sdebit;@endphp
                        {{ number_format(abs($deffBlnc),2) . ($deffBlnc >0 ?' Cr':' Dr')}}

                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right">Profit/Loss as at
                        {{\Carbon\Carbon::parse($date)->format('d/m/Y')}}</td>
                    @if ($data['prePlRetain'])
                    <td colspan="2" class="text-center">{{number_format(($data['prePlRetain']),2)}}</td>
                    @else
                    <td colspan="2" class="text-center">{{number_format(0,2)}}</td>
                    @endif
                    @if ($data['plRetain'])
                    <td colspan="2" class="text-center">{{number_format(($data['plRetain']),2)}}</td>
                    @else
                    <td colspan="2">{{number_format(0,2)}}</td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
