@extends('frontend.layout.master')
@section('title', 'Payment List')
@section('content')
    <?php $p = 'peb';
    $mp = 'purchase'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong style="color:green; font-size:20px;">Payment Manage: </strong>
                                </div>
                                <div class="col-md-9">
                                    <div class="row justify-content-end">
                                        <div class="col-3 form-group">
                                            <input class="form-control datepicker" data-date-format="mm/dd/yyyy"
                                                name="" placeholder="From Date">
                                        </div>
                                        <div class="col-3 form-group">
                                            <input class="form-control datepicker" data-date-format="mm/dd/yyyy"
                                                name="" placeholder="To Date">
                                        </div>
                                        <div class="mr-3">
                                            <button class="btn btn-success" type="submit">Show Report</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <hr>
                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th width="10%">Date</th>
                                        <th width="10%">Tran Id</th>
                                        <th width="15%">Customer Name</th>
                                        <th width="9%">Receipt No</th>
                                        <th width="9%">Receipt Amount</th>
                                        <th width="9%" class="no-sort">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach ($payments as $invoice)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td>{{ bdDate($invoice->tran_date) }} </td>
                                            <td>{{ $invoice->tran_id }} </td>
                                            <td>{{ $invoice->customer->name }} </td>
                                            <td class="text-center text-primary">
                                                <a href="{{ route('spayment.report', $invoice->id) }}">
                                                    @if ($invoice->creditor_inv != null)
                                                        {{ invoice($invoice->id, 8, 'BILL') }}
                                                    @else
                                                        {{ invoice($invoice->id, 8, 'OBL') }}
                                                    @endif
                                                </a>
                                            </td>
                                            <td class="text-right text-success">$
                                                {{ number_format($invoice->payment_amount, 2) }} </td>
                                            <td>
                                                <div class="action">
                                                    <a href="{{ route('spayment.report', $invoice->id) }}"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-print fa-lg"></i>
                                                    </a>
                                                    <a href="{{ route('spayment.mailReport', $invoice->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-envelope fa-lg"></i>
                                                    </a>
                                                    <form action="{{ route('spayment.destroy', $invoice->id) }}"
                                                        method="post" style="display: inline">
                                                        @csrf @method('delete')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash-alt fa-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
