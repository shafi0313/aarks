@extends('admin.layout.master')
@section('title', 'Trashed Sources')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li><i class="ace-icon fa fa-trash trash-icon"></i> Trash</li>
                    <li class="">{{$client->full_name}}</li>
                    <li class="active">Sources</li>
                </ul>
                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                                autocomplete="off" />
                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                        </span>
                    </form>
                </div>
            </div>
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-6" style="padding:20px; border:1px solid #999999;">
                            <form action="" method="">
                                <div class="form-group">
                                    <label style="color:green;">Select Source</label>
                                    <select class="form-control" onchange="location = this.value;">
                                        <option disabled selected value>Select Source</option>
                                        {{-- <option value="{{ route('trash.details',[$client->id,'ADT']) }}">Data Entry From Receipt(ADT)</option> --}}
                                        <option value="{{ route('trash.details',[$client->id,'BST']) }}">Import Bank Statement(BST)</option>
                                        <option value="{{ route('trash.details',[$client->id,'INP']) }}">Input Bank Statement(INP)</option>
                                        <option value="{{ route('trash.details',[$client->id,'JNP']) }}">Journal(JNP)</option>
                                        <option value="{{ route('trash.details',[$client->id,'CBE']) }}">Cash Book</option>
                                        {{-- <option value="{{ route('trash.details',[$client->id,'DEP']) }}">Depreciation</option> --}}
                                        <option value="{{ route('trash.details',[$client->id,'CUSTOMER']) }}">Customer/Supplier</option>
                                        <option value="{{ route('trash.details',[$client->id,'PIN']) }}">Payment-Invoice</option>
                                        <option value="{{ route('trash.details',[$client->id,'INV']) }}">Invoice</option>
                                        <option value="{{ route('trash.details',[$client->id,'PBN']) }}">Bill-Payment</option>
                                        <option value="{{ route('trash.details',[$client->id,'PBP']) }}">Bill-Purchase</option>
                                        {{-- <option value="{{ route('trash.details',[$client->id,'PBN']) }}">Bank transaction</option> --}}
                                        
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
