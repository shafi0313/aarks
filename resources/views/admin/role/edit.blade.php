@extends('admin.layout.master')
@section('title', 'Update Role')
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
                    <li class="active">Update Role</li>
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
                        Update Role
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
                        <form class="form-horizontal" action="{{ route('role.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Role Name: </label>
                                <div class="col-sm-7">
                                    <input type="text" name="name" value="{{ $role->name }}"
                                        placeholder="Enter User Name" class="col-xs-10 col-sm-8" />
                                    @if ($errors->has('name'))
                                        <br><br> <span class="text-danger"> {{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="col-md-12 btn btn-primary bnt-block">Permissions: </label>
                                        <br>
                                        <br>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        User
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.user.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.user.index', $role_permissions) ? 'checked' : '' }}>
                                                        List <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.user.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.user.create', $role_permissions) ? 'checked' : '' }}>
                                                        Add <br>
                                                        <input type="checkbox" value="{{ $permissions['admin.user.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.user.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.user.deactivate'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.user.deactivate', $role_permissions) ? 'checked' : '' }}>
                                                        Deactivate
                                                        <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.user.reactivate'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.user.reactivate', $role_permissions) ? 'checked' : '' }}>
                                                        Reactivate
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Role
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.role.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.role.index', $role_permissions) ? 'checked' : '' }}>
                                                        List <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.role.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.role.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.role.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.role.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Profession
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.profession.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.profession.index', $role_permissions) ? 'checked' : '' }}>

                                                        List <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.profession.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.profession.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create
                                                        <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.profession.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.profession.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit
                                                        <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.profession.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.profession.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Master Chart
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.master-chart.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.master-chart.index', $role_permissions) ? 'checked' : '' }}>

                                                        Chart List <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.master-chart.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.master-chart.create', $role_permissions) ? 'checked' : '' }}>

                                                        Chart Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.master-chart.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.master-chart.edit', $role_permissions) ? 'checked' : '' }}>

                                                        Chart Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.master-chart.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.master-chart.delete', $role_permissions) ? 'checked' : '' }}>

                                                        Chart Delete <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.master-chart.sub-category.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.master-chart.sub-category.create', $role_permissions) ? 'checked' : '' }}>
                                                        Sub Group
                                                        Create
                                                        <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.master-chart.additional-category.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.master-chart.additional-category.create', $role_permissions) ? 'checked' : '' }}>
                                                        Sub
                                                        Sub
                                                        Group Create <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Account Code
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.account-code.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.account-code.index', $role_permissions) ? 'checked' : '' }}>

                                                        Code List <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.account-code.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.account-code.create', $role_permissions) ? 'checked' : '' }}>

                                                        Code Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.account-code.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.account-code.edit', $role_permissions) ? 'checked' : '' }}>

                                                        Code Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.account-code.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.account-code.delete', $role_permissions) ? 'checked' : '' }}>

                                                        Code Delete <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.account-code.sub-category.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.account-code.sub-category.create', $role_permissions) ? 'checked' : '' }}>
                                                        Sub Group Create
                                                        <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.account-code.additional-category.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.account-code.additional-category.create', $role_permissions) ? 'checked' : '' }}>
                                                        Sub Sub
                                                        Group Create <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Fuel Tax Credit Rate
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.fuel_tax_cr.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.fuel_tax_cr.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.fuel_tax_cr.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.fuel_tax_cr.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.fuel_tax_cr.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.fuel_tax_cr.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.fuel_tax_cr.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.fuel_tax_cr.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <hr> --}}

                                        {{-- <div class="row"> --}}
                                        {{-- <div class="col-sm-2">
                                            <div class="row">
                                                <div class="col-sm-5 text-center">
                                                    Coefficients
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.coefficients.index']}}"
                                                        name="permission[]" {{in_array('admin.coefficients.index',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Index <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.coefficients.create']}}"
                                                        name="permission[]" {{in_array('admin.coefficients.create',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Create <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.coefficients.edit']}}"
                                                        name="permission[]" {{in_array('admin.coefficients.edit',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Edit <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.coefficients.delete']}}"
                                                        name="permission[]" {{in_array('admin.coefficients.delete',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Delete <br>
                                                </div>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="col-sm-2">
                                            <div class="row">
                                                <div class="col-sm-5 text-center">
                                                    I Tax Table
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.i_tax_table.index']}}"
                                                        name="permission[]" {{in_array('admin.i_tax_table.index',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Index <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.i_tax_table.create']}}"
                                                        name="permission[]" {{in_array('admin.i_tax_table.create',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Create <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.i_tax_table.edit']}}"
                                                        name="permission[]" {{in_array('admin.i_tax_table.edit',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Edit <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.i_tax_table.delete']}}"
                                                        name="permission[]" {{in_array('admin.i_tax_table.delete',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Delete <br>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-sm-2">
                                            <div class="row">
                                                <div class="col-sm-5 text-center">
                                                    Wages
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="checkbox" value="{{$permissions['admin.wages.index']}}"
                                                        name="permission[]" {{in_array('admin.wages.index',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Index <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.wages.create']}}"
                                                        name="permission[]" {{in_array('admin.wages.create',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Create <br>
                                                    <input type="checkbox" value="{{$permissions['admin.wages.edit']}}"
                                                        name="permission[]" {{in_array('admin.wages.edit',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Edit <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.wages.delete']}}"
                                                        name="permission[]" {{in_array('admin.wages.delete',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Delete <br>
                                                </div>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="col-sm-2">
                                            <div class="row">
                                                <div class="col-sm-5 text-center">
                                                    Super Annuation
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.superannuation.index']}}"
                                                        name="permission[]" {{in_array('admin.superannuation.index',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Index <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.superannuation.create']}}"
                                                        name="permission[]" {{in_array('admin.superannuation.create',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Create <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.superannuation.edit']}}"
                                                        name="permission[]" {{in_array('admin.superannuation.edit',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Edit <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.superannuation.delete']}}"
                                                        name="permission[]" {{in_array('admin.superannuation.delete',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Delete <br>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-sm-2">
                                            <div class="row">
                                                <div class="col-sm-5 text-center">
                                                    Leave
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="checkbox" value="{{$permissions['admin.leave.index']}}"
                                                        name="permission[]" {{in_array('admin.leave.index',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Index <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.leave.create']}}"
                                                        name="permission[]" {{in_array('admin.leave.create',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Create <br>
                                                    <input type="checkbox" value="{{$permissions['admin.leave.edit']}}"
                                                        name="permission[]" {{in_array('admin.leave.edit',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Edit <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.leave.delete']}}"
                                                        name="permission[]" {{in_array('admin.leave.delete',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Delete <br>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-sm-2">
                                            <div class="row">
                                                <div class="col-sm-5 text-center">
                                                    Deducation
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.deducation.index']}}"
                                                        name="permission[]" {{in_array('admin.deducation.index',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Index <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.deducation.create']}}"
                                                        name="permission[]" {{in_array('admin.deducation.create',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Create <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.deducation.edit']}}"
                                                        name="permission[]" {{in_array('admin.deducation.edit',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Edit <br>
                                                    <input type="checkbox"
                                                        value="{{$permissions['admin.deducation.delete']}}"
                                                        name="permission[]" {{in_array('admin.deducation.delete',
                                                        $role_permissions) ? 'checked' : '' }}>
                                                    Delete <br>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- </div> --}}

                                        <hr>

                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Service
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.service.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.service.index', $role_permissions) ? 'checked' : '' }}>
                                                        List
                                                        <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.service.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.service.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create
                                                        <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.service.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.service.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit
                                                        <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.service.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.service.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Tools
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.verify_account.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.verify_account.index', $role_permissions) ? 'checked' : '' }}>
                                                        Verify & Fixed Trans <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.period_lock.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.period_lock.index', $role_permissions) ? 'checked' : '' }}>
                                                        Period Lock <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Client
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.client.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.client.index', $role_permissions) ? 'checked' : '' }}>
                                                        List <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.client.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.client.create', $role_permissions) ? 'checked' : '' }}>
                                                        Add <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.client.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.client.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.client.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.client.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Client Payment
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.client_payment.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.client_payment.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.client_payment.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.client_payment.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.client_payment.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.client_payment.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.client_payment.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.client_payment.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Period
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.period.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.period.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.period.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.period.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.period.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.period.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.period.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.period.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Data Entry from receipt (ADT)
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.adt.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.adt.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.adt.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.adt.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.adt.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.adt.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.adt.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.adt.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center text-danger">
                                                        Bank Statement Import
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_import.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_import.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_import.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_import.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_import.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_import.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center text-primary">
                                                        Bank Statement Input
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_input.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_input.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_input.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_input.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_input.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_input.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_input.post'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_input.post', $role_permissions) ? 'checked' : '' }}>
                                                        Post <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Depreciation
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.depreciation.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.depreciation.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.depreciation.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.depreciation.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.depreciation.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.depreciation.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.depreciation.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.depreciation.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Prepare Budget
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.budget.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.budget.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.budget.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.budget.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.budget.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.budget.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Business Plan
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.business_plan.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.business_plan.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.business_plan.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.business_plan.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.business_plan.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.business_plan.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        GST Reconciliation for TR
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.gst-reconciliation-for-tr.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.gst-reconciliation-for-tr.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.gst-reconciliation-for-tr.save'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.gst-reconciliation-for-tr.save', $role_permissions) ? 'checked' : '' }}>
                                                        Save <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.gst-reconciliation-for-tr.post'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.gst-reconciliation-for-tr.post', $role_permissions) ? 'checked' : '' }}>
                                                        Post <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.gst-reconciliation-for-tr.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.gst-reconciliation-for-tr.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                    </div>
                                                </div>
                                            </div>


                                            {{-- <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Close Year
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.close_year.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.close_year.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.close_year.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.close_year.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.close_year.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.close_year.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.close_year.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.close_year.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                        Reports
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.cash_basis.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.cash_basis.index', $role_permissions) ? 'checked' : '' }}>
                                                        GST/BAS(<span style="color: green">Consol.</span>Cash)  <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.accrued_basis.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.accrued_basis.index', $role_permissions) ? 'checked' : '' }}>
                                                            GST/BAS(<span style="color: green">Consol.</span>Acrued) <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.periodic_cash.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.periodic_cash.index', $role_permissions) ? 'checked' : '' }}>
                                                        Periodic BAS(<span style="color: green">s/actv</span>Cash) <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.periodic_accrued.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.periodic_accrued.index', $role_permissions) ? 'checked' : '' }}>
                                                            Periodic BAS(<span style="color: green">s/actv</span>Acur) <br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.general_ledger.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.general_ledger.index', $role_permissions) ? 'checked' : '' }}>
                                                        General Ledger<br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.trial_balance.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.trial_balance.index', $role_permissions) ? 'checked' : '' }}>
                                                        Trial Balance <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row">
                                                    <div class="col-sm-4 text-center"></div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.cons_trial_balance.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.cons_trial_balance.index', $role_permissions) ? 'checked' : '' }}>
                                                        <span class="text-success">Consol</span> Trial Balance <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.profit_loss_excl.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.profit_loss_excl.index', $role_permissions) ? 'checked' : '' }}>
                                                        P/L(GST <span style="color: red">Excl</span>,S/Activity) <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.profit_loss_incl.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.profit_loss_incl.index', $role_permissions) ? 'checked' : '' }}>
                                                            P/L(GST <span style="color: red">Incl</span>,S/Activity) <br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.console_accum_excl.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.console_accum_excl.index', $role_permissions) ? 'checked' : '' }}>
                                                            <span style="color: green">Consol.</span>P/L(GST <span style="color: red">Excl</span>) <br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.console_accum_incl.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.console_accum_incl.index', $role_permissions) ? 'checked' : '' }}>
                                                            <span style="color: green">Consol.</span>P/L(GST <span style="color: red">Incl</span>) <br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.balance_sheet.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.balance_sheet.index', $role_permissions) ? 'checked' : '' }}>
                                                        Balance Sheet(S/Activity)<br>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="row">
                                                    <div class="col-sm-4 text-center">

                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.balance_sheet.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.balance_sheet.index', $role_permissions) ? 'checked' : '' }}>
                                                            <span style="color: green">Consol.</span> Balance Sheet<br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.comperative_bs.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.comperative_bs.index', $role_permissions) ? 'checked' : '' }}>
                                                        Comparative B/S<br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.complete_financial_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.complete_financial_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Complete Financial R.<br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.complete_financial_report_t_f.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.complete_financial_report_t_f.index', $role_permissions) ? 'checked' : '' }}>
                                                        Complete Fin Report(T Form)<br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.console_financial_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.console_financial_report.index', $role_permissions) ? 'checked' : '' }}>
                                                            <span style="color: green">Consol.</span>Financial Report<br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.comperative_financial_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.comperative_financial_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Comparative Financial R.<br>
                                                        {{-- <input type="checkbox"
                                                            value="{{ $permissions['admin.advanced_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.advanced_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Advanced report<br> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">

                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.depreciation_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.depreciation_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Depreciation report<br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.business_plan_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.business_plan_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Business Plan Report<br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.business_analysis_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.business_analysis_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Monthly Business Analysis Details P & L<br>

                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.budget_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.budget_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Budget<br>

                                                        {{-- <input type="checkbox"
                                                            value="{{ $permissions['admin.consolidated_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.consolidated_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Consolidated report <br> --}}
                                                        {{-- <input type="checkbox"
                                                            value="{{ $permissions['admin.financial_analysis.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.financial_analysis.index', $role_permissions) ? 'checked' : '' }}>
                                                        Financial Analysis<br> --}}
                                                        {{-- <input type="checkbox"
                                                            value="{{ $permissions['admin.ratio_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.ratio_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Ratio Report<br> --}}
                                                        {{-- <input type="checkbox"
                                                            value="{{ $permissions['admin.investment_report.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.investment_report.index', $role_permissions) ? 'checked' : '' }}>
                                                        Investment Report<br> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Journal Entry (JNP)
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.journal_entry.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.journal_entry.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.journal_entry.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.journal_entry.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.journal_entry.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.journal_entry.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.journal_entry.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.journal_entry.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Journal List
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.journal_list.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.journal_list.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.journal_list.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.journal_list.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.journal_list.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.journal_list.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.journal_list.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.journal_list.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Bank Transaction List
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_tranlist.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_tranlist.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_tranlist.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_tranlist.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_tranlist.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_tranlist.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bs_tranlist.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bs_tranlist.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Bank Reconcilation
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bank_recon.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bank_recon.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bank_recon.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bank_recon.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bank_recon.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bank_recon.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.bank_recon.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.bank_recon.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Payment Tran Adv.delete
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.payment_sync.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.payment_sync.index', $role_permissions) ? 'checked' : '' }}>
                                                        Index <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.payment_sync.create'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.payment_sync.create', $role_permissions) ? 'checked' : '' }}>
                                                        Create <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.payment_sync.edit'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.payment_sync.edit', $role_permissions) ? 'checked' : '' }}>
                                                        Edit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.payment_sync.delete'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.payment_sync.delete', $role_permissions) ? 'checked' : '' }}>
                                                        Delete <br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Trash/Move Data
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.CB-data-move'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.CB-data-move', $role_permissions) ? 'checked' : '' }}>
                                                        C/B Data Move <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.trash'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.trash', $role_permissions) ? 'checked' : '' }}>
                                                        Trash <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.delete-data-permanently'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.delete-data-permanently', $role_permissions) ? 'checked' : '' }}>
                                                        Delete Data Permanently <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="row">
                                                    <div class="col-sm-5 text-center">
                                                        Others
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.database_dump.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.database_dump.index', $role_permissions) ? 'checked' : '' }}>
                                                        Database Download<br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.logging_audit.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.logging_audit.index', $role_permissions) ? 'checked' : '' }}>
                                                        Logging Audit <br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.agent_audit.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.agent_audit.index', $role_permissions) ? 'checked' : '' }}>
                                                        Agent Audit<br>
                                                        <input type="checkbox"
                                                            value="{{ $permissions['admin.visitor_info.index'] }}"
                                                            name="permission[]"
                                                            {{ in_array('admin.visitor_info.index', $role_permissions) ? 'checked' : '' }}>
                                                        Visitor Informations<br>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <hr>

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
