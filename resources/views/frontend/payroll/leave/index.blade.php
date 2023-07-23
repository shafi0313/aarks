@extends('frontend.layout.master')
@section('title','Manage Leaves')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Admin</li>
                <li class="active">Manage Payroll</li>
                <li class="active">Leave</li>
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

                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="table-header" style="text-align: right;">
                        <button type="button" class="btn btn-success adddata" data-toggle="modal" data-target="#myModal"><i class="ace-icon fa fa-plus"></i> Add Leave Category</button>

                        {{-- <a href="#" class="btn btn-success adddata"><i class="ace-icon fa fa-plus"></i> Add Data</a> --}}
                    </div>

                    <!-- div.table-responsive -->

                    <!-- div.dataTables_borderWrap -->

                    <!-- Modal -->

                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add New Leave</h4>
                                </div>
                                <form role="form" action="{{route('standardleave.store')}}" method="POST" autocomplete="off">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row" style="padding:0  20px 10px 0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="col-sm-3 control-label text-right">
                                                        Leave Name<strong style="color:red;">*</strong></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="name" id="name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding:0  20px 10px 0">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="rate" class="col-sm-6 control-label"
                                                        align="right">Equals</label>
                                                    <div class="col-sm-6">
                                                        <input type="number" class="form-control" id="rate"
                                                            name="rate">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tools" class="col-sm-4 control-label"
                                                        align="right">Percent of</label>
                                                    <div class="col-sm-6">
                                                        <select name="tools" id="tools" class="form-control">
                                                            <option value="BS" selected="">Basic Sallary</option>
                                                            <option value="GH">Gross Hours</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding:0  20px 10px 0">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="fix_amt" class="col-sm-6 control-label"
                                                        align="right">Equals</label>
                                                    <div class="col-sm-6">
                                                        <input type="number" class="form-control" id="fix_amt"
                                                            name="fix_amt">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="period" class="col-sm-4 control-label"
                                                        align="right">Dollars Per</label>
                                                    <div class="col-sm-6">
                                                        <select name="period" id="period" class="form-control">
                                                            <option value="Pay Period" selected="">Pay Period</option>
                                                            <option value="Per Hours">Per Hours</option>
                                                            <option value="Per Month">Per Month</option>
                                                            <option value="Per Forthnighty">Per Forthnighty</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="padding:0  20px 10px 50px">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="carry" id="carry"
                                                                value="1">Carry Remaining Entitlement Over to Next Year
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-seccess">Submit</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="center">SN</th>
                                <th>Leave Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $x=1 @endphp
                            @foreach ($leavs as $leav)
                            <tr>
                                <td class="center">{{ $x++ }}</td>
                                <td>{{$leav->name}}</td>
                                <td>
                                    <div class="  action-buttons">
                                        <a title="Standard Leave Edit" class="green" href="{{route('standardleave.edit',$leav->id)}}"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
                                        <a title="Standard Leave Delete" class="red" href="{{route('standardleave.delete',$leav->id)}}" onclick="return confirm('Are you sure?')"><i class="ace-icon fa fa-trash bigger-130"></i></a>
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- Footer Start -->

<!-- Footer End -->


@stop
