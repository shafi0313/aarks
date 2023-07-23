<table class="table table-bordered">
    <thead>
        <tr>
            <th class="center">SN</th>
            <th>code</th>
            <th>amount debit</th>
            <th>amount credit</th>
            <th>gst accrued debit</th>
            <th>gst accrued credit</th>
            <th>gst cash debit</th>
            <th>gst cash credit</th>
            <th>net amount debit</th>
            <th>net amount credit</th>
        </tr>
    </thead>
    <tbody>
        @php $x=1 @endphp
        @foreach ($transactions as $i=> $transaction)
        <tr>
            <td>{{$x++}} </td>
            <td>{{$transaction->chart_id}} </td>
            <td class="text-right">{{ number_format($transaction->amount_debit,2) }} </td>
            <td class="text-right">{{ number_format($transaction->amount_credit,2) }} </td>
            <td class="text-right">{{ number_format($transaction->gst_accrued_debit,2) }} </td>
            <td class="text-right">{{ number_format($transaction->gst_accrued_credit,2) }} </td>
            <td class="text-right">{{ number_format($transaction->gst_cash_debit,2) }} </td>
            <td class="text-right">{{ number_format($transaction->gst_cash_credit,2) }} </td>
            <td class="text-right">{{ number_format($transaction->net_amount_debit,2) }} </td>
            <td class="text-right">{{ number_format($transaction->net_amount_credit,2) }} </td>
        </tr>
        @endforeach
    </tbody>
</table>
