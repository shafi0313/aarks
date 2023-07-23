@extends('frontend.layout.master')
@section('title','Customer Ledger')
@section('content')
<?php $p="cled"; $mp="report";?>
<!-- Page Content Start -->
<style>
    .left_billingaddress {
    border: 2px solid #333333;
    border-radius: 15px;
    height: 200px;
}

</style>
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div align="right">
                    <a href="" class="btn btn-primary">Print</a>
                    <a href="" class="btn btn-primary">E-mail</a>
                </div>

                <div class="row" style="border:2px solid #666666; min-height:600px; background:#FFFFFF; padding:10px">
                    <div class="col-md-5" align="left">
                        <div class="row" style="padding-top:20px;">
                            <div class="col-md-6">
                                <img src="https://aarks.net.au/upload/logo/1498024984.jpg" class="img-responsive" style="max-width:300px; max-height:120px;">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7" style="padding-left:0px;">
                        <div class="">
                            <div >
                                <strong style="font-size:25px;">Focus Taxation and Accounting Pty Ltd</strong><br>
                                <strong>A.B.N : {{$dedotr->client->abn_number}}</strong><br>
                                <strong>{{$dedotr->client->address}}</strong><br>
                                <strong>{{$dedotr->client->suburb}}</strong><br>
                                <strong>{{$dedotr->client->state}} {{$dedotr->client->post_code}}</strong><br>
                                <strong>Phone: {{$dedotr->client->phone}}.</strong><br>
                                <strong>E-mail: {{$dedotr->client->email}}</strong><br>
                                <strong>Web : www.focustaxation.com.au</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" align="center" style="padding-top:30px;">
                        <strong style="font-size:30px;"><b><u>TAX INVOICE</u></b></strong>
                    </div>
                    <div class="col-md-12" style="padding-top:10px;">
                        <div class="row">
                            <div class="col-md-5 " align="left">
                                <strong>Billing Address :</strong>
                                <div class="col-md-12 left_billingaddress">
                                    <p><strong>Attn:</strong><br>
                                    <strong style="font-size:20px;">{{$dedotr->customer->name}}</strong>
                                    <br>
                                    {{$dedotr->customer->b_address}}<br>
                                    {{$dedotr->customer->b_city}}<br>
                                    {{$dedotr->customer->b_state}}, <b>Post: </b>{{$dedotr->customer->b_postcode}}<br>
                                    <b>Phone: </b>{{$dedotr->customer->phone}}<br>
                                    <b>Email: </b>{{$dedotr->customer->email}}</p>
                                </div>
                            </div>
                            {{-- <div class="col-md-6" align="left">
                                <strong>Shipping Address :</strong>
                                <div class="col-md-12">
                                    <p><strong>Attn:</strong></p>
                                    <p><b>Name</b>{{$dedotr->customer->name}}</p>
                                    <p><b>Address: </b>{{$dedotr->customer->s_address}}</p>
                                    <p><b>City: </b>{{$dedotr->customer->s_city}}</p>
                                    <p><b>State: </b>{{$dedotr->customer->s_state}}, <b>Post: </b>{{$dedotr->customer->s_postcode}}</p>
                                    <p><b>Phone: </b>{{$dedotr->customer->phone}}</p>
                                    <p><b>E-mail: </b>{{$dedotr->customer->email}}</p>
                                </div>
                            </div> --}}
                        </div>
                    </div>


                    <div class="col-md-12" style="padding-top:10px;">
                        <div align="left"><strong>Invoice Details :</strong></div>
                        <table width="100%" cellpadding="2" class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td align="center">Invoice Date</td>
                                    <td align="center">Invoice Number</td>
                                    <td align="center">Payment Terms</td>
                                    <td align="center">Your Ref</td>
                                    <td align="center">Our Ref</td>
                                </tr>
                                <tr>
                                    <td align="center">{{$dedotr->tran_date->format('d/m/Y')}}</td>
                                    <td align="center">{{$dedotr->inv_no}}</td>
                                    <td align="center"></td>
                                    <td align="center">{{$dedotr->your_ref}}</td>
                                    <td align="center">{{$dedotr->our_ref}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12" style="padding-top:10px;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td width="5%" align="center">SN</td>
                                    <td width="50%" align="center">Description</td>
                                    <td width="15%" align="center">Price</td>
                                    <td width="10%" align="center">Dis%</td>
                                    <td width="10%" align="center">Amount</td>
                                    <td width="7%" align="center">Tax</td>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <strong>ITR 2017</strong><br>
                                        Fees for the following professional services rendered on behalf of client:
                                        Preparation of income tax return for the year ended 30 June 2017.
                                    </td>
                                    <td>{{number_format($dedotrs->sum('price'),2)}}</td>
                                    <td>{{number_format($dedotr->disc_rate,2)}}</td>
                                    <td>{{number_format($dedotrs->sum('amount'),2)}}</td>
                                    <td>{{number_format($dedotr->tax_rate,2)}}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="col-md-12" style="padding: 0 28px">
                    <div class="row">
                        <div class="col-md-7">
                            <div align="left" class="row">
                                <strong>Terms and Condition :</strong>
                            </div>
                            <div class="row" style="padding-right:15px;">
                                <p style="text-align:justify;">Please note that our terms are STRICTLY PAYMENT ON
                                    COMPLETION &amp; any unpaid accounts are liable for an 15% debt collection service
                                    fee to be added to the unpaid balance. Our customers are our most valuable asset, we
                                    appreciate your business and look forward to serving you again soon. - Sales AGENT -
                                    Mrs Fateha Begum</p>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td width="80%">Total Amount(Without GST)</td>
                                            <td>{{number_format($dedotrs->sum('amount'),2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Freight Charge</td>
                                            <td>{{number_format($dedotr->freight_charge,2)}}</td>
                                        </tr>

                                        <tr>
                                            <td>GST </td>
                                            <td>0</td>
                                        </tr>
                                        <tr>
                                            <td align="center">TOTAL</td>
                                            <td>{{number_format($dedotrs->sum('amount'),2)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row" style="padding-top:10px;">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>We appreciate your business.</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>
                                        <p>
                                            {{$dedotr->customer->name}} <br>Please forward your payment to BSB
                                            :302-162&nbsp;&nbsp;Account no 0614697 Account Name : Focus Taxation and
                                            Accounting Pty Ltd </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center">
                            Powered by <a href="http://www.aakrs.com.au/" target="_blank">AARKS</a>
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
