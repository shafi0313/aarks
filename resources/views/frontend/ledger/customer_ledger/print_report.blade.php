<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>CUSTOMER LEDGERS</title>
        @include('frontend.print-css')
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div id="resultshow" style="padding-top:10px;">
                        <div align="center">
                            <h2 style="color:#666666;"><u> {{$cId->name}}</u></h2>
                            <strong>Customer Ledger</strong>
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
                                        <th style="text-align:center">Invoice/Repect No</th>
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
                                            {{Carbon\Carbon::parse($payment['tran_date'])->format('d/m/Y')}}</td>
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
                                        <td colspan="4" style="text-align:right">Outstanding</td>
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
    </body>

</html>
