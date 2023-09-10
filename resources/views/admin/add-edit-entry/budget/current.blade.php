@extends('admin.layout.master')
@section('title','Budget Entry')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li class="active">Budget Entry</li>
                <li class="active">Enter</li>
            </ul>
        </div>
        <style>
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                padding: 1px 5px;
                vertical-align: middle;
            }
            .form-control {
                height: 28px;
            }
        </style>
        <div class="page-content" style="margin-top: 50px;">
            <form action="{{route('budget.update', 1)}}" class="was-validated" method="post" autocomplete="off"
                autocapitalize="off">
                @csrf @method('PUT')
                <input type="hidden" name="client_id" value="{{$client->id}}">
                <input type="hidden" name="profession_id" value="{{$profession->id}}">
                <input type="hidden" name="date" value="{{$date}}">
                <div align="center" class="row">
                    <div class="col-lg-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="text-center">
                            <h2 style="padding: 0;margin:0"><b>{{ $client->full_name}}</b></h2>
                            <h2 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h2>
                            <h4><b>Budget as at: {{\Carbon\Carbon::parse($date)->format('d/m/Y')}}</b></h4>
                        </div>
                        <style>
                            .text-danger {
                                color: red;
                            }
                        </style>
                        {{-- @include('admin.reports.trial_balance.table') --}}
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @include('admin.add-edit-entry.budget.current-table')
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button type="submit" class="pull-right btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('admin.add-edit-entry.budget.budget-js')
@endsection
