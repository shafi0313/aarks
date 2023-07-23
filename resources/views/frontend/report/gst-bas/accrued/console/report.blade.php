@extends('frontend.layout.master')
@section('title', 'Consolidation GST/BAS Accrued Report')
@section('content')
<?php $p="cbs"; $mp="advr";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-heading">
                        <h3> GST/BAS Consolidation Accrued Report</h3>
                    </div>
                    <div class="card-body">

                <table class="table table-bordered table-striped mb-4">
                    <tr>
                        <th>Company Name</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>ABN No</th>
                        <th>TFN</th>
                        <th>Date Of Birth</th>
                        <th>Phone Number</th>
                    </tr>
                    <tr>
                        <td>{{$client->company}}</td>
                        <td>{{$client->first_name}}</td>
                        <td>{{$client->last_name}}</td>
                        <td>{{$client->email}}</td>
                        <td>{{$client->abn_number}}</td>
                        <td>{{$client->tax_file_number}}</td>
                        <td>{{$client->birthday}}</td>
                        <td>{{$client->phone}}</td>
                    </tr>
                </table>

            <div align="center" class="py-4">
                <h2>GST REPORT/BAS(CASH)</h2>
                <h4><u> From Date : {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y')}} To {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y')}}</u></h4>
            </div>
                        @include('admin.reports.cash_basis.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->

@stop
