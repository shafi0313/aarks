@extends('admin.layout.master')
@section('title','Bank Statement List Page')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>
                    <a href="{{ route('general_ledger.index') }}">Bank Statement List</a>
                </li>
                <li>
                    <a href="#">{{$profession->name}}</a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">

            <!-- Settings -->
            {{--            @include('admin.layout.settings')--}}
            <!-- /Settings -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <h3>Bank Statement Transaction List</h3>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Transaction Id</th>
                                        <th>Transaction Date</th>
                                        {{-- <th>Debit Amount</th>
                                        <th>Credit Amount</th> --}}
                                        {{-- <th>Balance Amount</th> --}}
                                        <th width="120px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1 ;@endphp
                                    @foreach ($ledgers->groupBy('transaction_id') as $ledger)
                                    <tr>
                                        <td> {{$i++}} </td>
                                        <td>
                                            {{-- <a href="{{route('bs_tran_list.details',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source])}}">{{$ledger->first()->transaction_id}}</a> --}}
                                            <a href="{{route('general_ledger.transaction',[$ledger->first()->transaction_id,$ledger->first()->source])}}">{{$ledger->first()->transaction_id}}</a>
                                            {{-- {{route('general_ledger.transaction',[ $generalLedger->transaction_id,$generalLedger->source])}} --}}
                                            {{-- {{$ledger->first()->transaction_id}} --}}
                                        </td>
                                        <td>{{$ledger->first()->date->format('d/m/Y')}}</td>
                                        {{-- <td class="text-right"> {{number_format($ledger->sum('debit'),2)}} </td>
                                        <td class="text-right"> {{number_format($ledger->sum('credit'),2)}} </td> --}}
                                        {{-- <td> {{number_format($ledger->sum('balance'),2)}} </td> --}}
                                        <td class="text-center">
                                            {{-- <a href="{{route('general_ledger.transaction',[$ledger->first()->transaction_id,$ledger->first()->source])}}" ><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp; --}}
                                            <a  title="Lsit Details Only View" href="{{route('bs_tran_list.details',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source, 'only_view'=>'yes'])}}"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                                            <a title="Lsit Details " title="" href="{{route('bs_tran_list.details',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source])}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                                            @if ($ledger->first()->source == "BST")
                                            <a title="Lsit Details Delete" onclick="return confirm('Are you sure?')" href="{{route('bs_tran_list.import.delete',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source])}}" class="text-danger"><i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                            @else
                                            <a title="Lsit Details Delete" onclick="return confirm('Are you sure?')" href="{{route('bs_tran_list.input.delete',[$client->id,$profession->id,$ledger->first()->transaction_id,$ledger->first()->source])}}" class="text-danger"><i class="fa fa-trash" aria-hidden="true"></i>
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
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<script>
    $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
</script>
@endsection
