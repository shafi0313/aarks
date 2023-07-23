@extends('frontend.layout.master')
@section('title','Add Edit Period')
@section('content')
<?php $p="aep"; $mp="acccounts";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-heading">
                        <h3 class="card-title">Activity Name: {{$profession->name}}
                            <span style="color:hotpink; float:right">Period:
                                {{ $period->start_date->format(aarks('frontend_date_format'))}} to
                                {{ $period->end_date->format(aarks('frontend_date_format'))}}
                            </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <h4 style="color:green;">Select Account Name: </h4>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <select class="form-control chosen-select select2Single" id="subid" name="subid"
                                        onchange="location = this.value">
                                        <option>
                                            <p>Select Account Name</p>
                                        </option>
                                        @foreach ($client->account_codes as $client_ac_code)
                                        @if ($client_ac_code->type ==1)
                                        <option
                                            value="{{route('client.periodCodeAddEdit',[$client_ac_code->id,$client_ac_code->code,$client->id,$profession->id,$period->id])}}"
                                            style="color: green;">
                                            {{$client_ac_code->name}}
                                        </option>
                                        @else
                                        <option
                                            value="{{route('client.periodCodeAddEdit',[$client_ac_code->id,$client_ac_code->code,$client->id,$profession->id,$period->id])}}"
                                            style="color: hotpink;">
                                            {{$client_ac_code->name}}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" style="min-height:300px;">
                            <div id="addatalistpage">
                                <div class="row">
                                    <style>
                                        table.scroll {
                                        width: 100%;
                                        }
                                        table.scroll th,
                                        table.scroll td,
                                        table.scroll tr,
                                        table.scroll thead,
                                        table.scroll tbody {
                                            display: block;
                                        }
                                        table.scroll thead tr {
                                            /* fallback */
                                            width: 100%;
                                            /* minus scroll bar width */
                                            width: -webkit-calc(100% - 16px);
                                            width: -moz-calc(100% - 30px);
                                            width: calc(100% - 16px);
                                        }
                                        table.scroll tr:after {
                                            content: ' ';
                                            display: block;
                                            visibility: hidden;
                                            clear: both;
                                        }
                                        table.scroll tbody {
                                            height: 190px;
                                            overflow-x: auto;
                                            overflow-x: hidden;
                                        }
                                        table.scroll tbody td,
                                        table.scroll thead th {
                                            float: left;
                                        }
                                        thead tr th {
                                            height: 30px;
                                            line-height: 20px;
                                        }
                                        tbody td:last-child,
                                        thead th:last-child {
                                            border-right: none !important;
                                        }
                                        .table td, .table th {
                                            font-size: 14px;
                                            padding: 0;
                                        }

                                        .table th {
                                            padding-top: 3px;
                                        }
                                        .table td {
                                            font-size: 14px;
                                            padding: 5px 8px;
                                        }
                                    </style>
                                    {{-- <div class="col-lg-12 bg-info p-2">
                                        <h2 class="pull-left text-light">{{$client_account->name}}</h2>
                                    </div> --}}
                                    <div class="col-lg-12">

                                    <strong class="datelockd"></strong>

                                    <table class="scroll table table-bordered table-stipe">
                                        <div class="col-lg-12" style="background: #337ab7;color:white;font-size: 20px">
                                            <p style="padding:4px;margin:0">{{$client_account->name}}</p>
                                        </div>
                                        <thead>
                                            <tr>
                                                <th width="13%" style="text-align:center; color:#9966FF;">
                                                Date </th>
                                                <th width="10%" style="text-align:center; color:#9966FF;">
                                                Amount </th>
                                                <th width="8%" style="text-align:center; color:#9966FF;">GST
                                                </th>
                                                <th width="9%" style="text-align:center; color:#9966FF;">
                                                T/Inv</th>
                                                <th width="10%" style="text-align:center; color:#9966FF;">
                                                Balance</th>
                                                <th width="10%" style="text-align:center; color:#9966FF;">%
                                                </th>
                                                <th width="30%" style="text-align:center; color:#9966FF;">
                                                Note</th>
                                                <th width="5%" style="text-align:center; color:#9966FF;">
                                                Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
@foreach ($data as $item)
@if ($item->ac_type == 1)
<tr>
    <td width="12.8%">
        {{ \Carbon\Carbon::parse($item->trn_date)->format('d/m/Y') }}
    </td>
    <td width="9.9%">
        @php
        $chard_code = $client_account->code;
        @endphp
        @if($item->chart_id == $chard_code && $item->amount_debit !='')
        {{ $item->amount_debit }}
        @elseif($item->chart_id == $chard_code && $item->amount_credit !='')
        {{ $item->amount_credit }} <span style="color:red; font-weight: bold">R</span>
        @endif
        @if($item->chart_id != $chard_code && $item->amount_credit !='')
        {{ $item->amount_credit }}
        @elseif($item->chart_id != $chard_code && $item->amount_debit !='')
        {{ $item->amount_debit }} <span style="color:red; font-weight: bold">R</span>
        @endif
    </td>
    <td width="7.9%">
        @if($item->total_inv !='' && $item->gst_accrued_debit =='' && $item->gst_accrued_credit =='' && $item->gst_cach_credit =='')
        {{ $item->gst_cash_debit }}
        @elseif($item->total_inv !='' && $item->gst_accrued_debit =='' && $item->gst_accrued_credit =='' && $item->gst_cash_debit =='')
        {{ $item->gst_cash_credit }}
        @elseif($item->total_inv !='' && $item->gst_accrued_debit =='' && $item->gst_cash_credit =='' && $item->gst_cash_debit
        =='')
        {{$item->gst_accrued_credit}}
        @elseif($item->total_inv !='' && $item->gst_accrued_credit =='' && $item->gst_cash_credit =='' && $item->gst_cash_debit =='')
        {{ $item->gst_accrued_debit }}
        @else
        0
        @endif
    </td>
    <td width="8.8%">{{$item->total_inv==''?0:$item->total_inv}}
    </td>
    <td width="9.8%">
    {{$item->balance==''?0:abs($item->balance)}}</td>
    <td width="9.9%">{{$item->percent==''?0:$item->percent}}
    </td>
    <td width="29.6%">{{$item->narration}}</td>
    <td width="5%">
        <form action="{{route('adtperiod.destroy',$item->id)}}" method="POST">
            @csrf
            @method("DELETE")
            <button title="Data Store Delete" style="background: transparent;border: none;color: red;font-size: 14px;line-height: 0;" type="submit"
                class=""><i class="fa fa-trash delete"></i></button>
        </form>
    </td>
</tr>
@elseif($item->ac_type == 2)
<tr>
    <td width="12.8%">
        {{ \Carbon\Carbon::parse($item->trn_date)->format('d/m/Y') }}
    </td>
    <td width="9.9%">
        @php
        $chard_code = $client_account->code;
        @endphp
        @if($item->chart_id == $chard_code && $item->amount_debit !='')
        {{ $item->amount_debit }} <span style="color:red; font-weight: bold">R</span>
        @elseif($item->chart_id == $chard_code && $item->amount_credit !='')
        {{ $item->amount_credit }}
        @endif
        @if($item->chart_id != $chard_code && $item->amount_credit !='')
        {{ $item->amount_credit }} <span
        style="color:red; font-weight: bold">R</span>
        @elseif($item->chart_id != $chard_code && $item->amount_debit !='')
        {{ $item->amount_debit }}
        @endif
    </td>
    <td width="7.9%" height="33px">
        @if($item->total_inv !='' && $item->gst_accrued_debit =='' && $item->gst_accrued_credit =='' && $item->gst_cach_credit =='')
        {{ $item->gst_cash_debit }}
        @endif
        @if($item->total_inv !='' && $item->gst_accrued_debit =='' && $item->gst_accrued_credit =='' && $item->gst_cash_debit =='')
        {{ $item->gst_cash_credit }}
        @endif
        @if($item->total_inv !='' && $item->gst_accrued_debit =='' && $item->gst_cash_credit =='' && $item->gst_cash_debit
        =='')
        {{  $item->gst_accrued_credit}}
        @endif
        @if($item->total_inv !='' && $item->gst_accrued_credit =='' && $item->gst_cash_credit =='' && $item->gst_cash_debit
        =='')
        {{ $item->gst_accrued_debit }}
        @endif
    </td>
    <td width="8.8%">{{$item->total_inv==''?0:$item->total_inv}}
    </td>
    <td width="9.8%">
    {{$item->balance==''?0:abs($item->balance)}}</td>
    <td width="9.9%">{{$item->percent==''?0:$item->percent}}
    </td>
    <td width="29.6%">{{$item->narration}} </td>
    <td width="5%">
        <form action="{{route('adtperiod.destroy',$item->id)}}"
            method="POST">
            @csrf
            @method("DELETE")
            <button title="ADT Delete"
            style="background: transparent;border: none;color: red;font-size: 14px;line-height: 0;"
            type="submit" class=""><i class="fa fa-trash delete"></i></button>
        </form>
    </td>
</tr>
@endif
@endforeach
                                                            </tbody>
                                                        </table>
            @include('frontend.accounts.period.add-code')
            @include('frontend.accounts.period.payg')
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->
@stop
