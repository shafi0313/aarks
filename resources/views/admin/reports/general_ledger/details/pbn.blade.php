<table class="table table-bordered">
    <thead>
        <tr>
            <th>SN</th>
            <th>Date</th>
            <th>Account Name</th>
            <th>Tran Id</th>
            <th>Customer Name</th>
            <th>Invoice No</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $i => $invoice)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$invoice->tran_date->format('d/m/Y')}} </td>
            <td>{{$invoice->clientAccountCode->name}}</td>
            <td>{{$invoice->tran_id}} </td>
            <td>{{$invoice->customer->name}} </td>
            <td class="text-center text-primary">
                {{invoice($invoice->inv_no, 8, 'PIN')}}
            </td>
            <td class="text-info text-right">$ {{number_format($invoice->payment_amount,2)}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
