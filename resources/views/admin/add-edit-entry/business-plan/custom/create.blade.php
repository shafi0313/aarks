@extends('admin.layout.master')
@section('title','Business Plan')
@section('style')
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

    table {
        width: 100%;
    }

    th,
    td {
        border: 1px solid #c42727;
        padding: 8px;
        width: 100px;
    }

    th {
        background-color: #f2f2f2;
    }

    /* tr:nth-child(even) {
        background-color: #f2f2f2;
    } */

    .text-center {
        text-align: center;
    }

    .text-danger {
        color: red;
    }
</style>
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
                <li class="active">Business Plan</li>
                <li class="active">Enter</li>
            </ul>
        </div>
        <div class="page-content" style="margin-top: 50px;">
            <form action="{{route('business-plan.store')}}" class="was-validated" method="post" autocomplete="off"
                id="business-plan-form" autocapitalize="off">
                @csrf
                <input type="hidden" name="client_id" value="{{$client->id}}">
                <input type="hidden" name="profession_id" value="{{$profession->id}}">
                <input type="hidden" name="date" value="{{$date}}">
                <div align="center" class="row">
                    <div class="col-lg-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="text-center">
                            <h2 style="padding: 0;margin:0"><b>{{ $client->full_name}}</b></h2>
                            <h2 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h2>
                            <h3 style="padding: 0;margin:0"><b>Forecast Profit & Loss</b></h3>
                            <h4><b>Business as at: {{\Carbon\Carbon::parse($date)->format('d/m/Y')}}</b></h4>
                        </div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if (session('error'))
                        <div class="alert alert-error">
                            {{ session('error') }}
                        </div>

                        @endif
                        <div class="form-group">
                            <button type="submit" class="pull-right btn btn-success">Save/Update</button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        @include('admin.add-edit-entry.business-plan.custom.table')
                        {{-- @include('admin.add-edit-entry.business-plan.custom.actable') --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button type="submit" class="pull-right btn btn-success">Save/Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.add-edit-entry.business-plan.custom.js')
@endsection
