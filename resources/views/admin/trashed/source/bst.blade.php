<h2>Bank Statement Import</h2>
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
        @foreach ($imports as $import)
        @php
            $import = $import->first();
        @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $import->client_account_code->name }}</td>
                <td>{{ $import->date->format('d/m/Y') }}</td>
                <td>{{ $import->tran_id }}</td>
                <td>{{ $import->narration }}</td>
                <td>{{ $import->gst_code }}</td>
                <td class="text-right">{{ number_format(abs($import->debit), 2) }}</td>
                <td class="text-right">{{ number_format(abs($import->credit), 2) }}</td>
                <td>
                    @if ($trxid != $import->tran_id)
                        @php
                            $trxid = $import->tran_id;
                        @endphp
                        @include('admin.trashed.action', ['item' => $import])
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
