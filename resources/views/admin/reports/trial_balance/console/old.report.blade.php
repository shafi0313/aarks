@extends('admin.layout.master')
@section('title','Trial Balance Report')
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
                        <a href="#">Report</a>
                    </li>
                    <li>
                        <a href="#">Trial Balance Report</a>
                    </li>
                    <li>
{{--                        <a href="#">{{$client->first_name}}</a>--}}
                    </li>
                    <li>
{{--                        <a href="#">{{$profession->name}}</a>--}}
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
                            <div class="row center">
                                <h2><b>{{$client->first_name}} {{$client->last_name}}</b></h2>
                                <h2><b>ABN {{$client->abn_number}}</b></h2>
                                <h4><b>Trial Balance as at: {{\Carbon\Carbon::parse($date)->format('d/m/Y')}}</b></h4>
                            </div>
                        <style>
                            .text-danger{
                                color: red;
                            }
                        </style>
                            <div class="row">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <td class="center">Account Code</td>
                                        <td class="center">Account Name</td>
                                        <td class="center">Debit</td>
                                        <td class="center">Credit</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $sum = ['assDebit'=> 0, 'assCredit'=> 0,'debit'=> 0, 'credit'=> 0, 'income' => 0, 'expense' => 0];
                                        $Sdebit = $Scredit = $debit =$credit =$assDebit =$assCredit = $retain =$Rcredit=$Rdebit=0;
                                    @endphp
                                    @foreach($ledgers as $code)
                                    {{-- <tr>
                                        <td>{{$code->name}}<==>{{$code->code}}~~~~{{$preAssLilas->where('chart_id',$code->code)->first()}} </td>
                                    </tr> --}}
@php
$AssLai = $preAssLilas->where('chart_id',$code->code)->first();


if ($code->generalLedger->count()){
$ledger = $code->generalLedger->first();
if($AssLai){

if($ledger->balance_type==1){
    if($ledger->trail_balance < 0){
        $credit = $ledger->trail_balance +$AssLai->OpenBl;
        $sum['credit'] +=abs($credit);
        $debit = 0;
    }else{
        $debit = $ledger->trail_balance +$AssLai->OpenBl;
        $sum['debit'] +=abs($debit);
        $credit = 0;
    }
}elseif($ledger->balance_type==2){
    if($ledger->trail_balance < 0){
        $debit = $ledger->trail_balance +$AssLai->OpenBl;
        $sum['debit'] +=abs($debit);
        $credit = 0;
    }else{
        $credit = $ledger->trail_balance +$AssLai->OpenBl;
        $sum['credit'] +=abs($credit);
        $debit =0;
    }
}
}else{
if($ledger->balance_type==1){
    if($ledger->trail_balance < 0){ 
    $credit=$ledger->trail_balance;
    $sum['credit'] +=abs($credit);
    $debit = 0;
    }else{
    $debit = $ledger->trail_balance;
    $sum['debit'] +=abs($debit);
    $credit = 0;
    }
}elseif($ledger->balance_type==2){
    if($ledger->trail_balance < 0){ 
    $debit=$ledger->trail_balance;
    $sum['debit'] +=abs($debit);
    $credit = 0;
    }else{
    $credit = $ledger->trail_balance;
    $sum['credit'] +=abs($credit);
    $debit =0;
    }
}    
}
}
if($AssLai){
if ($AssLai->chart_id != $ledger->chart_id){
if($AssLai->balance_type==1){
if($AssLai->OpenBl < 0){ $assCredit=$AssLai->OpenBl;
    $sum['assCredit'] +=abs($assCredit);
    $assDebit = 0;
    }else{
    $assDebit = $AssLai->OpenBl;
    $sum['assDebit'] +=abs($assDebit);
    $assCredit = 0;
    }
    }elseif($AssLai->balance_type==2){
    if($AssLai->OpenBl < 0){ $assDebit=$AssLai->OpenBl;
        $sum['assDebit'] +=abs($assDebit);
        $assCredit = 0;
        }else{
        $assCredit = $AssLai->OpenBl;
        $sum['assCredit'] +=abs($assCredit);
        $assDebit =0;
        }
        }
        }
        }
@endphp
@if ($AssLai)
@if ($AssLai->chart_id != $ledger->chart_id)
<tr>
    <td>{{$AssLai->chart_id}} </td>
    <td>{{$code->name}} </td>
    <td>{{abs($assDebit)}} </td>
    <td>{{abs($assCredit)}} </td>
</tr>
@endif
@endif
@foreach ($code->generalLedger as $ledger)
<tr>
    <td>{{$ledger->chart_id}} </td>
    <td>{{$code->name}} </td>
    <td>{{abs($debit)}} </td>
    <td>{{abs($credit)}} </td>
</tr>
   
@endforeach 
@endforeach

                                    @if ($retains and $date->format('dm') =='0107')
                                    @php
                                        if($retains->credit != 0){
                                            $Rcredit = abs($retains->totalRetain);
                                            $Rdebit = 0;
                                        }elseif($retains->debit != 0){
                                            $Rdebit = abs($retains->totalRetain);
                                            $Rcredit = 0;
                                        }
                                    @endphp
                                        <tr>
                                            <td>99999</td>
                                            <td>Retain earning</td>
                                            <td>{{$Rdebit}} </td>
                                            <td>{{$Rcredit}} </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td>{{$Sdebit = $sum['debit']+$Rdebit+$sum['assDebit']}}</td>
                                        <td>{{$Scredit = $sum['credit']+$Rcredit+$sum['assCredit']}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-danger text-right">Difference between Dr. and Cr.</td>
                                        <td colspan="2" class="center text-danger">
                                            @php $deffBlnc = $Scredit-$Sdebit;@endphp
                                           {{ abs($deffBlnc) . ($deffBlnc >0 ?' Cr':' Dr')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="center text-right">Profit/Loss as at {{\Carbon\Carbon::parse($date)->format('d/m/Y')}}</td>
                                        @if ($date->format('dm') !='0107' and $CRetains->count())
                                        <td colspan="2">{{abs($CRetains->sum('balance'))}}</td>
                                        @else
                                        <td colspan="2">0</td>
                                        @endif
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row text-right">
                                <div class="btn-group btn-group">
                                    <button type="button" class="btn btn-primary">Print</button>
                                    <button type="button" class="btn btn-success">Send Email</button>
                                </div>
                            </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
@endsection
