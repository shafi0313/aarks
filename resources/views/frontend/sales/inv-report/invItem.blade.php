@extends('frontend.layout.master')
@section('title', 'Invoice Report for ' . $source)
@section('content')
    <?php $p = '';
    $mp = $source; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body row" id="printarea">
                            <div class="col-md-4" align="center">
                                <div class="row" style="padding-top:20px;">
                                    <div class="col-md-12" align="left">
                                        <img src="{{ $client->logo ? asset($client->logo) : asset('frontend/assets/images/logo/focus-icon.png') }}"
                                            class="img-responsive" style="max-width:241px; height:auto;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8" style="padding:5px;">
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
                                <strong style="font-size:30px;">{{ clientName($client) }}</strong><br>
                                <strong>A.B.N : {{ $client->abn_number }}</strong><br>
                                <strong> {{ $client->street_address }}</strong><br>
                                <strong>{{ $client->suburb }}</strong><br>
                                <strong>{{ $client->state }} {{ $client->post_code }}</strong>
                                <strong>Phone: {{ $client->phone }}</strong><br>
                                <strong>E-mail: {{ $client->email }}</strong><br>
                                <strong>Website: <a href="{{ $client->website }}">{{ $client->website }}</a></strong>
                            </div>


                            <div class="col-md-12">
                                <b>Billing Address:</b><u class="text-center"
                                    style="font-size: 25px; font-weight: bold; margin-left:175px">TAX INVOICE</u>
                            </div>

                            <div class="col-md-6" align="left" style="padding-right:20px;">
                                @if ($one_of)
                                    <div style="padding:10px; border:2px solid #666666;">
                                        <div style="font-size:14px; font-weight:800;">Attn:</div>
                                        <span style="font-size:22px;font-weight:800;"> {{ $one_of->name }} </span><br>
                                        <span>{{ $one_of->address }}</span><br>
                                        <span>{{ $one_of->city }}</span><br>
                                        <span>{{ $one_of->state }}</span></span><br>
                                        <span>Phone : {{ $one_of->phone }}</span><br>
                                        <span>E-mail : {{ $one_of->email }}</span>
                                    </div>
                                @else
                                    <div style="padding:10px; border:2px solid #666666;">
                                        <div style="font-size:14px; font-weight:800;">Attn:</div>
                                        <span style="font-size:22px;font-weight:800;"> {{ $customer->name }} </span><br>
                                        <span>{{ $customer->b_address }}</span><br>
                                        <span>{{ $customer->b_city }}</span><br>
                                        <span>{{ $customer->b_state }}</span>,
                                        <span>{{ $customer->b_postcode }}</span><br>
                                        <span>Phone : {{ $customer->phone }}</span><br>
                                        <span>E-mail : {{ $customer->email }}</span> <br>
                                        <strong>Website: <a href="{{ $client->website }}">{{ $client->website }}</a></strong>
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
                                            <td align="center">Terms</td>
                                            <td align="center">Your Ref</td>
                                            <td align="center">Our Ref</td>
                                        </tr>
                                        <tr>
                                            <td align="center">{{ $inv->tran_date->format('d/m/Y') }}</td>
                                            <td align="center">

                                                {{ invoice($inv->inv_no) }}
                                            </td>
                                            <td align="center">{{ $terms }}</td>
                                            <td align="center">{{ $inv->your_ref }}</td>
                                            <td align="center">{{ $inv->our_ref }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12" style="padding-top:0px;">
                                <table class="table table-bordered">
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
                                            <tr>
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
                            <div class="col-md-6">
                                <strong>Terms and Condition: </strong><br>
                                {{ $inv->quote_terms }}
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
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
                                            <td>TOTAL</td>
                                            <td>{{ number_format($invoices->sum('amount'), 2) }}</td>
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
                            {{-- @if ($client->bsb) --}}
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p>
                                                    <strong style="font-size:15px;">{{$one_of ? $one_of->name:$customer->name }}</strong><br>
                                                    Please forward your payment to BSB :
                                                    {{ $client->bsb->bsb_number ?? '' }}&nbsp;&nbsp;Account no
                                                    {{ $client->bsb->account_number ?? '' }} Account Name :
                                                    {{ $client->company ?? $client->first_name . ' ' . $client->last_name }}
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{-- @endif --}}
                            <div class="col-12" align="center">
                                <b>Powered by <a href="https://aarks.com.au">AARKS</a> <a href="https://aarks.net.au">(ADVANCED ACCOUNTING & RECORD KEEPING SOFTWARE)</a></b>
                            </div>
                            <div class="col-12">

                            <div class="d-flex pull-right">
                                <div class="mx-2">

                            <a href="mailto:{{$client->email}}?subject=Invoice%20No%20{{$inv_no}}&body={{route('inv.email_view_report',['item',open_encrypt($inv_no), open_encrypt($client->id)])}}"
                                target="_blank" class="btn btn-outline-info text-dark btn-lg"> <i
                                    class="fa fa-"></i> EMAIL </a>
                                </div>
                                <div class="mx-2">
                            <a href="{{route('inv.report.print', ['item',$inv_no, $client->id, $customer->id])}}"
                                target="_blank" class="btn btn-outline-info text-dark btn-lg"> <i
                                    class="fa fa-print"></i> PRINT</a>

                                </div>
                            </div>
                                {{-- <a href="{{ route('inv.report.print', ['item', $inv_no, $client->id, $customer->id]) }}" target="_blank"
                                    class="btn btn-outline-info text-dark pull-right btn-lg"> <i class="fa fa-print"></i>
                                    PRINT</a> --}}
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-12">
                            <button class="btn btn-success pull-right" onclick="printDiv('printarea')">Print</button>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

    <!-- Footer Start -->

    <!-- Footer End -->


    @include('admin.reports.profit_loss.print_div')
    <!-- inline scripts related to this page -->
    <!-- Data Table -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "lengthMenu": [
                    [50, 100, -1],
                    [50, 100, "All"]
                ],
                "order": [
                    [0, "asc"]
                ]
            });
        });
    </script>
@stop
