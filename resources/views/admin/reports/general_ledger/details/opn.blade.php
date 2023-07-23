<table class="table table-bordered">
    <thead>
        <tr>
            <th>SN</th>
            <th>Date</th>
            <th>Account Name</th>
            <th>Tran Id</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $i => $tran)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$tran->date->format('d/m/Y')}} </td>
            <td>{{$tran->clientAccountCode->name}}</td>
            <td>{{$tran->transaction_id}} </td>
            <td class="text-info text-right">$ {{number_format($tran->debit,2)}}</td>
            <td class="text-info text-right">$ {{number_format($tran->credit,2)}}</td>
            <td class="text-info text-right">$ {{number_format($tran->balance,2)}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
