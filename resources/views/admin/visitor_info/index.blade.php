@extends('admin.layout.master')
@section('title','Visitors Information')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Visitors Information</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div>
            <!-- /.nav-search -->
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
                            <div class="table-header" style="text-align: center;">
                                <strong>Visitor Informations</strong>
                            </div>
                            <br>
                            <br>

                            <!-- div.table-responsive -->

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <form action="{{route('visitor.delSelected')}}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-info bnt-xl">Delete Selected</button>
                                            <a href="{{route('visitor.destroy')}}" class="btn btn-danger bnt-xl">Delete All</a>
                                        </div>
                                    </div>

                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>
                                                    <input  type="checkbox" id="checkAll"> &nbsp;&nbsp; SL
                                                    </label>
                                                </th>
                                                <th class="text-center">IP</th>
                                                <th class="text-center">Country Name</th>
                                                <th class="text-center">Region Name</th>
                                                <th class="text-center">City Name</th>
                                                <th class="text-center">Zip Code</th>
                                                <th class="text-center">Latitude</th>
                                                <th class="text-center">Longitude </th>
                                                {{-- <th>currency</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($visitors as $key=>$visitor)

                                            <tr>
                                                <td>
                                                    <label>
                                                    <input value="{{$visitor->id}}" type="checkbox" name="id[]" class="child" > &nbsp;&nbsp; {{$key+1}}
                                                    </label>
                                                </td>
                                                <td>{{$visitor->ip}} </td>
                                                <td>
                                                    <span><i class="{{strtolower($visitor->country)}} flag"></i></span>
                                                    {{$visitor->country}}
                                                </td>
                                                <td>{{$visitor->state_name}} </td>
                                                <td>{{$visitor->city}} </td>
                                                <td>{{$visitor->postal_code}} </td>
                                                <td>{{$visitor->lat}} </td>
                                                <td>{{$visitor->lon}} </td>
                                                {{-- <td>{{$visitor->currency}} </td> --}}
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="9">{{$visitors->links()}} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
@endsection
@push('custom_scripts')
<script>
    $(function () {
        $("#checkAll").on("click", function () {
            if($(this).is(":checked")){
                $('.child').prop('checked', true);
            } else {
                $('.child').prop('checked', false);
            }
        })
    });
</script>
@endpush
