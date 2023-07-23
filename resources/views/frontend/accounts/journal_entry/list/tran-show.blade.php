@extends('frontend.layout.master')
@section('title','Select Activity')
@section('content')
<?php $p="cjl"; $mp="acccounts"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
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
                                        <td>{{number_format(abs($Idebit),2)}}</td>
                                        <td>{{number_format(abs($Icredit),2)}}</td>
                                        <td>{{number_format(abs($journal->gst),2)}}</td>
                                        <td>{{number_format(abs($journal->net_amount),2)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="text-align:right;" colspan="5">Total</td>
                                        <td>{{number_format(abs($totalD),2)}}</td>
                                        <td>{{number_format(abs($totalC),2)}}</td>
                                        <td>{{number_format(abs($totalG),2)}}</td>
                                        <td>{{number_format(abs($totalB),2)}}</td>
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
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->

@stop
