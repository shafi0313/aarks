@extends('admin.layout.master')
@section('title', 'Trashed')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li><i class="ace-icon fa fa-trash trash-icon"></i> Trash</li>
                    <li class="active">Client List</li>
                </ul>
                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                                autocomplete="off" />
                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                        </span>
                    </form>
                </div>
            </div>
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div class="table-header" style="text-align: right;"> </div>
                                <div>
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="center">SN</th>
                                                <th>Company /Trust/Partner ship Name</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Phone Number</th>
                                                <th>Email Address</th>
                                                <th>ABN Number</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($clients as $i=>$client)
                                                <tr>
                                                    <td class="center">{{$i+1}}</td>
                                                    <td>{{ $client->company }} </td>
                                                    <td>{{ $client->first_name }}</td>
                                                    <td>{{ $client->last_name }}</td>
                                                    <td>{{ $client->phone }}</td>
                                                    <td>{{ $client->email }}</td>
                                                    <td>{{ $client->abn_number }}</td>
                                                    <td>
                                                        <div class="  action-buttons">
                                                            <a class="red"
                                                                href="{{ route('trash.source', $client->id) }}">Select Client</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        
    </script>
@endsection
