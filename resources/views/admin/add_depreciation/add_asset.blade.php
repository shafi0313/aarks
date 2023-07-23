@extends('admin.layout.master')
@section('title','Add Asset name')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{route('admin.dashboard')}}">Home</a>
                </li>

                <li>Add/Edit Data</li>
                <li>Depreciation</li>
                <li><a href="{{ route('depreciation.index') }}">Client List</a></li>
                <li class="active">Select Business Activity</li>
                <li class="active">Add Asset name</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#newAsset" aria-controls="newAsset" role="tab" data-toggle="tab">New Asset</a>
                        </li>
                        <li role="presentation">
                            <a href="#activeasset" aria-controls="activeasset" role="tab" data-toggle="tab">Active Asset
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#disponal_tab" id="disponal_tab-link" onclick="return getDisposal()" aria-controls="disponal_tab" role="tab" data-toggle="tab">Disposal Assets</a>
                        </li>
                        <li role="presentation">
                            <a href="#update-old-asset" aria-controls="update-old-asset" role="tab"
                                data-toggle="tab">Asset Rollover/Reinstated Assets</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="newAsset" style="min-height: 100px;">
                            @include('admin.add_depreciation.tab-content.new-tab')
                        </div>
                        <div role="activeasset" class="tab-pane" id="activeasset">
                            @include('admin.add_depreciation.tab-content.active-tab')
                        </div>
                        <div role="tabpanel" class="tab-pane" id="disponal_tab">
                            @include('admin.add_depreciation.tab-content.disposal-tab')
                        </div>
                        <div role="update-old-asset" class="tab-pane" id="update-old-asset">
                            @include('admin.add_depreciation.tab-content.old-tab')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.add_depreciation.modal')
@include('admin.add_depreciation.dep_js')
@endsection
