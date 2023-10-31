@extends('frontend.layout.master')
@section('title', 'Monthly business analysis report')
@section('content')
    <?php $p = 'avdbudget';
    $mp = 'avdr'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-heading py-2 d-flex justify-content-between">
                            <h3>Monthly business analysis report</h3>
                            <a href="javascript:printDiv()" class="btn btn-warning mr-2">Print/PDF</a>
                        </div>
                        <div class="card-body">
                            <div class="row" id="print-area">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h4 style="padding: 0;margin:2px"><b>{{ $client->full_name }}</b></h4>
                                        <h5 style="padding: 0;margin:2px"><b>Monthly Business Analysis Details P & L</b></h5>
                                        <h5 style="padding: 0;margin:0"><b>ABN {{ $client->abn_number }}</b></h5>
                                        <h5><b>for the financial year: {{ $date->format('d/m/Y') }}</b></h5>
                                    </div>
                                </div>
                                @include('admin.reports.advance.business-analysis.monthly-business-analysis-table')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    @include('admin.reports.advance.business-analysis.monthly-business-analysis-js')
@endsection
