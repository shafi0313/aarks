@extends('admin.layout.master')
@section('title','Show Transaction')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{route('admin.dashboard')}}">Home</a>
                </li>

                <li>
                    <a href="#">Important Transaction List</a>
                </li>
                <li class="active">Show Transaction</li>
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

                    <div class="row">
                        <div class="col-xs-12" style="height: 450px;overflow-y: scroll;">
                            <h2>Transaction View </h2>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Account Name </th>
                                        <th>Trn.Date </th>
                                        <th>Trn.ID</th>
                                        <th>Particular</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
@php
$sum = ['debit' => 0, 'credit' => 0];
$Bdebit =$Bcredit = 0;
@endphp
@forelse($transactions as $key => $transaction)
@php
$debit=$credit=0;
if($transaction->balance_type === 1){
    if($transaction->tranBlnc < 1){
        $credit=$transaction->tranBlnc;
    }else{
        $debit = $transaction->tranBlnc;
    }
}elseif($transaction->balance_type === 2){
    if($transaction->tranBlnc < 1){
        $debit=$transaction->tranBlnc;
    }else{
        $credit = $transaction->tranBlnc;
    }
}
$sum['debit'] += abs($debit);
$sum['credit'] += abs($credit);
@endphp
        <tr>
            <td class="center">{{ $key + 1 }}</td>
            <td>{{ $transaction->client_account_code->name }}</td>
            <td>{{ $transaction->date->format(aarks('frontend_date_format')) }}</td>
            <td class="center">
                <a href="{{route('show.bst.tran.details', [$transaction->transaction_id,$transaction->chart_id,])}}"
                    style="color: green;text-decoration: underline">{{($transaction->transaction_id)}}
                </a>
            </td>
            <td>{{ $transaction->narration }}</td>
            <td class="text-right">{{ number_format(abs($debit),2) }}</td>
            <td class="text-right">{{ number_format(abs($credit),2) }}</td>
        </tr>
@empty
<tr>
<td colspan="6" class="text-center">
    <h4>No Data Found</h4>
</td>
</tr>
@endforelse
@if ($bank)
@php
$Bdebit = abs($bank->debit);
$Bcredit = abs($bank->credit);
@endphp
<tr>
    <td class="center">{{ $key??0 +2 }}</td>
    <td>{{ $bank->client_account_code->name }}</td>
    <td>{{ \Carbon\Carbon::parse($bank->date)->format('d/m/Y') }}</td>
    <td class="center">
        <a href="{{route('show.data_store.transaction', [$bank->transaction_id,$bank->chart_id,])}}"
            style="color: green;text-decoration: underline">{{$bank->transaction_id}}
        </a>
    </td>
    <td>{{ $bank->narration }}</td>
    <td class="text-right">{{ number_format(abs($bank->debit),2) }}</td>
    <td class="text-right">{{ number_format(abs($bank->credit),2) }}</td>
    {{-- <td></td> --}}
</tr>
@endif

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Total</b></td>
                                        <td class="text-right">{{ number_format(abs($sum['debit']+abs($Bdebit)),2)}}</td>
                                        <td class="text-right">{{ number_format(abs($sum['credit']+abs($Bcredit)),2) }}</td>
                                        {{-- <td></td> --}}
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-12 thumbnail" style="min-height:150px;" align="center">
                        <form id="imp_add" action="{{route('bst.imp.store')}} " method="post" autocomplete="off">
                            @csrf
                            <table class="table table-bordered">
                                <tr>
                                    <th style="text-align:center;" width="25%">A/c Code</th>
                                    <th style="text-align:center;">Transaction Date</th>
                                    <th style="text-align:center;">Particular</th>
                                    <th style="text-align:center;" width="15%">Transaction</th>
                                    <th style="text-align:center;" width="15%">Debit</th>
                                    <th style="text-align:center;" width="15%">Credit</th>
                                </tr>
                                <tr>
                                    <td style="width:12%">
                                        <select class="form-control" id="account_name" name="code">
                                            <option value="">-- Select Account Name --</option>
                                            @php
                                                $acCode = App\ClientAccountCode::where('client_id',$transaction->client_id)->orderBy('code')->get();
                                            @endphp
                                            @foreach ($acCode as $item)
                                            <option value="{{$item->id}} "> {{$item->name}} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="date"  value="{{ $transaction->date->format(aarks('frontend_date_format')) }}" readonly="readonly">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="narration"  value="{{ $transaction->narration}}" readonly="readonly">
                                    </td>
                                    <td>
                                        <input type="text" name="transaction_id"  class="form-control" value="{{ $transaction->transaction_id }}" readonly="readonly">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control price" id="debit" name="debit" value="{{old('debit')}} "  />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="credit" name="credit"  value="{{old('credit')}} " />
                                    </td>
                                </tr>
                            </table>
                            <button type="submit" style="width: 150px" class="btn btn-primary btn-sm">Save</button>
                        </form>
                    </div>
                </div> <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
</div><!-- /.main-content -->

<!-- inline scripts related to this page -->
<script>
$("#debit").on('keyup', function(){
    var debit = $(this).val();
    $("#credit").attr('disabled', 'disabled');
    $("#credit").val('');
    if(debit ==''){
        $("#credit").removeAttr('disabled', 'disabled');
    }
});
$("#credit").on('keyup', function(){
    var credit = $(this).val();
    $("#debit").attr('disabled', 'disabled');
    $("#debit").val('');
    if(credit ==''){
        $("#debit").removeAttr('disabled', 'disabled');
    }
});
</script>
@endsection
