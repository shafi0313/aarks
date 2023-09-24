<div class="reportH">
    <div class="text-center">
        <h3 class="company_name">{{ clientName($client) }}</h3>
        <h5 class="report_name">Detail Balance Sheet</h5>
        <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the year ended
            {{ $date->format('d-M-Y') }}</h5>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-offset-2 card" style="padding-top:40px; ">
            <div class="card-body">
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

                            $cPreLoop = 0;
                        @endphp

                        @foreach ($data['bs_accountCodeCategories'] as $accountCodeCategory)
                            @php
                                $gtDebit = $gtCredit = $gtBalance = $preGtBalance = 0;
                            @endphp

                            @foreach ($accountCodeCategory->subCategoryWithoutAdditional->sortBy('code') as $subCategory)
                                @php
                                    $subGrpBalance = $preSubGrpBalance = 0;
                                @endphp
                                <tr>
                                    <td style="color: green" colspan="3">
                                        <u>{{ $subCategory->name }} :</u>
                                    </td>
                                </tr>
                                @foreach ($subCategory->additionalCategory->sortBy('code') as $additionalCategory)
                                    @php
                                        $subSubGrpBalance = $preSubSubGrpBalance = $code = 0;
                                    @endphp
                                    @foreach ($data['bs_accountCodes'] as $accountCode)
                                        @if ($accountCode->additional_category_id == $additionalCategory->id && $code != $accountCode->code)
                                            @php
                                                $code = $accountCode->code;
                                                $ledgerBalance = $data['bs_ledgers']->where('chart_id', $accountCode->code)->sum('balance');
                                                $preLedgerBalance = $data['bs_preLedgers']->where('chart_id', $accountCode->code)->sum('balance');
                                            @endphp
                                            <tr>
                                                <td style="color: #1B6AAA">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ $accountCode->name }}
                                                </td>
                                                {{-- PRE YEAR CALCULATION --}}
                                                @if ($preLedgerBalance != 0)
                                                    @php
                                                        $preLedger = $data['bs_preLedgers']->where('chart_id', $accountCode->code)->first();
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
                                                                $preGtBalance = $preGtBalance += abs($preLedgerBalance);
                                                                $preSubSubGrpBalance = $preSubSubGrpBalance += abs($preLedgerBalance);
                                                                $preBlncType = '';
                                                            } elseif ($preLedger->balance_type == 1 && $preLedgerBalance < 0) {
                                                                $preGtBalance = $preGtBalance += abs($preLedgerBalance);
                                                                $preSubSubGrpBalance = $preSubSubGrpBalance += abs($preLedgerBalance);
                                                                $preBlncType = '';
                                                            } else {
                                                                $preGtBalance = $preGtBalance -= abs($preLedgerBalance);
                                                                $preSubSubGrpBalance = $preSubSubGrpBalance -= abs($preLedgerBalance);
                                                                $preBlncType = '-';
                                                            }
                                                        }

                                                        // For GST Clearing Account (912101)
                                                        // if ($accountCodeCategory->code == 9) {
                                                        //     if ($ledger->chart_id == 912101) {
                                                        //         if ($preLedger->balance_type == 1 && $preLedgerBalance > 0) {
                                                        //             $preGtBalance = $preGtBalance -= abs($preLedgerBalance);
                                                        //             $preSubSubGrpBalance = $preSubSubGrpBalance -= abs($preLedgerBalance);
                                                        //             $preBlncType = '-';
                                                        //         } else {
                                                        //             $preGtBalance = $preGtBalance += abs($preLedgerBalance);
                                                        //             $preSubSubGrpBalance = $preSubSubGrpBalance += abs($preLedgerBalance);
                                                        //             $preBlncType = '';
                                                        //         }
                                                        //     }
                                                        // }
                                                        // if ($subCategory->id == 16 && $accountCode->code == 999998) {
                                                        //     $preSubSubGrpBalance += $totalPrePl;
                                                        // } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
                                                        //     $preSubSubGrpBalance += $totalPreRetain;
                                                        // }

                                                    @endphp
                                                    <td style="text-align: right;color: #1B6AAA">
                                                        {{-- @if ($subCategory->name == 'Capital & Equity' && ($totalPrePl != 0 || $totalPreRetain != 0) && $cPreLoop == 10)
                                                            {{ number_format($totalPl + $totalPreRetain, 2) }}
                                                        @else --}}
                                                        {{ $preBlncType }}
                                                        {{ number_format(abs($preLedgerBalance), 2) }}
                                                        {{-- @endif --}}
                                                    </td>
                                                @else
                                                    <td style="text-align: right;color: #1B6AAA">0.00</td>
                                                @endif
                                                {{-- END PRE YEAR CALCULATION --}}

                                                {{-- CURRENT YEAR CALCULATION --}}
                                                @if ($ledgerBalance != 0)
                                                    @php
                                                        $ledger = $data['bs_ledgers']->where('chart_id', $accountCode->code)->first();
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
                                                        if ($subCategory->id == 16 && $accountCode->code == 999998) {
                                                            $subSubGrpBalance += $totalRetain;
                                                        } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
                                                            $subSubGrpBalance += $totalRetain;
                                                        }
                                                    @endphp
                                                    <td style="text-align: right;color: #1B6AAA">
                                                        {{-- @if ($ledger->chart_id == 999999)
                                                            {{ number_format($totalRetain, 2) }}
                                                        @elseif($ledger->chart_id == 999998)
                                                            {{ number_format($totalRetain, 2) }}
                                                        @else --}}
                                                        {{ $blncType }}
                                                        {{ number_format(abs($ledgerBalance), 2) }}
                                                        {{-- @endif --}}
                                                    </td>
                                                @else
                                                    <td style="text-align: right;color: #1B6AAA">0.00</td>
                                                @endif
                                                {{-- END CURRENT YEAR CALCULATION --}}

                                            </tr>
                                        @endif
                                    @endforeach
                                    @php
                                        $subGrpBalance += $subSubGrpBalance;
                                        $preSubGrpBalance += $preSubSubGrpBalance;
                                    @endphp
                                @endforeach
                                @if ($additionalCategory->name == 'Capital & Equity' || $additionalCategory->id == 76)
                                    {{-- P/L Appropriation a/c --}}
                                    <tr>
                                        <td style="color: #1B6AAA;padding-left: 70px !important">
                                            P/L Appropriation a/c</td>
                                        {{-- Pre PL --}}
                                        @if ($totalPrePl != 0)
                                            <td style="text-align: right;color: #1B6AAA">{{ nF2($totalPrePl) }}</td>
                                        @else
                                            <td style="text-align: right;color: #1B6AAA">0.00</td>
                                        @endif
                                        {{-- Current PL --}}
                                        @if ($totalPl != 0)
                                            <td style="text-align: right;color: #1B6AAA">{{ nF2($totalPl) }}</td>
                                        @else
                                            <td style="text-align: right;color: #1B6AAA">0.00</td>
                                        @endif
                                    </tr>

                                    {{-- Retain earning --}}
                                    <tr>
                                        <td style="color: #1B6AAA;padding-left: 70px !important">
                                            Retain earning</td>
                                        {{-- Pre Retain --}}
                                        @if ($totalPreRetain != 0)
                                            <td style="text-align: right;color: #1B6AAA">{{ nF2($totalPreRetain) }}
                                            </td>
                                        @else
                                            <td style="text-align: right;color: #1B6AAA">0.00</td>
                                        @endif

                                        {{-- Current Retain --}}
                                        @if ($totalRetain != 0)
                                            <td style="text-align: right;color: #1B6AAA">{{ nF2($totalRetain) }}</td>
                                        @else
                                            <td style="text-align: right;color: #1B6AAA">0.00</td>
                                        @endif
                                    </tr>
                                @endif
                                {{-- /For Retain Earning & Profit & Loss Account --}}

                                <tr>
                                    <td style="color: green;text-align:right">
                                        {{-- Total {{$subCategory->name}} --}}
                                    </td>
                                    <td style="color: green;text-align: right;font-weight: bold;">
                                        <span
                                            style="border-top:1px solid;border-bottom:1px solid;text-align:right;float: right;">
                                            @if (
                                                ($additionalCategory->name == 'Capital & Equity' || $additionalCategory->id == 76) &&
                                                    ($totalPrePl != 0 || $totalPreRetain != 0))
                                                {{ number_format($preSubGrpBalance + $totalPrePl + $totalPreRetain, 2) }}
                                            @else
                                                {{ number_format($preSubGrpBalance, 2) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td style="color: green;text-align: right;font-weight: bold;">
                                        <span
                                            style="border-top:1px solid;border-bottom:1px solid;text-align:right;float: right;">
                                            @if (
                                                ($additionalCategory->name == 'Capital & Equity' || $additionalCategory->id == 76) &&
                                                    ($totalPl != 0 || $totalRetain != 0))
                                                {{ number_format($subGrpBalance + $totalPl + $totalRetain, 2) }}
                                            @else
                                                {{ number_format($subGrpBalance, 2) }}
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
                                        style="border-top:1px solid;border-bottom-style:double;font-weight: bold;float: right;">
                                        @if ($accountCodeCategory->code != 5 && ($totalPrePl != 0 || $totalPreRetain != 0))
                                            {{ number_format($preGtBalance + $totalPrePl + $totalPreRetain, 2) }}
                                        @else
                                            {{ number_format($preGtBalance, 2) }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span
                                        style="border-top:1px solid;border-bottom-style:double;font-weight: bold;float: right;">
                                        @if ($accountCodeCategory->code != 5 && ($totalPl != 0 || $totalRetain != 0))
                                            {{ number_format($gtBalance + $totalPl + $totalRetain, 2) }}
                                        @else
                                            {{ number_format($gtBalance, 2) }}
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
