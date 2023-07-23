
<div class="table-responsive cashflow-tbl">
    <table class="table table-bordered">
        <thead>
            <tr>
                {{-- <th class="text-center" style="width: 10%;">Account Code</th> --}}
                <th class="text-center" style="width: 30%;" rowspan="2">Receipt</th>
                @foreach ($months as $month)
                    <th class="text-center" colspan="2">{{ $month }} </th>
                @endforeach
                <th style="width: 10%;" colspan="2">Total</th>
            </tr>
            <tr>
                @foreach ($months as $month)
                <td class="text-center">Expected</td>
                <td class="text-center">Actual</td>
                @endforeach
                <td class="text-center">Expected</td>
                <td class="text-center">Actual</td>
            </tr>
        </thead>
        <tbody>
            {{-- @if (isset($incomes) && count($incomes) > 0)
                @include('admin.reports.advance.business-plan._table', [
                    'items' => $incomes,
                    'type' => 'Income',
                ])
            @endif --}}
@php
    $payments = [
        'Cost of Goods Sold',
        'Selling Overhead',
        'Administrative Overhead',
        'General Overhead',
        'Financial Overhead',
    ];
@endphp
<tr>
    <td><b>(A) Total Receipts</b></td>
    @for ($i = 1; $i < 14; $i++)
        <td class="total-receipt-{{$i}}">0.00</td>
        <td>0.00</td>
    @endfor
</tr>
<tr>
    <td colspan="100%">&nbsp;</td>
</tr>
<tr>
    <td colspan="100%" class="text-success"><b>Less Payments*</b></td>
</tr>
@foreach ($payments as $payment)
<tr>
    <td>{{$payment}}</td>
    @for ($i = 1; $i < 14; $i++)
        <td class="{{Str::slug($payment)}}-{{$i}} less-{{$i}}">0.00</td>
        <td>0.00</td>
    @endfor
</tr>
@endforeach
<tr>
    <td><b>(B) Total Less Payments</b></td>
    @for ($i = 1; $i < 14; $i++)
        <td class="total-lessp-{{$i}}">0.00</td>
        <td>0.00</td>
    @endfor
</tr>
<tr>
    <td colspan="100%">&nbsp;</td>
</tr>

<tr>
    <td><b>(C) NET CASH FLOW (A-B)**</b></td>
    @for ($i = 1; $i < 14; $i++)
        <td><b class="net-cash-{{$i}} net-cash">0.00</b></td>
        <td class="net-cash">0.00</td>
    @endfor
</tr>
<tr>
    <td><b>(D) Opening Bank Balance***</b></td>
    <td class="opening-bl-1 opening">0.00</td>
    <td class="opening-bl-2 opening">0.00</td>
    @for ($i = 3; $i < 27; $i++)
        <td class="opening-bl-{{$i}} opening">0.00</td>
    @endfor
</tr>
<tr>
    <td><b>Closing Bank Balance (D+C)</b></td>
    <td class="closing-bl-1 closing">0.00</td>
    <td class="closing-bl-2 closing">0.00</td>
    @for ($i = 3; $i < 27; $i++)
        <td class="closing-bl-{{$i}} closing">0.00</td>
    @endfor
</tr>
        </tbody>
    </table>
</div>
