@extends('frontend.layout.master')
@section('title','Income & Expense Comparison')
@section('content')
<?php $p="iec"; $mp="avdr";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading py-2">
                        <h3>Income & Expense Comparison</h3>
                    </div>
                    <div class="card-body">
                        <style>
                            .table>tbody>tr>td,
                            .table>tbody>tr>th,
                            .table>tfoot>tr>td,
                            .table>tfoot>tr>th,
                            .table>thead>tr>td,
                            .table>thead>tr>th {
                                padding: 1px 5px;
                                vertical-align: middle;
                            }

                            .form-control {
                                height: 28px;
                            }
                        </style>
                        <div class="col-lg-12">
                            <!-- PAGE CONTENT BEGINS -->
                            <div class="text-center">
                                <h2 style="padding: 0;margin:0"><b>{{ $client->full_name}}</b></h2>
                                <h2 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h2>
                                <h4><b>Budget Report as at: {{now()->format('d/m/Y')}}</b></h4>
                            </div>
                            <style>
                                .text-danger {
                                    color: red;
                                }
                            </style>
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                        <div class="col-12 mt-5">
                            @include('admin.reports.cash_periodic.period-table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
