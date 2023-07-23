@extends('admin.layout.master')
@section('title','Audit')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li class="active">Agent Audit</li>
                <li class="active">Agent Activity</li>
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
                            <div class="clearfix">
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header" style="text-align: center;">
                                <strong>Agent Activity</strong>

                            </div>
                            <!-- div.dataTables_borderWrap -->

                            <form action="{{route('audit.agent_delete_checked')}}" method="POST" class="">
                                @csrf @method('delete')
                                <div class="row">
                                    <div class="col-md-6">
                                        <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-info bnt-xl">Delete Selected</button>
                                        <a onclick="return confirm('Are you sure?')" href="{{route('audit.agent_destroy')}}" class="btn btn-danger bnt-xl">Delete
                                            All</a>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <label>
                                                    <input type="checkbox" id="checkAll"> &nbsp;&nbsp; SL
                                                </label>
                                            </th>
                                            <th>Customer Name</th>
                                            <th>Menu Name</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activities as $i => $activity)
                                        <tr>
                                            <td>
                                                <label>
                                                    <input value="{{$activity->id}}" type="checkbox" name="id[]"
                                                        class="child"> &nbsp;&nbsp; {{$i+1}}
                                                </label>
                                            </td>
                                            <td>{{($activity->properties['client'])??'N/A'}}</td>
                                            <td>{{$activity->description}}</td>
                                            <td>{{date('d/m/Y h:i',strtotime($activity->created_at))}}</td>
                                            <td>
                                                <a title="Delete This Audit" onclick="return confirm('Are you sure?')" href="{{route('audit.agent_delete', $activity->id)}}"
                                                    class="btn btn-danger"><i
                                                        class="ace-icon fa fa-trash-o bigger-130"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="5">
                                                {{$activities->links()}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
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
