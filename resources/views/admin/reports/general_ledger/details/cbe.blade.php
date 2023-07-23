<table class="table table-bordered">
    <thead>
        <tr>
            <th class="center">SN</th>
            <th>Account Name</th>
            <th>Trn.Date</th>
            <th>Trn.ID</th>
            <th>Particular</th>
            <th>Debit</th>
            <th>Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
        $credit = $debit = 0;
        @endphp
        @foreach($transactions as $i => $transaction)
        <tr>
            <td class="center">{{ $i+1 }}</td>
            <td>{{ $transaction->accountCode->name }}</td>
            <td>{{ $transaction->tran_date->format(aarks('frontend_date_format')) }}</td>
            <td>{{ $transaction->tran_id }}</td>
            <td>{{ $transaction->narration }}</td>
            <td class="text-right">{{ number_format(abs($transaction->amount_debit),2) }}</td>
            <td class="text-right">{{ number_format(abs($transaction->amount_credit),2) }}</td>
            @php
            $credit += abs($transaction->amount_credit);
            $debit += abs($transaction->amount_debit);
            @endphp
        </tr>
        @endforeach
        <tr>
            <td class="text-right" colspan="5">Total</td>
            <td class="text-right">{{ number_format($debit,2) }}</td>
            <td class="text-right">{{ number_format($credit,2) }}</td>
        </tr>
    </tbody>
</table>
