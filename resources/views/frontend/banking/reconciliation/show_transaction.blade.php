@extends('frontend.layout.master')
@section('title','General Ledger')
@section('content')
<?php $p="gl"; $mp="report";?>
    <div class="main-content">
        <div class="container">
            <div class="page-content">

                <div class="row">
                    <div class="col-lg-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="card">
                            <div class="card-body">
                                <div class="card-heading d-flex">
                                    <p>Transaction View</p>
                                </div>
                                <table class="table table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th class="center">SL</th>
                                        <th>Account Name</th>
                                        <th>Trn.Date</th>
                                        <th>Trn.ID</th>
                                        <th>Particular</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        {{-- <th class="center">Action</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $sum = ['debit' => 0, 'credit' => 0];
                                        $ldebit =$lcredit = $Bdebit =$Bcredit = 0;
                                    @endphp
                                    @foreach($transactions as $index => $transaction)
                                        @php
                                        $debit=$credit=0;
                                            if($transaction->balance_type === 1){
                                                if($transaction->tranBlnc < 1){
                                                    $credit = $transaction->tranBlnc;
                                                }else{
                                                    $debit = $transaction->tranBlnc;
                                                }
                                            }elseif($transaction->balance_type === 2){
                                                if($transaction->tranBlnc < 1){
                                                    $debit = $transaction->tranBlnc;
                                                }else{
                                                    $credit = $transaction->tranBlnc;
                                                }
                                            }
                                            $sum['debit'] += abs($debit);
                                            $sum['credit'] += abs($credit);
                                        @endphp
                                        <tr>
                                            <td class="center">{{ $index + 1 }}</td>
                                            <td>{{ $transaction->client_account_code->name }}</td>
                                            <td>{{ $transaction->date->format(aarks('frontend_date_format')) }}</td>
                                            <td class="center">
                                                <a href="{{route('ledger.data_store.transaction', [$transaction->transaction_id,$transaction->chart_id,])}}"
                                                    style="color: green;text-decoration: underline">{{$transaction->transaction_id}}
                                                </a>
                                            </td>
                                            <td>{{ $transaction->narration }}</td>
                                            <td class="text-right">{{ number_format(abs($debit),2) }}</td>
                                            <td class="text-right">{{ number_format(abs($credit),2) }}</td>
                                        </tr>
                                    @endforeach
                                    @if ($loan && $transaction->source == 'ADT')
                                    @php
                                        $ldebit = abs($loan->debit);
                                        $lcredit = abs($loan->credit);
                                    @endphp
                                        <tr>
                                            <td class="center">{{ $index +2 }}</td>
                                            <td>Loan from/to Others</td>
                                            <td>{{ \Carbon\Carbon::parse($loan->date)->format('d/m/Y') }}</td>
                                            <td class="center">
                                                <a href="{{route('show.data_store.transaction', [$loan->transaction_id,$loan->chart_id,])}}"
                                                    style="color: green;text-decoration: underline">{{makeNineDigitNumber($loan->transaction_id)}}
                                                </a>
                                            </td>
                                            <td>{{ $loan->narration }}</td>
                                            <td class="text-right">{{ number_format(abs($loan->debit),2) }}</td>
                                            <td class="text-right">{{ number_format(abs($loan->credit),2) }}</td>
                                        </tr>
                                    @endif
                                    @if ($bank && ($transaction->source == 'BST' || $transaction->source == 'INP'))
                                    @php
                                        $Bdebit = abs($bank->debit);
                                        $Bcredit = abs($bank->credit);
                                    @endphp
                                        <tr>
                                            <td class="center">{{ $index +2 }}</td>
                                            <td>{{ $bank->client_account_code->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($bank->date)->format('d/m/Y') }}</td>
                                            <td class="center">
                                                <a href="{{route('ledger.data_store.transaction', [$bank->transaction_id,$bank->chart_id,])}}"
                                                    style="color: green;text-decoration: underline">{{makeNineDigitNumber($bank->transaction_id)}}
                                                </a>
                                            </td>
                                            <td>{{ $bank->narration }}</td>
                                            <td class="text-right">{{ number_format(abs($bank->debit),2) }}</td>
                                            <td class="text-right">{{ number_format(abs($bank->credit),2) }}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right"><b>Total</b></td>
                                            <td class="text-right">{{ number_format(abs($sum['debit']+abs($ldebit)+abs($Bdebit)),2)}}</td>
                                            <td class="text-right">{{ number_format(abs($sum['credit']+abs($lcredit)+abs($Bcredit)),2) }}</td>
                                            {{--                                        <td></td>--}}
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

@endsection
