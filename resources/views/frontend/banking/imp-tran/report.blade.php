@extends('frontend.layout.master')
@section('title','Bank Statement Transaction List')
@section('content')
<?php $p="impt"; $mp="bank"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Bank Statement Transaction List</h3>
                    </div>
                    <div class="card-body">
                        <table id="example" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Transaction Id</th>
                                    <th>Transaction Date</th>
                                    {{-- <th>Debit Amount</th>
                                    <th>Credit Amount</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1 ;@endphp
                                @foreach ($ledgers->groupBy('transaction_id') as $ledger)
                                <tr>
                                    <td> {{$i++}} </td>
                                    <td>{{$ledger->first()->transaction_id}}
                                        {{-- <a href="{{route('cbs_tranlist.details',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source])}}">{{$ledger->first()->transaction_id}}</a> --}}
                                        {{-- {{$ledger->first()->transaction_id}} --}}
                                    </td>
                                    <td>{{$ledger->first()->date->format('d/m/Y')}}</td>
                                    {{-- <td class="text-right"> {{number_format($ledger->sum('debit'),2)}} </td>
                                    <td class="text-right"> {{number_format($ledger->sum('credit'),2)}} </td> --}}
                                    {{-- <td> {{number_format($ledger->sum('balance'),2)}} </td> --}}
                                    <td class="text-center">
                                        <a title="Tran. List Details" href="{{route('ledger.transaction',[ $ledger->first()->transaction_id,$ledger->first()->source])}}" ><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                                        {{-- <a href="{{route('cbs_tranlist.details',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source])}}" ><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp; --}}
                                        <a title="Tran. List edit" href="{{route('cbs_tranlist.details',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source])}}"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                                        @if ($ledger->first()->source == "BST")
                                        <a title="Tran. List delete" onclick="return confirm('Are you sure?')" href="{{route('cbs_tranlist.import.delete',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source])}}" class="text-danger"><i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        @else
                                        <a title="Tran. List delete" onclick="return confirm('Are you sure?')" href="{{route('cbs_tranlist.input.delete',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source])}}" class="text-danger"><i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        @endif
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

$(document).ready(function() {
    $('#example').DataTable( {
        "order": [[ 0, "asc" ]]
    } );
} );
</script>
@stop
