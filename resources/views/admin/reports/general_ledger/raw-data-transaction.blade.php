@extends('admin.layout.master')
@section('title','Raw Data Transaction View')
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
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Details Transaction View of {{ fullFormOfSource($src) }}</h1>
                    <hr>
                    @include('admin.reports.general_ledger.details.'.strtolower($src))
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
