<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Business Plan</title>
        @include('frontend.print-css')
        <style>
                table {
                    border-spacing: 0;
                    border-collapse: none;
                    width: 100% !important;
                }
                table tr, table td, table th {
                    font-size: 9.5px !important;
                }

        </style>
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="text-center">
                        <h2 style="padding: 0;margin:0"><b>{{ $client->full_name }}</b></h2>
                        <h2 style="padding: 0;margin:0"><b>ABN {{ $client->abn_number }}</b></h2>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-right" rowspan="2" style="width: 10%;">Account Code</th>
                                    <th class="text-right" rowspan="2" style="width: 30%;">Account Name</th>
                                    @foreach ($months as $month)
                                    <th class="text-right" colspan="2">{{$month}} </th>
                                    @endforeach
                                    <th rowspan="2">Total</th>
                                </tr>
                                <tr>
                                    @foreach ($months as $month)
                                    <td class="text-right">Amount</td>
                                    <td class="text-right">Percent</td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                    @php
                                        $July=$August=$September=$October=$November=$December=$January=$February=$March=$April=$May=$June= $_total = 0;
                                    @endphp
                                @forelse ($ledgers->sortBy('id') as $first_loop)
                                @php
                                    $ledger = $first_loop->first();
                                    $code = $ledger->client_account_code;
                                    // info($code->name. ' <=> ' .$code->code);
                                @endphp
                                <tr>
                                    <td>{{$code->code}}</td>
                                    <td>{{$code->name}}</td>
                                    @php
                                        $_total = 0;
                                    @endphp
                                    @foreach ($months as $key => $month)
                                        @php
                                            $ledgerMonth = $first_loop->where('month', $key)->first();
                                        @endphp
                                        @if ($ledgerMonth)
                                        @php
                                            $_total += $ledgerMonth->_balance;
                                        @endphp
                                            <td class="text-right"> {{abs_number($ledgerMonth->_balance)}} </td>
                                            @if ($code->category_id == 1)
                                            @php
                                                ${explode(" ", $month)[0]} += $ledgerMonth->_balance;
                                            @endphp
                                            <td class="text-right">100</td>
                                            @else
                                            <td class="text-right">{{number_format(($ledgerMonth->_balance*100)/ (${explode(" ", $month)[0]}),4)}}</td>
                                            @endif
                                        @else
                                            <td class="text-right"> 0.00 </td>
                                            <td class="text-right">0.00</td>
                                        @endif
                                    @endforeach
                                    <td class="text-right">{{abs_number($_total)}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="100%">No Data Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{asset('admin/assets/cdn/jquery.min.js')}}"></script>
        <script>
            $(function() {
                $('[id^="income-"]').each(function() {
                    var id = $(this).attr('id');
                    var split = id.split('-');
                    var type = split[0];
                    var number = split[1];
                    var value = $(this).val() ?? 0;
                    var goods = $("#cost-of-goods-sold-" + number).val() ?? 0;
                    var gross_profit = parseFloat(value) - parseFloat(goods);
                    $(".total-gross-profit-" + number).html(gross_profit.toFixed(2));

                    // Calculate total Expenses
                    var selling = $("#selling-overhead-" + number).val() ?? 0;
                    var administrative = $("#administrative-overhead-" + number).val() ?? 0;
                    var general = $("#general-overhead-" + number).val() ?? 0;
                    var financial = $("#financial-overhead-" + number).val() ?? 0;
                    var total_exp = parseFloat(selling) + parseFloat(administrative) + parseFloat(general) +
                        parseFloat(financial);
                    $(".total-exp-" + number).html(total_exp.toFixed(2));

                    // Calculate EBIT
                    var ebit = parseFloat(gross_profit) - parseFloat(total_exp);
                    $(".total-ebit-" + number).html(ebit.toFixed(2));

                    // Profit after Tax
                    var tax = $(".total-tax-" + number).text() ?? 0;
                    var profit_after_tax = parseFloat(ebit) - parseFloat(tax);
                    $(".total-profit-after-" + number).html(profit_after_tax.toFixed(2));

                    // Gross profit  margin
                    var gross_profit_margin = parseFloat(gross_profit) / parseFloat(value) * 100;
                    $(".profit-margin-" + number).html(gross_profit_margin.toFixed(2));
                });
            });
        </script>
    </body>

</html>
