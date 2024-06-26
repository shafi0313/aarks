<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Invoice{{ invoice($invoices->first()->inv_no) }} | AARKS</title>
    <style type="text/css">
        @media print {
            body {
                margin: 3mm 8mm 5mm 5mm;
            }
        }

        @page {
            margin: 3mm 8mm 5mm 5mm;
        }
    </style>
</head>

<body>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <thead>
            <tr>
                <td width="50%">
                    <img src="{{ $client->logo ? asset($client->logo) : asset('frontend/assets/images/logo/focus-icon.png') }}"
                        width="100px">
                </td>
                <td width="50%">
                    @php
                        $inv = $invoices->first();
                        $customer = $invoices->first()->customer;
                    @endphp
                    <strong style="font-size:25px;">{{ $client->fullname }}</strong><br>
                    <span>A.B.N : {{ $client->abn_number }}</span><br>
                    <span> {{ $client->street_address }}</span><br>
                    <span>{{ $client->suburb }}</span><br>
                    <span>{{ $client->state }} {{ $client->post_code }}</span>
                    <span>Phone: {{ $client->phone }}</span><br>
                    <span>E-mail: {{ $client->email }}</span><br>
                </td>
            </tr>
            <tr>
                <td width="100%" style="text-align: center; font-size: 20px">
                    <u>TAX INVOICE</u>
                </td>
            </tr>
        </thead>
        <tr>
            <td width="100%" style="font-size: 12px;">
                <div style="font-size:14px; font-weight:800;">Billing Address:</div> <br> <br>
                <span style="font-size:17px;"> {{ $customer->name }} </span><br>
                <span>{{ $customer->b_address }}</span>,
                <span>{{ $customer->b_city }}</span>,<br>
                <span>{{ $customer->b_state }}</span>,
                <span>{{ $customer->b_postcode }}</span>
                <span>Phone : {{ $customer->phone }}</span><br>
                <span>E-mail : {{ $customer->email }}</span>
            </td>
        </tr>
        {{-- <tr>
                <td style="height: 10px">&nbsp; </td>
            </tr> --}}

        <tr>
            <td height="0" style="border: 0px solid #fff;" border="0" valign="top">
                <table width="100%" border="0" cellpadding="2" cellspacing="0">
                    <tr>
                        <td align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>Invoice Date</strong>
                        </td>
                        <td align="left"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>Invoice Number</strong>
                        </td>
                        {{-- <td align="left"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                <strong>Terms</strong>
                            </td> --}}

                        <td align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>Your Ref</strong>
                        </td>
                        <td align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>Our Ref</strong>
                        </td>

                        <td align="center" style="border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong> Due Date</strong>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="border-right:1px solid #000;font-size: 10pt;">
                            {{ bdDate($inv->tran_date) }}</td>
                        <td align="left" style="border-right:1px solid #000;font-size: 10pt;">
                            {{ invoice($inv->inv_no) }}</td>
                        {{-- <td align="left" style="border-right:1px solid #000;font-size: 10pt;">
                                {!! $inv->quote_terms !!}</td> --}}
                        <td align="center" style="border-right:1px solid #000;font-size: 10pt;">
                            {{ $inv->your_ref }}</td>
                        <td align="right" style="border-right:1px solid #000;font-size: 10pt;">
                            {{ $inv->our_ref }}</td>
                        <td align="center" style="font-size: 10pt;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="height: 2px">&nbsp; </td>
        </tr>
        <tr>
            <td height="0" style="border: 0px solid #fff;" border="0" valign="top">
                <table width="100%" border="0" cellpadding="2" cellspacing="0">
                    <tr>
                        <td align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>SL</strong>
                        </td>
                        {{-- <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                <strong>Job Title</strong></td> --}}
                        <td align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>Job Des</strong>
                        </td>
                        {{-- <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                <strong>Rate(Ex GST)</strong></td> --}}
                        <td align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>Amount</strong>
                        </td>
                        <td align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>Dis %</strong>
                        </td>
                        <td align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>Total Amount</strong>
                        </td>
                        <td align="center" style="border-bottom: 1px solid #000;font-size: 10pt;">
                            <strong>Tax</strong>
                        </td>
                    </tr>
                    @foreach ($invoices as $i => $invoice)
                        <tr>

                            <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                {{ $i + 1 }}</td>
                            {{-- <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                {{$invoice->job_title}}</td> --}}
                            <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                {{ $invoice->job_des }}</td>
                            {{-- <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                {{number_format($invoice->disc_amount,2)}}</td> --}}
                            <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                {{ number_format($invoice->price, 2) }}</td>
                            <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                {{ number_format($invoice->disc_rate, 2) }}</td>
                            <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                {{ number_format($invoice->amount, 2) }}</td>
                            <td align="center" style="border-bottom: 1px solid #000;font-size: 10pt;">
                                {{ number_format($invoice->tax_rate, 2) }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td style="height: 2px">&nbsp; </td>
        </tr>

        <tr>
            <td>
                <table border="0" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <td width="55%" valign="top">
                            {!! $inv->quote_terms !!}
                        </td>
                        <td width="45%" border="0" style="border-left:1px solid black;">
                            <table width="100%" border="0">

                                <tr>
                                    <td style="border-bottom:1px solid black;border-right:1px solid black;">Total
                                        Amount(Without GST)</td>
                                    <td style="border-bottom:1px solid black;border-right:1px solid black;">
                                        {{ number_format($invoices->sum('price'), 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid black;border-right:1px solid black;">Freight
                                        Charge</td>
                                    <td style="border-bottom:1px solid black;border-right:1px solid black;">
                                        {{ number_format($invoices->sum('freight_charge'), 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid black;border-right:1px solid black;">GST </td>
                                    {{-- <td style="border-bottom:1px solid black;border-right:1px solid black;">{{number_format($invoices->sum('disc_amount'),2)}}</td> --}}
                                    <td style="border-bottom:1px solid black;border-right:1px solid black;">
                                        {{ number_format($invoices->sum('amount') - $invoices->sum('freight_charge') - $invoices->sum('price'), 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid black;border-right:1px solid black;">TOTAL</td>
                                    <td style="border-bottom:1px solid black;border-right:1px solid black;">
                                        {{ number_format($invoices->sum('amount'), 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-right:1px solid black;">PAID Amt</td>
                                    <td style="border-right:1px solid black;">
                                        {{ number_format($invoices->sum('payment_amount'), 2) }}</td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        {{-- <tr>
                <td style="height: 2px">&nbsp; </td>
            </tr> --}}

        <tr>
            <td>
                <table border="0" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <td width="55%">Please forward your payment to BSB :
                            {{ optional($client->bsb)->bsb_number }}&nbsp;&nbsp;Account
                            {{ optional($client->bsb)->account_number }} no Account Name :
                            {{ $client->company ?? $client->first_name . ' ' . $client->last_name }}</td>
                        <td width="45%" border="0" style="border-left:1px solid black;">
                            <table width="100%" border="0">
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>We appreciate your business with us.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align:center">Powered by<a href="https://www.aarks.com.au/"> AARKS
                    &nbsp;</a>{{ now()->format('Y') }}</td>
        </tr>
    </table>
</body>

</html>
