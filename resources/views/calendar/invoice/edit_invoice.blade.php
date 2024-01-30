@extends('frontend.layout.master')
@section('title', 'Invoice')
@section('content')
    <?php $p = 'invoice';
    $mp = 'sales'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <strong style="color:green; font-size:20px;">Update Invoice:
                                    </strong>
                                </div>
                            </div>
                            <hr>
                            <form
                                action="{{ route('invoice.invupdate', [$invoice->inv_no, $invoice->client_id, $invoice->profession_id]) }}"
                                method="POST" autocomplete="off">
                                @csrf @method('put')
                                @if ($errors->any())
                                    <span class="text-danger">{{ $errors->first() }} </span>
                                @endif
                                <input type="hidden" name="client_id" value="{{ $invoice->client_id }}">
                                <input type="hidden" name="profession_id" value="{{ $invoice->profession_id }}">
                                <input type="hidden" name="tran_id" value="{{ $invoice->tran_id }}">
                                <input type="hidden" name="our_ref" value="{{ $invoice->our_ref }}">
                                <input type="hidden" name="due_date" value="{{ $invoice->due_date }}">
                                <input type="hidden" name="source" value="invoice">
                                <div class="row">
                                    <div class="col-2 form-group">
                                        <label>Customer Name:</label>
                                        <select required class="form-control  form-control-sm" name="customer_card_id">
                                            <option disabled selected value>Select Customer</option>
                                            @foreach ($customers as $customer)
                                                <option {{ $invoice->customer_card_id == $customer->id ? 'selected' : '' }}
                                                    value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 form-group">
                                        <label>Invoice Date: </label>
                                        <input required class="form-control form-control-sm datepicker" type="text"
                                            name="start_date" data-date-format="dd/mm/yyyy"
                                            value="{{ bdDate($invoice->tran_date) }}">
                                    </div>
                                    <div class="col-2 form-group">
                                        <label>Invoice No: </label>
                                        <input class="form-control form-control-sm" readonly type="text" name="inv_no"
                                            value="{{ $invoice->inv_no }}">
                                    </div>
                                    {{-- <div class="col-2 form-group">
                                        <label>Your Reference: </label>
                                        <input class="form-control form-control-sm" type="text" name="your_ref"
                                            value="{{ $invoice->your_ref }}">
                                    </div>
                                    <div class="col-2 form-group">
                                        <label>Our Reference: <button type="button" class="btn btn-warning btn-sm"
                                                style="padding:0 13px; font-size:12px" data-toggle="modal"
                                                data-target="#ourReference">
                                                <i class="far fa-clipboard"></i></button>
                                        </label>
                                        <input class="form-control form-control-sm ourRefInput" type="text"
                                            name="our_ref" placeholder="{{ $invoice->customer->customer_ref }}">

                                    </div> --}}
                                </div>

                                {{-- <div class="row">
                                    <div class="col-sm-11 form-group">
                                        <label>Quote terms and Conditions: </label>
                                        <textarea class="form-control" rows="2" placeholder="Quote terms and Conditions" id="tearms_area"
                                            style="margin-top: 0px;margin-bottom: 0px;height: 145px;resize: none;" name="quote_terms">{{ $invoice->quote_terms }}</textarea>
                                    </div>
                                    <div class="col-sm-1">
                                        <br><br>
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#quote"><i class="far fa-clipboard"></i></button>
                                    </div>
                                </div> --}}
                                <div class="row mx-auto">
                                    {{-- <div class="form-group mx-1">
                                        <label class="">Job Title:<span class="t_red">*</span> </label>
                                        <input class="form-control form-control-sm" type="text" name="job_title" placeholder="Job Title" id="job_title">
                                    </div>
                                    <div class="form-group mx-1" style="width: 250px">
                                        <label>Job Description:<span class="t_red">*</span> <button type="button" class="btn btn-warning btn-sm"
                                                style="padding:0 13px; font-size:12px" data-toggle="modal" data-target="#job"><i
                                                    class="fas fa-sticky-note"></i></button>
                                        </label>
                                        <textarea class="form-control form-control-sm" rows="1" name="job_des" placeholder="Job Description"
                                            id="job_des"></textarea>
                                    </div> --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Account Code: <span class="t_red">*</span></label>
                                            <select name="chart_id" class="form-control form-control-sm chart_id"
                                                id="chart_id" required>
                                                <option disabled selected value>Select Account Code</option>
                                                @foreach ($codes as $code)
                                                    <option value="{{ $code->id }}">{{ $code->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mx-1">
                                        <label>Price:<span class="t_red">*</span> </label>
                                        <input class="form-control form-control-sm" step="any" type="Number"
                                            name="price" id="price" placeholder="Price">
                                    </div>
                                    <div class="form-group mx-1">
                                        <label>Disc %: </label>
                                        <input class="form-control form-control-sm" step="any" type="Number"
                                            name="disc_rate" id="disc_rate" placeholder="Disc %">
                                    </div>
                                    {{-- @if (!in_array('Services', $profession->industryCategories->pluck('name')->toArray())) --}}
                                    {{-- <div class="form-group mx-1" style="width: 120px">
                                            <label>Freight Charge: </label>
                                            <input class="form-control form-control-sm" step="any" type="Number" name="freight_charge"
                                                id="freight_charge">
                                        </div> --}}
                                    {{-- @else
                                        <div class="form-group mx-1" style="width: 120px">
                                            <label>Freight Charge: </label>
                                            <input class="form-control form-control-sm" disabled placeholder="0.00" step="any" type="Number"
                                                id="freight_charge">
                                        </div>
                                    @endif --}}
                                    {{-- <div class="form-group mx-1" style="width: 130px">
                                        <label>Income Account: </label>
                                        <input type="hidden" name="chart_id" id="chart_id">
                                        <input class="form-control form-control-sm" type="text" readonly id="ac_code_name">
                                    </div> --}}

                                    <div class="form-group mx-1" style="width:70px">
                                        <label>Tax: </label>
                                        <input type="text" name="is_tax" step="any" id="is_tax" readonly value
                                            class="form-control form-control-sm">
                                    </div>
                                    <div style="margin-top: 32px;margin-left:7px">
                                        <button class="btn btn-success btn-sm add-item" type="button">Add</button>
                                    </div>
                                </div>
                                <hr>

                                <table class="table table-striped table-bordered table-hover table-sm item-table">
                                    <thead class="text-center" style="font-size: 15px;">
                                        <tr>
                                            <th width="4%">SN</th>
                                            <th width="%">Account Code</th>
                                            <th width="8%">Price </th>
                                            <th width="7%">Disc %</th>
                                            {{-- <th width="11%">Freight Chrg</th> --}}
                                            {{-- <th width="10%">Account</th> --}}
                                            <th width="9%">Tax Rate</th>
                                            <th width="12%">Amount AUD</th>
                                            <th width="3%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total:</th>
                                            <th class="text-right sub-total">$ 0.00 </th>
                                            <th>
                                                <input type="hidden" name="total_amount" id="total_amount">
                                                <input type="hidden" name="gst_amt_subtotal" id="gst_amt_subtotal">
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div></div>
                                <table class="table table-light table-bordered">
                                    <tbody class="invContent">
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col text-center">
                                        <div style="margin-top: 32px;margin-left:7px">
                                            <button class="btn btn-outline-info" type="submit">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

    <!-- Footer Start -->

    <!-- Footer End -->
    @include('frontend.sales.modal')

    <!-- inline scripts related to this page -->
    <script>
        $(document).ready(function() {
            readData();
            jobReadData();
            Quotes();
        });

        //COPY FTOM ONLINE
        $('.add-item').on('click', function() {
            var chart_name = $('#chart_id :selected').text();
            var chart_id = $('#chart_id :selected').val();
            var price = $('#price').val();
            var disc = $('#disc_rate').val() == '' ? 0 : $('#disc_rate').val();
            var tax = $('#is_tax').val();

            if (chart_id == '') {
                alert('Please select account code');
                $('#chart_id').focus();
                return false;
            }
            if (price == '') {
                alert('Please enter price');
                $('#price').focus();
                return false;
            }
            if (tax == '') {
                alert('Income Tax');
                $('#is_tax').focus();
                return false;
            }
            var totalamount = gst_total = price;
            var gst = trate = disc_amount = 0;
            if (disc != '') {
                disc_amount = totalamount * (disc / 100);
                totalamount = gst_total = (totalamount - (totalamount * (disc / 100)));
            }
            if (tax == 'yes') {
                totalamount = parseFloat(totalamount) + (totalamount * 0.1);
                gst = gst_total * 0.1;
                trate = 10.00;
            } else {
                trate = 0.00;
            }
            var pro_name = $('#ac_code_name').val();
            let html = `
                <tr class="trData">
                    <td class="serial"></td>
                    <td>${chart_name}</td>
                    <td>${parseFloat(price).toFixed(2)}</td>
                    <td class="text-right">${parseFloat(disc).toFixed(2)}</td>
                    <td class="text-right">${trate}</td>
                    <td class="text-right">${parseFloat(totalamount).toFixed(2)}</td>
                    <td align="center">
                        <input type="hidden" name="chart_id[]" value="${chart_id}" />
                        <input type="hidden" name="price[]" value="${price}" />
                        <input type="hidden" name="disc_rate[]" value="${disc}" />
                        <input type="hidden" name="disc_amount[]" value="${disc_amount}" />
                        <input type="hidden" name="tax_rate[]" value="${trate}" />
                        <input type="hidden" name="is_tax[]" value="${tax}" />
                        <input type="hidden" name="gst_amt[]" value="${trate}" />
                        <input type="hidden" name="totalamount[]" value="${totalamount}" />
                        <a class="item-delete" href="#"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>`;
            toast('success', 'Code Added');
            $('.item-table tbody').append(html);
            $('#chart_id').val('');
            $('#price').val('');
            $('#disc_rate').val('');
            serialMaintain();
        });

        $('.item-table').on('click', '.item-delete', function(e) {
            var element = $(this).parents('tr');
            element.remove();
            toast('warning', 'item removed!');
            e.preventDefault();
            serialMaintain();
        });

        $(document).on('change', '.chart_id', function() {
            var chart_id = $(this).val();
            $.ajax({
                url: '{{ route('calendar.get_tax') }}',
                method: 'get',
                data: {
                    chart_id: chart_id
                },
                success: function(res) {
                    console.log(chart_id);
                    $('#is_tax').val(res.tax);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                }
            });
        });

        function serialMaintain() {
            var i = 1;
            var subtotal = gst_amt_subtotal = 0;
            $('.serial').each(function(key, element) {
                $(element).html(i);
                var total = $(element).parents('tr').find('input[name="totalamount[]"]').val();
                var gst_amt = $(element).parents('tr').find('input[name="gst_amt[]"]').val();
                subtotal += +parseFloat(total);
                gst_amt_subtotal += +parseFloat(gst_amt);
                i++;
            });
            $('.sub-total').html(subtotal.toFixed(2));
            $('#total_amount').val(subtotal);
            $('#gst_amt_subtotal').val(gst_amt_subtotal);
        };

        function bankamount() {
            $("#payment_amount").removeAttr('disabled', 'disabled')
        }
        $("#invoiceStore").on('submit', function(e) {
            e.preventDefault();
            let data = $(this).serialize();
            let url = $(this).attr('action');
            let method = $(this).attr('method');
            $.ajax({
                url: url,
                method: method,
                data: data,
                success: res => {
                    if (res.status == 200) {
                        $(".trData").remove();
                        $(".sub-total").html('$ 0.00')
                        $("#payment_amount").val('0.00')
                        $("#inv_no").val(res.inv_no.toString().padStart(8, '0'));
                        toast('success', res.message);
                    } else {
                        toast('error', res.message);
                    }
                },
            });
        });

        function deleteData(id) {
            if (confirm('Are You sure?') == true) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route('invoice.delete') }}',
                    method: 'delete',
                    data: {
                        id: id
                    },
                    success: res => {
                        if (res.status == 200) {
                            toast('success', res.message);
                            Quotes();
                        } else if (res.status == 406) {
                            toast('warning', res.message);
                        } else {
                            toast('error', res.message);
                        }
                    },
                });
            }
        };

        function Quotes() {
            $.ajax({
                url: '{{ route('calendar.invoices.edit', [$invoice->inv_no, $invoice->client_id, $invoice->customer_card_id]) }}',
                method: 'get',
                success: res => {
                    if (res.status == 200) {
                        let jobData = '';
                        $.each(res.invoices, function(i, v) {
                            console.log(v.job_des);
                            jobData += `
                                <tr>
                                    <td>
                                    <input type="hidden" name="inv_id[]" value="${v.id}">
                                    <input type="hidden" name="tax_rate[]" value="${v.tax_rate}">
                                    <div class="row mx-auto">
                                        <div class="form-group mx-1">
                                        <label>Account Code: </label>
                                        <input step="any" class="form-control form-control-sm" type="text" name="price[]" value="${v.client_account_code2.name}">
                                        </div>
                                        <div class="form-group mx-1">
                                        <label>Price: </label>
                                        <input step="any" class="form-control form-control-sm" type="Number" name="price[]" value="${v.price}">
                                        </div>
                                        <div class="form-group mx-1">
                                        <label>Disc %: </label>
                                        <input step="any" class="form-control form-control-sm" type="Number" name="disc_rate[]" value="${v.disc_rate}">
                                        </div>
                                        <div class="form-group mx-1">
                                        <label>Income Account: </label>
                                        <input type="hidden" name="chart_id[]" id="chart_id" value="${v.chart_id}">
                                        <input class="form-control form-control-sm" type="text" readonly id="ac_code_name" value="${v.chart_id}">
                                        </div>
                                        <div class="form-group mx-1" style="width:70px">
                                        <label>Tax: </label>
                                        <input type="text" name="is_tax[]" id="is_tax" readonly class="form-control form-control-sm" value="${v.is_tax}">
                                        </div>
                                        <div style="margin-top: 32px; margin-left:7px">
                                        <button class="btn btn-danger btn-sm" type="button" onclick="deleteData(${v.id})">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                        </div>
                                    </div>
                                    </td>
                                </tr>`;

                        });
                        $(".invContent").html(jobData);
                    } else {
                        toast('error', 'No data found!');
                    }
                },
            });
        }
    </script>
@stop
