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
                        <div class="card-body row">
                            <div class="col-md-4" align="center">
                                <div class="row" style="padding-top:20px;">
                                    <div class="col-md-12" align="left">
                                        <img src="{{ $client->logo ? asset($client->logo) : asset('frontend/assets/images/logo/focus-icon.png') }}"
                                            class="img-responsive" style="max-width:90px; max-height:90px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8" style="padding:5px;">
                                @php
                                    $inv = $invoices->first();
                                    info($inv);
                                    $customer = $invoices->first()->customer;
                                    // info($customer);
                                    $one_of = \App\Models\CustomerTempInfo::whereCustomerCardId($customer->id)
                                        ->whereInvNo($inv->inv_no)
                                        ->first();

                                @endphp
                                <strong style="font-size:25px;">{{ clientName($client) }}</strong><br>
                                <strong>A.B.N : {{ $client->abn_number }}</strong><br>
                                <strong> {{ $client->street_address }}, </strong>
                                <strong>{{ $client->suburb }}</strong><br>
                                <strong>{{ $client->state }} {{ $client->post_code }}</strong>
                                <strong>Phone: {{ $client->phone }}</strong><br>
                                <strong>E-mail: {{ $client->email }}</strong><br>
                                <strong>Website: <a href="{{ $client->website }}">{{ $client->website }}</a></strong><br>
                            </div>

                            <div class="col-md-12 text-center" style="font-size: 25px; font-weight: bold">
                                <u>TAX INVOICE</u>
                            </div>

                            <div class="col-md-8" align="left" style="padding-right:20px;">
                                @if ($one_of)
                                    <div style="padding:10px; border:2px solid #666666;">
                                        <div style="font-size:14px; font-weight:800;">Billing Address:</div>
                                        <span style="font-size:17px;"> {{ $one_of->name }} </span><br>
                                        <span>{{ $one_of->address }}</span><br>
                                        <span>{{ $one_of->city }}</span><br>
                                        <span>{{ $one_of->state }}</span></span><br>
                                        <span>Phone : {{ $one_of->phone }}</span><br>
                                        <span>E-mail : {{ $one_of->email }}</span>
                                    </div>
                                @else
                                    <div style="padding:10px; border:2px solid #666666;">
                                        <div style="font-size:14px; font-weight:800;">Billing Address:</div>
                                        <span style="font-size:17px;"> {{ $customer->name }} </span><br>
                                        <span>{{ $customer->b_address }}</span><br>
                                        <span>{{ $customer->b_city }}</span><br>
                                        <span>{{ $customer->b_state }}</span>,
                                        <span>{{ $customer->b_postcode }}</span><br>
                                        <span>Phone : {{ $customer->phone }}</span><br>
                                        <span>E-mail : {{ $customer->email }}</span>
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
                                            <td align="center">Your Ref</td>
                                            <td align="center">Our Ref</td>
                                            <td align="center">Due Date</td>
                                        </tr>
                                        <tr>
                                            <td align="center">{{ bdDate($inv->tran_date) }}</td>
                                            <td align="center">{{ invoice($inv->inv_no) }}</td>
                                            <td align="center">{{ $inv->your_ref }}</td>
                                            <td align="center">{{ $inv->our_ref }}</td>
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
                                            <td width="8%" align="center">Amount</td>
                                            <td width="2%" align="center">Dis%</td>
                                            <td width="10%" align="center">Total Amount</td>
                                            <td width="2%" align="center">GST Rate</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $i => $invoice)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $invoice->job_title }}</td>
                                                <td>{{ $invoice->job_des }}</td>
                                                <td>{{ number_format($invoice->price, 2) }}</td>
                                                <td>{{ number_format($invoice->disc_rate, 2) }}</td>
                                                <td>{{ number_format($invoice->amount, 2) }}</td>
                                                <td>{{ number_format($invoice->tax_rate, 2) }}</td>
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
                                            <td class="text-right">{{ number_format($invoices->sum('price'), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Freight Charge</td>
                                            <td class="text-right">{{ number_format($invoices->sum('freight_charge'), 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>GST </td>
                                            <td class="text-right">
                                                {{ number_format($invoices->sum('amount') - $invoices->sum('freight_charge') - $invoices->sum('price'), 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td class="text-right">{{ number_format($invoices->sum('amount'), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Paid Amt</td>
                                            <td class="text-right">
                                                {{ number_format($invoices->first()->payments->sum('payment_amount'), 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Due on this Invoice</td>
                                            <td class="text-right">
                                                {{ number_format($invoices->sum('amount') - $invoices->first()->payments->sum('payment_amount'), 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="margin: 0" class="text-center">We appreciate your business with
                                                    us.</p>
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
                                                        <b style="font-size: 18px">{{ $client->bsb->bsb_number }}</b>
                                                        &nbsp;Account number
                                                        <b style="font-size: 18px">{{ $client->bsb->account_number }}</b>
                                                        Account Name :
                                                        {{ $client->company ?? $client->first_name . ' ' . $client->last_name }}
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
                                <div class="d-flex pull-right">
                                    <div class="mx-2">

                                        <a href="mailto:{{ $customer->email }}?subject=Invoice%20No%20{{ $inv_no }}&body={{ route('inv.email_view_report', ['service', open_encrypt($inv_no), open_encrypt($client->id)]) }} "
                                            target="_blank" class="btn btn-outline-info text-dark btn-lg"> <i
                                                class="fa fa-"></i> EMAIL </a>
                                    </div>
                                    <div class="mx-2">
                                        <a href="{{ route('inv.report.print', ['service', $inv_no, $client->id, $customer->id]) }}"
                                            target="_blank" class="btn btn-outline-info text-dark btn-lg"> <i
                                                class="fa fa-print"></i> PRINT</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

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
