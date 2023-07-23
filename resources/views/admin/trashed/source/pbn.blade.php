<table id="d-table" class="table table-striped table-bordered table-hover display table-sm">
    <thead class="text-center" style="font-size: 14px">
        <tr>
            <th width="4%">SL</th>
            <th width="10%">Date</th>
            <th width="10%">Tran Id</th>
            <th width="15%">Customer Name</th>
            <th width="9%">Receipt No</th>
            <th width="9%">Receipt Amount</th>
            <th width="9%" class="no-sort">Action</th>
        </tr>
    </thead>

    <tbody>
        @php $i=1; @endphp
        @foreach ($payments as $payment)
            <tr>
                <td class="text-center">{{ $i++ }}</td>
                <td>{{ $payment->tran_date->format('d/m/Y') }} </td>
                <td>{{ $payment->tran_id }} </td>
                <td>{{ $payment->customer->name }} </td>
                <td class="text-center text-primary">
                    <a href="javascript:void">
                        @if ($payment->creditor_inv != null)
                            {{ invoice($payment->id, 8, 'BILL') }}
                        @else
                            {{ invoice($payment->id, 8, 'OBL') }}
                        @endif
                    </a>
                </td>
                <td class="text-right text-success">$
                    {{ number_format($payment->payment_amount, 2) }} </td>
                <td>
                    @include('admin.trashed.action', ['item'=>$payment])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
