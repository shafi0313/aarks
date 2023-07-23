<h2>Bank Statement Input</h2>
<table id="dataTable" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SL</th>
            <th>Account Name </th>
            <th>Trn.Date </th>
            <th>Trn.ID</th>
            <th>Paticluar</th>
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
        @foreach ($inputs as $input)
        @php
            $input = $input->first();
        @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $input->client_account_code->name }}</td>
                <td>{{ $input->date->format('d/m/Y') }}</td>
                <td>{{ $input->tran_id }}</td>
                <td>{{ $input->narration }}</td>
                <td>{{ $input->gst_code }}</td>
                <td class="text-right">{{ number_format(abs($input->debit), 2) }}</td>
                <td class="text-right">{{ number_format(abs($input->credit), 2) }}</td>
                <td>
                    @if ($trxid != $input->tran_id)
                        @php
                            $trxid = $input->tran_id;
                        @endphp
                        @include('admin.trashed.action', ['item' => $input])
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
