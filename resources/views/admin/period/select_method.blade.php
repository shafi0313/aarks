@extends('admin.layout.master')
@section('title','Add/Edit Entry')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{route('admin.dashboard')}}">Home</a>
                </li>
                <li>
                    <a href="{{ route('select_method') }}">Add/Edit Entry</a>
                </li>
                <li class="active">Select Method</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
        </div>

        <div class="page-content">


            <div class="row">

                <div class="col-lg-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <br>
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <label style="font-size: 20px;">Select Method</label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" id="select-client" onchange="location = this.value">
                                    <option selected value disabled> Select </option>
                                    @can('admin.period.index')
                                    <option value="{{route('period.index')}}">Data Entry From Receipt(ADT)</option>
                                    @endcan
                                    @can('admin.bs_import.index')
                                    <option value="{{route('bs_import.index')}}">Import Bank Statement(BST)</option>
                                    @endcan
                                    @can('admin.bs_input.index')
                                    <option value="{{route('bs_input.index')}}">Input Bank Statement(INP)</option>
                                    @endcan
                                    @can('admin.journal_entry.index')
                                    <option value="{{route('journal_entry_client')}}">Journal Entry (JNP)</option>
                                    @endcan
                                    @can('admin.depreciation.index')
                                    <option value="{{route('depreciation.index')}}">Depreciation(DEP)</option>
                                    @endcan
                                    @can('admin.budget.index')
                                    <option value="{{route('budget.index')}}">Prepare Budget</option>
                                    @endcan
                                    @can('admin.business_plan.index')
                                    <option value="{{route('business-plan.index')}}">Business Plan/Monthly Budget Plan for Existing Business</option>
                                    @endcan
                                </select>
                            </div>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- inline scripts related to this page -->

@endsection
