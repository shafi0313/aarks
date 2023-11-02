<table class="table table-bordered">
    <thead>
        <tr>
            <td class="text-center" rowspan="2" style="width: 10%;">Account Code</td>
            <td class="text-center" rowspan="2" style="width: 30%;">Account Name</td>
            <td class="text-center" colspan="2" style="width: 30%;">Last Year ({{ \Carbon\Carbon::parse($date)->format('Y') }})</td>
            <td class="text-center" colspan="2" style="width: 30%;">Budget Year ({{ \Carbon\Carbon::parse($date)->addYear()->format('Y') }})</td>
        </tr>
        <tr>
            <td class="text-center" style="width: 15%;">Amount</td>
            <td class="text-center" style="width: 15%">Percent</td>
            <td class="text-center" style="width: 15%">Proposed %</td>
            <td class="text-center" style="width: 15%;">Amount</td>
        </tr>
    </thead>
    <tbody>
        @php
            $other_balance = $balance = $salse_balance = $sum_sales_budget_amount = $sum_without_sales_budget_amount = $sum_expense_budget_amount = 0;
        @endphp

        @foreach ($currentBudgets as $i => $budgets)
            @foreach ($budgets as $j => $budget)
                @php
                    $code = $budget->chart;
                    $balance = $budget->last_year_amount;
                    $salse_balance += $balance;
                    if ($i != 1) {
                        $other_balance += abs($balance);
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
                                    class="last_year_with_sales last_year_amount" value="{{ $balance }}">
                                {{ number_format(abs($balance), 2) }}
                            </td>
                            <td>
                                <div class="input-group">
                                    <input readonly value="100" class="form-control w-25" type="number"
                                        name="entries[old_percent][]"
                                        aria-describedby="old_percent_{{ $i }}_{{ $j }}">
                                    <span class="input-group-addon"
                                        id="old_percent_{{ $i }}_{{ $j }}">&percnt;</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="entries[percent][]"
                                        class="with_sales_percent form-control w-25" value="{{ $budget->percent ?? 0 }}"
                                        aria-describedby="percent_{{ $i }}_{{ $j }}"
                                        step="any">
                                    <span class="input-group-addon"
                                        id="percent_{{ $i }}_{{ $j }}">&percnt;</span>
                                </div>
                            </td>
                            <td style="text-align: right">
                                @php
                                    $sales_budget_amount = ($budget->last_year_amount * $budget->percent) / 100;
                                    $sum_sales_budget_amount += $sales_budget_amount;
                                @endphp
                                <span class="budget_amount">{{ number_format(abs($sales_budget_amount), 2) }}</span>
                                <input type="hidden" value="{{ $sales_budget_amount }}"
                                    name="entries[budget_amount][]" class="budget_amount">
                            </td>
                        </tr>
                    @else
                        <tr class="without_sales">
                            <td style="text-align: center">{{ $code->code }}</td>
                            <td>{{ $code->name }}</td>
                            <td style="text-align: right">
                                {{-- {{$balance}} --}}
                                <input type="hidden" name="entries[last_year_amount][]" class="last_year_amount"
                                    value="{{ abs($balance) }}">
                                {{ number_format(abs($balance), 2) }}
                            </td>
                            <td>
                                <div class="input-group">
                                    <input class="old_percent form-control w-25" type="number" step="any"
                                        name="entries[old_percent][]"
                                        value="{{ number_format($budget->old_percent ?? 0, 4) }}"
                                        aria-describedby="old_percent_{{ $i }}_{{ $j }}">
                                    <span class="input-group-addon"
                                        id="old_percent_{{ $i }}_{{ $j }}">&percnt;</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" value="{{ $budget->percent ?? 0 }}" name="entries[percent][]"
                                        class="percent form-control w-25"
                                        aria-describedby="percent_{{ $i }}_{{ $j }}"
                                        step="any">
                                    <span class="input-group-addon"
                                        id="percent_{{ $i }}_{{ $j }}">&percnt;</span>
                                </div>
                            </td>
                            <td style="text-align: right">
                                @php
                                    $without_sales_budget_amount = ($sum_sales_budget_amount * $budget->percent) / 100;
                                    $sum_without_sales_budget_amount += $without_sales_budget_amount;
                                    if ($i == 2) {
                                        $sum_expense_budget_amount += $without_sales_budget_amount;
                                    }
                                @endphp
                                <span
                                    class="budget_amount">{{ number_format(abs($without_sales_budget_amount), 2) }}</span>
                                <input type="hidden" value="{{ $without_sales_budget_amount }}"
                                    name="entries[budget_amount][]"
                                    class="budget_amount budget_amount_{{ $i }}">
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach
            {{-- <tr>
            <td><b class="text-danger">{{number_format($other_balance,2)}}</b> </td>
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
                        <input type="hidden" value="{{ $sum_sales_budget_amount }}" name="total_budget"
                            class="total_budget">
                        <b class="total_budget">{{ number_format(abs($sum_sales_budget_amount), 2) }}</b>
                    </td>
                </tr>
            @endif
        @endforeach
        <tr class="text-right without_sales_total" style="font-size:16px">
            <td colspan="2">
                <b>Total</b>
            </td>
            <td>
                <input type="hidden" value="{{ $other_balance }}" name="last_year_total" class="last_year_total">
                <b class="last_year_total">{{ number_format($other_balance, 2) }}</b>
            </td>
            <td colspan="2"></td>
            <td>
                <input type="hidden" value="{{ $sum_without_sales_budget_amount }}" name="total_budget"
                    class="total_budget">
                <b class="total_budget">{{ number_format($sum_without_sales_budget_amount, 2) }}</b>
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
            <td colspan="2">
                @php($pl = abs($sum_sales_budget_amount) - abs($sum_expense_budget_amount))
                <span class="budget_pl">
                    {{ number_format($pl, 2) }}
                </span>
            </td>
        </tr>
    </tbody>
</table>
