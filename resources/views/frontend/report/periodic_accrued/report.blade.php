@extends('frontend.layout.master')
@section('title', 'Periodic BAS Accrued Report')
@section('content')
    <?php $p = 'cbs';
    $mp = 'advr'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-heading">
                            <h3>Periodic BAS Accrued Report</h3>
                        </div>
                        <div class="card-body">
                            @include('admin.reports.accrued_periodic.period-table')
                            @include('admin.reports.accrued_periodic.gst-report-table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
