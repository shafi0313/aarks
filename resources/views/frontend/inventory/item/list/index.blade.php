@extends('frontend.layout.master')
@section('title','Inventory Item List')
@section('content')
<?php $p="invList"; $mp="inventory"?>
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading d-flex">
                            <p>Inventory Item List</p>
                        </div>
                        <table id="DataTable" class="table table-bordered table-hover table-sm display">
                            <thead>
                                <tr>
                                    <th>ITEM NUMBER</th>
                                    <th>ITEM NAME</th>
                                    <th>TYPE</th>
                                    <th>BIN NUMBER</th>
                                    <th>STATUS</th>
                                    <th>QUN ON HAND</th>
                                    <th>CURRENT VALUE</th>
                                    <th style="width:7%" class="no-sort">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr>
                                    <td>{{$item->item_number}}</td>
                                    <td style="color: green">{{$item->item_name}}</td>

                                    <td>{{($item->type==1)?'Buy Item':(($item->type==2)?'Sale Item':'Stock Item')}}</td>


                                    <td>{{$item->bin_number}}</td>
                                    <td style="color: rgb(9, 111, 170)">{{$item->status==1?'active':'inactive'}}</td>
                                    <td>{{$item->qun_hand??'0.00'}}</td>
                                    <td>{{number_format($item->current_value,2)}}</td>
                                    <td align="center">
                                        <a  title="Invoice Items Edit" class="btn btn-sm btn-info fa fa-edit edit" href="{{route('inv_item.show', $item->id)}}"></a>
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
</section>


<script>
$(document).ready(function () {
    $('#DataTable').DataTable();
});
</script>
@stop
