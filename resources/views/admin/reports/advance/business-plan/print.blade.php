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
                    font-size: 10px !important;
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
                                        {{-- <th class="text-center" style="width: 10%;">Account Code</th> --}}
                                        <th class="text-center" style="width: 230px;">Account Name</th>
                                        @foreach ($months as $month)
                                        <th class="text-center">{{ $month }} </th>
                                        @endforeach
                                        <th class="text-center" style="width: 10%;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($incomes) && count($incomes) > 0)
                                    @include('admin.reports.advance.business-plan._table', [
                                    'items' => $incomes,
                                    'type' => 'Income',
                                    ])
                                    @endif
                                    @if (isset($goods_solds) && count($goods_solds) > 0)
                                    @include('admin.reports.advance.business-plan._table', [
                                    'items' => $goods_solds,
                                    'type' => 'Cost of Goods Sold',
                                    ])
                                    @endif
                                    <tr class="text-danger text-right">
                                        <td>
                                            <b>Gross Profit</b>
                                        </td>

                                        @foreach ($months as $month)
                                        <td>
                                            <b class="total-gross-profit-{{ $loop->iteration }}">{{ number_format(0, 2)
                                                }}</b>
                                        </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td colspan="14">
                                            <b class="text-info">@lang('Expenses')</b>
                                        </td>
                                    </tr>
                                    @if (isset($selling) && count($selling) > 0)
                                    @include('admin.reports.advance.business-plan._table', [
                                    'items' => $selling,
                                    'type' => 'Selling Overhead',
                                    ])
                                    @endif
                                    @if (isset($administrative) && count($administrative) > 0)
                                    @include('admin.reports.advance.business-plan._table', [
                                    'items' => $administrative,
                                    'type' => 'Administrative overhead',
                                    ])
                                    @endif
                                    @if (isset($general) && count($general) > 0)
                                    @include('admin.reports.advance.business-plan._table', [
                                    'items' => $general,
                                    'type' => 'General overhead',
                                    ])
                                    @endif
                                    @if (isset($financial) && count($financial) > 0)
                                    @include('admin.reports.advance.business-plan._table', [
                                    'items' => $financial,
                                    'type' => 'Financial overhead',
                                    ])
                                    @endif
                                    <tr class="text-danger text-right">
                                        <td>
                                            <b>Total Expenses</b>
                                        </td>

                                        @foreach ($months as $month)
                                        <td>
                                            <b class="total-exp-{{ $loop->iteration }}">{{ number_format(0, 2) }}</b>
                                        </td>
                                        @endforeach
                                    </tr>
                                    <tr class="text-right">
                                        <td>
                                            <b>Earnings Before Interest & Tax (EBIT)</b>
                                        </td>

                                        @foreach ($months as $month)
                                        <td>
                                            <b class="total-ebit-{{ $loop->iteration }}">{{ number_format(0, 2) }}</b>
                                        </td>
                                        @endforeach
                                    </tr>
                                    <tr class="text-right">
                                        <td>
                                            <b>Income tax and other tax</b>
                                        </td>

                                        @foreach (json_decode($tax->months) as $name => $month)
                                        @php
                                        $mnt = json_decode($month);
                                        $new_amount = $mnt->new_amount;
                                        // $total_ += $new_amount;

                                        // $tb[$loop->iteration] += $new_amount;
                                        @endphp
                                        <td class="total-tax-{{ $loop->iteration }}">{{ $new_amount }}</td>
                                        @endforeach
                                    </tr>
                                    <tr class="text-right">
                                        <td>
                                            <b>Profit after Tax</b>
                                        </td>

                                        @foreach ($months as $month)
                                        <td>
                                            <b class="total-profit-after-{{ $loop->iteration }}">{{ number_format(0, 2)
                                                }}</b>
                                        </td>
                                        @endforeach
                                    </tr>
                                    <tr class="text-right">
                                        <td>
                                            <b>Gross profit margin</b>
                                        </td>

                                        @foreach ($months as $month)
                                        <td>
                                            <b class="profit-margin-{{ $loop->iteration }}">{{ number_format(0, 2)
                                                }}</b>
                                        </td>
                                        @endforeach
                                    </tr>
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
