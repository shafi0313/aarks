<table class="table table-bordered">
    <thead>
        <tr>
            <td class="text-center" rowspan="2" style="width: 10%;">Account Code</td>
            <td class="text-center" rowspan="2" style="width: 30%;">Account Name</td>
            <td class="text-center" colspan="2" style="width: 30%;">Last Year ({{ \Carbon\Carbon::parse($date)->format('Y') }})</td>
            <td class="text-center" colspan="2" style="width: 30%;">Budget Year ({{ \Carbon\Carbon::parse($date)->addYear()->format('Y') }})</td>
        </tr>
        <tr>
            <td class="text-center" style="width: 18%;">Amount</td>
            <td class="text-center" style="width: 12%">Percent</td>
            <td class="text-center" style="width: 12%">Proposed %</td>
            <td class="text-center" style="width: 18%;">Amount</td>
        </tr>
    </thead>
    <tbody>
        @php
            $sum_balance = $balance = $salse_balance = 0;
        @endphp
        @foreach ($ledgers as $i => $first_loop)
            @foreach ($first_loop as $j => $ledger)
                @php
                    $code = $ledger->client_account_code;
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
                    <input type="hidden" name="entries[chart_id][]" value="{{ $code->code }}">
                    @if ($code->category_id == 1)
                        <tr class="with_sales">
                            <td style="text-align: center">{{ $code->code }}</td>
                            <td>{{ $code->name }}</td>
                            <td style="text-align: right">
                                {{-- {{$balance}} --}}
                                <input type="hidden" name="entries[last_year_amount][]"
                                    class="last_year_with_sales last_year_amount text-right"
                                    value="{{ $balance }}">
                                {{ number_format(abs($balance), 2) }}
                            </td>
                            <td>
                                <div class="input-group ">
                                    <input readonly value="100" class="form-control w-25 text-right" type="number"
                                        name="entries[old_percent][]"
                                        aria-describedby="old_percent_{{ $i }}_{{ $j }}">
                                    <span class="input-group-addon"
                                        id="old_percent_{{ $i }}_{{ $j }}">&percnt;</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="entries[percent][]"
                                        class="with_sales_percent form-control w-25 text-right"
                                        aria-describedby="percent_{{ $i }}_{{ $j }}"
                                        step="any">
                                    <span class="input-group-addon"
                                        id="percent_{{ $i }}_{{ $j }}">&percnt;</span>
                                </div>
                            </td>
                            <td style="text-align: right">
                                <span class="budget_amount">{{ number_format(0, 2) }}</span>
                                <input type="hidden" value="0" name="entries[budget_amount][]"
                                    class="budget_amount">
                            </td>
                        </tr>
                    @else
                        <tr class="without_sales">
                            <td style="text-align: center">{{ $code->code }}</td>
                            <td>{{ $code->name }}</td>
                            <td style="text-align: right">
                                {{-- {{$balance}} --}}
                                <input type="hidden" name="entries[last_year_amount][]"
                                    class="last_year_amount text-right" value="{{ abs($balance) }}">
                                {{ number_format(abs($balance), 2) }}
                            </td>
                            <td>
                                <div class="input-group">
                                    <input class="old_percent form-control w-25 text-right" type="number" disabled
                                        name="entries[old_percent][]"
                                        value="{{ number_format(($balance / $salse_balance) * 100, 4) }}"
                                        aria-describedby="old_percent_{{ $i }}_{{ $j }}">
                                    <span class="input-group-addon"
                                        id="old_percent_{{ $i }}_{{ $j }}">&percnt;</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" disabled name="entries[percent][]"
                                        class="percent form-control w-25 text-right"
                                        aria-describedby="percent_{{ $i }}_{{ $j }}"
                                        step="any">
                                    <span class="input-group-addon"
                                        id="percent_{{ $i }}_{{ $j }}">&percnt;</span>
                                </div>
                            </td>
                            <td style="text-align: right">
                                <span class="budget_amount">{{ number_format(0, 2) }}</span>
                                <input type="hidden" value="0" name="entries[budget_amount][]"
                                    class="budget_amount budget_amount_{{ $i }}">
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
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>
                        <input type="hidden" value="0" name="total_budget" class="total_budget">
                        <b class="total_budget">{{ number_format(0, 2) }}</b>
                    </td>
                </tr>
            @endif
        @endforeach
        <tr class="text-right without_sales_total" style="font-size:16px">
            <td colspan="2">
                <b>Total</b>
            </td>
            <td>
                <input type="hidden" value="{{ $sum_balance }}" name="last_year_total" class="last_year_total">
                <b class="last_year_total">{{ number_format($sum_balance, 2) }}</b>
            </td>
            <td colspan="2"></td>
            <td>
                <input type="hidden" value="0" name="total_budget" class="total_budget">
                <b class="total_budget">{{ number_format(0, 2) }}</b>
            </td>
        </tr>
        <tr class="text-center" style="font-size:16px; font-weight: bold">
            <td colspan="2" class="text-right">Profit/Loss as at
                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
            @if ($CRetains)
                <td colspan="2">
                    {{ number_format($CRetains, 2) }}
                </td>
            @else
                <td colspan="2">{{ number_format(0, 2) }}</td>
            @endif
            <td colspan="2" class="text-right">
                <span class="budget_pl ">
                    {{ number_format(0, 2) }}
                </span>
            </td>
        </tr>
    </tbody>
</table>
