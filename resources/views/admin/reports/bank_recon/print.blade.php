<!doctype html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{{$client->fullname}} - GST Reconcilation</title>
        @include('admin.reports.gst_recon.css')
        @include('frontend.print-css')
    </head>

    <body>
        <div align="center">
            <h1>{{$client->fullname}}</h1>
            <h2>Bank Reconcilation</h2>
            {{-- <h4><u> From Date : {{ '01/07/'.($period->year-1)}} To {{ '30/06/'.$period->year}}</u></h4> --}}
        </div>
        <div class="tg-wrap">
            <table class="tg" class="width:100%">
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
                    $totalDebit  =
                    $totalCredit =
                    $totalDiff   = 0;
                    @endphp
                    @forelse ($reconcilations as $i => $recon)
                    @php
                    $debit  = abs_number($recon->debit);
                    $credit = abs_number($recon->credit);
                    $diff   = abs_number($recon->diff);

                    $totalDebit  += $debit;
                    $totalCredit += $credit;
                    $totalDiff   += $diff;
                    @endphp
                    <tr>
                        <td>{{\Carbon\Carbon::parse($recon->date)->addDay()->format('d/m/Y')}}</td>
                        <td>{{$recon->tran_id}}</td>
                        <td>{{$recon->code_name}} - ({{$recon->chart_id}})</td>
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
        </div>
    </body>

</html>
