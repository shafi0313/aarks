@extends('frontend.layout.master')
@section('title','Sales Register Report')
@section('content')<?php $p="salreg"; $mp="sales";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong style="color:green; font-size:20px;">Sales Register: </strong>
                            </div>
                            {{-- <div class="col-md-9">
                                <form action="{{route('edit.print.filter')}}" method="get" autocomplete="off">
                                    <input type="hidden" name="src" value="inv_item">
                                    <div class="row justify-content-end">
                                        <div class="col-3 form-group">
                                            <input class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                                name="start_date" placeholder="From Date">
                                        </div>
                                        <div class="col-3 form-group">
                                            <input class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                                name="end_date" placeholder="To Date">
                                        </div>
                                        <div class="mr-3">
                                            <button class="btn btn-success" type="submit">Show Report</button>
                                        </div>
                                    </div>
                                </form>
                            </div> --}}
                        </div>
                        <hr>
                        <table class="table table-striped table-bordered table-hover display table-sm text-left">
                                @php
                                    $i=1;
                                    $amount=$due=$paid=0;
                                @endphp
                                @foreach ($invoices->groupBy('customer_card_id') as $invoice)
                                <tr>
                                    <td colspan="7">
                                        <span style="color: #cc00ff;text-transform:uppercase;font-size:18px">{{$invoice->first()->customer->name}}</span>
                                    </td>
                                </tr>
                                <tr class="text-center" style="font-size: 14px">
                                    <th width="4%">SL</th>
                                    <th width="10%">Date</th>
                                    <th width="10%">Tran Id</th>
                                    <th width="9%">Invoice No</th>
                                    <th width="9%">Inv Amount</th>
                                    <th width="9%">Due Amount</th>
                                    <th width="9%">Paid Amount</th>
                                </tr>
                                @foreach ($invoice->groupBy('inv_no') as $inv)
                                @php
                                    $pay_amount = $inv->first()->payments->first()->sum_pay_amount;
                                    $amount += $inv->sum('amount');
                                    $due    += $inv->sum('amount')-$pay_amount;
                                    $paid   += $pay_amount;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{$inv->first()->tran_date->format('d/m/Y')}} </td>
                                    <td>{{$inv->first()->tran_id}} </td>
                                    <td class="text-center text-primary">
                                        <a href="{{route('inv.report',['item',$inv->first()->inv_no,$client->id])}}">{{invoice($inv->first()->inv_no, 8, 'INV')}}</a>
                                    </td>
                                    <td class="text-left text-info">$ {{number_format($inv->sum('amount'),2)}}
                                    </td>
                                    <td class="text-left text-danger">$
                                        {{number_format($inv->sum('amount') - $pay_amount,2)}}
                                    </td>
                                    <td class="text-left text-success">$
                                        {{number_format($pay_amount,2)}} </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="text-right">
                                        <span style="color: #009e7e;text-transform:uppercase;font-size:18px">TOTAL: </span>
                                    </td>
                                    <td>
                                        $ {{number_format($amount,2)}}
                                    </td>
                                    <td>
                                        $ {{number_format($due,2)}}
                                    </td>
                                    <td>
                                        $ {{number_format($paid,2)}}
                                    </td>
                                </tr>
                                @endforeach
                        </table>
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
