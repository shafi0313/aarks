@extends('admin.layout.master')
@section('title','Coefficients')
@section('content')


<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{route('admin.dashboard')}}">Home</a>
                </li>
                <li>Admin</li>
                <li>Manage Payroll</li>
                <li class="active">Coefficients</li>
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

                            <h2 class="text-center">Payroll Coefficients Upload <span><a class="btn btn-info" href="{{asset('coefficient.csv')}}"> <i class="fa fa-download"></i>&nbsp; blank CSV</a></span></h2>

                    <div class="col-md-12">
                        <div class="col-md-3"></div>
                        <div class="col-md-6" style="padding-top:10px;">
                            <div class="row thumbnail" style="padding:20px 5px;">
                                <form class="form-inline" method="POST" action="{{route('coefficient.store')}}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <select class="form-control" name="holding_type" id="holding_type" required>
                                            <option value="">Select Holding Type</option>
                                            <option value="Scale 1">Scale 1</option>
                                            <option value="Scale 2">Scale 2</option>
                                            <option value="Scale 3">Scale 3</option>
                                            <option value="Scale 4">Scale 4.1</option>
                                            <option value="Scale 4">Scale 4.2</option>
                                            <option value="Scale 5">Scale 5</option>
                                            <option value="Scale 6">Scale 6</option>
                                            <option value="Scale 7">Scale 7</option>
                                            <option value="Scale 8">Scale 8</option>
                                            <option value="Scale 9">Scale 9</option>
                                            <option value="Scale 10">Scale 10</option>
                                            <option value="Scale 11">Scale 11</option>
                                            <option value="Scale 12">Scale 12</option>
                                            <option value="Scale 13">Scale 13</option>
                                            <option value="Scale 14">Scale 14</option>
                                            <option value="Scale 15">Scale 15</option>
                                            <option value="Scale 16">Scale 16</option>
                                            <option value="Scale 17">Scale 17</option>
                                            <option value="Scale 18">Scale 18</option>
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <input type="file" id="csvfile" name="csvfile" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">Upload</button>
                                </form>
                                @if ($errors->any())
                                <span class="text-danger">
                                    {{$errors->first()}}
                                </span>
                                @endif
                            </div>

                        </div>
                        <div class="col-md-3"></div>

                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Holding Name</th>
                                        <th>Weekly Earning Lebel</th>
                                        <th>Per Dollar A</th>
                                        <th>Per Dollar B</th>
                                        <th>Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($coefficient as $item)
                                    <tr>
                                        <td>{{$item->holding_type}}</td>
                                        <td>{{number_format($item->weekly_earning,4)}}</td>
                                        <td>{{number_format($item->per_dollar_a,4)}}</td>
                                        <td>{{number_format($item->per_dollar_b,4)}}</td>
                                        <td>{{$item->year}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            <h1 class="display-1 text-danger">Empty Table</h1>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- inline scripts related to this page -->

@endsection
