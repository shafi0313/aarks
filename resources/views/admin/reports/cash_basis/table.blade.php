@if ($data->count() > 0)
    <table width="100%">
        <tr>
            <td width="48%" valign="top">
                <div style="text-align:center; font-size:18px; color:#0099FF;">ACTIVITY DETAILS</div>
                <table width="100%" class="table table-bordered table-striped table-hover table-custom">
                    <tr style="background-color:#6699FF !important; color:#FFFFFF;">
                        <td><b>Account Name</b></td>
                        <td><b>Amount</b></td>
                        <td><b>GST</b></td>
                    </tr>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                @php $datas = $item->first() @endphp
                                <th>{{ $datas->accountCodes->name }}</th>
                                <th class="text-right">{{ number_format($item->sum('gross_amount'), 2) }}</th>
                                <th class="text-right">{{ number_format($item->sum('gst_cash_amount'), 2) }}</th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            @php
                if ($payg) {
                    $payPersent = $payg->percent;
                    $payAmount = $payg->amount;
                } else {
                    $payPersent = 0;
                    $payAmount = 0;
                }
            @endphp
            <td width="2%" valign="top">
                <table width="100%">
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td valign="top" width="48%">
                <table width="100%" class="customfont">
                    <div style="text-align:center; font-size:18px; color:#0099FF;">ACTIVITY SUMMERY</div>
                    <tr>
                        <td width="50%">
                            <table width="100%" class="table table-bordered table-striped table-hover">
                                <tr style="background-color:#6699FF !important; color:#FFFFFF;">
                                    <td width="40%"><b>Code</b></td>
                                    <td width="60%"><b>Amount</b></td>
                                </tr>
                                <tbody>
                                    <!----------g1 = ac 1 gross total----->
                                    <tr>
                                        <td style="color:#6633FF;">G1</td>
                                        <td>
                                            @php
                                                $income_gross = $income->sum('gross_amount');
                                            @endphp
                                            {{ number_format(abs($income_gross), 2) }}
                                        </td>
                                    </tr>
                                    <!----------g3 = ac 1 er free gross total----->
                                    <tr>
                                        <td style="color:#6633FF;">G3</td>
                                        <td>
                                            @if ($incomeNonGst)
                                                {{ number_format(abs($incomeNonGst->sum('net_amount')), 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <!----------g10 ----->
                                    <tr>
                                        <td style="color:#6633FF;">G10</td>
                                        <td>{{ number_format(abs($asset), 2) }}</td>
                                    </tr>
                                    <!----------g11 = ac 2 - 4 gross total----->
                                    <tr>
                                        <td style="color:#6633FF;">G11</td>
                                        <td>{{ number_format(abs($expense_code->sum('gross_amount')), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td align="center">-</td>
                                        <td align="center">-</td>
                                    </tr>
                                    <!-----w1 = w1+w2 er gross total------->
                                    <tr>
                                        <td style="color:#6633FF;">W1</td>
                                        <td>{{ number_format(abs($w1->sum('gross_amount')), 2) }}</td>
                                    </tr>
                                    <!-----Pore------>
                                    <tr>
                                        <td style="color:#6633FF;">7C</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td align="center">-</td>
                                        <td align="center">-</td>
                                    </tr>
                                    <!-----percentage = add data theke asbe------>
                                    <tr>
                                        <td style="color:#6633FF;">PERCENTAGE(%)</td>
                                        <td>{{ abs($payPersent) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="50%">
                            <table width="100%" class="table table-bordered table-striped table-hover">
                                <tr style="background-color:#6699FF !important; color:#FFFFFF;">
                                    <td width="40%"><b>Code</b></td>
                                    <td width="60%"><b>Amount</b></td>
                                </tr>
                                <tbody>
                                    <!-----1a = ac 1 er gst total------>
                                    <tr>
                                        <td style="color:#6633FF;">1A</td>
                                        <td>
                                            @php
                                                $income_cash = $income->sum('gst_cash_amount');
                                            @endphp
                                            {{ number_format(abs($income_cash), 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">-</td>
                                        <td align="center">-</td>
                                    </tr>
                                    <tr>
                                        <td align="center">-</td>
                                        <td align="center">-</td>
                                    </tr>
                                    <!----1b  = ac 2 - 4(inp total) n 6(cap total) (5 baad)------>
                                    <tr>
                                        <td style="color:#6633FF;">1B</td>
                                        <td>{{ $expense }}</td>
                                    </tr>
                                    <!----- 9  = 1A-1B------>
                                    <tr>
                                        <td style="color:#6633FF;">9</td>
                                        <td>
                                            @php
                                                $diff_in_ex = abs($income_cash) - abs($expense);
                                            @endphp
                                            {{ number_format($diff_in_ex,2) }}
                                        </td>
                                    </tr>
                                    <!----- w2 = w2 er gross total ------>
                                    <tr>
                                        <td style="color:#6633FF;">W2</td>
                                        <td>{{ number_format(abs($w2->sum('gross_amount')), 2) }}</td>
                                    </tr>
                                    <!----- 7d = pore(between the perod add data  fuel Ltr X rate ) ------>
                                    <tr>
                                        <td style="color:#6633FF;">7D</td>
                                        <td>{{ number_format(abs($fuel_tax_ltr), 2) }}</td>
                                    </tr>
                                    <!----- t1 = g1 - 1a ------>
                                    <tr>
                                        <td style="color:#6633FF;">T1</td>
                                        <td>{{ number_format(abs($income_gross - $income_cash), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color:#6633FF;">PAYG</td>
                                        <div style="display: none">
                                            {{ $payg_percent = ($income_gross - $income_cash) * ($payPersent / 100) }}
                                        </div>
                                        <td>
                                            {{ number_format(abs($payPersent == '' ? $payAmount : $payg_percent), 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="table-customm">
                        <td align="right">
                            <b><span style="color:#000000;">Total Payable: </span></b>
                        </td>
                        <td align="left">
                            <b>
                                <!-- Total Payable = 1a - 1b + w2 - 7d + payg amount -->
                                @php
                                    $total_payable = abs($income_cash) - abs($expense) + abs($w2->sum('gross_amount')) - abs($fuel_tax_ltr) + ($payPersent == '' ? abs($payAmount) : abs($payg_percent));
                                @endphp
                                <span style="color:#000000;">
                                    @if ($total_payable < 0)
                                        <span style="color: red">{{ $total_payable }}</span>
                                    @else
                                        <span>&nbsp;
                                            {{ number_format($total_payable, 2) }}</span>
                                    @endif
                                </span>
                            </b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@else
    <h1 class="display-1 text-danger text-center text-uppercase">SORRY NO DATA FOUND!</h1>
@endif
