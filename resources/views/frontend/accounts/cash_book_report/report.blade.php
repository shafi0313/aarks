@extends('frontend.layout.master')
@section('title', 'Select Activity')
@section('content')
    <?php $p = 'cbr';
    $mp = 'acccounts'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="card">
                <div class="card-heading">
                    <h3>Cash Book Report</h3>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 thumbnail" align="center">
                            <div style="font-size:20px; color:#ff5733;">Cash Book: <span
                                    style="color:green;">{{ $office->name }}</span>
                                <p style="font-size:16px; color:black; margin:0"><u>{{ $office->address }}</u>
                                </p>
                                <strong style="color:black;font-size:18px;">Form Date: {{ $start_date->format('d/m/Y') }} To Date:
                                    {{ $end_date->format('d/m/Y') }} </strong>
                            </div>
                            <br>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="color:green; text-align:center; ">Opening Balance </th>
                                        <th style="color:green; text-align:center;">
                                            @if ($office->id == $first_office->id)
                                                $ {{ number_format($open_balance, 2) }}
                                            @else
                                                $ {{ number_format($open_balance = $openbl->credit - $openbl->debit, 2) }}
                                            @endif
                                        </th>
                                        <th style="color:#ff5733; text-align:center; ">Total Received</th>
                                        <th style="color:#ff5733; text-align:center; ">
                                            {{ number_format($total_rcv, 2) }}</th>
                                        <th style="color:green; text-align:center; ">Total Payment</th>
                                        <th style="color:green; text-align:center; ">
                                            {{ number_format($cashbooks->sum('amount_debit'), 2) }}</th>
                                        <th style="color:#ff5733; text-align:center; ">Closing Balance</th>
                                        <th style="color:#ff5733; text-align:center;">
                                            {{ number_format($open_balance + abs($cashbooks->sum('amount_credit')) - abs($cashbooks->sum('amount_debit')), 2) }}
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                            <table width="100%" border="1" cellspacing="5" cellpadding="5">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;" width="10%">Date</th>
                                        <th style="text-align:center;" width="20%">A/c Code</th>
                                        <th style="text-align:center;" width="20%">Tran</th>
                                        <th style="text-align:center;" width="11%">Payment</th>
                                        <th style="text-align:center;" width="11%">Recevied</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cashbooks as $cbook)
                                        <tr>
                                            <td align="center">{{ $cbook->tran_date->format('d/m/Y') }}</td>
                                            <td align="center">{{ $codes->where('code', $cbook->chart_id)->first()->name }}</td>
                                            <td align="center">{{ $cbook->narration }}</td>
                                            <td align="center">{{ number_format($cbook->amount_debit, 2) }}</td>
                                            <td align="center">{{ number_format($cbook->amount_credit, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" align="right" style="color:green; font-size:16px;">Total</td>
                                        <td align="center" style="color:green; font-size:16px;">
                                            {{ number_format($cashbooks->sum('amount_debit'), 2) }}</td>
                                        <td align="center" style="color:green; font-size:16px;">
                                            {{ number_format($cashbooks->sum('amount_credit'), 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
