<h2>Journal List</h2>
<table id="dataTable" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SL</th>
            <th>Account Name </th>
            <th>Trn.Date </th>
            <th>Trn.ID</th>
            <th>Paticluar</th>
            <th>Tx Code</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Tax</th>
            <th>Excl Tax</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
            $totalD = $totalC = $totalB = $totalG = 0;
        @endphp
        @foreach ($ledgers as $journal)
            @php

                $Idebit = $journal->debit;
                $Icredit = $journal->credit;

                if ($journal->credit < 0) {
                    $Idebit = $Icredit;
                    $Icredit = 0;
                }
                if ($journal->debit < 0) {
                    $Icredit = $Idebit;
                    $Idebit = 0;
                }

                $totalD += abs($Idebit);
                $totalC += abs($Icredit);
                $totalG += abs($journal->gst);
                $totalB += abs($journal->net_amount);

            @endphp
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $journal->client_account_code->name }}</td>
                <td>{{ $journal->date->format('d/m/Y') }}</td>
                <td>{{ $journal->tran_id }}</td>
                <td>{{ $journal->narration }}</td>
                <td>{{ $journal->gst_code }}</td>
                <td class="text-right">{{ number_format(abs($Idebit), 2) }}</td>
                <td class="text-right">{{ number_format(abs($Icredit), 2) }}</td>
                <td class="text-right">{{ number_format(abs($journal->gst), 2) }}</td>
                <td class="text-right">{{ number_format(abs($journal->net_amount), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
