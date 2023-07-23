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
                        <th style="text-align: center;font-weight:bold;width:20%">Amount</th>
                    </tr>
                    @php
                    if($data['IEretain']){
                        $retain = $data['IEretain']->totalRetain;
                    }else{
                        $retain = 0;
                    }
                    if($data['IECRetain']){
                        $CRetain = $data['IECRetain']->CRetain;
                    }else{
                        $CRetain = 0;
                    }
                    $totalIncome = $totalExpense = $totalTrading = $totalManufacturing = 0;
                    @endphp
                    @foreach($data['IEaccountCodeCategories'] as $mainCategory)
                    <tr>
                        <td colspan="2" style="color: red; text-decoration:underline;">{{$mainCategory->name}}
                        </td>
                    </tr>
                    @php $grand_total = 0; @endphp
                    @foreach($mainCategory->subCategory as $subCategory)
                    <tr>
                        <td colspan="1" style="color: green"> &nbsp;&nbsp;&nbsp;
                            {{$subCategory->name}} {{-- - {{$mainCategory->code.$subCategory->code}} --}}
                        </td>
                        <td style="color: #048300;text-align:right;font-weight:bold;">
                            <div style="border-top: 1px solid #048300; border-bottom: 1px solid #048300;float:right">
                                @php
                                $bl_type = '';
                                $subBalance = \App\Models\GeneralLedger::select('balance_type',DB::raw("sum(balance) as
                                subTotal,sum(credit) as Scredit,sum(debit) as Sdebit,sum(gst) as Sgst"))
                                ->where('chart_id','like',$mainCategory->code.$subCategory->code.'%')
                                ->where('client_id', $client->id)
                                ->whereNotIn('chart_id', [999999, 999998])
                                ->where('date', '>=', $start_date)
                                ->where('date', '<=', $end_date)->first();
                                $subCodeBalance = $subBalance->subTotal;
                                if($mainCategory->code == 1){
                                    $subCodeBalance = $subBalance->subTotal;
                                    if($subBalance->balance_type == 1){
                                        $totalIncome = $grand_total += $subCodeBalance;
                                        $bl_type = '- ';
                                    } else{
                                        $totalIncome = $grand_total -= $subCodeBalance;
                                    }
                                }elseif($mainCategory->code == 2){
                                    $subCodeBalance = $subBalance->subTotal;
                                    if($subBalance->balance_type == 2){
                                        $totalExpense = $grand_total += $subCodeBalance;
                                        $bl_type = '- ';
                                    } else{
                                        $totalExpense = $grand_total -= $subCodeBalance;
                                    }
                                }elseif($mainCategory->code == 9){
                                    $subCodeBalance = $subBalance->Scredit - $subBalance->Sdebit + $retain + $CRetain;
                                }

                                if($mainCategory->code.$subCategory->code == 21){
                                    $totalTrading += $subCodeBalance;
                                }elseif($mainCategory->code.$subCategory->code == 22){
                                    $totalManufacturing += $subCodeBalance;
                                }
                                @endphp
                                {{$bl_type}} {{number_format(abs($subCodeBalance),2)}}
                            </div>
                        </td>
                    </tr>
                    {{-- Additonal Category End--}}
                    @endforeach
                    <tr style="text-align:right;color: #d35400">
                        <td style="font-weight:bold; text-align:center" class="grand_total">Grand Total</td>
                        <td colspan="1">
                            <div
                                style="border-top:1px solid; border-bottom-style: 1px solid;border-bottom-style:double;float:right">
                                {{number_format(abs($grand_total),2)}}
                            </div>
                        </td>
                    </tr>
                    {{-- Sub Category End--}}
                    @endforeach

                    <tr style="text-align:right; color: #262626">
                        <td>
                           Gross Profit
                        </td>
                        @php($gross_profit = abs($totalIncome) - abs($totalTrading)- abs($totalManufacturing))
                        <td class="{{$gross_profit< 0?'text-danger':''}}">
                            {{ number_format(abs($gross_profit),2) }}</td>
                    </tr>
                    <tr class="text-right">
                        <td>Profit & loss</td>
                        @php($profit_loss = abs($totalIncome) - abs($totalExpense) )
                        <td class="{{(($profit_loss< 0)?'text-danger':'')}}">
                            {{ number_format(abs($profit_loss),2) }}
                        </td>
                    </tr>
                    {{--Category End--}}
                </tbody>
            </table>
        </div>
    </div>
</div>
