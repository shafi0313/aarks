@extends('frontend.layout.master')
@section('title','Purchase Register Report')
@section('content')
<?php $p="pr"; $mp="purchase";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong style="color:green; font-size:20px;">Purchase Register: </strong>
                            </div>
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
                                    $amount += $inv->sum('amount');
                                    $due    += $inv->sum('amount')-$inv->first()->payment_amount;
                                    $paid   += $inv->first()->payment_amount;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{$inv->first()->tran_date->format('d/m/Y')}} </td>
                                    <td>{{$inv->first()->tran_id}} </td>
                                    <td class="text-center text-primary">
                                        <a href="{{route('inv.report',['purchase','item',$inv->first()->inv_no,$inv->first()->customer_card_id])}}">BILL#{{$inv->first()->inv_no}}</a>
                                    </td>
                                    <td class="text-right text-info">$ {{number_format($inv->sum('amount'),2)}}
                                    </td>
                                    <td class="text-right text-danger">$
                                        {{number_format($inv->sum('amount') - $inv->first()->payment_amount,2)}}
                                    </td>
                                    <td class="text-right text-success">$
                                        {{number_format($inv->first()->payment_amount,2)}} </td>
                                </tr>
                                @endforeach
                                <tr class="text-right">
                                    <td colspan="4" >
                                        <span style="color: #009e7e;text-transform:uppercase;font-size:18px">TOTAL: </span>
                                    </td>
                                    <td>$ {{number_format($amount,2)}}</td>
                                    <td>$ {{number_format($due,2)}}</td>
                                    <td>$ {{number_format($paid,2)}}</td>
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
