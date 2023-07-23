@extends('admin.layout.master')

@section('title', 'Select Profession')

@section('content')

    <div class="main-content">
{{--        <div class="main-content-inner">--}}
{{--            <div class="breadcrumbs" id="breadcrumbs">--}}
{{--                <script type="text/javascript">--}}
{{--                    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}--}}
{{--                </script>--}}

{{--                <ul class="breadcrumb">--}}
{{--                    <li>--}}
{{--                        <i class="ace-icon fa fa-home home-icon"></i>--}}
{{--                        <a href="#">Home</a>--}}
{{--                    </li>--}}

{{--                    <li>--}}
{{--                        <a href="#">Add/Edit Data</a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">Import Data</a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="#">{{$client->first_name}}</a>--}}
{{--                    </li>--}}
{{--                </ul><!-- /.breadcrumb -->--}}
{{--            </div>--}}
{{--        </div>--}}
        <!-- Settings -->
    {{-- @include('admin.layout.settings') --}}
    <!-- /Settings -->

        <div class="page-content">
            <div class="row">
                <div class="col-md-3">

                </div>

                <div class="col-md-4" style="padding-top:20px;">
                    <h2>Select Professions</h2>
                    <form action="#" name="topform">
                        <div class="form-group">
                            <select class="form-control" id="proid" name="proid" tabindex="7" onchange="location = this.value">
                                <option>Select Profession</option>
                                @foreach($client->professions as $profession)
                                    <option value="{{route('bs_import.BS',['client' => $client->id,'profession' => $profession->id])}}">{{$profession->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
