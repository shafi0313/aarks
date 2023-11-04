<h2>Add Edit Entry</h2>
<table id="dataTable" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SL</th>
            <th>Account Name </th>
            <th>Trn.Date </th>
            <th>Trn.ID</th>
            <th>Particular</th>
            <th>Tax</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $trxid = '';
        @endphp
        @foreach ($adts as $adt)
        @php
            $adt = $adt->first();
        @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $adt->client_account_code->name }}</td>
                <td>{{ bdDate($adt->date) }}</td>
                <td>{{ $adt->tran_id }}</td>
                <td>{{ $adt->narration }}</td>
                <td>{{ $adt->gst_code }}</td>
                <td class="text-right">{{ number_format(abs($adt->debit), 2) }}</td>
                <td class="text-right">{{ number_format(abs($adt->credit), 2) }}</td>
                <td>
                    @include('admin.trashed.action', ['item' => $adt])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
