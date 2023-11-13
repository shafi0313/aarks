<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice{{ invoice($invoices->first()->inv_no) }} | AARKS</title>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}
    @include('frontend.print-css')
    <style>
        .area {
            border: 1px solid black;
            padding: 5px 5px;
        }

        .header {
            padding-top: 20px;
        }

        .header p {
            margin: 0;
        }

        .header .img {
            width: 35%;
            display: inline-block;
        }

        .header .text {
            width: 64%;
            display: inline-block;
        }

        .tax p {
            margin: 0px;
        }

        .tax .invoice {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .customer .address {
            margin-top: 5px;
            width: 45%;
            padding: 10px;
            border: 2px solid #666666;
        }

        .inv_total .quote {
            width: 60%;
            /* float: left; */
            display: inline-block;
            height: 243px;
        }

        .inv_total .data {
            width: 39%;
            display: inline-block;
            background: red;

        }

        table {
            margin-bottom: 2px !important;
        }
    </style>
</head>

<body>
    <div class="area">
        <div class="header">
            <div class="img">
                <img src="{{ $client->logo ? asset($client->logo) : asset('frontend/assets/images/logo/focus-icon.png') }}"
                    class="img-responsive" style="max-width:220px; height:auto;">
                {{-- <img src="{{ asset('frontend/assets/images/logo/focus-icon.png') }}"
                    class="img-responsive" style="max-width:110px; height:auto;"> --}}
            </div>
            <div class="text">
                @php
                    $inv = $invoices->first();
                    $customer = $invoices->first()->customer;
                    $one_of = \App\Models\CustomerTempInfo::whereCustomerCardId($customer->id)
                        ->whereInvNo($inv->inv_no)
                        ->first();

                    switch (intval($customer->days)) {
                        case 7:
                            $terms = '7 Days';
                            break;
                        case 15:
                            $terms = '15 Days';
                            break;
                        case 21:
                            $terms = '21 Days';
                            break;
                        case 30:
                            $terms = '1 Month';
                        case 90:
                            $terms = '3 Month';
                        case 180:
                            $terms = '6 Month';
                            break;
                        default:
                            $terms = $customer->payment_due;
                            break;
                    }
                @endphp
                <strong style="font-size:25px;">{{ clientName($client) }}</strong><br>
                <strong>A.B.N : {{ $client->abn_number }}</strong><br>
                <strong> {{ $client->street_address }}</strong>,<strong>{{ $client->suburb }}</strong><br>
                <strong>{{ $client->state }} {{ $client->post_code }}</strong>
                <strong>Phone: {{ $client->phone }}</strong><br>
                <strong>E-mail: {{ $client->email }}</strong> <br>
                <strong>Website: <a href="{{ $client->website }}">{{ $client->website }}</a></strong>
            </div>
        </div>

        <div class="tax" style="text-align: center;">
            <b style="float: left; padding-top: 10px">Billing Address:</b>
            <u class="invoice">TAX INVOICE</u>
        </div>

        <div class="customer">
            @if ($one_of)
                <div class="address">
                    <div style="font-size:14px; font-weight:800;">Attn:</div>
                    <span style="font-size:18px;font-weight:800;"> {{ $one_of->name }} </span><br>
                    <span>{{ $one_of->address }}</span>,
                    <span>{{ $one_of->city }}</span><br>
                    <span>{{ $one_of->state }}</span> <span>{{ $one_of->b_postcode }}</span>,
                    <span>Phone : {{ $one_of->phone }}</span>
                </div>
            @else
                <div class="address">
                    <div style="font-size:14px; font-weight:800;">Attn:</div>
                    <span style="font-size:18px;font-weight:800;"> {{ $customer->name }} </span><br>
                    <span>{{ $customer->b_address }}</span>,
                    <span>{{ $customer->b_city }}</span><br>
                    <span>{{ $customer->b_state }}</span>, <span>{{ $customer->b_postcode }}</span>,
                    <span>Phone : {{ $customer->phone }}</span>
                </div>
            @endif
        </div>
        <br>
        <div align="left"><strong>Invoice Details :</strong></div>
        <div class="inv_info">
            <table width="100%" cellpadding="2" class="table table-bordered">
                <tbody>
                    <tr>
                        <td align="center">Invoice Date</td>
                        <td align="center">Invoice Number</td>
                        <td align="center">Terms</td>
                        <td align="center">Your Ref</td>
                        <td align="center">Our Ref</td>
                    </tr>
                    <tr>
                        <td align="center">{{ bdDate($inv->tran_date) }}</td>
                        <td align="center">
                            {{ str_pad($inv->inv_no, 9, '0', STR_PAD_LEFT) }}
                        </td>
                        <td align="center">{{ $terms }}</td>
                        <td align="center">{{ $inv->your_ref }}</td>
                        <td align="center">{{ $inv->our_ref }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="inv_data">
            <table width="100%" cellpadding="2" class="table table-bordered">
                <thead>
                    <tr>
                        <td width="1%" align="center">Sl</td>
                        <td width="13%" align="center">Item Number</td>
                        <td width="40%" align="center">Item Name</td>
                        <td width="7%">Qty</td>
                        <td width="2%">Rate(Ex GST)</td>
                        <td width="8%" align="center">Amount</td>
                        <td width="2%" align="center">Dis%</td>
                        <td width="10%" align="center">Total Amount</td>
                        <td width="2%" align="center">Tax</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $i => $invoice)
                        <tr class="text-center">
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $inv->alige }}{{ $invoice->item_no }}</td>
                            <td>{{ $invoice->item_name }}</td>
                            <td>{{ $invoice->item_quantity }}</td>
                            <td>{{ number_format($invoice->ex_rate, 2) }}</td>
                            <td>{{ number_format($invoice->price, 2) }}</td>
                            <td>{{ number_format($invoice->disc_rate, 2) }}</td>
                            <td>{{ number_format($invoice->amount, 2) }}</td>
                            <td>{{ number_format($invoice->tax_rate, 2) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <table width="100%" cellpadding="2" class="table inv_total">
            <tr>
                <td width="60%">
                    <table width="100%" cellpadding="2" class="table">
                        <tr>
                            <td style="border: none">
                                <strong>Terms and Condition: </strong><br>
                                {{ $inv->quote_terms }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%">
                    <table width="100%" cellpadding="2" class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width="80%">Total Amount(Without GST)</td>
                                <td>{{ number_format($invoices->sum('price'), 2) }}</td>
                            </tr>
                            <tr>
                                <td>Freight Charge</td>
                                <td>{{ number_format($invoices->sum('freight_charge'), 2) }}</td>
                            </tr>
                            <tr>
                                <td>GST </td>
                                {{-- <td>{{number_format($invoices->sum('disc_amount'),2)}}</td> --}}
                                <td>{{ number_format($invoices->sum('amount') - $invoices->sum('freight_charge') - $invoices->sum('price'), 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>{{ number_format($invoices->sum('amount'), 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p>We appreciate your business with us.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        {{-- <br> --}}
        <div style="width: 100%;">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            <strong style="font-size:15px;">{{ $one_of ? $one_of->name : $customer->name }}</strong><br>
                            Please forward your payment to BSB :
                            {{ $client->bsb->bsb_number ?? '' }}&nbsp;&nbsp;Account no
                            {{ $client->bsb->account_number ?? '' }} Account Name :
                            {{ $client->company ?? $client->first_name . ' ' . $client->last_name }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-12" align="center">
            <b>Powered by <a href="https://aarks.com.au">AARKS</a> <a href="https://aarks.net.au">(ADVANCED ACCOUNTING &
                    RECORD KEEPING SOFTWARE)</a></b>
        </div>
    </div>
</body>

</html>
