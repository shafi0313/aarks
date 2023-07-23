@extends('frontend.layout.master')
@section('title', 'Recurring')
@section('content')
    <?php $p = 'rs';
    $mp = 'sales'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <form id="recurringStore" action="{{ route('recurring.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <strong style="color:green; font-size:20px;">Recurring:
                                        </strong>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                <input type="hidden" name="source" value="recurring">
                                <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                                <div class="row">
                                    <div class="col-3 form-group">
                                        <label>Customer Name:</label>
                                        <select required class="form-control  form-control-sm" name="customer_card_id"
                                            id="customer_card_id">
                                            <option disabled selected value>Select Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 form-group">
                                        <label>Recurring Date: </label>
                                        <input required class="form-control form-control-sm datepicker" type="text"
                                            name="start_date" data-date-format="dd/mm/yyyy">
                                    </div>
                                    <div class="col-2 form-group">
                                        <label>Recurring No: </label>
                                        <input class="form-control form-control-sm" readonly type="text" name="inv_no"
                                            id="inv_no"
                                            value="{{ str_pad(\App\Models\Frontend\Recurring::whereClientId($client->id)->whereProfessionId($profession->id)->max('inv_no') + 1,8,'0',STR_PAD_LEFT) }}">
                                    </div>
                                    <div class="col-2 form-group">
                                        <label>Your Reference: </label>
                                        <input class="form-control form-control-sm" type="text" name="your_ref"
                                            placeholder="Your Reference">
                                    </div>
                                    <div class="col-2 form-group">
                                        <label>Our Reference: <button type="button" class="btn btn-warning btn-sm"
                                                style="padding:0 13px; font-size:12px" data-toggle="modal"
                                                data-target="#ourReference">
                                                <i class="fas fa-sticky-note"></i></button>
                                        </label>
                                        <input class="form-control form-control-sm ourRefInput" type="text"
                                            name="" placeholder="Our Reference">

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-11 form-group">
                                        <label>Quote terms and Conditions: </label>
                                        <textarea class="form-control" rows="2" placeholder="Quote terms and Conditions" id="tearms_area"
                                            style="margin-top: 0px;margin-bottom: 0px;height: 145px;resize: none;" name="quote_terms"></textarea>
                                    </div>
                                    <div class="col-sm-1">
                                        <br><br>
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#quote"><i class="fas fa-sticky-note"></i></button>
                                    </div>
                                </div>
                                @include('frontend.sales.job')
                                <div class="invContent"></div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Recurrening:</label>
                                            <select name="recurring" id="recurring" class="form-control">
                                                <option value="1">Daily</option>
                                                <option value="2">Weekly </option>
                                                <option value="3">Forthrightly</option>
                                                <option value="4">Every four weeks</option>
                                                <option value="5">Every monthly</option>
                                                <option value="6">Every three month</option>
                                                <option value="7">Every yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="recur_end" value="radio1"
                                                id="radio1">
                                            <label class="form-check-label" for="radio1">
                                                Until date
                                            </label>
                                            <input type="text" class="form-control datepicker" name="untill_date"
                                                id="untill_date" data-date-format="dd/mm/yyyy" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="recur_end"
                                                value="radio2" id="no_date" checked>
                                            <label class="form-check-label" for="no_date">
                                                No end date
                                            </label>
                                            <input type="hidden" name="unlimited" id="unlimited" value="1">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <div class="row">
                                                <input class="form-check-input" type="radio" name="recur_end"
                                                    value="radio3" id="radio3">
                                                <label class="form-check-label" for="radio3">
                                                    Transaction more with initial transaction
                                                </label>
                                                <input type="text" class="form-control" name="recur_tran"
                                                    id="recur_tran" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <label class="form-check-label" for="dsa1">
                                            Mail to:
                                        </label>
                                        <input type="email" class="form-control" id="mail_to" name="mail_to">
                                    </div>
                                </div>
                                <br>
                                <div class="" align="right">
                                    {{-- <input type="button" class="btn btn-info" value="Preview & Save"> --}}
                                    <input type="submit" id="save" class="btn btn-success" name="recurr_type"
                                        value="Save">
                                    <input type="submit" class="btn btn-primary" name="recurr_type"
                                        value="E-mail & Save">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

    @include('frontend.sales.modal')
    @push('script')
        <script>
            $(document).ready(function() {
                readData();
                jobReadData();


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
                    html += '<tr class="trData"><td class="serial"></td><td>' + job_description + '</td><td>' +
                        parseFloat(
                            price).toFixed(2) + '</td><td class="text-right">' + parseFloat(disc).toFixed(2) +
                        '</td><td class="text-right">' + parseFloat(freight).toFixed(2) +
                        '</td><td class="text-right">' +
                        pro_name + '</td><td class="text-right">' + tax_rate + '</td><td class="text-right">' +
                        parseFloat(
                            totalamount).toFixed(2) + '</td><td align="center">';
                    html += '<input type="hidden" name="job_title[]" value="' + job_title + '" />';
                    html += '<input type="hidden" name="job_des[]" value="' + job_description + '" />';
                    html += '<input type="hidden" name="price[]" value="' + price + '" />';
                    html += '<input type="hidden" name="disc_rate[]" value="' + disc + '" />';
                    html += '<input type="hidden" name="disc_amount[]" value="' + disc_amount + '" />';
                    html += '<input type="hidden" name="freight_charge[]" value="' + freight + '" />';
                    html += '<input type="hidden" name="tax_rate[]" value="' + tax_rate + '" />';
                    html += '<input type="hidden" name="is_tax[]" value="' + tax + '" />';
                    if (tax == 'yes') {
                        html += '<input type="hidden" name="gst_amt[]" value="' + gst + '" />';
                    } else {
                        html += '<input type="hidden" name="gst_amt[]" value="0" />';
                    }
                    html += '<input type="hidden" name="totalamount[]" value="' + totalamount + '" />';
                    html += '<input type="hidden" name="chart_id[]" value="' + chart_id + '" />';
                    html += '<a class="item-delete" href="#"><i class="fas fa-trash"></i></a></td></tr>';
                    toast('success', 'Added');
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
                    toast('warnig', 'item removed!');
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
                // $("#recurringStore").on('submit',
                $("#save").on('click', function(e) {
                    e.preventDefault();
                    let data = $("#recurringStore").serialize();
                    let url = $("#recurringStore").attr('action');
                    let method = $("#recurringStore").attr('method');
                    // console.log(data);
                    // $('input[type=submit]', '#recurringStore').prop('disabled', 'disabled');
                    $.ajax({
                        url: url,
                        method: method,
                        data: data,
                        success: res => {
                            if (res.status == 200) {
                                $(".trData").remove();
                                $(".sub-total").html('$ 0.00')
                                $("#payment_amount").val('')
                                $("#inv_no").val(res.inv_no.toString().padStart(8, '0'));
                                toast('success', res.message);
                                // $('input[type=submit]', '#recurringStore').prop('disabled', false);
                            } else {
                                toast('error', res.message);
                            }
                        },
                        error: err => {
                            if (err.status == 500) {
                                toast('error', err.responseText);
                            }
                            $.each(err.responseJSON.errors, (i, v) => {
                                toast('error', v);
                            })
                        }
                    });
                });

                $('#recurringStore input').on('change', (e) => {
                    var radioval = $('input[name=recur_end]:checked', '#recurringStore').val();
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

                $('#customer_card_id').on('change', function() {
                    let customerCardId = $(this).val();
                    $.ajax({
                        url: "{{ route('recurring.getCustomerInfo') }}",
                        method: 'GET',
                        data: {
                            customer_card_id: customerCardId
                        },
                        success: res => {
                            if (res.status == 200) {
                                $('#mail_to').val(res.customer.email);
                            } else {
                                toast('error', res.message);
                            }
                        },
                        error: err => {
                            $.each(err.responseJSON.errors, (i, v) => {
                                toast('error', v);
                            })
                        }
                    })
                })
            });
        </script>
    @endpush
@stop
