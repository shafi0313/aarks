@extends('frontend.layout.master')
@section('title','Profession')
@section('content')
<?php $p="cdep"; $mp="acccounts"?>

<!-- PAGE CONTENT BEGINS -->
<div class="row">
    <div class="col-lg-12">
        <div class="clearfix">
            <div class="pull-right tableTools-container"></div>
        </div>
        <div class="table-header" style="text-align: right;"> </div>
        <!-- div.table-responsive -->
        <!-- div.dataTables_borderWrap -->
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
                    @php $x=1 @endphp
                    @foreach ($clients as $client)
                    <tr>
                        <td class="center">{{$x++}}</td>
                        <td>{{$client->company}} </td>
                        <td>{{$client->first_name}}</td>
                        <td>{{$client->last_name}}</td>
                        <td>{{$client->phone}}</td>
                        <td>{{$client->email}}</td>
                        <td>{{$client->abn_number}}</td>
                        <td>
                            <div class="  action-buttons">
                                <a class="red" href="{{ route('depreciation.profession',$client->id) }}">Show Report</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- PAGE CONTENT ENDS -->
@endsection
