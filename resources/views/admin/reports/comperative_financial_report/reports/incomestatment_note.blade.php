<div class="reportH">

    <div class="text-center">
        <h3 class="company_name">{{$client->fullname}}</h3>
        <h5 class="report_name">Income statement</h5>
        <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the year ended
            {{$data['date']->format('d-M-Y')}}</h5>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-offset-2" style="padding-top:40px; ">
            <table class="table table-bordered table-hover table-striped">
                <tbody>
                    <tr>
                        <th style="text-align: center; width: 70%; font-weight:bold">Description</th>
                        <th class="text-center t-right">{{$date->format('Y')-1}}</th>
                        <th class="text-center t-right">{{$date->format('Y')}}</th>
                    </tr>
                    @php
                    $retain      = $data['retain'];
                    $plRetain    = $data['plRetain'];
                    $preRetain   = $data['preRetain'];
                    $prePlRetain = $data['prePlRetain'];

                    $totalIncome = $totalExpense = $totalTrading = $totalManufacturing =
                    $pre_totalIncome = $pre_totalExpense = $pre_totalTrading = $pre_totalManufacturing = 0;
                    @endphp
                    @foreach($data['IEaccountCodeCategories'] as $mainCategory)
                    <tr>
                        <td colspan="3" style="color: red; text-decoration:underline;">{{$mainCategory->name}}
                        </td>
                    </tr>

                    @php $grand_total = $pre_grand_total = 0; @endphp
                    @foreach($mainCategory->subCategory as $subCategory)
                    @php
                    $subBalance = \App\Models\GeneralLedger::select('*',DB::raw("sum(balance) as
                    subTotal,sum(credit) as Scredit,sum(debit) as Sdebit,sum(gst) as Sgst"))
                    ->where('chart_id','like',$mainCategory->code.$subCategory->code.'%')
                    ->where('client_id', $client->id)
                    ->whereNotIn('chart_id', [999998, 999999])
                    ->where('date', '>=', $start_date)
                    ->where('date', '<=', $end_date) ->first();
                    $pre_subBalance = \App\Models\GeneralLedger::select('*',DB::raw("sum(balance) as
                    subTotal,sum(credit) as Scredit,sum(debit) as Sdebit,sum(gst) as Sgst"))
                    ->where('chart_id','like',$mainCategory->code.$subCategory->code.'%')
                    ->where('client_id', $client->id)
                    ->whereNotIn('chart_id', [999998, 999999])
                    ->where('date', '>=', $pre_start_date)
                    ->where('date', '<=', $start_date) ->first();

                    $subCodeBalance = $subBalance->subTotal;
                    $pre_subCodeBalance = $subBalance->subTotal;
                    if($mainCategory->code == 1){
                        // Pre
                        $pre_subCodeBalance = ($pre_subBalance->Scredit - $pre_subBalance->Sdebit) - $pre_subBalance->Sgst;
                        $pre_totalIncome += $pre_subCodeBalance;
                        // Current
                        $subCodeBalance = ($subBalance->Scredit - $subBalance->Sdebit) - $subBalance->Sgst;
                        $totalIncome += $subCodeBalance;
                    }elseif($mainCategory->code == 2){
                        // Pre
                        $pre_subCodeBalance = ($pre_subBalance->Sdebit - $pre_subBalance->Scredit) - $pre_subBalance->Sgst;
                        $pre_totalExpense += $pre_subCodeBalance;
                        // Current
                        $subCodeBalance = ($subBalance->Sdebit - $subBalance->Scredit) - $subBalance->Sgst;
                        $totalExpense += $subCodeBalance;
                    }elseif($mainCategory->code == 9){
                        // Pre
                        $pre_subCodeBalance = $pre_subBalance->Scredit - $pre_subBalance->Sdebit + $preRetain + $prePlRetain;
                        // Current
                        $subCodeBalance = $subBalance->Scredit - $subBalance->Sdebit + $retain + $plRetain;
                    }

                    $pre_grand_total += $pre_subCodeBalance;
                    $grand_total += $subCodeBalance;

                    if($mainCategory->code.$subCategory->code == 21){
                        $pre_totalTrading += $pre_subCodeBalance;
                        $totalTrading += $subCodeBalance;
                    }elseif($mainCategory->code.$subCategory->code == 22){
                        $pre_totalManufacturing += $pre_subCodeBalance;
                        $totalManufacturing += $subCodeBalance;
                    }
                    @endphp
                    <tr>
                        <td colspan="1" style="color: green"> &nbsp;&nbsp;&nbsp;
                            {{$subCategory->name}}
                        </td>
                        <td style="color: #048300;text-align:right;font-weight:bold;">
                            <div style="border-top: 1px solid #048300; border-bottom: 1px solid #048300;float:right">
                                {{number_format(abs($pre_subCodeBalance),2)}}
                            </div>
                        </td>
                        <td style="color: #048300;text-align:right;font-weight:bold;">
                            <div style="border-top: 1px solid #048300; border-bottom: 1px solid #048300;float:right">
                                {{number_format(abs($subCodeBalance),2)}}
                            </div>
                        </td>
                    </tr>
                    {{-- Additonal Category End--}}
                    @endforeach
                    <tr style="text-align:right;color: #d35400">
                        <td style="font-weight:bold; text-align:center">Grand Total</td>
                        <td colspan="1">
                            <div
                                style="float:right;border-top:1px solid; border-bottom-style: 1px solid;border-bottom-style:double">
                                {{number_format(abs($pre_grand_total),2)}}
                            </div>
                        </td>
                        <td colspan="1">
                            <div
                                style="float:right;border-top:1px solid; border-bottom-style: 1px solid;border-bottom-style:double">
                                {{number_format(abs($grand_total),2)}}
                            </div>
                        </td>
                    </tr>
                    {{-- Sub Category End--}}
                    @endforeach
                    <tr class="text-right">
                        <td>Profit & loss</td>
                        @php($pre_profit_loss = abs($pre_totalIncome) - abs($pre_totalExpense) )
                        @php($profit_loss = abs($totalIncome) - abs($totalExpense) )
                        <td class="{{(($pre_profit_loss< 0)?'text-danger':'')}}">
                            {{ number_format(($pre_profit_loss),2) }}</td>
                        <td class="{{(($profit_loss< 0)?'text-danger':'')}}">
                            {{ number_format(($profit_loss),2) }}</td>
                    </tr>
                    {{--Category End--}}
                </tbody>
            </table>
        </div>
    </div>
</div>
