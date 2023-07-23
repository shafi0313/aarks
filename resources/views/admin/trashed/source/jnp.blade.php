<table id="d-table" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>SL </th>
            <th>Journal Serial </th>
            <th>Journal Date</th>
            <th>Journal Ref</th>
            <th>Trn.ID</th>
            <th>Action</th>
        </tr>

    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach ($journals->groupBy('journal_number') as $journal)
            <?php $journal = $journal->first(); ?>
            <tr>
                <td>{{ $i++ }}</td>
                <td>
                    <a href="javascript:void">
                        {{ invoice($journal->journal_number, 8, 'JNL') }}
                    </a>
                </td>
                <td>{{ $journal->date->format('d/m/Y') }}</td>
                <td>{{ $journal->narration }}</td>
                <td>{{ $journal->tran_id }}</td>
                <td>
                    @include('admin.trashed.action', ['item' => $journal])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
