<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Invoice#{{ $invoice ?? ''}} | AARKS</title>
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

    <body style="margin-bottom: 8px;height: 100%;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">

            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        @php
                        $client   = $payment->client;
                        $customer = $payment->customer;
                        @endphp
                        <tr>
                            <td width="15%">
                                <img src="{{$client->logo?asset($client->logo):asset('frontend/assets/images/logo/focus-icon.png')}}"
                                    width="100px">
                            </td>
                            <td width="10%"> &nbsp; </td>
                            <td width="35%" valign="top">
                                <strong style="font-size:25px;">{{$client->company? $client->company: $client->first_name .' '. $client->last_name}}</strong><br>
                                <span>A.B.N : {{$client->abn_number}}</span><br>
                                <span> {{$client->street_address}}</span><br>
                                <span>{{$client->suburb}}</span><br>
                                <span>{{$client->state}} {{$client->post_code}}</span>
                                <span>Phone: {{$client->phone}}</span><br>
                                <span>E-mail: {{$client->email}}</span><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="100%">
                                <br>
                                <div style="text-align: center; font-size: 20px" ><u>Receipt</u> </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table border="1" cellpadding="0" cellspacing="0" width="100%"
            style="border-collapse:collapse;font-size: 13px;border: 1px solid #000;">
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="64%">
                                <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="96%" style="font-size: 12px;">
                                            <div style="font-size:14px; font-weight:800;">Billing Address:</div>
                                            <span style="font-size:17px;"> {{$customer->name}} </span><br>
                                            <span>{{$customer->b_address}}</span><br>
                                            <span>{{$customer->b_city}}</span><br>
                                            <span>{{$customer->b_state}}</span>,
                                            <span>{{$customer->b_postcode}}</span><br>
                                            <span>Phone : {{$customer->phone}}</span><br>
                                            <span>E-mail : {{$customer->email}}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="0" style="border: 0px solid #fff;" border="0" valign="top">
                    <table width="100%" border="0" cellpadding="2" cellspacing="0">
                        <tr>
                            <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                <strong>Invoice Date</strong></td>
                            <td align="left"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                <strong>Invoice Number</strong>
                            </td>
                            <td align="center"
                                style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 10pt;">
                                <strong>Amount</strong>
                            </td>
                            <td align="center" style="border-bottom: 1px solid #000;font-size: 10pt;">
                                <strong> Due Date</strong></td>
                        </tr>
                        <tr>
                            <td align="center" style="border-right:1px solid #000;font-size: 10pt;">
                                {{$payment->tran_date->format('d/m/Y')}}</td>
                            <td align="left" style="border-right:1px solid #000;font-size: 10pt;">
                                {{$payment->inv_no}}</td>
                            <td align="center" style="border-right:1px solid #000;font-size: 10pt;">
                                {{number_format($payment->payment_amount, 2)}}</td>
                            <td align="center" style="font-size: 10pt;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right" style="border-right:1px solid #000;border-top:1px solid #000;font-size: 10pt;">
                                Sub Total</td>
                            <td align="center" style="border-right:1px solid #000;border-top:1px solid #000;font-size: 10pt;">
                                {{number_format($payment->payment_amount, 2)}}</td>
                            <td align="center"  style="border-top:1px solid #000;font-size: 10pt;">
                            </td>
                        </tr>
                    </table>
                    {{-- <table  width="100%" border="0" cellpadding="2" cellspacing="0">

                    </table> --}}
                </td>
            </tr>
            <tr>
                <td style="text-align:center">Powered by<a href="http://aarks.net"> AARKS &nbsp;</a>{{now()->format('Y')}}</td>
            </tr>
        </table>
    </body>

</html>
