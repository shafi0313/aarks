<div class="row">
    <div class=" col-md-4 ">
        <div class="row">
            <table class="table table-bordered table-hover table-striped">
                <tr style="background:#178BFF; color:white;">
                    <th colspan="2" style="text-align:right;">
                        <samp style="padding-right:130px; color:white;">GST REPORT</samp>
                    </th>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">1A</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">1B</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">9</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">G1</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">G3</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">G10</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">G11</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">W1</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">W2</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">7C</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">7D</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">T1</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">Percentage
                        (%)
                    </td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">PAYG </td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">Net payable
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @foreach ($periods as $period)
        <div class=" col-md-1 ">
            <div class="row">
                <table class="table table-bordered table-hover table-striped text-right">
                    <tr style="background:#178BFF; color:white;">
                        <th colspan="2" style="text-align:right;">
                            {{ $period->end_date->format(aarks('frontend_date_format')) }}
                        </th>
                    </tr>
                    <!-----1a = ac 1 er gst total------>
                    @php
                        $income_gst = $income->where('period_id', $period->id)->sum('gst_cash_amount');
                    @endphp
                    <tr>
                        <td>{{ number_format($income_gst, 2) }}</td>
                    </tr>
                    <!----1b  = ac 2 - 4(inp total) n 6(cap total) (5 baad)------>
                    <tr>
                        @php
                            if (count($expense) > 0) {
                                $gst_item = $expense->where('period_id', $period->id)->first()?->gst_cash_amount;
                            } else {
                                $gst_item = 0;
                            }
                            if (count($sum95) > 0) {
                                $gst_95 = $sum95->where('period_id', $period->id)->first()?->gst_cash_amount;
                            } else {
                                $gst_95 = 0;
                            }
                            
                            $expense_gst = $gst_95 > 0 ? $gst_item - abs($gst_95) : $gst_item + abs($gst_95);
                        @endphp
                        <td>{{ number_format($expense_gst, 2) }}</td>
                    </tr>
                    <tr>
                        <td>{{ number_format($income_gst - $expense_gst, 2) }}</td>
                    </tr>
                    <!----------g1 = ac 1 gross total----->
                    @php
                        $income_gross = abs($income->where('period_id', $period->id)->sum('gross_amount'));
                    @endphp
                    <tr>
                        <td>{{ number_format($income_gross, 2) }}</td>
                    </tr>
                    <!----------g3 = ac 1 er free gross total----->
                    <tr>
                        <td>{{ number_format(abs($incomeNonGst->where('period_id', $period->id)->sum('net_amount')), 2) }}
                        </td>
                    </tr>
                    <!----------g10 ----->
                    <tr>
                        <td>{{ number_format(abs($asset->where('period_id', $period->id)->sum('gross_amount')), 2) }}
                        </td>
                    </tr>
                    <!----------g11 = ac 2 - 4 gross total----->
                    <tr>
                        <td>{{ number_format(abs($expense_code->where('period_id', $period->id)->sum('gross_amount')), 2) }}
                        </td>
                    </tr>
                    <!-----w1 = w1+w2 er gross total------->
                    <tr>
                        <td>{{ number_format(abs($w1->where('period_id', $period->id)->sum('gross_amount')), 2) }}
                        </td>
                    </tr>
                    <!----- w2 = w2 er gross total ------>
                    @php
                        $w2_gross = $w2->where('period_id', $period->id)->sum('gross_amount');
                    @endphp
                    <tr>
                        <td>{{ number_format(abs($w2_gross), 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">0.00</td>
                    </tr>
                    <!----- 7d = pore(between the perod add data  fuel Ltr X rate ) ------>
                    @php
                        $tax_ltr = $fuel_tax_ltr->where('period_id', $period->id)->sum('amount');
                    @endphp
                    <tr>
                        <td>{{ number_format(abs($tax_ltr), 2) }}</td>
                    </tr>
                    <!----- t1 = g1 - 1a ------>
                    <tr>
                        <td>{{ number_format($income_gross - $income_gst, 2) }}</td>
                    </tr>
                    <!-----percentage = add data theke asbe------>
                    @php
                        $payPersent = $payAmount = 0;
                    @endphp
                    @foreach ($payg as $item)
                        @if ($period->id == $item->period_id)
                            @php
                                $payPersent = $item->percent;
                                $payAmount = $item->amount;
                            @endphp
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $payPersent }}</td>
                    </tr>
                    <tr>
                        @php
                            $payg_percent = ($income_gross - $income_gst) * ($payPersent / 100);
                        @endphp
                        <td>
                            {{ $payPersent == '' ? $payAmount : $payg_percent }}
                        </td>
                    </tr>
                    <tr class="table-customm">
                        <td>
                            <!-- Total Payable = 1a - 1b + w2 - 7d + payg amount -->
                            @php
                                $total_payable = abs($income_gst) - abs($expense_gst) + abs($w2_gross) - abs($tax_ltr) + ($payPersent == '' ? abs($payAmount) : abs($payg_percent));
                            @endphp
                            <span style="color:#000000;">
                                @if ($total_payable < 0)
                                    <span style="color: red">{{ $total_payable }}</span>
                                @else
                                    <span>&nbsp;
                                        {{ number_format($total_payable, 2) }}</span>
                                @endif
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
</div>
