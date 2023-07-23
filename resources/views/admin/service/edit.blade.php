@extends('admin.layout.master')
@section('title','Edit Service')
@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">Admin</a>
                    </li>
                    <li>
                        <a href="#">Service</a>
                    </li>
                    <li class="active">Edit Service</li>
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



                

                <div class="page-header">
                    <h1>
                        Edit Service
                        <small>
                            <i class="ace-icon fa fa-angle-double-right"></i>
                        </small>
                    </h1>
                </div><!-- /.page-header -->
                <br>
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <form class="form-horizontal" action="{{ route('service.update', $service->id) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> Service Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name"  value="{{$service->name}}" placeholder="Enter Service Name" class="col-xs-10 col-sm-8" />
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{$errors->first('name')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="space-4"></div>



                            <div class="clearfix form-actions d-flex justify-content-center">
                                <div class="text-center">
                                    <button class="btn btn-info" type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Update
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
{{--                                    <button class="btn" type="reset">--}}
{{--                                        <i class="ace-icon fa fa-undo bigger-110"></i>--}}
{{--                                        Reset--}}
{{--                                    </button>--}}
                                </div>
                            </div>
                        </form>
                    </div><!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->

    </div>
  <!-- /.main-content -->

    @include('admin.layout.footer')

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
