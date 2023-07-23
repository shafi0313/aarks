<div class="reportH">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h3 class="company_name">{{$client->fullname}}</h3>
            <h5 class="report_name">Detailed Trading, Profit & Loss</h5>
            <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the year
                ended
                {{$data['date']->format('d-M-Y')}}</h5>
        </div>

        <div class="col-12" style="padding-top:40px; ">
            <table class="table table-bordered table-hover table-striped" style="margin: 0 auto; ">
                <thead>
                    <tr>
                        <th style="text-align: center; font-weight:bold">Description</th>
                        {{-- <th t-right>{{$date->format('Y')-1}}</th> --}}
                        <th t-right>{{$date->format('Y')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data["tpl_accountCodeCategories"] as $accountCodeCategory)
                    @php
                    $mainCodes = $data["tpl_accountCodes"]->where('category_id' ,$accountCodeCategory->id);
                    $gtDebit = $gtCredit = $gtBalance = $preGtBalance = 0;
                    @endphp
                    @foreach($accountCodeCategory->subCategoryWithoutAdditional as $subCategory)
                    @php
                    $subCodes = $data["tpl_accountCodes"]->where('sub_category_id' ,$subCategory->id);
                    $subGrpBalance = $preSubGrpBalance = 0;
                    @endphp
                    <tr>
                        <td style="color: green">
                            <u>{{$subCategory->name}} :</u>
                        </td>
                        {{-- <td></td> --}}
                        <td></td>
                    </tr>
                    @foreach($subCategory->additionalCategory as $additionalCategory)
                    @php
                    $adSubCodes = $data["tpl_accountCodes"]->where('additional_category_id' ,$additionalCategory->id);
                    $subSubGrpBalance = $preSubSubGrpBalance = 0;
                    @endphp
                    <tr>
                        <td style="color: violet">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{$additionalCategory->name}} :
                        </td>
                        {{-- <td></td> --}}
                        <td></td>
                    </tr>
                    {{-- // ACCOUNT CODE // --}}
                    @foreach($data["tpl_accountCodes"] as $accountCode)
                    @if($accountCode->additional_category_id == $additionalCategory->id &&
                    $data["tpl_ledgers"]->where('chart_id', $accountCode->code)->sum('balance') != 0)
                    @php
                    // Pre Ledgers Data
                    $preLedger = $data["tpl_preLedgers"]->where('chart_id',$accountCode->code)->first();
                    $preLedgerBalance = $data["tpl_preLedgers"]->where('chart_id', $accountCode->code)->sum('balance');

                    // Current Ledgers Data
                    $ledger = $data["tpl_ledgers"]->where('chart_id',$accountCode->code)->first();
                    $ledgerBalance = $data["tpl_ledgers"]->where('chart_id', $accountCode->code)->sum('balance');

                    $blncType = $preBT ='';
                    if($accountCodeCategory->code == 1){
                    // Pre Year Ledgers
                    if($ledger->balance_type == 2 && $preLedgerBalance > 0){
                    $preGtBalance += abs($preLedgerBalance);
                    $preSubSubGrpBalance += abs($preLedgerBalance);
                    $preBT = ''; // Balance Type
                    }else{
                    $preGtBalance -= abs($preLedgerBalance);
                    $preSubSubGrpBalance -= abs($preLedgerBalance);
                    $preBT = $preLedgerBalance != 0? '-': '';
                    }
                    // Current Ledgers
                    if($ledger->balance_type == 2 && $ledgerBalance > 0){
                    $gtBalance += abs($ledgerBalance);
                    $subSubGrpBalance += abs($ledgerBalance);
                    $blncType = '';
                    }else{
                    $gtBalance -= abs($ledgerBalance);
                    $subSubGrpBalance -= abs($ledgerBalance);
                    $blncType = '-';
                    }
                    }
                    if($accountCodeCategory->code == 2){
                    // Pre year Ledgers
                    if($ledger->balance_type == 1 && $preLedgerBalance > 0){
                    $preGtBalance += abs($preLedgerBalance);
                    $preSubSubGrpBalance += abs($preLedgerBalance);
                    $preBT = '';
                    }else{
                    $preGtBalance -= abs($preLedgerBalance);
                    $preSubSubGrpBalance -= abs($preLedgerBalance);
                    $preBT = $preLedgerBalance != 0? '-': '';
                    }
                    // Current Ledgers
                    if($ledger->balance_type == 1 && $ledgerBalance > 0){
                    $gtBalance += abs($ledgerBalance);
                    $subSubGrpBalance += abs($ledgerBalance);
                    $blncType = '';
                    }else{
                    $gtBalance -= abs($ledgerBalance);
                    $subSubGrpBalance -= abs($ledgerBalance);
                    $blncType = '-';
                    }
                    }
                    if($subCategory->id == 16 && $accountCode->code == 299998){
                    $preSubSubGrpBalance += $data["tpl_totalPrePl"];
                    $subSubGrpBalance += $data["tpl_totalPl"];
                    }elseif($subCategory->id == 16 && $accountCode->code == 299999){
                    $preSubSubGrpBalance += $data["tpl_totalPreRetain"] ;
                    $subSubGrpBalance += $data["tpl_totalRetain"] ;
                    }
                    @endphp
                    <tr>
                        <td style="color: #1B6AAA">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{$accountCode->name}}
                        </td>
                        {{-- Pre Year Code balance --}}
                        {{-- <td style="text-align: right;color: #1B6AAA">
                            @if (optional($preLedger)->chart_id ==999999)
                            {{$data["tpl_totalPreRetain"]? number_format(($data["tpl_totalPreRetain"]),2) : '0.00'}}
                            @elseif(optional($preLedger)->chart_id ==999998)
                            {{$data["tpl_totalPrePl"]? number_format(($data["tpl_totalPrePl"]),2) : '0.00'}}
                            @else
                            {{$preBT}} {{number_format(abs($preLedgerBalance),2)}}
                            @endif
                        </td> --}}
                        {{-- Currest Year Code balance --}}
                        <td style="text-align: right;color: #1B6AAA">
                            @if ($ledger->chart_id ==999999)
                            {{$data["tpl_totalRetain"]? number_format(($data["tpl_totalRetain"]),2) : '0.00'}}
                            @elseif($ledger->chart_id ==999998)
                            {{$data["tpl_totalPl"]? number_format(($data["tpl_totalPl"]),2) : '0.00'}}
                            @else
                            {{$blncType}} {{number_format(abs($ledgerBalance),2)}}
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @php
                    $preSubGrpBalance += $preSubSubGrpBalance;
                    $subGrpBalance += $subSubGrpBalance;
                    @endphp
                    <tr>
                        <td style="color: violet;text-align:right">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Total {{$additionalCategory->name}}
                        </td>
                        {{-- <td style="color: violet;text-align: right;">
                            <span
                                style="solid;border-bottom:1px solid;text-align:right;float: right;font-weight: bold;">
                                {{number_format(($preSubSubGrpBalance),2)}}
                            </span>
                        </td> --}}
                        <td style="color: violet;text-align: right;">
                            <span
                                style="solid;border-bottom:1px solid;text-align:right;float: right;font-weight: bold;">
                                {{number_format(($subSubGrpBalance),2)}}
                            </span>
                        </td>
                    </tr>
                    {{--Account Code End--}}
                    @endforeach

                    @php
                    if($subCategory->id == 7){
                    $pre_manufacturing = $preSubGrpBalance;
                    $manufacturing = $subGrpBalance;
                    }elseif($subCategory->id == 8){
                    $pre_trading = $preSubGrpBalance;
                    $trading = $subGrpBalance;
                    }
                    @endphp
                    <tr>
                        <td style="color: green;text-align:right">
                            Total {{$subCategory->name}}
                        </td>
                        {{-- <td style="color: green;text-align: right;font-weight: bold;">
                            <span style="border-top:1px solid;border-bottom:1px solid;text-align:right;float: right;">
                                {{number_format(($preSubGrpBalance),2)}}
                            </span>
                        </td> --}}
                        <td style="color: green;text-align: right;font-weight: bold;">
                            <span style="border-top:1px solid;border-bottom:1px solid;text-align:right;float: right;">
                                {{number_format(($subGrpBalance),2)}}
                            </span>
                        </td>
                    </tr>
                    {{--Additonal Category End--}}
                    @endforeach
                    {{--Sub Category End--}}
                    <tr style="text-align:right;color: #d35400;">

                        @php
                        if ($accountCodeCategory->code == 1){
                        $income = $gtBalance;
                        $preIncome = $preGtBalance;
                        } else {
                        $expense = $gtBalance;
                        $preExpense = $preGtBalance;
                        }
                        @endphp
                        <td class="text-center" style="font-size: 16px;">Total {{$accountCodeCategory->name}}</td>
                        {{-- <td>
                            <span
                                style="border-top:1px solid;border-bottom-style:double;font-weight: bold;float: right;">
                                {{number_format(($preGtBalance),2)}}
                            </span>
                        </td> --}}
                        <td>
                            <span
                                style="border-top:1px solid;border-bottom-style:double;font-weight: bold;float: right;">
                                {{-- @if ($accountCodeCategory->code == 1) --}}
                                {{number_format(($gtBalance),2)}}
                                {{-- @else
                                {{number_format(($gtBalance+ $data["tpl_totalRetain"] + $data["tpl_totalPl"]),2)}}
                                @endif --}}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    <tr style="text-align:right; color: #262626">
                        <td>
                            Gross Profit
                        </td>
                        {{-- @php($pre_gross_profit = abs($preIncome) - abs($pre_trading??0)-
                        abs($pre_manufacturing??0))
                        <td class="{{(($pre_gross_profit < 0)?'text-danger':'')}}">
                            {{ number_format(($pre_gross_profit),2) }}</td> --}}

                        @php($gross_profit = abs($income) - abs($trading??0)- abs($manufacturing??0))
                        <td class="{{(($gross_profit< 0)?'text-danger':'')}}">
                            {{ number_format(($gross_profit),2) }}</td>
                    </tr>
                    <tr style="text-align:right; color: #262626">
                        <td>
                            Net Profit/(loss)
                        </td>
                        @php($pre_profit_loss = abs($preIncome) - abs($preExpense) )
                        {{-- <td class="{{(($pre_profit_loss< 0)?'text-danger':'')}}">
                            {{ number_format(($pre_profit_loss),2) }}</td> --}}

                        @php($profit_loss = abs($income) - abs($expense) )
                        <td class="{{(($profit_loss< 0)?'text-danger':'')}}">
                            {{ number_format(($profit_loss),2) }}</td>
                    </tr>
                    {{--Category End--}}
                </tbody>
            </table>
        </div>
    </div>
</div>
