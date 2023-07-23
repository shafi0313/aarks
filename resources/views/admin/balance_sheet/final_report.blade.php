@extends('admin.layout.master')
@section('title','Balance Sheet Report')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Report</li>
                <li class="active">Activity Balance Sheet Report</li>
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
            

            <div align="center" class="row" >
                <div class="col-xs-12">

                    <!-- PAGE CONTENT BEGINS -->
                    <style>
                        .doubleUnderline {
                            text-decoration: underline;
                            border-bottom: 1px solid #000;
                        }
                    </style>

                    <form action="" target="_blank" method="">
                        <input type="hidden" name="professionid" value="21" />
                        <input type="hidden" name="client_id" value="627" />
                        <input type="hidden" name="form_date" value="25/03/2020" />
                        <input type="hidden" name="shortingdate" value="2020-03-25" />

                        <div class="col-md-12" style="padding-right:20px; padding-left:20px;">
                            <div class="col-xs-12">
                                <div class="col-md-3"></div>
                                <div class="col-md-6" align="center">
                                    <div style="font-size:24px; font-weight:800;">{{$client->fullname}} <br />
                                    </div>
                                    {{$client->address}}
                                    <strong style="font-size:14px;">ABN {{$client->abn_number}}</strong>
                                    <br>
                                    <strong style="font-size:16px;"><u>Detailed Balance Sheet as at: {{$date->format('d/m/Y')}}</u></strong>
                                    <br />
                                </div>
                                <div class="col-md-12" style="padding-top:10px; ">
<table class="table table-bordered table-hover table-striped" style="width:70%">
    <tbody>
        <tr>
            <th style="text-align: center; width: 70%">Description</th>
            {{-- <th style="text-align: center;width: 15%">Note</th> --}}
            <th style="text-align: center;">Balance</th>
        </tr>
@php
    if($retain){
        if($retain->balance_type == 1 && $retain->debit == '') {
            $retain =  abs($retain->totalRetain);
        } else {
            $retain =  - $retain->totalRetain;
        }
    }else{
        $retain = 0;
    }
    if($CRetain){
        if($CRetain->balance_type == 1 && $CRetain->debit == '') {
            $CRetain =  abs($CRetain->CRetain);
        } else {
            $CRetain = - $CRetain->CRetain;
        }
    }else{
        $CRetain = 0;
    }
@endphp
        @foreach($accountCodeCategories as $accountCodeCategory)
        <tr>
            <td colspan="2" style="color: red">{{$accountCodeCategory->name}}</td>
        </tr>
        @foreach($accountCodeCategory->subCategory as $subCategory)
        <tr>
            <td colspan="1" style="color: green"> &nbsp;&nbsp;&nbsp;
                {{$subCategory->name}}
            </td>
            <td  style="color: #048300;text-align:right;font-weight:bold;">
                <div style="border-top: 1px solid #048300; border-bottom: 1px solid #048300; width:50%; float:right">
@php
$subBalance = \App\Models\GeneralLedger::select('balance_type',DB::raw("sum(balance) as subTotal"))
->where('client_id', $client->id)
->where('profession_id', $profession->id)
->where('chart_id','like',$accountCodeCategory->code.$subCategory->code.'%')
->where('chart_id', '!=', 999999)
->where('chart_id', '!=', 999998)
->where('date','<=',$date->format('Y-m-d'))
->groupBy('balance_type')->get();

$subCodeBalance = $sCredit = $sDebit = 0;

if($subBalance->count()){
    $sDebit = $subBalance->where('balance_type', 1)->first()?$subBalance->where('balance_type', 1)->first()->subTotal:0;
    $sCredit = $subBalance->where('balance_type', 2)->first()?$subBalance->where('balance_type', 2)->first()->subTotal:0;
}

if($accountCodeCategory->code == 5){
    $subCodeBalance = $sDebit - $sCredit;
}

if($accountCodeCategory->code == 9){
    if($subCategory->code==9){
        $subCodeBalance = ($sCredit - $sDebit) + $retain + $CRetain;
    }else{
        $subCodeBalance = $sCredit - $sDebit;
    }
}
@endphp
                    {{number_format($subCodeBalance,2)}}
                    {{-- {{$subBalance}} --}}
                </div>
            </td>
        </tr>

        @foreach($subCategory->additionalCategory as $additionalCategory)
        <tr>
            <td colspan="1" style="color: violet "> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {{$additionalCategory->name}}
            </td>
            <td  style="color: #ca27ca;text-align:right;font-weight:bold;">
@php
$subSubBalance = \App\Models\GeneralLedger::select('balance_type', DB::raw("sum(balance) as subSubTotal"))
->where('client_id', $client->id)
->where('profession_id', $profession->id)
->where('chart_id','like',$accountCodeCategory->code.$subCategory->code.$additionalCategory->code.'%')
->where('chart_id', '!=', 999998)
->where('chart_id', '!=', 999999)
->where('date','<=',$date->format('Y-m-d'))
->groupBy('balance_type')->get();

$ssDebit = $ssCredit = $subSubCodeBalance = 0;

if($subSubBalance->count()){
    $ssDebit = $subSubBalance->where('balance_type', 1)->first()?$subSubBalance->where('balance_type', 1)->first()->subSubTotal:0;
    $ssCredit = $subSubBalance->where('balance_type', 2)->first()?$subSubBalance->where('balance_type', 2)->first()->subSubTotal:0;
}

if($accountCodeCategory->code == 5){
    $subSubCodeBalance = $ssDebit-$ssCredit;
}
if($accountCodeCategory->code == 9){
    if($additionalCategory->code==9){
        $subSubCodeBalance = ($ssCredit-$ssDebit) + $retain + $CRetain;
    }else{
        $subSubCodeBalance = $ssCredit-$ssDebit;
    }
}
@endphp
            {{number_format($subSubCodeBalance,2)}}
            {{-- {{$subSubBalance}} --}}

            </td>
        </tr>

        @foreach($masterAccountCodes as $masterAccountCode)
        @if($masterAccountCode->additional_category_id == $additionalCategory->id)
        <tr>
            <td style="color: #1B6AAA">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{$masterAccountCode->name}}
            </td>
            {{-- <td>{{$masterAccountCode->note}}</td> --}}
        @php
           $ledger = $ledgers->where('chart_id',$masterAccountCode->code)->first();
        @endphp
            <td style="color: #1B6AAA; text-align:right;">
                @if ($ledger)
@php
if($accountCodeCategory->code == 9){
    if($ledger->credit == ''){
        $blncType = '-';
    }else{
        $blncType = '';
    }
}
if($accountCodeCategory->code == 5){
    if($ledger->debit == ''){
        $blncType = '-';
    }else{
        $blncType = '';
    }
}
@endphp
                @if ($ledger->chart_id ==999999)
                {{$retain ? $blncType . number_format(abs($retain),2) : '0.00'}}
                @elseif($ledger->chart_id ==999998)
                {{$CRetain? $blncType.number_format(abs($CRetain),2) : '0.00'}}
                @else
                {{$blncType}} {{number_format(abs($ledger->sheet_balance),2)}}
                @endif
                @else {{number_format(0,2)}}
                @endif
            </td>
        </tr>


        @endif
        @endforeach
        {{-- Account Code End--}}
        @endforeach
        {{-- Additonal Category End--}}
        @endforeach
        <tr style="text-align:right;color: #d35400; border-bottom: 2px double red">
            <td class="text-center" style="font-size: 20px;">Grand total</td>
               <td colspan="1">
                <div style="float:right;width:30%;border-top:1px solid; border-bottom-style: 1px solid;border-bottom-style:double">
@php
$totalBalance = \App\Models\GeneralLedger::select('balance_type', DB::raw("sum(balance) as total"))
->where('client_id', $client->id)
->where('profession_id', $profession->id)
->where('chart_id','like',$accountCodeCategory->code.'%')
->where('chart_id', '!=', 999998)
->where('chart_id', '!=', 999999)
->where('date','<=',$date->format('Y-m-d'))
->groupBy('balance_type')->get();

$totalCodeBalance = $tDebit = $tCredit = 0;

if($totalBalance->count()){
    $tDebit = $totalBalance->where('balance_type', 1)->first()?$totalBalance->where('balance_type', 1)->first()->total:0;
    $tCredit = $totalBalance->where('balance_type', 2)->first()?$totalBalance->where('balance_type', 2)->first()->total:0;
}

if($accountCodeCategory->code == 5){
    $totalCodeBalance = $tDebit-$tCredit;
}
if($accountCodeCategory->code == 9){
    $totalCodeBalance = ($tCredit - $tDebit) + $retain + $CRetain;
}
@endphp
                        {{number_format($totalCodeBalance,2)}}
                </div>
               </td>
        </tr>

        {{-- Sub Category End--}}
        @endforeach
        {{--Category End--}}
    </tbody>
</table>
                                </div>
                            </div>
                        </div>
                    </form>


                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

@stop
