@extends('admin.layout.master')
@section('title','Console Trial Balance')
@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>
                    <li>Trial Balance</li>
                    <li class="active">Console Trial Balance</li>
                </ul><!-- /.breadcrumb -->

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
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
                                <div class="clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div class="table-header">
                                    All Client
                                </div>

                                <!-- div.table-responsive -->

                                <!-- div.dataTables_borderWrap -->
                                <div>
                                    {{--                                /////////////////////--}}
                                    @include('admin._client_index_table',['from' => 'console_trial_balance_index'])
                                    {{--                                ////////////////////--}}
                                </div>
                            </div>
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <!-- Modal -->


    @include('admin.layout.footer')

    @include('admin.layout.delete_model')

    <script>
        $(document).ready(function(){
            $(".delete_link").on('click', function(){
                var id = $(this).attr("rel");
                var delete_url = "";
                $(".modal_delete_link").attr("href", delete_url);
                $("#exampleModalCenter").modal('show');
            });
        });
    </script>

    <!-- page specific plugin scripts -->
    <script src="{{ asset('admin/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/jquery.dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/buttons.flash.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/buttons.colVis.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/dataTables.select.min.js')}}"></script>

    <!-- ace scripts -->
    <script src="{{ asset('admin/assets/js/ace-elements.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/ace.min.js')}}"></script>
@endsection
