@extends('admin.layout.master')
@section('title','Go to Period')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>

                <li>Add/Edit Data
                    {{-- <a href="#"></a> --}}
                </li>
                <li class="active">Select Client</li>
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
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-2">
                                    <h2>Select Client</h2>
                                </div>
                                <div class="col-md-5" style="padding-top:20px;">
                                    <form action="#" name="topform">
                                        <div class="form-group">
                                            <select class="form-control" id="select-client"
                                                onchange="location = this.value">
                                                <option> Select a Client</option>
                                            </select>
                                        </div>
                                    </form>
                                    <script>
                                        $('#select-client').select2({
                                        ajax           : {
                                        url            : '../api/clients',
                                        type           : 'get',
                                        dataType       : 'json',
                                        delay          : 250,
                                        processResults : function (data) {
                                        return {
                                        results : data
                                        };
                                        },
                                        cache:true,
                                        }
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- inline scripts related to this page -->
@stop
