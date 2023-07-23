@extends('frontend.layout.master')
@section('title','Customer Ledger Reports')
@section('content')
<?php $p="cled"; $mp="report";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Customer Ledger</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger" style="list-style: none">{{$error}}</li>
                            @endforeach
                        </ul>
                        @endif
                        <form action="{{route('cledger.show')}}" method="get" autocomplete="off">
                            <div class="row justify-content-center">
                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <div class="form-inline col-md-3">
                                    <label class="mr-2 t_b">Customer: </label>
                                    <select class="form-control form-control-sm col-md-8" name="customer_id" required>
                                        <option disabled selected value>Select Customer</option>
                                        @foreach ($customers as $customer)
                                        <option {{$cId->id==$customer->id?'selected':''}} value="{{$customer->id}}">
                                            {{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-inline col-lg-3">
                                    <label class="mx-2 t_b">From Date:</label>
                                    <input required class="form-control form-control-sm datepicker"
                                        data-date-format="dd/mm/yyyy" name="from_date" value="{{$from_date}}">
                                </div>
                                <div class="form-inline col-md-3">
                                    <label class="mx-2 t_b">To Date:</label>
                                    <input required class="form-control form-control-sm datepicker"
                                        data-date-format="dd/mm/yyyy" name="to_date" value="{{$to_date}}">
                                </div>
                                <div class="form-inline col-md-3">
                                    <input type="submit" name="submit" class="btn btn-sm btn-info" value="Show Report">
                                    <input type="submit" name="submit" class="btn btn-sm btn-warning" value="Print">
                                    <input type="submit" name="submit" class="btn btn-sm btn-success" value="Email">
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div id="resultshow" style="padding-top:10px;">
                            <div align="center">
                                <h2 style="color:#666666;"><u> {{$cId->name}}</u></h2>
                                <strong>Customer Ledger</strong>
                                <style>
                                    table tr td {
                                        font-size: 15px;
                                        padding: 5px 2px !important
                                    }
                                </style>
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="text-align:center" width="15%">Customer Name</th>
                                            <th style="text-align:center" width="15%">Email</th>
                                            <th style="text-align:center" width="15%">Phone</th>
                                            <th style="text-align:center" width="15%">ABN</th>
                                            <th style="text-align:center">Address</th>
                                        </tr>
                                        <tr>
                                            <td align="center" width="15%"> {{$cId->name}}</td>
                                            <td align="center" width="15%">{{$cId->email}} </td>
                                            <td align="center" width="15%">{{$cId->phone}} </td>
                                            <td align="center" width="15%">{{$cId->abn}} </td>
                                            <td align="center">{{$cId->b_address}}</td>
                                        </tr>
                                    </tbody>
                                </table>


                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="text-align:center">Transaction Date</th>
                                            <th style="text-align:center">Invoice/Repeat No</th>
                                            <th style="text-align:center">Invoice Amount</th>
                                            <th style="text-align:center"> Paid Amount</th>
                                            <th style="text-align:center"> Balance</th>
                                        </tr>
                                        <tr>
                                            <td align="center">Opening Balance</td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td align="center">$ {{number_format($cId->opening_blnc,2)}}</td>
                                        </tr>
                                        @php
                                        $payment_amount = $inv_amt = 0;
                                        $balance = $cId->opening_blnc;
                                        @endphp
                                        @php

                                        $array = array_merge($dedotrs->toArray(), $payments->toArray());
                                        array_multisort(array_column($array, 'tran_date'), SORT_ASC, $array);
                                        @endphp
                                        @foreach ($array as $payment)
                                        @if (isset($payment['inv_amt']))
                                        @php
                                            $balance += $payment['inv_amt'];
                                        @endphp
                                                                                    <tr>
                                            <td align="center">
                                                {{Carbon\Carbon::parse($payment['tran_date'])->format('d/m/Y')}}
                                            </td>
                                            <td align="center">
                                                <a href="{{route('inv.report',[$layout,$payment['inv_no'],$client->id,$cId->id])}}">
                                                    {{client()->invoiceLayout}} {{invoice($payment['inv_no'], 8, 'INV')}}
                                                </a>
                                            </td>
                                            <td align="center">
                                                {{number_format($payment['inv_amt'],2)}}
                                            </td>
                                            <td align="center">0.00</td>
                                            <td align="center">{{number_format($balance,2)}}</td>
                                        </tr>
                                        @else
                                        @php
                                            $payment_amount += $payment['payment_amount'];
                                            $balance        -= $payment['payment_amount'];
                                        @endphp
                                        <tr>
                                            <td align="center" class="text-success">
                                                {{Carbon\Carbon::parse($payment['tran_date'])->addDay()->format('d/m/Y')}}</td>
                                            <td align="center">
                                                <a target="_blank" href="{{route('cledger.payment',$payment['id'])}}">
                                                    @if (isset($payment['dedotr_inv']) && $payment['dedotr_inv'] != null)
                                                    {{invoice($payment['id'], 8, 'REC')}}
                                                    @else
                                                    {{invoice($payment['id'], 8, 'REC')}}
                                                    @endif
                                                </a>
                                            </td>
                                            <td align="center">0.00</td>
                                            <td align="center">
                                                {{number_format($payment['payment_amount'],2)}}
                                            </td>
                                            <td align="center">{{number_format($balance,2)}}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" style="text-align:right">Net Balance</td>
                                            <td class="text-center">
                                                $ <b>{{number_format($balance, 2)}}</b>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
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
