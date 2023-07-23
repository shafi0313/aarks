@extends('admin.layout.master')

@section('title', 'Go to Account Code')

@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="#">Master File</a>
                    </li>
                </ul><!-- /.breadcrumb -->
            </div>
        </div>
        <!-- Settings -->
    {{-- @include('admin.layout.settings') --}}
    <!-- /Settings -->

            <div class="page-content">
                <div class="row">
                    <div class="col-md-3">
                        <h2>Select Professions</h2>
                    </div>

                    <div class="col-md-4" style="padding-top:20px;">
                        <form action="#" name="topform">
                            <div class="form-group">
                                <select class="form-control select2Single" id="proid" name="proid" tabindex="7" onchange="location = this.value" >
                                    <option>Select Professions</option>
                                    @foreach($professions as $profession)
                                        <option value="{{route('account-code.index', $profession->id)}}">{{$profession->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <!-- Footer -->
{{--    @include('admin.layout.footer')--}}
    <!-- /Footer -->

    <!-- ace scripts -->
{{--        <script src="{{ asset('admin/assets/js/ace-elements.min.js')}}"></script>--}}
{{--        <script src="{{ asset('admin/assets/js/ace.min.js')}}"></script>--}}

@stop
