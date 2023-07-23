@extends('frontend.layout.master')
@section('title','Details Transaction View')
@section('content')
<?php $p="gl"; $mp="report";?>
<div class="main-content">
    <div class="container">
        <div class="page-content">
            <div class="row">
                <div class="col">
                    <a href="{{ url()->previous() }}" class="pull-right btn btn-danger">Go Back</a>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-heading">
                            <h3>Details Transaction View of {{ fullFormOfSource($src) }}</h3>
                        </div>
                        <div class="card-body">
                            @include('admin.reports.general_ledger.details.'.strtolower($src))
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
