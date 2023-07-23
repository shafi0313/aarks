@extends('admin.layout.master')
@section('title','Details Transaction View')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>

                <li>
                    <a href="#">Business Activity</a>
                </li>
                <li>
                    <a href="#"></a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Details Transaction View</h1>
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
