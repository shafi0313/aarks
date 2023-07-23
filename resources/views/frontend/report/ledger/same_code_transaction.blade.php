@extends('frontend.layout.master')
@section('title','Same Transaction View')
@section('content')
<?php $p="gl"; $mp="report";?>
<div class="container main-content">
    <div class="main-content-inner">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Same Transaction View</h1>
                    <div class="row">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="center">SN</th>
                                    <th>Account Name</th>
                                    <th>Trn.Date</th>
                                    {{-- <th>Trn.ID</th> --}}
                                    <th>Particular</th>
                                    <th>Tx Code</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $credit = $debit = 0;
                                @endphp
                                @foreach($transactions as $i => $transaction)
                                    <tr>
                                        <td class="center">{{ $i+1 }}</td>
                                        <td>{{ $transaction->client_account_code->name }}</td>
                                        <td>{{ $transaction->date->format(aarks('frontend_date_format')) }}</td>
                                        <td>{{ $transaction->narration }}</td>
                                        <td>{{$transaction->client_account_code->gst_code}}</td>
                                        <td>{{ number_format(abs($transaction->debit),2) }}</td>
                                        <td>{{ number_format(abs($transaction->credit),2) }}</td>
                                        @php
                                            $credit += abs($transaction->credit);
                                            $debit += abs($transaction->debit);
                                        @endphp
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5"></td>
                                    <td>{{$debit}}</td>
                                    <td>{{$credit}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
@endsection
