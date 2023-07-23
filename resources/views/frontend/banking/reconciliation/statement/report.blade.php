@extends('frontend.layout.master')
@section('title','Reconciliation Statement')
@section('content')
<?php $p="brecs"; $mp="bank";?>
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading py-3">
                        <h3>Reconciliation Statement</h3>
                    </div>
                    <div class="card-body">
                        <div class="div">
                            <h2 class="text-center">{{$client->fullname}}</h2>
                            <p class="text-center">For The End of the Period {{$end_date->format('d/m/Y')}}</p>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <tr>
                                <th colspan="6" class="text-right">Amount</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-left">Bank Balance as per Ledger book</th>
                                <th class="text-right">$ {{number_format($ledgers->sum('balance'), 2)}}</th>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-left">Add: Cheque Issued but not presented in the bank</th>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <th>Cheq No</th>
                                <th>To whom/ Narration</th>
                                <th>Amount</th>
                                <th>Cleared Date</th>
                                <th></th>
                            </tr>
                            @php($totalCredit = $totalDebit = $totalBalance = 0)
                            @foreach ($ledgers->where('balance', '<', 0) as $ledger)
                            <tr>
                                <td>{{$ledger->date->format('d/m/Y')}}</td>
                                <td>{{$ledger->transaction_id}}</td>
                                <td>{{$ledger->narration}}</td>
                                <td>
                                    @php($totalCredit += abs($ledger->balance))
                                    {{number_format(abs($ledger->balance), 2)}}
                                </td>
                                <td>{{$ledger->date->format(aarks('frontend_date_format')) }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-left">Total:</td>
                                <td>$ {{number_format($totalCredit, 2)}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-left">Less : Debited in the ledger book but not recorded in the bank :</th>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <th>Cheq No</th>
                                <th>To whom/ Narration</th>
                                <th>Amount</th>
                                <th>Cleared Date</th>
                                <th></th>
                            </tr>
                            @foreach ($ledgers->where('balance', '>', 0) as $ledger)
                            <tr>
                                <td>{{$ledger->date->format('d/m/Y')}}</td>
                                <td>{{$ledger->transaction_id}}</td>
                                <td>{{$ledger->narration}}</td>
                                <td>
                                    @php($totalDebit += abs($ledger->balance))
                                    {{number_format(abs($ledger->balance), 2)}}
                                </td>
                                <td>{{$ledger->date->format(aarks('frontend_date_format')) }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-left">Total:</td>
                                <td>$ {{number_format($totalDebit, 2)}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-left">Less : Debited in the ledger book but not recorded in the bank :</th>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-left">Bank Balance as per bank statement</td>
                                <td>$ {{number_format(($totalDebit - $totalCredit) + 0, 2)}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
