@extends('admin.layout.master')
@section('title','Profession')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Add/Edit Data</li>
                <li>Journal List</li>
                <li><a href="{{ route('journal_entry_client') }}">Client List</a></li>
                <li class="active">Select Business Activity</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="journal-icon">
                        <journal type="text" placeholder="Search ..." class="nav-search-journal" id="nav-search-journal"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
        </div>

        <div class="page-content">
            

            <div class="row">
                <div class="col-xs-12">

                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-12">
                            <h2>Journal View</h2>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Account Name </th>
                                        <th>Trn.Date </th>
                                        <th>Trn.ID</th>
                                        <th>Paticluar</th>
                                        <th>Tx Code</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Tax</th>
                                        <th>Excl Tax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i      = 1;
                                        $totalD = $totalC = $totalB = $totalG = 0;
                                    @endphp
                                    @foreach ($journals as $journal)
                                    @php

                                    $Idebit   = $journal->debit;
                                    $Icredit  = $journal->credit;

                                    if ($journal->credit < 0) {
                                        $Idebit = $Icredit;
                                        $Icredit = 0;
                                    }
                                    if ($journal->debit < 0) {
                                        $Icredit = $Idebit;
                                        $Idebit = 0;
                                    }

                                    $totalD += abs($Idebit);
                                    $totalC += abs($Icredit);
                                    $totalG += abs($journal->gst);
                                    $totalB += abs($journal->net_amount);

                                    @endphp
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$journal->client_account_code->name}}</td>
                                        <td>{{$journal->date->format('d/m/Y')}}</td>
                                        <td>{{$journal->tran_id}}</td>
                                        <td>{{$journal->narration}}</td>
                                        <td>{{$journal->gst_code}}</td>
                                        <td class="text-right">{{number_format(abs($Idebit),2)}}</td>
                                        <td class="text-right">{{number_format(abs($Icredit),2)}}</td>
                                        <td class="text-right">{{number_format(abs($journal->gst),2)}}</td>
                                        <td class="text-right">{{number_format(abs($journal->net_amount),2)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="text-align:right;" colspan="6">Total</td>
                                        <td class="text-right">{{number_format(abs($totalD),2)}}</td>
                                        <td class="text-right">{{number_format(abs($totalC),2)}}</td>
                                        <td class="text-right">{{number_format(abs($totalG),2)}}</td>
                                        <td class="text-right">{{number_format(abs($totalB),2)}}</td>
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

<!-- Script -->
@stop
