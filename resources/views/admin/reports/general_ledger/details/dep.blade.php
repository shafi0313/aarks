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
        <tr>
            <td colspan="100%">
                <h1>DEPRECEATION</h1>
            </td>
        </tr>
        {{-- @foreach ($transactions as $i => $invoice)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{bdDate($invoice->tran_date)}} </td>
            <td>{{$invoice->clientAccountCode->name}}</td>
            <td>{{$invoice->tran_id}} </td>
            <td>{{$invoice->customer->name}} </td>
            <td class="text-center text-primary">
                {{invoice($invoice->inv_no, 8, 'PIN')}}
            </td>
            <td class="text-info text-right">$ {{number_format($invoice->payment_amount,2)}}
            </td>
        </tr>
        @endforeach --}}
    </tbody>
</table>
