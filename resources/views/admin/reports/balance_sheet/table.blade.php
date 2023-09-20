<table class="table table-bordered table-hover table-striped" style="font-size: 14px;">
    <tbody>
        <tr>
            <th class="text-center">Particulars</th>
            <th class="text-right">Amount ($)</th>
        </tr>

        @php
            $totalRetain = $retain ? $retain : 0;
        @endphp

        @foreach ($accountCodeCategories as $accountCodeCategory)
            @php
                $mainCodes = $accountCodes->where('category_id', $accountCodeCategory->id);
                $gtDebit = $gtCredit = $gtBalance = 0;
            @endphp
            @foreach ($accountCodeCategory->subCategoryWithoutAdditional as $subCategory)
                @php
                    $subCodes = $accountCodes->where('sub_category_id', $subCategory->id);
                    $subGrpBalance = 0;
                @endphp
                <tr>
                    <td style="color: green">
                        <u>{{ $subCategory->name }} :</u>
                    </td>
                    <td></td>
                </tr>
                @foreach ($subCategory->additionalCategory as $additionalCategory)
                    @php
                        $adSubCodes = $accountCodes->where('additional_category_id', $additionalCategory->id);
                        $subSubGrpBalance = 0;
                    @endphp
                    <tr>
                        <td style="color: violet">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{ $additionalCategory->name }} :
                        </td>
                        <td></td>
                    </tr>
                    {{-- // ACCOUNT CODE // --}}
                    @foreach ($accountCodes as $accountCode)
                        @if (
                            $accountCode->additional_category_id == $additionalCategory->id &&
                                $ledgers->where('chart_id', $accountCode->code)->sum('balance') != 0)
                            @php
                                $ledger = $ledgers->where('chart_id', $accountCode->code)->first();
                                $ledgerBalance = $ledgers->where('chart_id', $accountCode->code)->sum('balance');
                                
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
                                    $subSubGrpBalance += $plRetain;
                                } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
                                    $subSubGrpBalance += $totalRetain;
                                }
                            @endphp
                            <tr>
                                <td style="color: #1B6AAA;padding-left: 70px !important">
                                    {{ $accountCode->name }}
                                    {{-- {{$ledgers->where('chart_id', $accountCode->code)->count()}} --}}
                                </td>
                                <td style="text-align: right;color: #1B6AAA">
                                    {{-- @if ($ledger->chart_id == 999999)
                                        {{ $retain ? number_format($totalRetain, 2) : '0.00' }}
                                    @elseif($ledger->chart_id == 999998)
                                        {{ number_format($plRetain, 2) }}
                                    @else --}}
                                        {{ $blncType }} {{ number_format(abs($ledgerBalance), 2) }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    {{-- For Retain Earning & Profit & Loss Account --}}
                    {{-- @if($additionalCategory->name == 'P/L Appropriation')
                    <tr>                        
                        @if ($plRetain != 0)
                            <td style="color: #1B6AAA;padding-left: 70px !important">
                                P/L Appropriation a/c</td>
                            <td style="text-align: right;color: #1B6AAA">{{ nF2($plRetain) }}</td>
                        @endif
                        @if ($retain != 0)
                            <td style="color: #1B6AAA;padding-left: 70px !important">
                                Retain earning</td>
                            <td style="text-align: right;color: #1B6AAA">{{ nF2($retain) }}</td>
                        @endif
                    </tr>
                    @endif --}}
                    <tr>
                        <td style="color: violet;text-align:right">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Total {{ $additionalCategory->name }}
                        </td>
                        <td style="color: violet;text-align: right;">
                            <span style="solid;border-bottom:1px solid;text-align:right;font-weight: bold;">
                                {{ number_format($subSubGrpBalance, 2) }}
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
                            {{ number_format($subGrpBalance, 2) }}
                        </span>
                    </td>
                </tr>
                {{-- Additonal Category End --}}
            @endforeach
            {{-- Sub Category End --}}
            <tr style="text-align:right;color: #d35400;">
                <td class="text-center" style="font-size: 15px;">Total {{ $accountCodeCategory->name }}:</td>
                <td>
                    <span style="border-top:1px solid;border-bottom-style:double;font-weight: bold;">
                        @if ($accountCodeCategory->code == 5)
                            {{ number_format($gtBalance, 2) }}
                        @else
                            {{ number_format($gtBalance + $totalRetain + $plRetain, 2) }}
                        @endif
                    </span>
                </td>
            </tr>
        @endforeach
        {{-- Category End --}}

        <tr>
            <td colspan="2" class="text-center">
                <b>Powered by <a href="https://aarks.com.au">AARKS</a> <a href="https://aarks.net.au">(ADVANCED
                        ACCOUNTING & RECORD KEEPING SOFTWARE)</a></b>
            </td>
        </tr>
    </tbody>
</table>
