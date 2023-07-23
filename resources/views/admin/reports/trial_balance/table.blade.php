<table class="table table-bordered">
    <thead>
        <tr>
            <td class="center" style="width:10%">Account Code</td>
            <td class="center" style="width:50%">Account Name</td>
            <td class="center" style="width:20%">Debit</td>
            <td class="center" style="width:20%">Credit</td>
        </tr>
    </thead>
    <tbody>
        @php
            $sum = ['debit' => 0, 'credit' => 0, 'income' => 0, 'expense' => 0];
            $Sdebit = $Scredit = $debit = $credit = $retain = $Rcredit = $Rdebit = $Ccredit = $Cdebit = $chart_id = $preBalance = 0;
        @endphp
        @foreach ($codes as $i => $code)
            @php
                $ledger = $ledgers->where('chart_id', $code->code)->first();
                if (!$ledger) {
                    continue;
                }
                $chart_id = $ledger->chart_id;
                if ($ledger->balance_type == 1) {
                    if ($ledger->trail_balance < 0) {
                        $credit = $ledger->trail_balance;
                        $sum['credit'] += abs($credit);
                        $debit = 0;
                    } else {
                        $debit = $ledger->trail_balance;
                        $sum['debit'] += abs($debit);
                        $credit = 0;
                    }
                } elseif ($ledger->balance_type == 2) {
                    if ($ledger->trail_balance < 0) {
                        $debit = $ledger->trail_balance;
                        $sum['debit'] += abs($debit);
                        $credit = 0;
                    } else {
                        $credit = $ledger->trail_balance;
                        $sum['credit'] += abs($credit);
                        $debit = 0;
                    }
                }
            @endphp
            <tr>
                <td style="text-align: center">{{ $chart_id }}</td>
                <td>{{ $code->name }}
                    {{-- = {{$loop->iteration}} --}}
                    {{-- = {{$i}} --}}
                </td>
                <td style="text-align: right">{{ number_format(abs($debit), 2) }}</td>
                <td style="text-align: right">{{ number_format(abs($credit), 2) }}</td>
            </tr>
        @endforeach
        @if ($retains)
            @php
                if ($retains > 0) {
                    $Rcredit = abs($retains);
                    $Rdebit = 0;
                } else {
                    $Rdebit = abs($retains);
                    $Rcredit = 0;
                }
            @endphp
            <tr>
                <td class="text-center">999999</td>
                <td>Retain earning</td>
                <td class="text-right">{{ number_format($Rdebit, 2) }} </td>
                <td class="text-right">{{ number_format($Rcredit, 2) }} </td>
            </tr>
        @endif
        <tr style="text-align: right;  color:blue; font-weight:bold">
            <td colspan="2" class="text-right;">Total</td>
            <td>{{ number_format($Sdebit = $sum['debit'] + $Rdebit, 2) }}</td>
            <td>{{ number_format($Scredit = $sum['credit'] + $Rcredit, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="text-danger text-right">Difference between Dr. and
                Cr.</td>
            <td colspan="2" class="center text-center text-danger">
                @php $deffBlnc = $Scredit-$Sdebit;@endphp
                {{ number_format(abs($deffBlnc), 2) . ($deffBlnc > 0 ? ' Cr' : ' Dr') }}

            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-right">Profit/Loss as at
                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
            @if ($CRetains)
                <td colspan="2" class="text-center">
                    {{ number_format($CRetains, 2) }}
                </td>
            @else
                <td colspan="2">{{ number_format(0, 2) }}</td>
            @endif

        </tr>

        <tr>
            <td colspan="4" class="text-center">
                <b>Powered by <a href="https://aarks.com.au">AARKS</a> <a href="https://aarks.net.au">(ADVANCED
                        ACCOUNTING & RECORD KEEPING SOFTWARE)</a></b>
            </td>
        </tr>
    </tbody>
</table>
