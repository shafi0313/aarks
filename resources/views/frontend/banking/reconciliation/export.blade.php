<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Particular</th>
            <th>Transaction Id</th>
            <th>JFL</th>
            <th>Dr.amount</th>
            <th>Cr.amount</th>
            <th>Balance</th>
            <th>Reconcile</th>
        </tr>
    </thead>
    <tbody>
        @php
        $Sdebit = $Scredit = $balance = 0;
        @endphp
        @forelse ($reconcilations as $recon)
        @php
        $ledger = $recon->generalLedger;
        $debit = 0;
        $credit = 0;
        if($ledger->credit < 0){
            $debit   = $ledger->credit;
            $Sdebit += $ledger->credit;
        }elseif($ledger->debit < 0){
            $credit   = $ledger->debit;
            $Scredit += $ledger->debit;
        }else{
        $debit    = $ledger->debit;
        $credit   = $ledger->credit;
        $Sdebit  += $ledger->debit;
        $Scredit += $ledger->credit;
        }
        @endphp
        <tr>
            <td>{{$recon->date}}</td>
            <td>{{$ledger->narration}}</td>
            <td>{{$ledger->transaction_id}}</td>
            <td>{{$ledger->source}}</td>
            <td>{{$ledger->debit}}</td>
            <td>{{$ledger->credit}}</td>
            <td>{{$recon->gl_balance}}</td>
            <td>{{$recon->balance}}</td>
        </tr>
        @empty
        <tr>
            <td colspan="100%">
                <h3>No Reconcilation Found</h3>
            </td>
        </tr>
        @endforelse
        <tr>
            <td colspan="4">Ledger total(including ledger opening balance)</td>
            <td style="color: red">{{abs($Sdebit)}}</td>
            <td style="color: red">{{abs($Scredit)}}
            </td>
        </tr>
        <tr>
            <td colspan="4">Reconciled total</td>
            <td style="color: red">
                <span>{{number_format($bank_recon->reconciled_dr, 2)}}</span>
            </td>
            <td style="color: red">
                <span>{{number_format($bank_recon->reconciled_cr, 2)}}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4">Unreconciled total</td>
            <td style="color: red">
                <span>{{number_format($bank_recon->unreconciled_dr, 2)}}</span>
            </td>
            <td style="color: red">
                <span>{{number_format($bank_recon->unreconciled_cr, 2)}}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4">Difference between bank statement balance and General Ledger Balance</td>
            <td colspan="5"  style="color: red">
                <span>{{number_format($bank_recon->balance_diff, 2)}}</span>
            </td>
        </tr>
    </tbody>
</table>
