@extends('frontend.layout.master')
@section('title','Client Depreciation')
@section('content')
<?php $p="cdep"; $mp="acccounts";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-header">
                        <nav>
                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="newAsset-tab" data-toggle="tab" href="#newAsset"
                                    role="tab" aria-controls="newAsset" aria-selected="true">New Asset</a>
                                <a class="nav-item nav-link" id="activeasset-tab" data-toggle="tab" href="#activeasset"
                                    role="tab" aria-controls="activeasset" aria-selected="false">Active Asset</a>
                                <a class="nav-item nav-link" id="disponal_tab-tab" data-toggle="tab" onclick="getDisposal()"
                                    href="#disponal_tab" role="tab" aria-controls="disponal_tab"
                                    aria-selected="false">Disposal Asset</a>
                                <a class="nav-item nav-link" id="update-old-asset-tab" data-toggle="tab"
                                    href="#update-old-asset" role="tab" aria-controls="update-old-asset"
                                    aria-selected="false">Asset Rollover/Reinstated</a>
                            </div>
                        </nav>
                    </div>
                    <div class="card-body">
                        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="newAsset" style="min-height: 100px;">
                                    @include('frontend.accounts.depreciation.asset-name.tab-content.new-tab')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="activeasset">
                                    @include('frontend.accounts.depreciation.asset-name.tab-content.active-tab')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="disponal_tab">
                                    @include('frontend.accounts.depreciation.asset-name.tab-content.disposal-tab')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="update-old-asset">
                                    @include('frontend.accounts.depreciation.asset-name.tab-content.old-tab')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('frontend.accounts.depreciation.modal', ['type'=> 'asset-name-delete'])
@include('frontend.accounts.depreciation.modal', ['type'=> 'ajax-modal'])
@include('frontend.accounts.depreciation.dep_js')
@endsection
