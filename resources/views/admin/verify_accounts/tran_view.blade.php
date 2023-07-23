@extends('admin.layout.master')
@section('title', 'Verify & Fixed Transactions')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Tools</li>
                    <li class="active">Verify Account</li>
                    <li class="active">Transaction View</li>
                </ul><!-- /.breadcrumb -->
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="title">
                            <h2>Transaction View</h2>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="center">SN</th>
                                    <th>A. Code</th>
                                    <th>Type</th>
                                    <th>Account Name</th>
                                    <th>Trn.Date</th>
                                    <th>Trn.ID</th>
                                    <th>Particular</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>GST</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $gstDebit = $gstCredit = 0;
                                @endphp
                                @foreach ($ledgers as $ledger)
                                @php
                                    $debit  = abs($ledger->debit)  != 0 ? abs($ledger->debit) - abs($ledger->gst) : abs($ledger->debit);
                                    $credit = abs($ledger->credit) != 0 ? abs($ledger->credit) - abs($ledger->gst) : abs($ledger->credit) ;
                                    $gstDebit  += abs($debit);
                                    $gstCredit += abs($credit);
                                @endphp
                                    <tr>
                                        <td>{{ @$x += 1 }}</td>
                                        <td>{{ $ledger->chart_id }}</td>
                                        <td>{{ $ledger->client_account_code->type==1?'Debit':'Credit' }}</td>
                                        <td>{{ $ledger->client_account_code->name }}</td>                                        
                                        <td>{{ $ledger->date->format('d/m/Y') }}</td>
                                        <td>{{ $ledger->transaction_id }}</td>
                                        <td>{{ $ledger->narration }}</td>
                                        <td>{{ $debit }}</td>
                                        <td>{{ $credit }}</td>
                                        <td>{{ $ledger->gst }}</td>
                                        <td>{{ $ledger->balance }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7"></td>
                                    <td>{{ number_format($gstDebit,2) }}</td>
                                    <td>{{ number_format($gstCredit,2) }}</td>
                                    <td></td>
                                    <td>{{ number_format($ledgers->sum('balance'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div>
        </div><!-- /.main-content -->
    </div><!-- /.main-content -->
@endsection
