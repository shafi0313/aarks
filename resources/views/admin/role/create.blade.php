@extends('admin.layout.master')
@section('title', 'Add Role')
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
                        <a href="#">Role</a>
                    </li>
                    <li class="active">Add Role</li>
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
                <div class="page-header">
                    <h1>
                        Add New Role
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
                        <form class="form-horizontal" action="{{ route('role.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Role Name: </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            placeholder="Enter Role Name" class="col-xs-10 col-sm-8" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label"></label>
                                    <span class="text-danger col-sm-9"> {{ $errors->first('name') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    @if ($errors->has('permission'))
                                        <label class="col-sm-3 control-label"></label>
                                        <span class="text-danger col-sm-9"> {{ $errors->first('permission') }}</span>
                                    @endif
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label">Permissions: </label>
                                    <div class="col-sm-9">
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 text-center">
                                                User
                                            </div>
                                            {{--                                            <div class="col-sm-2"> --}}
                                            {{--                                                <input type="checkbox" name="select_all"> Select All --}}
                                            {{--                                            </div> --}}
                                            <div class="col-sm-8">
                                                <input type="checkbox" value="{{ $permissions['admin.user.index'] }}"
                                                    name="permission[]"> User List <br>
                                                <input type="checkbox" value="{{ $permissions['admin.user.create'] }}"
                                                    name="permission[]"> Add User <br>
                                                <input type="checkbox" value="{{ $permissions['admin.user.edit'] }}"
                                                    name="permission[]"> Edit User <br>
                                                <input type="checkbox" value="{{ $permissions['admin.user.deactivate'] }}"
                                                    name="permission[]"> Deactivate User <br>
                                                <input type="checkbox" value="{{ $permissions['admin.user.reactivate'] }}"
                                                    name="permission[]"> Reactivate User <br>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 text-center">
                                                Client
                                            </div>
                                            {{--                                           <div class="col-sm-2"> --}}
                                            {{--                                               <input type="checkbox" name="select_all"> Select All --}}
                                            {{--                                           </div> --}}
                                            <div class="col-sm-8">
                                                <input type="checkbox" value="{{ $permissions['admin.client.index'] }}"
                                                    name="permission[]"> Client List <br>
                                                <input type="checkbox" value="{{ $permissions['admin.client.create'] }}"
                                                    name="permission[]"> Add Client <br>
                                                <input type="checkbox" value="{{ $permissions['admin.client.edit'] }}"
                                                    name="permission[]"> Edit Client <br>
                                                <input type="checkbox" value="{{ $permissions['admin.client.delete'] }}"
                                                    name="permission[]"> Delete Client <br>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 text-center">
                                                Role
                                            </div>
                                            {{--                                           <div class="col-sm-2"> --}}
                                            {{--                                               <input type="checkbox" name="select_all"> Select All --}}
                                            {{--                                           </div> --}}
                                            <div class="col-sm-8">
                                                <input type="checkbox" value="{{ $permissions['admin.role.index'] }}"
                                                    name="permission[]"> Role List <br>
                                                <input type="checkbox" value="{{ $permissions['admin.role.create'] }}"
                                                    name="permission[]"> Create Role <br>
                                                <input type="checkbox" value="{{ $permissions['admin.role.edit'] }}"
                                                    name="permission[]"> Edit Role <br>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 text-center">
                                                Profession
                                            </div>
                                            {{--                                           <div class="col-sm-2"> --}}
                                            {{--                                               <input type="checkbox" name="select_all"> Select All --}}
                                            {{--                                           </div> --}}
                                            <div class="col-sm-8">
                                                <input type="checkbox" value="{{ $permissions['admin.profession.index'] }}"
                                                    name="permission[]"> Profession List <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.profession.create'] }}"
                                                    name="permission[]"> Create Profession <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.profession.edit'] }}"
                                                    name="permission[]"> Edit Profession <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.profession.delete'] }}"
                                                    name="permission[]"> Delete Profession <br>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 text-center">
                                                Service
                                            </div>
                                            {{--                                           <div class="col-sm-2"> --}}
                                            {{--                                               <input type="checkbox" name="select_all"> Select All --}}
                                            {{--                                           </div> --}}
                                            <div class="col-sm-8">
                                                <input type="checkbox" value="{{ $permissions['admin.service.index'] }}"
                                                    name="permission[]"> Service List <br>
                                                <input type="checkbox" value="{{ $permissions['admin.service.create'] }}"
                                                    name="permission[]"> Create Service <br>
                                                <input type="checkbox" value="{{ $permissions['admin.service.edit'] }}"
                                                    name="permission[]"> Edit Service <br>
                                                <input type="checkbox" value="{{ $permissions['admin.service.delete'] }}"
                                                    name="permission[]"> Delete Service <br>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 text-center">
                                                Account Code
                                            </div>
                                            {{--                                           <div class="col-sm-2"> --}}
                                            {{--                                               <input type="checkbox" name="select_all"> Select All --}}
                                            {{--                                           </div> --}}
                                            <div class="col-sm-8">
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.account-code.index'] }}"
                                                    name="permission[]"> Account Code List <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.account-code.create'] }}"
                                                    name="permission[]"> Account Code Create <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.account-code.edit'] }}"
                                                    name="permission[]"> Account Code Edit <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.account-code.delete'] }}"
                                                    name="permission[]"> Account Code Delete <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.account-code.sub-category.create'] }}"
                                                    name="permission[]"> Sub Group Create <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.account-code.additional-category.create'] }}"
                                                    name="permission[]"> Sub Sub Group Create <br>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 text-center">
                                                Master Chart
                                            </div>
                                            {{--                                           <div class="col-sm-2"> --}}
                                            {{--                                               <input type="checkbox" name="select_all"> Select All --}}
                                            {{--                                           </div> --}}
                                            <div class="col-sm-8">
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.master-chart.index'] }}"
                                                    name="permission[]"> Master Chart List <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.master-chart.create'] }}"
                                                    name="permission[]"> Master Chart Create <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.master-chart.edit'] }}"
                                                    name="permission[]"> Master Chart Edit <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.master-chart.delete'] }}"
                                                    name="permission[]"> Master Chart Delete <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.master-chart.sub-category.create'] }}"
                                                    name="permission[]"> Sub Group Create <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.master-chart.additional-category.create'] }}"
                                                    name="permission[]"> Sub Sub Group Create <br>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 text-center">
                                                Bank-Statement Import
                                            </div>
                                            {{--                                           <div class="col-sm-2"> --}}
                                            {{--                                               <input type="checkbox" name="select_all"> Select All --}}
                                            {{--                                           </div> --}}
                                            <div class="col-sm-8">
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.bs_import.index'] }}"
                                                    name="permission[]"> Bank-Statement List <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.bs_import.create'] }}"
                                                    name="permission[]"> Bank-Statement Create <br>
                                                <input type="checkbox" value="{{ $permissions['admin.bs_import.edit'] }}"
                                                    name="permission[]"> Bank-Statement Edit <br>
                                                {{-- <input type="checkbox" value="{{$permissions['admin.bs_import.import']}}" name="permission[]"> Bank-Statement Import <br> --}}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2 text-center">
                                                Bank-Statement Input
                                            </div>
                                            {{--                                           <div class="col-sm-2"> --}}
                                            {{--                                               <input type="checkbox" name="select_all"> Select All --}}
                                            {{--                                           </div> --}}
                                            <div class="col-sm-8">
                                                <input type="checkbox" value="{{ $permissions['admin.bs_input.index'] }}"
                                                    name="permission[]"> Bank-Statement List <br>
                                                <input type="checkbox"
                                                    value="{{ $permissions['admin.bs_input.create'] }}"
                                                    name="permission[]"> Bank-Statement Create <br>
                                                <input type="checkbox" value="{{ $permissions['admin.bs_input.edit'] }}"
                                                    name="permission[]"> Bank-Statement Edit <br>
                                                <input type="checkbox" value="{{ $permissions['admin.bs_input.post'] }}"
                                                    name="permission[]"> Bank-Statement Post <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="clearfix form-actions ">
                                <div class="text-center">
                                    <button class="btn btn-info" type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Submit
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
                                    <a href="{{ route('role.index') }}">
                                        <button class="btn btn-danger" type="button">
                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                            Cancel
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div><!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
@stop
