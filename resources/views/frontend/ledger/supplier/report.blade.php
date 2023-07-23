@extends('frontend.layout.master')
@section('title','Supplier Ledger')
@section('content')
<?php $p="sl"; $mp="purchase";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Supplier Ledger</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger" style="list-style: none">{{$error}}</li>
                            @endforeach
                        </ul>
                        @endif
                        <form action="{{route('sledger.show')}}" method="get" autocomplete="off">
                            <div class="row justify-content-center">
                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <div class="form-inline col-md-4">
                                    <label class="mr-2 t_b">Supplier: </label>
                                    <select class="form-control form-control-sm col-md-8" name="customer_id" required>
                                        <option disabled selected value>Select Supplier</option>
                                        @foreach ($customers as $customer)
                                        <option {{$cId->id==$customer->id?'selected':''}}
                                            value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="form-inline col-lg-3.2">
                                    <label class="mx-2 t_b">From Date:</label>
                                    <input required class="form-control form-control-sm datepicker" data-date-format="dd/mm/yyyy"
                                        name="from_date" value="{{$from_date}}">
                                </div>
                                <div class="form-inline col-md-3.2">
                                    <label class="mx-2 t_b">To Date:</label>
                                    <input required class="form-control form-control-sm datepicker" data-date-format="dd/mm/yyyy"
                                        name="to_date" value="{{$to_date}}">
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;
                                <div style="margin-top: px">
                                    <button type="submit" class="btn btn-sm btn-info">Show Report</button>
                                    <button type="submit" class="btn btn-sm btn-warning">Send Email</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div id="resultshow" style="padding-top:10px;">
                            <div align="center">
                                <h2 style="color:#666666;"><u> {{$cId->name}}</u></h2>
                                <strong>Supplier Ledger</strong>
                                <style>
                                    table tr td {font-size: 15px; padding: 5px 2px !important}
                                </style>
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="text-align:center" width="15%">Supplier Name</th>
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
                                            <th style="text-align:center">Bill/Payment</th>
                                            <th style="text-align:center">Bill Amount</th>
                                            <th style="text-align:center"> Paid Amount</th>
                                            <th style="text-align:center"> Balance</th>
                                        </tr>
                                        <tr>
                                            <td align="center">Opening Balance</td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td class="text-right">$ {{number_format($cId->opening_blnc,2)}}</td>
                                        </tr>
                                        @php
                                        $payment_amount = 0;
                                        $balance = $cId->opening_blnc;
                                        @endphp
                                        @foreach ($creditors->groupBy('inv_no') as $creditor)
                                        @php $balance += $creditor->sum('amount'); @endphp
                                        <tr>
                                            <td align="center">{{$creditor->first()->tran_date->format('d/m/Y')}}</td>
                                            <td align="center">
                                                <a href="{{route('bill.report',[$layout,$creditor->first()->inv_no,$client->id])}}">
                                                    {{invoice($creditor->first()->inv_no, 8, 'INV')}}
                                                </a>
                                            </td>
                                            <td align="center">{{number_format($creditor->sum('amount'),2)}}</td>
                                            <td align="center">0.00</td>
                                            <td align="center">{{number_format($balance,2)}}</td>
                                        </tr>
                                        @endforeach
                                        @foreach ($payments as $payment)
                                        <tr>
                                            <td align="center" class="text-success">
                                                {{$payment->tran_date->format('d/m/Y')}}</td>
                                            <td align="center">
                                                <a target="_blank" href="{{route('cledger.payment',$payment->inv_no)}}">
                                                    @if ($payment->creditor_inv != null)
                                                    {{invoice($payment->id, 8, 'REC')}}
                                                    @else
                                                    {{invoice($payment->id, 8, 'REC')}}
                                                    @endif
                                                </a>
                                            </td>
                                            <td align="center">0.00</td>
                                            <td align="center">
                                                {{number_format($payment->payment_amount,2)}}
                                                @php
                                                $payment_amount += $payment->payment_amount;
                                                $balance -= $payment->payment_amount;
                                                @endphp
                                            </td>
                                            <td align="center">{{number_format($balance,2)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-right">
                                            <td colspan="4" style="text-align:right">Net Balance</td>
                                            <td>$ {{number_format(($creditors->sum('amount') + $cId->opening_blnc) - $payment_amount,2)}}</td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div style="text-align:right;">
                                    <form action="" method="POST">
                                        <button type="submit" class="btn btn-success">PDF</button>
                                    </form>
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
