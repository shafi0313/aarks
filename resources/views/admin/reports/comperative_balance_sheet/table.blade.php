<table class="table table-bordered table-hover table-striped" style="font-size: 14px;">
    <tbody>
        <tr>
            <th>Particulars</th>
            <th class="text-right">{{ $date->format('Y') - 1 }}</th>
            <th class="text-right">{{ $date->format('Y') }}</th>
        </tr>

        @php
            $retain = $totalRetain;
            $plRetain = $totalPl;
            $preRetain = $totalPreRetain;
            $prePlRetain = $totalPrePl;
        @endphp
        @foreach ($accountCodeCategories as $accountCodeCategory)
            @php
                $gtDebit = $gtCredit = $gtBalance = $preGtBalance = 0;
            @endphp
            @foreach ($accountCodeCategory->subCategoryWithoutAdditional as $subCategory)
                @php
                    $subGrpBalance = $preSubGrpBalance = 0;
                @endphp
                <tr>
                    <td style="color: green">
                        <u>{{ $subCategory->name }} :</u>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($subCategory->additionalCategory as $additionalCategory)
                    @php
                        $subSubGrpBalance = $preSubSubGrpBalance = $code = 0;
                    @endphp
                    <tr>
                        <td style="color: violet">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{ $additionalCategory->name }} :
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    {{-- /ACCOUNT CODE --}}
                    @foreach ($accountCodes as $accountCode)
                        @if ($accountCode->additional_category_id == $additionalCategory->id && $code != $accountCode->code)
                            @php
                                $code = $accountCode->code;

                                // Pre Ledgers Data
                                $preLedger = $preLedgers->where('chart_id', $accountCode->code)->first();
                                $preLedgerBalance = $preLedgers->where('chart_id', $accountCode->code)->sum('balance');

                                // Current Ledgers Data
                                $ledger = $ledgers->where('chart_id', $accountCode->code)->first();
                                $ledgerBalance = $ledgers->where('chart_id', $accountCode->code)->sum('balance');

                                $blncType = '';
                            @endphp
                            <tr>
                                <td style="color: #1B6AAA">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    {{ $accountCode->name }}
                                </td>
                                @if ($preLedgerBalance != 0)
                                    @php
                                        $preLedger = $preLedgers->where('chart_id', $accountCode->code)->first();
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
                                        if ($accountCodeCategory->code == 9 && !in_array($accountCode->code, [999999, 999998, 912101])) {
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
                                        if ($accountCodeCategory->code == 9) {
                                            if ($ledger->chart_id == 912101) {
                                                if ($preLedger->balance_type == 2 && $preLedgerBalance > 0) {
                                                    $preGtBalance = $preGtBalance -= abs($preLedgerBalance);
                                                    $preSubSubGrpBalance = $preSubSubGrpBalance -= abs($preLedgerBalance);
                                                    $preBlncType = '-';
                                                } else {
                                                    $preGtBalance = $preGtBalance += abs($preLedgerBalance);
                                                    $preSubSubGrpBalance = $preSubSubGrpBalance += abs($preLedgerBalance);
                                                    $preBlncType = '';
                                                }
                                            }
                                        }

                                        // if ($subCategory->id == 16 && $accountCode->code == 999998) {
                                        //     $preSubSubGrpBalance += $prePlRetain;
                                        // } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
                                        //     $preSubSubGrpBalance += $preRetain;
                                        // }

                                    @endphp
                                    <td style="text-align: right;color: #1B6AAA">
                                        {{-- @if ($preLedger->chart_id == 999999)
                                            {{ number_format($preRetain, 2) }}
                                        @elseif($preLedger->chart_id == 999998)
                                            {{ number_format($prePlRetain, 2) }}
                                        @else --}}
                                        {{ $preBlncType }} {{ number_format(abs($preLedgerBalance), 2) }}
                                        {{-- @endif --}}
                                    </td>
                                @else
                                    <td style="text-align: right;color: #1B6AAA">0.00</td>
                                @endif
                                {{-- END PRE YEAR CALCULATION --}}

                                {{-- Current YEAR CALCULATION --}}
                                @if ($ledgerBalance != 0)
                                    @php
                                        $ledger = $ledgers->where('chart_id', $accountCode->code)->first();
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
                                        //     $subSubGrpBalance += $plRetain;
                                        // } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
                                        //     $subSubGrpBalance += $retain;
                                        // }
                                    @endphp
                                    <td style="text-align: right;color: #1B6AAA">
                                        {{-- @if ($ledger->chart_id == 999999)
                                            {{ number_format($retain, 2) }}
                                        @elseif($ledger->chart_id == 999998)
                                            {{ number_format($plRetain, 2) }}
                                        @else --}}
                                        {{ $blncType }} {{ number_format(abs($ledgerBalance), 2) }}
                                        {{-- @endif --}}
                                    </td>
                                @else
                                    <td style="text-align: right;color: #1B6AAA">0.00</td>
                                @endif
                                {{-- END CURRENT YEAR CALCULATION --}}

                            </tr>
                        @endif
                    @endforeach

                    @if ($additionalCategory->name == 'P/L Appropriation' || $additionalCategory->id == 76)
                        {{-- P/L Appropriation a/c --}}
                        <tr>
                            <td style="color: #1B6AAA;padding-left: 70px !important">
                                P/L Appropriation a/c</td>
                            {{-- Pre PL --}}
                            @if ($prePlRetain != 0)
                                <td style="text-align: right;color: #1B6AAA">{{ nF2($prePlRetain) }}</td>
                            @else
                                <td style="text-align: right;color: #1B6AAA">0.00</td>
                            @endif
                            {{-- Current PL --}}
                            @if ($plRetain != 0)
                                <td style="text-align: right;color: #1B6AAA">{{ nF2($plRetain) }}</td>
                            @else
                                <td style="text-align: right;color: #1B6AAA">0.00</td>
                            @endif
                        </tr>

                        {{-- Retain earning --}}
                        <tr>
                            <td style="color: #1B6AAA;padding-left: 70px !important">
                                Retain earning</td>
                            {{-- Pre Retain --}}
                            @if ($preRetain != 0)
                                <td style="text-align: right;color: #1B6AAA">{{ nF2($retain) }}</td>
                            @else
                                <td style="text-align: right;color: #1B6AAA">0.00</td>
                            @endif

                            {{-- Current Retain --}}
                            @if ($retain != 0)
                                <td style="text-align: right;color: #1B6AAA">{{ nF2($retain) }}</td>
                            @else
                                <td style="text-align: right;color: #1B6AAA">0.00</td>
                            @endif
                        </tr>
                    @endif
                    {{-- /For Retain Earning & Profit & Loss Account --}}

                    <tr>
                        <td style="color: violet;text-align:right">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Total {{ $additionalCategory->name }}
                        </td>
                        <td style="color: violet;text-align: right;">
                            <span style="solid;border-bottom:1px solid;text-align:right;font-weight: bold;">
                                @if ($additionalCategory->name == 'P/L Appropriation' || $additionalCategory->id == 76)
                                    {{-- For Retain Earning & Profit & Loss Account --}}
                                    {{ number_format($prePlRetain + $preRetain, 2) }}
                                @else
                                    {{ number_format($preSubSubGrpBalance, 2) }}
                                @endif
                                @php
                                    $preSubGrpBalance += $preSubSubGrpBalance;
                                @endphp
                            </span>
                        </td>
                        <td style="color: violet;text-align: right;">
                            <span style="solid;border-bottom:1px solid;text-align:right;font-weight: bold;">
                                @if ($additionalCategory->name == 'P/L Appropriation' || $additionalCategory->id == 76)
                                    {{-- For Retain Earning & Profit & Loss Account --}}
                                    {{ number_format($plRetain + $retain, 2) }}
                                @else
                                    {{ number_format($subSubGrpBalance, 2) }}
                                @endif
                                @php
                                    $subGrpBalance += $subSubGrpBalance;
                                @endphp
                            </span>
                        </td>
                    </tr>
                    {{-- Account Code End --}}
                @endforeach
                <tr>
                    <td style="color: green;text-align:right">
                        Total {{ $subCategory->name }}
                    </td>
                    <td style="color: green;text-align: right;font-weight: bold;">
                        <span style="border-top:1px solid;border-bottom:1px solid;text-align:right">
                            @if ($subCategory->name == 'Capital & Equity' || $subCategory->id == 16)
                                {{ number_format($preSubGrpBalance + $preRetain + $prePlRetain, 2) }}
                            @else
                                {{ number_format($preSubGrpBalance, 2) }}
                            @endif
                        </span>
                    </td>
                    <td style="color: green;text-align: right;font-weight: bold;">
                        <span style="border-top:1px solid;border-bottom:1px solid;text-align:right">
                            @if ($subCategory->name == 'Capital & Equity' || $subCategory->id == 16)
                                {{ number_format($subGrpBalance + $retain + $plRetain, 2) }}
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
                <td class="text-center" style="font-size: 16px;">Total {{ $accountCodeCategory->name }}</td>
                <td>
                    <span style="border-top:1px solid;border-bottom-style:double;font-weight: bold;">
                        @if ($accountCodeCategory->code == 5)
                            {{ number_format($preGtBalance, 2) }}
                        @else
                            {{ number_format($preGtBalance + $preRetain + $prePlRetain, 2) }}
                        @endif
                    </span>
                </td>
                <td>
                    <span style="border-top:1px solid;border-bottom-style:double;font-weight: bold;">
                        @if ($accountCodeCategory->code == 5)
                            {{ number_format($gtBalance, 2) }}
                        @else
                            {{ number_format($gtBalance + $retain + $plRetain, 2) }}
                        @endif
                    </span>
                </td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3" class="text-center">
                <b>Powered by <a href="https://aarks.com.au">AARKS</a> <a href="https://aarks.net.au">(ADVANCED
                        ACCOUNTING & RECORD KEEPING SOFTWARE)</a></b>
            </td>
        </tr>
        {{-- Category End --}}
    </tbody>
</table>
