@extends('admin.layout.master')
@section('title','Edit Profession')
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
                        <a href="#">Profession</a>
                    </li>
                    <li class="active">Edit Profession</li>
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
                        Edit profession
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
                        <form class="form-horizontal" action="{{ route('profession.update', $profession->id) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> Profession Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name"  value="{{old('name', $profession->name)}}" placeholder="Enter Profession Name" class="col-xs-10 col-sm-8" />
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{$errors->first('name')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group hide">
                                <label class="col-sm-3 control-label no-padding-right">Industry Categories</label>
                                <div class="col-sm-9">
                                    <?php $selected_industry_categories = $profession->industryCategories->pluck('id')->toArray(); ?>
                                    @foreach($industry_categories as $industry_category)
                                        <label class="checkbox-inline"><input type="checkbox" name="industry_category[]" value="{{$industry_category->id}}" @if(in_array($industry_category->id, $selected_industry_categories)) checked @endif>{{ $industry_category->name }}</label>
                                    @endforeach

                                    @if($errors->has('industry_category'))
                                        <span class="text-danger">{{$errors->first('industry_category')}}</span>
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
    </div><!-- /.main-content -->

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
