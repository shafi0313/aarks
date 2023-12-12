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
                    <td width="73%" style="color:#CC0066; text-align:center;">3</td>
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
                        (%) T7/T11
                    </td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">PAYG W2</td>
                </tr>
                <tr>
                    <td width="73%" style="color:#CC0066; text-align:center;">Net payable (9)
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @foreach ($periods as $period)
        <div class=" col-md-1 ">
            <div class="row">
                <table class="table table-bordered table-hover table-striped">
                    <tr style="background:#178BFF; color:white;">
                        <th colspan="2" style="text-align:right;">
                            {{ bdDate($period->end_date) }}
                        </th>
                    </tr>
                    <!-----1a = ac 1 er gst total------>
                    <div style="display: none">{{ $income_gst = 0 }}</div>
                    @foreach ($income as $item)
                        @if ($period->id == $item->period_id)
                            <td style="display: none">
                                {{ $income_gst += $item->gst_cash_amount }}</td>
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $income_gst }}</td>
                    </tr>
                    <!----1b  = ac 2 - 4(inp total) n 6(cap total) (5 baad)------>
                    <tr>
                        @php
                            if (count($expense) > 0) {
                                $gst_item = $expense->where('period_id', $period->id)?->first()->gst_cash_amount;
                            } else {
                                $gst_item = 0;
                            }
                            if (count($sum95) > 0) {
                                $gst_95 = $sum95->where('period_id', $period->id)?->first()->gst_cash_amount;
                            } else {
                                $gst_95 = 0;
                            }

                            $expense_gst = $expense_gst_amt = $gst_95 > 0 ? $gst_item - abs($gst_95) : $gst_item + abs($gst_95);
                        @endphp
                        <td>{{ $expense_gst }}</td>
                    </tr>


                    <tr>
                        <td>{{ $income_gst - $expense_gst }}</td>
                    </tr>


                    <!----------g1 = ac 1 gross total----->
                    <div style="display: none">{{ $income_gross = 0 }}</div>
                    @foreach ($income as $item)
                        @if ($period->id == $item->period_id)
                            <td style="display: none">
                                {{ $income_gross += $item->gross_amount }}</td>
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $income_gross }}</td>
                    </tr>
                    <!----------g3 = ac 1 er free gross total----->
                    <div style="display: none">{{ $nonGst = 0 }}</div>
                    @foreach ($incomeNonGst as $item)
                        @if ($period->id == $item->period_id)
                            <td style="display: none">{{ $nonGst += $item->net_amount }}
                            </td>
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $nonGst }}</td>
                    </tr>
                    <!----------g10 ----->
                    <div style="display: none">{{ $asset_gross = 0 }}</div>
                    @foreach ($asset as $item)
                        @if ($period->id == $item->period_id)
                            <td style="display: none">
                                {{ $asset_gross += $item->gross_amount }}</td>
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $asset_gross }}</td>
                    </tr>
                    <!----------g11 = ac 2 - 4 gross total----->
                    <div style="display: none">{{ $expense_code_betwen = 0 }}</div>
                    @foreach ($expense_code as $item)
                        @if ($period->id == $item->period_id)
                            <td style="display: none">
                                {{ $expense_code_betwen += $item->gross_amount }}</td>
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $expense_code_betwen }}</td>
                    </tr>
                    <!-----w1 = w1+w2 er gross total------->
                    <div style="display: none">{{ $w1_gross = 0 }}</div>
                    @foreach ($w1 as $item)
                        @if ($period->id == $item->period_id)
                            <td style="display: none">
                                {{ $w1_gross += $item->gross_amount }}</td>
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $w1_gross }}</td>
                    </tr>
                    <!----- w2 = w2 er gross total ------>
                    <div style="display: none">{{ $w2_gross = 0 }}</div>
                    @foreach ($w2 as $item)
                        @if ($period->id == $item->period_id)
                            <td style="display: none">
                                {{ $w2_gross += $item->gross_amount }}</td>
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $w2_gross }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">0</td>
                    </tr>
                    <!----- 7d = pore(between the perod add data  fuel Ltr X rate ) ------>
                    <div style="display: none">{{ $tax_ltr = 0 }}</div>
                    @foreach ($fuel_tax_ltr as $item)
                        @if ($period->id == $item->period_id)
                            <td style="display: none">{{ $tax_ltr += $item->amount }}
                            </td>
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $tax_ltr }}</td>
                    </tr>
                    <!----- t1 = g1 - 1a ------>
                    <tr>
                        <td>{{ $income_gross - $income_gst }}</td>
                    </tr>
                    <!-----percentage = add data theke asbe------>
                    <div style="display: none">{{ $payPersent = 0 }}</div>
                    <div style="display: none">{{ $payAmount = 0 }}</div>
                    @foreach ($payg as $item)
                        @if ($period->id == $item->period_id)
                            <td style="display: none">{{ $payPersent = $item->percent }}
                            </td>
                            <td style="display: none">{{ $payAmount = $item->amount }}
                            </td>
                        @endif
                    @endforeach
                    <tr>
                        <td>{{ $payPersent }}</td>
                    </tr>
                    <tr>
                        <div style="display: none">
                            {{ $payg_percent = ($income_gross - $income_gst) * ($payPersent / 100) }}
                        </div>
                        <td>
                            {{ $payPersent == '' ? $payAmount : $payg_percent }}
                        </td>
                    </tr>
                    <tr class="table-customm">
                        <td align="left">
                            <!-- Total Payable = 1a - 1b + w2 - 7d + payg amount -->
                            <span
                                style="display: none;">{{ $total_payable = $income_gst - $expense_gst + $w2_gross - $tax_ltr + ($payPersent == '' ? $payAmount : $payg_percent) }}</span>
                            <span style="color:#000000;">
                                @if ($total_payable < 0)
                                    <span style="color: red">{{ $total_payable }}</span>
                                @else
                                    <span>&nbsp; {{ $total_payable }}</span>
                                @endif
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
</div>
