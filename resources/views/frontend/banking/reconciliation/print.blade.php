<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Bank Reconcilation</title>
        @include('frontend.print-css')
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="text-center" style="text-align: center">
                    <h2 class="font-weight-bolder">
                        {{ clientName($client)}}
                    </h2>
                    </div>
                    <table class="table table-hover table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Particular</th>
                                <th class="center">Transaction Id</th>
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
                                $debit  = 0;
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
                                    <h3 class="text-center">No Reconcilation Found</h3>
                                </td>
                            </tr>
                            @endforelse
                            <tr>
                                <td colspan="4">Ledger total(including ledger opening balance)</td>
                                <td style="color: red">{{abs($Sdebit)}}
                                </td>
                                <td colspan="4" style="color: red">{{abs($Scredit)}}
                                </td>
                            </tr>
<tr>
    <td colspan="4">Reconciled total</td>
    <td>
        <span class="text-danger">{{number_format($bank_recon->reconciled_dr, 2)}}</span>
    </td>
    <td colspan="4">
        <span class="text-danger">{{number_format($bank_recon->reconciled_cr, 2)}}</span>
    </td>
</tr>
<tr>
    <td colspan="4">Unreconciled total</td>
    <td>
        <span class="text-danger">{{number_format($bank_recon->unreconciled_dr, 2)}}</span>
    </td>
    <td colspan="4">
        <span class="text-danger">{{number_format($bank_recon->unreconciled_cr, 2)}}</span>
    </td>
</tr>
<tr>
    <td colspan="4">Difference between bank statement balance and General Ledger Balance</td>
    <td colspan="5">
        <span class="text-danger">{{number_format($bank_recon->balance_diff, 2)}}</span>
    </td>
</tr>
                        </tbody>
                    </table>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.Container -->

    </body>

</html>
