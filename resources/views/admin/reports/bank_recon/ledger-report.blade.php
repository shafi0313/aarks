@extends('admin.layout.master')
@section('title','Bank Conciliation')
@section('style')
@endsection
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Reports</li>
                <li class="active">Bank Conciliation</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i>
                                        {{$client->fullname}}
                                    </h3>
                                </div>
                                <div align="center">
                                    <h1>{{$client->fullname}}</h1>
                                    <h2>Bank Reconciliation</h2>
                                    <h4><u> From Date : {{ $data['from_date']}} To {{ $data['to_date'] }}</u></h4>
                                </div>
                                <div class="table table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Tran ID</th>
                                                <th>Narration</th>
                                                <th>Tran Total</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Recon</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $totalDebit = $totalCredit = $totalDiff = 0;
                                            @endphp
                                            @forelse ($recons as $recon)
                                            @php
                                                $totalDebit  += abs_number($recon->debit);
                                                $totalCredit += abs_number($recon->credit);
                                                $totalDiff   += abs_number($recon->debit - $recon->credit);
                                            @endphp
                                                <tr>
                                                    <td>{{\Carbon\Carbon::parse($recon->date)->format('d/m/Y')}}</td>
                                                    <td>{{$recon->transaction_id}}</td>
                                                    <td>{{$recon->narration}}</td>
                                                    <td>{{$recon->narration}}</td>
                                                    <td>{{abs_number($recon->debit)}}</td>
                                                    <td>{{abs_number($recon->credit)}}</td>
                                                    <td>{{abs_number($recon->debit - $recon->credit)}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No Data Found</td>
                                                </tr>
                                            @endforelse
                                            <tr>
                                                <td colspan="4">Total:</td>
                                                <td>{{abs_number($totalDebit)}}</td>
                                                <td>{{abs_number($totalCredit)}}</td>
                                                <td>{{abs_number($totalDiff)}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
@endsection
