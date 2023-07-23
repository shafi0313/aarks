<table id="d-table" class="table table-striped table-bordered table-hover display table-sm">
    <thead class="text-center" style="font-size: 14px">
        <tr>
            <th width="10%">Date</th>
            <th width="10%">Tran Id</th>
            <th width="15%">Customer Name</th>
            <th width="9%">Invoice No</th>
            <th width="9%">Inv Amount</th>
            <th width="9%">Due Amount</th>
            <th width="9%">Paid Amount</th>
            <th width="13%" class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        {{-- @php $i=1; @endphp --}}
        @foreach ($invoices->groupBy(['customer_card_id', 'inv_no']) as $invoicess)
            @foreach ($invoicess as $invoice)
                <tr>
                    <td>{{ $invoice->first()->tran_date->format('d/m/Y') }} </td>
                    <td>{{ $invoice->first()->tran_id }} </td>
                    <td>{{ $invoice->first()->customer ? $invoice->first()->customer->name : '' }} </td>
                    <td class="text-center text-primary">
                        <a
                            href="#INV">{{ invoice($invoice->first()->inv_no, 8, 'INV') }}</a>
                    </td>
                    <td class="text-info">$ {{ number_format($invoice->sum('amount'), 2) }}
                    </td>
                    <td class="text-danger">$
                        {{ number_format($invoice->sum('amount') - $invoice->first()->payments->sum('payment_amount'), 2) }}
                    </td>
                    <td class="text-success">$
                        {{ number_format($invoice->first()->payments->sum('payment_amount'), 2) }} </td>
                    <td>
                        @include('admin.trashed.action', ['item'=>$invoice->first()])
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
