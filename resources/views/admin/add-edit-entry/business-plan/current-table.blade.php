<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center" rowspan="2" style="width: 10%;">Account Code</th>
                <th class="text-center" rowspan="2" style="width: 30%;">Account Name</th>
                <th class="text-center" colspan="2">Last Year</th>
                @foreach ($months as $month)
                    <th class="text-center" colspan="2">{{ $month }} </th>
                    <input type="hidden" name="months[]" value="{{ $month }}">
                @endforeach
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                <td class="text-center">Amount</td>
                <td class="text-center">Percent</td>
                @foreach ($months as $month)
                    <td class="text-center">Percent</td>
                    <td class="text-center">Amount</td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $l = 0;
            @endphp
            @foreach ($currentPlans as $i => $first_loop)
                @php
                    $type = $i == 1 ? 'sales' : 'other';
                    $total_budget = $last_year_total = 0;
                @endphp
                @foreach ($first_loop as $j => $plan)
                    @php
                        $l++;
                        $code = $plan->chart;
                        $last_year_total += $plan->last_amount;
                    @endphp
                    <input type="hidden" name="entries[{{ $code->code }}][account_code_id]"
                        value="{{ $code->id }}">
                    <input type="hidden" name="entries[{{ $code->code }}][chart_id]" value="{{ $code->code }}">

                    <tr class="{{ substr($code->code, -6, 1) == 1 ? 'with_sales' : 'without_sales' }}">
                        @if ($code->code == 999999)
                            <td style="text-align: center" id="A-{{ $l }}"></td>
                            <td id="B-{{ $l }}">Income tax and other tax:</td>
                        @else
                            <td style="text-align: center" id="A-{{ $l }}">{{ $code->code }} </td>
                            <td id="B-{{ $l }}">{{ $code->name }}</td>
                        @endif
                        <td style="text-align: right" id="C-{{ $l }}">
                            <input type="text" id="last_year_amount-{{ $l }}"
                                name="entries[{{ $code->code }}][last_amount]"
                                class="last_year_with_sales last_year_amount text-right"
                                value="{{ $plan->last_amount }}">
                        </td>
                        <td id="D-{{ $l }}">
                            <input value="{{ $plan->last_percent }}" id="old_percent-{{ $l }}"
                                class="form-control text-right last_year_percent" type="text"
                                name="entries[{{ $code->code }}][last_percent]">
                        </td>
                        @php
                            $a = 'D';
                            $total_ = 0;
                        @endphp
                        @foreach (json_decode($plan->months) as $name => $month)
                            @php
                                $mnt = json_decode($month);
                                $new_amount = $mnt->new_amount;
                                $new_percent = $mnt->new_percent;
                                $total_ += $new_amount;
                            @endphp
                            <td id="{{ ++$a }}-{{ $l }}">
                                {{-- {{$a}}{{$l}} --}}
                                <input type="text" value="{{ $new_percent }}"
                                    name="entries[{{ $code->code }}][{{ $name }}][new_percent]"
                                    class="with_sales_percent form-control text-right month_percent-{{ $l }}">
                            </td>
                            <td style="text-align: right" id="{{ ++$a }}-{{ $l }}"
                                class="{{ $type }}">
                                {{-- {{$a}}{{$l}} --}}
                                <span class="budget_amount">{{ number_format($new_amount, 2) }}</span>
                                <input type="hidden" value="{{ $new_amount }}"
                                    name="entries[{{ $code->code }}][{{ $name }}][new_amount]"
                                    class="budget_amount _amount-{{ $l }}">
                            </td>
                        @endforeach
                        @php
                            $total_budget += $new_amount;
                        @endphp
                        <td class="total_" id="total-{{ $l }}">
                            <b class="total_">{{ number_format($total_, 2) }}</b>
                            <input type="hidden" value="{{ $total_ }}" name="total_[]" class="total_">
                        </td>
                    </tr>
                @endforeach

                <tr class="text-right {{ $type }} " style="font-size:16px">
                    <td colspan="2" id="{{ $type }}-B">
                        <b>Total: </b>
                    </td>
                    <td id="{{ $type }}_C">
                        <input type="hidden" value="{{ $last_year_total }}" name="last_year_total[]"
                            class="last_year_total">
                        <b class="last_year_total">{{ number_format($last_year_total, 2) }}</b>
                    </td>
                    <td id="{{ $type }}_D"></td>
                    @php
                        $sum = 'D';
                    @endphp
                    @foreach ($months as $month)
                        <td id="{{ $type }}_{{ ++$sum }}"></td>
                        <td id="{{ $type }}_{{ ++$sum }}">
                            <input type="hidden" value="{{ $total_budget }}" name="total_budget[]"
                                class="total_budget">
                            <b class="total_budget">{{ number_format($total_budget, 2) }}</b>
                        </td>
                    @endforeach
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
