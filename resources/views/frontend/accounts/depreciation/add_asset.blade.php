@extends('frontend.layout.master')
@section('title','Add Asset name')
@section('content')
<?php $p="cdep"; $mp="acccounts"?>
<div class="container">
    <div class="row mt-5">
        <div class="col-lg-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="nav-item">
                    <a href="#newAsset"class="nav-link active" aria-controls="newAsset" role="tab" data-toggle="tab">New Asset</a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#activeasset"class="nav-link" aria-controls="activeasset" role="tab" data-toggle="tab">Active Asset
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#disponal_tab"class="nav-link" id="disponal_tab-link" onclick="return getDisposal()"
                        aria-controls="disponal_tab" role="tab" data-toggle="tab">Disposal Assets</a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#update-old-asset"class="nav-link" aria-controls="update-old-asset" role="tab" data-toggle="tab">Asset
                        Rollover/Reinstated Assets</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="newAsset" style="min-height: 100px;">
                    @include('frontend.accounts.depreciation.tab-content.new-tab')
                </div>
                <div role="activeasset" class="tab-pane" id="activeasset">
                    @include('frontend.accounts.depreciation.tab-content.active-tab')
                </div>
                <div role="tabpanel" class="tab-pane" id="disponal_tab">
                    @include('frontend.accounts.depreciation.tab-content.disposal-tab')
                </div>
                <div role="update-old-asset" class="tab-pane" id="update-old-asset">
                    @include('frontend.accounts.depreciation.tab-content.old-tab')
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.accounts.depreciation.modal')
@include('frontend.accounts.depreciation.dep_js')
@endsection
