@extends('admin.layout.master')
@section('title','User List')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>Admin</li>
                    <li>User Management</li>
                    <li class="active">User List</li>
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
                                    Results for All User
                                    @can('admin.user.create')
                                    <a class="table-header bg-danger" style="float: right !important; padding-right: 10px;background: #5cb85c;text-decoration: none" href="{{ route('user.create') }}">
                                        <i class="fa fa-plus"></i>
                                        Add User
                                    </a>
                                    @endcan
                                </div>

                                <!-- div.table-responsive -->

                                <!-- div.dataTables_borderWrap -->
                                <div>
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th class="center">SN</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <th class="center">Status</th>
                                            @canany(['admin.user.edit'])
                                            <th class="center">Action</th>
                                            @endcanany
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($admins as $index => $admin)
                                            <tr>
                                                <td class="center">{{ $index+1 }}</td>
                                                <td>{{ $admin->name }}</td>
                                                <td>
                                                    @foreach($admin->roles()->get() as $role)
                                                        <p>{{$role->name}}</p>
                                                    @endforeach
                                                </td>
                                                <td>{{$admin->email}}</td>
                                                <td class="center">
                                                   @if($admin->is_active == 1 )
                                                        <a href="@can('admin.user.deactivate') {{route('user.deactivate',$admin->id)}} @else # @endcan">
                                                            <button class="btn btn-success">Active</button>
                                                        </a>
                                                   @else
                                                        <a href="@can('admin.user.reactivate') {{route('user.reactivate',$admin->id)}} @else # @endcan">
                                                            <button class="btn btn-danger">Deactive</button>
                                                        </a>
                                                    @endif
                                                </td>
                                                @canany(['admin.user.edit'])
                                                <td class="center">
                                                    <div>
                                                        <a title="User Edit" class="green" href="{{route('user.edit',$admin->id)}}">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                @endcanany
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
@stop
