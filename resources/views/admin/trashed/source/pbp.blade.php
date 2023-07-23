<table id="d-table" class="table table-striped table-bordered table-hover display table-sm">
    <thead class="text-center" style="font-size: 14px">
        <tr>
            <th width="4%">SL</th>
            <th width="10%">Date</th>
            <th width="10%">Tran Id</th>
            <th width="15%">Supplier Name</th>
            <th width="9%">Order No</th>
            <th width="9%">Order Amount</th>
            <th width="9%">Due Amount</th>
            <th width="9%">Paid Amount</th>
            <th width="9%" class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        @php $i=1; @endphp
        @foreach ($services->groupBy('inv_no') as $service)
            <tr>
                <td class="text-center">{{ $i++ }}</td>
                <td>{{ $service->first()->tran_date->format('d/m/Y') }} </td>
                <td>{{ $service->first()->tran_id }} </td>
                <td>{{ $service->first()->customer->name }} </td>
                <td class="text-center text-primary">
                    <a
                        href="{{ route('bill.report', ['service', $service->first()->inv_no, $client->id]) }}">{{ invoice($service->first()->inv_no, 8, 'ODR') }}</a>
                </td>
                <td class="text-info">$ {{ number_format($service->sum('amount'), 2) }}
                </td>

                <td class="text-danger">$
                    {{ number_format($service->sum('amount') - $service->first()->payments->sum('payment_amount'), 2) }}
                </td>
                <td class="text-success">$
                    {{ number_format($service->first()->payments->sum('payment_amount'), 2) }} </td>
                <td>
                    @include('admin.trashed.action', ['item' => $service->first()])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
