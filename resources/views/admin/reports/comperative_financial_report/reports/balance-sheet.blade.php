<div class="reportH">
    <div class="text-center">
        <h3 class="company_name">{{ clientName($client) }}</h3>
        <h5 class="report_name">BALANCE SHEET</h5>
        <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the year ended
            {{ $data['date']->format('d-M-Y') }}</h5>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-offset-2 card" style="padding-top:40px; ">
            <div class="card-body">
                <div class="panel-body">
                    <table class="table table-bordered table-hover table-striped" style="font-size: 14px;">
                        <tbody>
                            <tr>
                                <th class="text-center">Particulars</th>
                                <th class="text-center t-right">{{ $date->format('Y') - 1 }}</th>
                                <th class="text-center t-right">{{ $date->format('Y') }}</th>
                            </tr>
                            @php
                                // Pre
                                $totalPrePl = $data['totalPrePl'];
                                $totalPreRetain = $data['totalPreRetain'];

                                // Current
                                $totalPl = $data['totalPl'];
                                $totalRetain = $data['totalRetain'];

                            @endphp

                            @foreach ($data['bs_accountCodeCategories'] as $accountCodeCategory)
                                @php
                                    $gtDebit = $gtCredit = $gtBalance = $preGtDebit = $preGtCredit = $preGtBalance = 0;
                                @endphp
                                <tr>
                                    <td colspan="3" style="color: red">{{ $accountCodeCategory->name }}</td>
                                </tr>
                                @foreach ($accountCodeCategory->subCategoryWithoutAdditional->sortBy('code') as $subCategory)
                                    @php
                                        $preSubGrpBalance = $subGrpBalance = 0;
                                    @endphp
                                    @foreach ($subCategory->additionalCategory->sortBy('code') as $additionalCategory)
                                        @php
                                            $subSubGrpBalance = $preSubSubGrpBalance = 0;
                                        @endphp
                                        {{-- ? ACCOUNT CODE // --}}
                                        @php
                                            $code = 0;
                                        @endphp
                                        @foreach ($data['bs_accountCodes'] as $accountCode)
                                            @if ($accountCode->additional_category_id == $additionalCategory->id && $code != $accountCode->code)
                                                @php
                                                    $code = $accountCode->code;
                                                    $preLedger = $data['bs_preLedgers']->where('chart_id', $accountCode->code)->first();
                                                    $preLedgerBalance = $data['bs_preLedgers']->where('chart_id', $accountCode->code)->sum('balance');

                                                    $ledger = $data['bs_ledgers']->where('chart_id', $accountCode->code)->first();
                                                    $ledgerBalance = $data['bs_ledgers']->where('chart_id', $accountCode->code)->sum('balance');

                                                    if ($ledgerBalance != 0) {
                                                        $blncType = '';
                                                        if ($accountCodeCategory->code == 5) {
                                                            if ($ledger->balance_type == 1 && $ledgerBalance > 0) {
                                                                $gtBalance += abs($ledgerBalance);
                                                                $subSubGrpBalance += abs($ledgerBalance);
                                                                $blncType = '';
                                                            } elseif ($ledger->balance_type == 2 && $ledgerBalance < 0) {
                                                                $gtBalance += abs($ledgerBalance);
                                                                $subSubGrpBalance += abs($ledgerBalance);
                                                                $blncType = '';
                                                            } else {
                                                                $gtBalance -= abs($ledgerBalance);
                                                                $subSubGrpBalance -= abs($ledgerBalance);
                                                                $blncType = '-';
                                                            }
                                                        }

                                                        if ($accountCodeCategory->code == 9 && !in_array($accountCode->code, [999999, 999998, 912101])) {
                                                            if ($ledger->balance_type == 2 && $ledgerBalance > 0) {
                                                                $gtBalance = $gtBalance += abs($ledgerBalance);
                                                                $subSubGrpBalance = $subSubGrpBalance += abs($ledgerBalance);
                                                                $blncType = '';
                                                            } elseif ($ledger->balance_type == 1 && $ledgerBalance < 0) {
                                                                $gtBalance = $gtBalance += abs($ledgerBalance);
                                                                $subSubGrpBalance = $subSubGrpBalance += abs($ledgerBalance);
                                                                $blncType = '';
                                                            } else {
                                                                $gtBalance = $gtBalance -= abs($ledgerBalance);
                                                                $subSubGrpBalance = $subSubGrpBalance -= abs($ledgerBalance);
                                                                $blncType = '-';
                                                            }
                                                        }

                                                        // For GST Clearing Account (912101)
                                                        if ($accountCodeCategory->code == 9) {
                                                            if ($ledger->chart_id == 912101) {
                                                                if ($ledger->balance_type == 1 && $ledgerBalance > 0) {
                                                                    $gtBalance = $gtBalance -= abs($ledgerBalance);
                                                                    $subSubGrpBalance = $subSubGrpBalance -= abs($ledgerBalance);
                                                                    $blncType = '-';
                                                                } else {
                                                                    $gtBalance = $gtBalance += abs($ledgerBalance);
                                                                    $subSubGrpBalance = $subSubGrpBalance += abs($ledgerBalance);
                                                                    $blncType = '';
                                                                }
                                                            }
                                                        }
                                                        // if ($subCategory->id == 16 && $accountCode->code == 999998) {
                                                        //     $subSubGrpBalance += $totalPl;
                                                        // } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
                                                        //     $subSubGrpBalance += $totalRetain;
                                                        // }
                                                    }
                                                    if ($preLedgerBalance != 0) {
                                                        $preBlncType = '';
                                                        if ($accountCodeCategory->code == 5) {
                                                            if ($preLedger->balance_type == 1 && $preLedgerBalance > 0) {
                                                                $preGtBalance += abs($preLedgerBalance);
                                                                $preSubSubGrpBalance += abs($preLedgerBalance);
                                                                $preBlncType = '';
                                                            } elseif ($preLedger->balance_type == 2 && $preLedgerBalance < 0) {
                                                                $preGtBalance += abs($preLedgerBalance);
                                                                $preSubSubGrpBalance += abs($preLedgerBalance);
                                                                $preBlncType = '';
                                                            } else {
                                                                $preGtBalance -= abs($preLedgerBalance);
                                                                $preSubSubGrpBalance -= abs($preLedgerBalance);
                                                                $preBlncType = '-';
                                                            }
                                                        }
                                                        if ($accountCodeCategory->code == 9 && !in_array($accountCode->code, [999999, 999998])) {
                                                            if ($preLedger->balance_type == 2 && $preLedgerBalance > 0) {
                                                                $preGtBalance += abs($preLedgerBalance);
                                                                $preSubSubGrpBalance += abs($preLedgerBalance);
                                                                $preBlncType = '';
                                                            } elseif ($preLedger->balance_type == 1 && $preLedgerBalance < 0) {
                                                                $preGtBalance += abs($preLedgerBalance);
                                                                $preSubSubGrpBalance += abs($preLedgerBalance);
                                                                $preBlncType = '';
                                                            } else {
                                                                $preGtBalance -= abs($preLedgerBalance);
                                                                $preSubSubGrpBalance -= abs($preLedgerBalance);
                                                                $preBlncType = '-';
                                                            }
                                                        }
                                                        // if ($subCategory->id == 16 && $accountCode->code == 999998) {
                                                        //     $subSubGrpBalance += $totalPl;
                                                        // } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
                                                        //     $subSubGrpBalance += $totalRetain;
                                                        // }
                                                    }
                                                @endphp
                                            @endif
                                        @endforeach
                                        @php
                                            $preSubGrpBalance += $preSubSubGrpBalance;
                                            $subGrpBalance += $subSubGrpBalance;
                                        @endphp
                                        {{-- Account Code End --}}
                                    @endforeach
                                    <tr>
                                        <td style="color: green;">
                                            {{ $subCategory->name }}
                                        </td>
                                        <td style="color: green;text-align: right;font-weight: bold;">
                                            <span style="border-top:1px solid;border-bottom:1px solid;float:right">
                                                {{-- {{ number_format($preSubGrpBalance, 2) }} --}}
                                                @if ($subCategory->name == 'Capital & Equity' && ($totalPrePl != 0 || $totalPreRetain != 0))
                                                    {{ nF2($preSubGrpBalance + $totalPrePl + $totalPreRetain) }}
                                                @else
                                                    {{ nF2($preSubGrpBalance) }}
                                                @endif
                                            </span>
                                        </td>

                                        <td style="color: green;text-align: right;font-weight: bold;">
                                            <span style="border-top:1px solid;border-bottom:1px solid;float:right">
                                                {{-- {{ number_format($subGrpBalance, 2) }} --}}
                                                @if ($subCategory->name == 'Capital & Equity' && ($totalPl != 0 || $totalRetain != 0))
                                                    {{ nF2($subGrpBalance + $totalPl + $totalRetain) }}
                                                @else
                                                    {{ nF2($subGrpBalance) }}
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    {{-- Additional Category End --}}
                                @endforeach
                                {{-- Sub Category End --}}
                                <tr style="text-align:right;color: #d35400;">
                                    <td class="text-center" style="font-size: 15px;">Total
                                        {{ $accountCodeCategory->name }}:</td>
                                    <td>
                                        <span
                                            style="border-top:1px solid;border-bottom-style:double;font-weight: bold;float:right">
                                            @if ($accountCodeCategory->code == 5)
                                                {{ number_format($preGtBalance, 2) }}
                                            @else
                                                {{ number_format($preGtBalance + $data['preRetain'] + $data['prePlRetain'], 2) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            style="border-top:1px solid;border-bottom-style:double;font-weight: bold;float:right">
                                            @if ($accountCodeCategory->code == 5)
                                                {{ number_format($gtBalance, 2) }}
                                            @else
                                                {{ number_format($gtBalance + $totalRetain + $totalPl, 2) }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- Category End --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
