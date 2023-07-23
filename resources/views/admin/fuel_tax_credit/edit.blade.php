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
                        <div class="col-md-12">
                        <form action="{{route('FuelTaxCredit.update',$fuel_tax_credits->id)}}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="form-group col-md-3">
                                <label>Financial Year <span style="color:red;">*</span></label>
                                <input type="number" class="form-control" name="financial_year" value="{{$fuel_tax_credits->financial_year}}">
                                <div id="mgs"></div>
                            </div>
                            
                            
                            <div class="form-group col-md-3">
                                <label>Start Month <span style="color:red;">*</span></label>
                                <input required class="form-control" name="start_date" type="date" data-date-format="dd/mm/yyyy" value="{{$fuel_tax_credits->start_date}}"/>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label>End Month <span style="color:red;">*</span></label>
                                <input required class="form-control" name="end_date" type="date" data-date-format="dd/mm/yyyy" value="{{$fuel_tax_credits->end_date}}"/>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label>Rate<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" name="rate" value="{{$fuel_tax_credits->rate}}">
                            </div>


                            <div class="space-4"></div>

                            <div class="clearfix form-actions">
                                <div class="col-sm-offset-4 col-md-9">
                                    <button class="btn btn-info" type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Update
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
                                    {{-- <button class="btn" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button> --}}
                                </div>
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
