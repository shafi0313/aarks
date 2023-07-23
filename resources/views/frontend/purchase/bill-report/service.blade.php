@extends('frontend.layout.master')
@section('title','Invoice Report for '.$source)
@section('content')
<?php $p=''; $mp=$source;?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-md-4" align="center">
                            <div class="row" style="padding-top:20px;">
                                <div class="col-md-12" align="left">
                                    <img src="{{$client->logo?asset($client->logo):asset('frontend/assets/images/logo/focus-icon.png')}}"
                                        class="img-responsive" style="max-width:90px; max-height:90px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8" style="padding:5px;">
                            @php
                            $inv = $invoices->first();
                            $customer = $invoices->first()->customer;
                            @endphp
                            <strong style="font-size:25px;">{{$client->company? $client->company: $client->first_name .'
                                '. $client->last_name}}</strong><br>
                            <strong>A.B.N : {{$client->abn_number}}</strong><br>
                            <strong> {{$client->street_address}}</strong><br>
                            <strong>{{$client->suburb}}</strong><br>
                            <strong>{{$client->state}} {{$client->post_code}}</strong>
                            <strong>Phone: {{$client->phone}}</strong><br>
                            <strong>E-mail: {{$client->email}}</strong><br>
                        </div>

                        <div class="col-md-12 text-center" style="font-size: 25px; font-weight: bold">
                            <u>TAX INVOICE</u>
                        </div>

                        <div class="col-md-8" align="left" style="padding-right:20px;">
                            <div style="padding:10px; border:2px solid #666666;">
                                <div style="font-size:14px; font-weight:800;">Billing Address:</div>
                                <span style="font-size:17px;"> {{$customer->name}} </span><br>
                                <span>{{$customer->b_address}}</span><br>
                                <span>{{$customer->b_city}}</span><br>
                                <span>{{$customer->b_state}}</span>, <span>{{$customer->b_postcode}}</span><br>
                                <span>Phone : {{$customer->phone}}</span><br>
                                <span>E-mail : {{$customer->email}}</span>
                            </div>
                        </div>
                        <div class="col-md-4" align="center" style="padding-right:20px;">
                        </div>
                        <div class="col-md-12" style="padding-top:5px;">
                            <div align="left"><strong>Invoice Details :</strong></div>
                            <table width="100%" cellpadding="2" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td align="center">Invoice Date</td>
                                        <td align="center">Invoice Number</td>
                                        {{-- <td align="center">Terms</td> --}}
                                        <td align="center">Your Ref</td>
                                        <td align="center">Our Ref</td>
                                        <td align="center">Due Date</td>
                                    </tr>
                                    <tr>
                                        <td align="center">{{$inv->tran_date->format('d/m/Y')}}</td>
                                        <td align="center">{{invoice($inv->inv_no)}}</td>
                                        {{-- <td align="center">{!! $inv->quote_terms !!}</td> --}}
                                        <td align="center">{{$inv->your_ref}}</td>
                                        <td align="center">{{$inv->our_ref}}</td>
                                        <td align="center"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12" style="padding-top:0px;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td width="1%" align="center">Sl</td>
                                        <td width="13%" align="center">Job Title</td>
                                        <td width="40%" align="center">Job Des</td>
                                        {{-- <td width="2%">Rate(Ex GST)</td> --}}
                                        <td width="8%" align="center">Amount</td>
                                        <td width="2%" align="center">Dis%</td>
                                        <td width="10%" align="center">Total Amount</td>
                                        <td width="2%" align="center">Tax</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $i=>$invoice)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$invoice->job_title}}</td>
                                        <td>{{$invoice->job_des}}</td>
                                        {{-- <td>{{number_format($invoice->disc_amount,2)}}</td> --}}
                                        <td>{{number_format($invoice->price,2)}}</td>
                                        <td>{{number_format($invoice->disc_rate,2)}}</td>
                                        <td>{{number_format($invoice->amount,2)}}</td>
                                        <td>{{number_format($invoice->tax_rate,2)}}</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            {!! $inv->quote_terms !!}
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td width="80%">Total Amount(Without GST)</td>
                                        <td>{{number_format($invoices->sum('price'),2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Freight Charge</td>
                                        <td>{{number_format($invoices->sum('freight_charge'),2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>GST </td>
                                        {{-- <td>{{number_format($invoices->sum('disc_amount'),2)}}</td> --}}
                                        <td>{{number_format($invoices->sum('amount') - $invoices->sum('freight_charge')
                                            -$invoices->sum('price') ,2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>TOTAL</td>
                                        <td>{{number_format($invoices->sum('amount'),2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>PAID Amt</td>
                                        <td>{{number_format($invoices->sum('payment_amount'),2)}}</td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td>
                                            <hr>
                                            <p>Accepected please Sign here.................</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="col-md-6 offset-lg-6" style="padding-top:10px;">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td></td>
                                    </tr>


                                </tbody>
                            </table>
                        </div> --}}
                        @if ($client->bsb)
                        <div class="col-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>
                                                {{-- <strong style="font-size:17px;"> {{$customer->name}} </strong><br>
                                                --}}
                                                Please forward your payment to BSB :
                                                {{$client->bsb->bsb_number}}&nbsp;&nbsp;Account
                                                {{$client->bsb->account_number}} no Account Name :
                                                {{$client->company?? $client->first_name .' '. $client->last_name}}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <div class="col-12" align="center">
                            Powered by <b class="text-info">AARKS</b>
                        </div>
                        <div class="col-12">
                            <a href="{{route('bill.report.print', ['service',$inv_no, $client->id])}}"
                                target="_blank" class="btn btn-outline-info text-dark pull-right btn-lg"> <i
                                    class="fa fa-print"></i> PRINT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->


<!-- inline scripts related to this page -->
<!-- Data Table -->
<script>
    $(document).ready(function() {
            $('#example').DataTable( {
                "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
                "order": [[ 0, "asc" ]]
            } );
        } );
</script>
@stop
