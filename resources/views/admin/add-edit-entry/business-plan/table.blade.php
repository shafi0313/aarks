<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center" rowspan="2" style="width: 10%;">Account Code</th>
                <th class="text-center" rowspan="2" style="width: 30%;">Account Name</th>
                <th class="text-center" colspan="2">Base/Last Year</th>
                @foreach ($months as $month)
                    <th class="text-center" colspan="2">{{ $month }} </th>
                    <input type="hidden" name="months[]" value="{{ $month }}">
                @endforeach
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                <td class="text-center">Amount</td>
                <td class="text-center" style="width: 200px">Percent</td>
                @foreach ($months as $month)
                    <td class="text-center" style="width: 200px">Percent</td>
                    <td class="text-center">Amount</td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $sum_balance = $balance = $salse_balance = 0;
                $l = 0;
            @endphp
            @foreach ($ledgers as $i => $first_loop)
                @foreach ($first_loop as $j => $ledger)
                    @php
                        $l++;
                        $code = $ledger->client_account_code;
                        // dd($ledger);
                        $balance = abs($ledger->trail_balance);
                        // $sum_balance += abs($balance);
                        $cat_id = $code->category_id;
                        
                        if ($i == 1) {
                            if ($ledger->balance_type == 1) {
                                if ($ledger->trail_balance < 0) {
                                    $salse_balance += $credit = abs($balance);
                                    $debit = 0;
                                } else {
                                    $salse_balance -= $debit = abs($balance);
                                    $credit = 0;
                                }
                            } elseif ($ledger->balance_type == 2) {
                                if ($ledger->trail_balance < 0) {
                                    $salse_balance -= $credit = abs($balance);
                                    $credit = 0;
                                } else {
                                    $salse_balance += $debit = abs($balance);
                                    $debit = 0;
                                }
                            }
                            if ($ledger->balance_type == 1) {
                                if (substr($code->code, -6, 1) == 1) {
                                    $balance = -$debit;
                                }
                            }
                        } else {
                            $sum_balance += abs($balance);
                        }
                    @endphp
                    @if ($balance != 0)
                        <input type="hidden" name="entries[{{ $code->code }}][account_code_id]"
                            value="{{ $code->id }}">
                        <input type="hidden" name="entries[{{ $code->code }}][chart_id]" value="{{ $code->code }}">
                        @if ($code->category_id == 1)
                            {{-- ! WITH SALES ! --}}
                            <tr class="with_sales">
                                <td style="text-align: center" id="A-{{ $l }}">{{ $code->code }}</td>
                                <td id="B-{{ $l }}">{{ $code->name }}</td>
                                <td style="text-align: right" id="C-{{ $l }}">
                                    {{ number_format(abs($balance), 2) }}
                                    <input type="hidden" name="entries[{{ $code->code }}][last_amount]"
                                        class="last_year_with_sales last_year_amount text-right"
                                        value="{{ $balance }}">
                                </td>
                                <td id="D-{{ $l }}">
                                    <input readonly value="100" class="form-control w-25 text-right" type="text"
                                        name="entries[{{ $code->code }}][last_percent]"
                                        aria-describedby="old_percent_{{ $i }}_{{ $j }}">
                                </td>
                                @php
                                    $a = 'D';
                                @endphp
                                @foreach ($months as $month)
                                    <td id="{{ ++$a }}-{{ $l }}">
                                        <input type="text"
                                            name="entries[{{ $code->code }}][{{ $month }}][new_percent]"
                                            class="with_sales_percent form-control w-25 text-right"
                                            aria-describedby="percent_{{ $i }}_{{ $j }}"
                                            step="any">
                                        {{-- {{$a}}{{$l}} --}}
                                    </td>
                                    <td style="text-align: right" id="{{ ++$a }}-{{ $l }}"
                                        class="sales">
                                        <span class="budget_amount">{{ number_format(0, 2) }}</span>
                                        {{-- {{$a}}{{$l}} --}}
                                        <input type="hidden" value="0"
                                            name="entries[{{ $code->code }}][{{ $month }}][new_amount]"
                                            class="budget_amount _amount-{{ $l }}">
                                    </td>
                                @endforeach
                                <td class="total_" id="total-{{ $l }}">
                                    <b class="total_">{{ number_format(0, 2) }}</b>
                                    <input type="hidden" value="0" name="total_[]" class="total_">
                                </td>
                            </tr>
                        @else
                            {{-- ! WITHOUT SALES ! --}}
                            <tr class="without_sales">
                                <td style="text-align: center" id="A-{{ $l }}">{{ $code->code }}</td>
                                <td id="B-{{ $l }}">{{ $code->name }}</td>
                                <td style="text-align: right" id="C-{{ $l }}">
                                    {{-- {{$balance}} --}}
                                    <input type="hidden" name="entries[{{ $code->code }}][last_amount]"
                                        class="last_year_amount text-right" value="{{ abs($balance) }}">
                                    {{ number_format(abs($balance), 2) }}
                                </td>
                                <td id="D-{{ $l }}">
                                    <input class="old_percent form-control text-right" style="width: 45px" type="text" readonly
                                        name="entries[{{ $code->code }}][last_percent]"
                                        value="{{ number_format(($balance / $salse_balance) * 100, 2) }}"
                                        aria-describedby="old_percent_{{ $i }}_{{ $j }}">
                                </td>
                                @php
                                    $b = 'D';
                                @endphp
                                @foreach ($months as $month)
                                    <td id="{{ ++$b }}-{{ $l }}">
                                        <input type="text" readonly
                                            name="entries[{{ $code->code }}][{{ $month }}][new_percent]"
                                            class="percent form-control w-25 text-right"
                                            aria-describedby="percent_{{ $i }}_{{ $j }}"
                                            step="any">
                                        {{-- {{$b}}{{$l}} --}}
                                    </td>
                                    <td style="text-align: right" id="{{ ++$b }}-{{ $l }}"
                                        class="other">
                                        <span class="budget_amount">{{ number_format(0, 2) }}</span>
                                        <input type="hidden" value="0"
                                            name="entries[{{ $code->code }}][{{ $month }}][new_amount]"
                                            class="budget_amount budget_amount_{{ $i }} _amount-{{ $l }}">
                                        {{-- {{$b}}{{$l}} --}}
                                    </td>
                                @endforeach

                                <td class="total_" id="total-{{ $l }}">
                                    <b class="total_">{{ number_format(0, 2) }}</b>
                                    <input type="hidden" value="0" name="total_[]" class="total_">
                                </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
                {{-- <tr>
            <td><b class="text-danger">{{number_format($sum_balance,2)}}</b> </td>
        </tr> --}}
                @if ($i == 1)
                    <tr class="text-right with_sales_total" style="font-size:16px">
                        <td colspan="2">
                            <b>Net Sale</b>
                        </td>
                        <td>
                            <input type="hidden" value="{{ $salse_balance }}" name="last_year_total"
                                class="last_year_total">
                            <b class="last_year_total">{{ number_format($salse_balance, 2) }}</b>
                        </td>
                        <td></td>
                        @php
                            $sum = 'D';
                        @endphp
                        @foreach ($months as $month)
                            <td id="sales_{{ ++$sum }}"></td>
                            <td id="sales_{{ ++$sum }}">
                                <input type="hidden" value="0" name="total_budget" class="total_budget">
                                <b class="total_budget">{{ number_format(0, 2) }}</b>
                            </td>
                        @endforeach
                        <td></td>
                    </tr>
                @endif
            @endforeach
            <tr class="text-right without_sales_total" style="font-size:16px">
                <td colspan="2">
                    <b>Total</b>
                </td>
                <td>
                    <input type="hidden" value="{{ $sum_balance }}" name="last_year_total[]"
                        class="last_year_total">
                    <b class="last_year_total">{{ number_format($sum_balance, 2) }}</b>
                </td>
                <td></td>
                @php
                    $sum = 'D';
                @endphp
                @foreach ($months as $month)
                    <td id="other_{{ ++$sum }}"></td>
                    <td id="other_{{ ++$sum }}">
                        <input type="hidden" value="0" name="total_budget[]" class="total_budget">
                        <b class="total_budget">{{ number_format(0, 2) }}</b>
                    </td>
                @endforeach
                <td></td>
            </tr>
            @include('admin.add-edit-entry.business-plan.income-tax-tbl', [
                'last_year_amount' => $salse_balance - $sum_balance,
            ])
        </tbody>
    </table>
</div>
