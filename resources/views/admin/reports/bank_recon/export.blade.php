<table>
    <tr>
        <th>{{$client->fullname}}</th>
    </tr>
    <tr>
        <th>Bank Reconcilation</th>
    </tr>
    <tr>
        {{-- <th>From Date : {{ '01/07/'.($period->year-1)}} To {{'30/06/'.$period->year}}</th> --}}
    </tr>
</table>
<table style="width:100%">
    <thead>
        <tr>
            <th>Date</th>
            <th>Tran ID</th>
            <th>Account Code</th>
            <th>Narration</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
        $totalDebit =
        $totalCredit =
        $totalDiff = 0;
        @endphp
        @forelse ($reconcilations as $i => $recon)
        @php
        $debit = abs_number($recon->debit);
        $credit = abs_number($recon->credit);
        $diff = abs_number($recon->diff);

        $totalDebit += $debit;
        $totalCredit += $credit;
        $totalDiff += $diff;
        @endphp
        <tr>
            <td>{{\Carbon\Carbon::parse($recon->date)->addDay()->format('d/m/Y')}}</td>
            <td>{{$recon->tran_id}}</td>
            <td>{{$recon->code}}</td>
            <td>{{$recon->narration}}</td>
            <td>{{$credit}}</td>
            <td>{{$debit}}</td>
            <td>{{$diff}}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No Data Found</td>
        </tr>
        @endforelse

        <tr>
            <td colspan="4">Total:</td>
            <td>{{abs_number($totalCredit)}}</td>
            <td>{{abs_number($totalDebit)}}</td>
            <td>{{abs_number($totalDiff)}}</td>
        </tr>
    </tbody>
</table>
