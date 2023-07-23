@extends('admin.layout.master')
@section('title','PERMISSIONS')
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
                <li class="active">User Management</li>
                <li class="active">Permission List</li>
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
                            <div class="table-header">
                                Results for All Role
                                @can('permission.create')
                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#permission" style="float: right !important;padding: 10px;background: #5cb85c !important;border: none;">
                                    <i class="fa fa-plus"></i>
                                    Add Permission
                                </button>
                                @endcan
                            </div>

                            <!-- div.table-responsive -->

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <table id="dataTable" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="center">SN</th>
                                            <th>Role</th>
                                            @can(['permission'])
                                            <th class="center">Action</th>
                                            @endcan
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($permissions as $index => $permission)
                                        <tr>
                                            <td class="center">{{ $index+1 }}</td>
                                            <td>{{ $permission->name }}</td>
                                            @can(['permission'])
                                            <td class="center">
                                                <div>
                                                    <a title="Role Edit" class="green" href="{{route('role.edit',$permission->id)}}">
                                                        <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            @endcan
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
<!-- Modal -->
<div class="modal fade" id="permission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionLabel">Add New Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('permission.store')}}" method="post">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $("#dataTable").dataTable()
</script>
@stop
<!-- Button trigger modal -->
