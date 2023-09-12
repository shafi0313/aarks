<div class="reportH">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h3 class="company_name">{{ $client->fullname }}</h3>
            <h5 class="report_name">BALANCE SHEET</h5>
            <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the year
                ended
                {{ $data['date']->format('d-M-Y') }}</h5>
        </div>

        <div class="col-12 card" style="padding-top:40px; ">
            <div class="card-body">
                <table class="table table-bordered table-hover table-striped" style="font-size: 14px;">
                    <tbody>
                        <tr>
                            <th class="text-center">Particulars</th>
                            <th class="text-right">Amount ($)</th>
                        </tr>

                        @php
                            $totalRetain = $data['bs_retain'] ? $data['bs_retain'] : 0;
                            $totalPl = $data['bs_plRetain'];
                        @endphp

                        @foreach ($data['bs_accountCodeCategories'] as $accountCodeCategory)
                            @php
                                $gtDebit = $gtCredit = $gtBalance = 0;
                            @endphp
                            @foreach ($accountCodeCategory->subCategoryWithoutAdditional->sortBy('code') as $subCategory)
                                @php
                                    $subGrpBalance = 0;
                                @endphp
                                <tr>
                                    <td style="color: green">
                                        <u>{{ $subCategory->name }} :</u>
                                    </td>
                                    <td></td>
                                </tr>
                                @foreach ($subCategory->additionalCategory->sortBy('code') as $additionalCategory)
                                    @php
                                        $subSubGrpBalance = 0;
                                    @endphp

                                    {{-- ? ACCOUNT CODE --}}

                                    @foreach ($data['bs_accountCodes'] as $accountCode)
                                        @if (
                                            $accountCode->additional_category_id == $additionalCategory->id &&
                                                $data['bs_ledgers']->where('chart_id', $accountCode->code)->sum('balance') != 0)
                                            @php
                                                $ledger = $data['bs_ledgers']->where('chart_id', $accountCode->code)->first();
                                                $ledgerBalance = $data['bs_ledgers']->where('chart_id', $accountCode->code)->sum('balance');
                                                
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
                                                    }else {
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
                                                    $subSubGrpBalance += $totalPl;
                                                } elseif ($subCategory->id == 16 && $accountCode->code == 999999) {
                                                    $subSubGrpBalance += $totalRetain;
                                                }
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td style="color: violet">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{ $additionalCategory->name }}
                                        </td>
                                        <td style="color: violet;text-align: right;">
                                            <span
                                                style="solid;border-bottom:1px solid;text-align:right;font-weight: bold;float:right">
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
                                        <span style="border-top:1px solid;border-bottom:1px solid;float:right">
                                            {{ number_format($subGrpBalance, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                {{-- Additonal Category End --}}
                            @endforeach
                            {{-- Sub Category End --}}
                            <tr style="text-align:right;color: #d35400;">
                                <td class="text-center" style="font-size: 15px;">Total {{ $accountCodeCategory->name }}:
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
