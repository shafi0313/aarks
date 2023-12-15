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
                            <form action="{{ route('recurring.update', $invoice->inv_no) }}" method="POST" autocomplete="off"
                                id="recurringUpdate">
                                @csrf @method('put')
                                @if ($errors->any())
                                    <span class="text-danger">{{ $errors->first() }} </span>
                                @endif
                                <input type="hidden" name="client_id" value="{{ $invoice->client_id }}">
                                <input type="hidden" name="profession_id" value="{{ $invoice->profession_id }}">
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
                                    <div class="col-2 form-group">
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

                                    </div>
                                </div>

                                <div class="row">
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
                                </div>
                                @include('frontend.sales.job')
                                <div class="invContent"></div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Recurrening:</label>
                                                    <select name="recurring" id="recurring" class="form-control">
                                                        <option {{ $invoice->recurring == 1 ? 'selected' : '' }} value="1">
                                                            Dalily
                                                        </option>
                                                        <option {{ $invoice->recurring == 2 ? 'selected' : '' }} value="2">
                                                            Weekly
                                                        </option>
                                                        <option {{ $invoice->recurring == 3 ? 'selected' : '' }} value="3">
                                                            Forthnightly</option>
                                                        <option {{ $invoice->recurring == 4 ? 'selected' : '' }} value="4">
                                                            Every
                                                            four weeks</option>
                                                        <option {{ $invoice->recurring == 5 ? 'selected' : '' }} value="5">
                                                            Every
                                                            monthly</option>
                                                        <option {{ $invoice->recurring == 6 ? 'selected' : '' }} value="6">
                                                            Every
                                                            three month</option>
                                                        <option {{ $invoice->recurring == 7 ? 'selected' : '' }} value="7">
                                                            Every
                                                            yearly</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="recur_end"
                                                        {{ $invoice->untill_date ? 'checked' : '' }} value="radio1"
                                                        id="radio1">
                                                    <label class="form-check-label" for="radio1">
                                                        Untill date
                                                    </label>
                                                    <input type="text" class="form-control item_date" name="untill_date"
                                                        value="{{ $invoice->untill_date != null ? $invoice->untill_date->format('d/m/Y') : '' }}"
                                                        id="untill_date" data-date-format="dd/mm/yyyy"
                                                        {{ $invoice->untill_date ? '' : 'disabled' }}>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="recur_end"
                                                        {{ $invoice->unlimited ? 'checked' : '' }} value="radio2"
                                                        id="no_date">
                                                    <label class="form-check-label" for="no_date">
                                                        No end date
                                                    </label>
                                                    <input type="hidden" name="unlimited" id="unlimited" value="1"
                                                        {{ $invoice->unlimited == 1 ? '' : 'disabled' }}>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <div class="row">
                                                        <input class="form-check-input" type="radio" name="recur_end"
                                                            {{ $invoice->recur_tran != 0 ? 'checked' : '' }} value="radio3"
                                                            id="radio3">
                                                        <label class="form-check-label" for="radio3">
                                                            Transation more with intilal transacton
                                                        </label>
                                                        <input type="text" class="form-control" name="recur_tran"
                                                            value="{{ $invoice->recur_tran != 0 ? $invoice->recur_tran : '' }}"
                                                            {{ $invoice->recur_tran == 0 ? 'disabled' : '' }} id="recur_tran">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <label class="form-check-label" for="dsa1">
                                                    Mail to:
                                                </label>
                                                <input type="email" class="form-control"
                                                    value="{{ $invoice->mail_to }}" name="mail_to">
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
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
            var job_title = $('#job_title').val();
            var job_description = $('#job_des').val();
            var price = $('#price').val();
            var disc = $('#disc_rate').val() == '' ? 0 : $('#disc_rate').val();
            var freight = $('#freight_charge').val() == '' ? 0 : $('#freight_charge').val();
            var account = $('#ac_code_name').val();
            var chart_id = $('#chart_id').val();
            var tax = $('#is_tax').val();

            if (job_title == '') {
                toast('warning', 'Please enter job title');
                $('#job_title').focus();
                return false;
            }
            if (job_description == '') {
                toast('warning', 'Please enter job description');
                $('#job_des').focus();
                return false;
            }
            if (price == '') {
                toast('warning', 'Please enter price');
                $('#price').focus();
                return false;
            }
            if (account == '') {
                toast('warning', 'Income Account Tax');
                $('#ac_code_name').focus();
                return false;
            }
            if (tax == '') {
                toast('warning', 'Income Tax');
                $('#is_tax').focus();
                return false;
            }
            var totalamount = gst_total = price;
            var gst = tax_rate = disc_amount = 0;
            if (disc != '') {
                disc_amount = totalamount * (disc / 100);
                totalamount = gst_total = (totalamount - (totalamount * (disc / 100)));
            }
            if (freight != '') {
                totalamount = gst_total = parseFloat(freight) + parseFloat(totalamount);
            }
            if (tax == 'yes') {
                totalamount = parseFloat(totalamount) + (totalamount * 0.1);
                gst = gst_total * 0.1;
                tax_rate = '10.00';
            } else {
                tax_rate = '0.00'
            }
            var pro_name = $('#ac_code_name').val();
            var html = '<tr>';
            html += '<tr class="trData"><td class="serial"></td><td>' + job_description + '</td><td>' + parseFloat(
                    price).toFixed(2) + '</td><td class="text-right">' + parseFloat(disc).toFixed(2) +
                '</td><td class="text-right">' + parseFloat(freight).toFixed(2) + '</td><td class="text-right">' +
                pro_name + '</td><td class="text-right">' + tax_rate + '</td><td class="text-right">' + parseFloat(
                    totalamount).toFixed(2) + '</td><td align="center">';
            html += '<input type="hidden" name="job_title[]" value="' + job_title + '" />';
            html += '<input type="hidden" name="inv_id[]">';
            html += '<input type="hidden" name="job_des[]" value="' + job_description + '" />';
            html += '<input type="hidden" name="tax_rate[]" value="' + tax_rate + '" />';
            html += '<input type="hidden" name="price[]" value="' + price + '" />';
            html += '<input type="hidden" name="disc_rate[]" value="' + disc + '" />';
            html += '<input type="hidden" name="disc_amount[]" value="' + disc_amount + '" />';
            html += '<input type="hidden" name="freight_charge[]" value="' + freight + '" />';
            // html += '<input type="hidden" name="account[]" value="' + account + '" />';
            html += '<input type="hidden" name="is_tax[]" value="' + tax + '" />';
            if (tax == 'yes') {
                html += '<input type="hidden" name="gst_amt[]" value="' + gst + '" />';
            } else {
                html += '<input type="hidden" name="gst_amt[]" value="0" />';
            }
            html += '<input type="hidden" name="totalamount[]" value="' + totalamount + '" />';
            html += '<input type="hidden" name="chart_id[]" value="' + chart_id + '" />';
            html += '<a class="item-delete" href="#"><i class="fas fa-trash"></i></a></td></tr>';
            toast('success', 'Code Added');
            $('.item-table tbody').append(html);
            $('#job_title').val('');
            $('#job_des').val('');
            $('#price').val('');
            $('#disc_rate').val('');
            $('#freight_charge').val('');
            serialMaintain();
        });

        $('.item-table').on('click', '.item-delete', function(e) {
            var element = $(this).parents('tr');
            element.remove();
            toast('warning', 'item removed!');
            e.preventDefault();
            serialMaintain();
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

        function deleteData(id) {
            if (confirm('Are You sure?') == true) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route('recurring_item.delete') }}',
                    method: 'delete',
                    data: {
                        id: id
                    },
                    success: res => {
                        if (res.status == 200) {
                            toast('success', res.message);
                            Quotes();
                        } else {
                            toast('error', res.message);
                        }
                    },
                });
            }
        };

        function Quotes() {
            $.ajax({
                url: '{{ route('recurring.edit', $invoice->inv_no) }}',
                method: 'get',
                success: res => {
                    if (res.status == 200) {
                        let jobData = '';
                        $.each(res.invoices, function(i, v) {
                            jobData += '<input type="hidden" name="tax_rate[]" value="' + v.tax_rate +
                                '"><input type="hidden" name="inv_id[]" value="' + v.id +
                                '"><div class="row mx-auto"><div class="form-group mx-1"><label class="">Job Title: </label><input class="form-control form-control-sm" type="text" name="job_title[]" placeholder="Job Title" id="job_title"value="' +
                                v.job_title +
                                '"></div><div class="form-group mx-1" style="width: 250px"><label>Job Description: <button type="button" class="btn btn-warning btn-sm"style="padding:0 13px; font-size:12px" data-toggle="modal" data-target="#job"><i class="far fa-clipboard"></i></button></label> <textarea class="form-control form-control-sm" rows="1" name="job_des[]" placeholder="Job Description" id="job_des">' +
                                v.job_des +
                                '</textarea> </div><div class="form-group mx-1" style="width: 100px"><label>Price: </label><input class="form-control form-control-sm" type="Number" name="price[]" value="' +
                                v.price +
                                '"></div><div class="form-group mx-1" style="width: 100px"><label>Disc %: </label><input class="form-control form-control-sm" type="Number" name="disc_rate[]" value="' +
                                v.disc_rate +
                                '"></div><div class="form-group mx-1" style="width: 120px"><label>Freight Charge: </label><input class="form-control form-control-sm" type="Number" name="freight_charge[]"value="' +
                                v.freight_charge +
                                '"></div><div class="form-group mx-1" style="width: 130px"><label>Income Account: </label><input type="hidden" name="chart_id[]" id="chart_id" value="' +
                                v.chart_id +
                                '"><input class="form-control form-control-sm" type="text" readonly id="`ac_code_name" value="' +
                                v.chart_id +
                                '"></div><div class="form-group mx-1" style="width:70px"><label>Tax: </label><input type="text" name="is_tax[]" id="is_tax" readonly class="form-control form-control-sm" value="' +
                                v.is_tax +
                                '"></div><div style="margin-top: 32px;margin-left:7px"><button class="btn btn-danger btn-sm" type="button" onclick="deleteData(' +
                                v.id + ')"><i class="far fa-trash-alt"></i></button></div></div>';
                        });
                        $(".invContent").html(jobData);
                    } else {
                        toast('error', 'No Data found!');
                    }
                },
            });
        }


        $('#recurringUpdate input').on('change', function(e) {
            var radioval = $('input[name=recur_end]:checked', '#recurringUpdate').val();
            if (radioval == 'radio1') {
                $('#untill_date').removeAttr('disabled', 'disabled');
                $('#recur_tran').attr('disabled', 'disabled');
                $('#unlimited').attr('disabled', 'disabled');
            } else if (radioval == 'radio2') {
                $('#unlimited').removeAttr('disabled', 'disabled');
                $('#recur_tran').attr('disabled', 'disabled');
                $('#untill_date').attr('disabled', 'disabled');
            } else {
                $('#recur_tran').removeAttr('disabled', 'disabled');
                $('#unlimited').attr('disabled', 'disabled');
                $('#untill_date').attr('disabled', 'disabled');
            }
        });

        $('.item_date').datepicker({
            format: 'dd/mm/yyyy',
            startDate: '{{ now()->format('d/m/Y') }}',
            autoclose: "close",
            todayHighlight: true,
            clearBtn: true,
        });
    </script>
@stop
