<!doctype html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <title>🧾 Invoice {{invoice($inv_no)}} from {{$client->fullname}}</title>
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-10">
                                    <div class="d-flex">
                                        <div class="">
                                            <img src="{{asset('admin/assets/images/inv.png')}}" alt="INV"
                                                class="img-responsive img-fluid" width="52">
                                        </div>
                                        <div class="">
                                            <h2>{{$client->fullname}}</h2>
                                            <p>Invoice No: <b>{{invoice($inv_no)}}</b></p>
                                        </div>
                                    </div>
                                    <nav>
                                        <ul class="nav">
                                            <li class="nav-item">
                                                <a target="_blank"
                                                    href="{{route('recurring.print', ['service',$inv_no, $client->id])}}" download="{{route('recurring.print', ['service',$inv_no, $client->id, 'download'])}}"
                                                    class="nav-link">
                                                    <div class="fa fa-download"></div> PDF
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a target="_blank"
                                                    href="{{route('recurring.print', ['service',$inv_no, $client->id, 'print'])}}"
                                                    class="nav-link">
                                                    <div class="fa fa-print"></div> Print
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="col-2">
                                    <p class="text-success">PAID</p>
                                    <h2>${{number_format($invoices->sum('payment_amount'),2)}}</h2>AUD
                                </div>
                            </div>
                        </div>
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
                                $one_of =
                                \App\Models\CustomerTempInfo::whereCustomerCardId($customer->id)->whereInvNo($inv->inv_no)->first();

                                @endphp
                                <strong style="font-size:25px;">{{$client->fullname}}</strong><br>
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
                                @if ($one_of)
                                <div style="padding:10px; border:2px solid #666666;">
                                    <div style="font-size:14px; font-weight:800;">Billing Address:</div>
                                    <span style="font-size:17px;"> {{$one_of->name}} </span><br>
                                    <span>{{$one_of->address}}</span><br>
                                    <span>{{$one_of->city}}</span><br>
                                    <span>{{$one_of->state}}</span></span><br>
                                    <span>Phone : {{$one_of->phone}}</span><br>
                                    <span>E-mail : {{$one_of->email}}</span>
                                </div>
                                @else
                                <div style="padding:10px; border:2px solid #666666;">
                                    <div style="font-size:14px; font-weight:800;">Billing Address:</div>
                                    <span style="font-size:17px;"> {{$customer->name}} </span><br>
                                    <span>{{$customer->b_address}}</span><br>
                                    <span>{{$customer->b_city}}</span><br>
                                    <span>{{$customer->b_state}}</span>, <span>{{$customer->b_postcode}}</span><br>
                                    <span>Phone : {{$customer->phone}}</span><br>
                                    <span>E-mail : {{$customer->email}}</span>
                                </div>
                                @endif
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
                                            <td width="2%" align="center">GST Rate</td>
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
                                            <td>{{number_format($invoices->sum('amount') -
                                                $invoices->sum('freight_charge')
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
                                        <tr>
                                            <td>Due on this Invoice</td>
                                            <td>{{number_format($invoices->sum('amount') -
                                                $invoices->sum('payment_amount'),2)}}</td>
                                        </tr>
                                        <br>
                                        <tr>
                                            <td>
                                                <hr>
                                                <p>We appreciate your business with us.</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @if ($client->bsb)
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js">
        </script>
    </body>

</html>
