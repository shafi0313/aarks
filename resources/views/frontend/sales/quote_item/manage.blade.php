@extends('frontend.layout.master')
@section('title', 'Manage Order')
@section('content')
    <?php $p = 'cquote_itemp';
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
                                    <strong style="color:green; font-size:20px;">Edit/Print/Email Order</strong>
                                </div>
                                <div class="col-md-9">
                                    <form action="{{ route('edit.print.filter') }}" method="get" autocomplete="off">
                                        <input type="hidden" name="src" value="quote_item">
                                        <div class="row justify-content-end">
                                            <div class="col-3 form-group">
                                                <input class="form-control datepicker" value="{{ request()->start_date }}"
                                                    data-date-format="dd/mm/yyyy" name="start_date" placeholder="From Date">
                                            </div>
                                            <div class="col-3 form-group">
                                                <input class="form-control datepicker" value="{{ request()->end_date }}"
                                                    data-date-format="dd/mm/yyyy" name="end_date" placeholder="To Date">
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
                                        <th>SL</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Customer Name</th>
                                        <th>Order No</th>
                                        <th>Order Amount</th>
                                        <th>Due Amount</th>
                                        <th>Paid Amount</th>
                                        <th width="120px" class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach ($quotes->groupBy('inv_no') as $quote)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td>{{ $quote->first()->start_date->format('d/m/Y') }} </td>
                                            <td>{{ $quote->first()->end_date->format('d/m/Y') }} </td>
                                            <td>{{ $quote->first()->customer->name }} </td>
                                            <td class="text-center text-primary">SIV#{{ $quote->first()->inv_no }} </td>
                                            <td class="text-right text-info">$ {{ number_format($quote->sum('amount'), 2) }}
                                            </td>
                                            <td class="text-right text-danger">$
                                                {{ number_format($quote->sum('amount') - $quote->first()->payment_amount, 2) }}
                                            </td>
                                            <td class="text-right text-success">$
                                                {{ number_format($quote->first()->payment_amount, 2) }} </td>
                                            <td>
                                                <div class="action">
                                                    <a title="Quote Print"
                                                        href="{{ route('quote.show', ['item', $quote->first()->inv_no, $client->id, $quote->first()->customer_card_id]) }}"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <a title="Item Edit"
                                                        href="{{ route('quote_item.edit', [$quote->first()->profession_id, $quote->first()->customer_card_id, $quote->first()->inv_no]) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('quote_item.destroy', [$quote->first()->id, $quote->first()->profession_id, $quote->first()->customer_card_id, $quote->first()->inv_no]) }}"
                                                        method="post" style="display: inline">
                                                        @csrf @method('delete')
                                                        <button title="Item Delete" type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash-alt"></i>
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
