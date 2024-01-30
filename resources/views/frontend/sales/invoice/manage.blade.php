@extends('frontend.layout.master')
@section('title', 'Invoice')
@section('content')
    <?php $p = 'invoicep';
    $mp = 'sales'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong style="color:green; font-size:20px;">Edit/Print/Email Invoice: </strong>
                                </div>
                                <div class="col-md-9">
                                    <form action="{{ route('invoice.manage') }}" method="get" autocomplete="off">
                                        <input type="hidden" name="src" value="inv">
                                        <div class="row justify-content-end">
                                            <div class="col-3 form-group">
                                                <input class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                                    value="{{ request()->start_date }}" name="start_date"
                                                    placeholder="From Date">
                                            </div>
                                            <div class="col-3 form-group">
                                                <input class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                                    value="{{ request()->end_date }}" name="end_date" placeholder="To Date">
                                            </div>
                                            <div class="mr-3">
                                                <button class="btn btn-success" type="submit">Show Report</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th>Date</th>
                                        <th>Tran Id</th>
                                        <th>Customer Name</th>
                                        <th>Invoice No</th>
                                        <th>Inv Amount</th>
                                        <th>Due Amount</th>
                                        <th>Paid Amount</th>
                                        <th style="width: 175px" class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices->groupBy(['tran_id']) as $inv_grp)
                                        @foreach ($inv_grp as $invoice)
                                            <tr>
                                                <td>{{ bdDate($invoice->tran_date) }} </td>
                                                <td>{{ $invoice->tran_id }} </td>
                                                <td>{{ $invoice->customer->name }} </td>
                                                <td class="text-center text-primary">
                                                    <a
                                                        href="{{ route('inv.report', ['service', $invoice->inv_no, $client->id, $invoice->customer_card_id]) }}">{{ invoice($invoice->inv_no, 8, 'INV') }}</a>
                                                </td>
                                                <td class="text-info text-right">$
                                                    {{ number_format($inv_grp->first()->totalAmt, 2) }}
                                                </td>
                                                <td class="text-danger text-right">$
                                                    {{ number_format($inv_grp->first()->totalAmt - $invoice->payments->sum('payment_amount'), 2) }}
                                                </td>
                                                <td class="text-success text-right">$
                                                    {{ number_format($invoice->payments->sum('payment_amount'), 2) }} </td>
                                                <td>
                                                    <div class="action">
                                                        <a title="Invoice Print"
                                                            href="{{ route('inv.report', ['service', $invoice->inv_no, $client->id, $invoice->customer_card_id]) }}"
                                                            class="btn btn-success btn-sm">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <a title="Invoice Mail"
                                                            href="{{ route('inv.viewable_mail', ['service', $invoice->inv_no, $client->id, $invoice->customer_card_id]) }}"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="far fa-envelope-open"></i>
                                                        </a>

                                                        @php
                                                            if ($invoice->job_title != '') {
                                                                $route = route('invoice.invedit', [$invoice->inv_no, $client->id, $invoice->customer_card_id]);
                                                            } else {
                                                                $route = route('calendar.invoices.edit', [$invoice->inv_no, $client->id, $invoice->customer_card_id]);
                                                            }
                                                        @endphp
                                                        <a title="Invoice Edit" href="{{ $route }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('invoice.destroy', $invoice->id) }}"
                                                            method="post" style="display: inline">
                                                            @csrf @method('delete')
                                                            <button title="Invoice Delete" type="submit"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                        <a title="Send to ATO" href=""
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fa-solid fa-briefcase"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                // "targets": [0],
                "lengthMenu": [
                    [50, 100, -1],
                    [50, 100, "All"]
                ],
                "order": []
            });
        });
    </script>
@stop
