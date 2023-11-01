<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                {{-- <th class="text-center" style="width: 10%;">Account Code</th> --}}
                <th class="text-center" style="width: 30%;">Account Name</th>
                @foreach ($months as $month)
                    <th class="text-center">{{ $month }} </th>
                @endforeach
                <th style="width: 10%;">Total</th>
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
                        <b class="total-gross-profit-{{ $loop->iteration }}">{{ number_format(0, 2) }}</b>
                    </td>
                @endforeach
            </tr>
            <tr>
                <td colspan="100%">
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
                        // $total_     += $new_amount;

                        // $tb[$loop->iteration]   += $new_amount;
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
                        <b class="total-profit-after-{{ $loop->iteration }}">{{ number_format(0, 2) }}</b>
                    </td>
                @endforeach
            </tr>
            <tr class="text-right">
                <td>
                    <b>Gross profit margin</b>
                </td>

                @foreach ($months as $month)
                    <td>
                        <b class="profit-margin-{{ $loop->iteration }}">{{ number_format(0, 2) }}</b>
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
