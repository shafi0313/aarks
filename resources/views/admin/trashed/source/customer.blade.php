<table id="d-table" class="table table-striped table-bordered table-hover display table-sm">
    <thead class="text-center">
        <tr>
            <th>SN</th>
            <th>Name</th>
            <th>Cust Ref</th>
            <th>Card Type</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>A.B.N</th>
            <th class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1 ;@endphp
        @forelse ($customers as $customer)
            <tr>
                <td class="text-center">{{ $i++ }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->customer_ref }}</td>
                <td>{{ $customer->customer_type }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->abn }}</td>
                <td>
                    @include('admin.trashed.action', ['item' => $customer])
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8" align="center">
                    <h1 class="display-1 text-danger">Table Empty</h1>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
