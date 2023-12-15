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
                                    <strong style="color:green; font-size:20px;">Update Recurrings:
                                    </strong>
                                </div>
                            </div>
                            <hr>
                            <form action="{{ route('recurring_item.update', $invoice->inv_no) }}" method="POST"
                                id="recurringUpdate" autocomplete="off">
                                @csrf @method('put')
                                @if ($errors->any())
                                    <span class="text-danger">{{ $errors->first() }} </span>
                                @endif
                                <input type="hidden" name="client_id" value="{{ $invoice->client_id }}">
                                <input type="hidden" name="tran_id" value="{{ $invoice->tran_id }}">
                                <input type="hidden" name="profession_id" value="{{ $invoice->profession_id }}">
                                <input type="hidden" name="source" value="recurring_item">
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
                                        <input required class="form-control form-control-sm item_date" type="text"
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
                                @include('frontend.sales.invoice_item.item')
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
                                                        readonly
                                                        value="{{ $invoice->untill_date ? $invoice->untill_date->format('d/m/Y') : '' }}"
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
    @include('frontend.sales.invoice_item.modal')

    <!-- inline scripts related to this page -->
    <script>
        $(document).ready(function() {
            readData();
            Quotes();
        });
        $('.item_date').datepicker({
            format: 'dd/mm/yyyy',
            startDate: '{{ now()->format('d/m/Y') }}',
            autoclose: "close",
            todayHighlight: true,
            clearBtn: true,

        });

        $("#quantity, #rate").on('keyup', function() {
            var quantity = $('#quantity').val();
            var rate = $('#rate').val();
            var current_value = rate * quantity;
            $('#amount').val(current_value.toFixed(2));
        });

        //COPY FTOM ONLINE
        $('.add-item').on('click', function() {
            var item_id = $('#item_id').val();
            var quantity = $('#quantity').val();
            var amount = $('#amount').val();
            var rate = $('#rate').val();
            var freight = $('#freight_charge').val() == '' ? 0 : $('#freight_charge').val();
            var disc = $('#disc_rate').val() == '' ? 0 : $('#disc_rate').val();
            var account = $('#accountName').val();
            var chart_id = $('#chart_id').val();
            var item_reg_name = $('#item_reg_name').val();
            var tax = $('#is_tax').val();
            var item_name = $('#item_id option:selected').html();

            if (item_id == '') {
                toast('warning', 'Please Seletec An Item');
                $('#item_id').focus();
                return false;
            }
            if (quantity == '') {
                toast('warning', 'Please enter quantity');
                $('#quantity').focus();
                return false;
            }
            if (amount == '') {
                toast('warning', 'Please enter amount');
                $('#amount').focus();
                return false;
            }
            if (account == '') {
                toast('warning', 'Income Account Tax');
                $('#accountName').focus();
                return false;
            }
            if (tax == '') {
                toast('warning', 'Income Tax');
                $('#is_tax').focus();
                return false;
            }
            var totalamount = gst_total = amount;
            var gst = trate = disc_amount = 0;
            if (disc != '') {
                disc_amount = totalamount * (disc / 100);
                totalamount = gst_total = (totalamount - (totalamount * (disc / 100)));
            }
            if (freight != '') {
                totalamount = gst_total = parseFloat(freight) + parseFloat(totalamount);
                console.log(totalamount);
            }
            if (tax == 'yes') {
                totalamount = parseFloat(totalamount) + (totalamount * 0.1);
                gst = gst_total * 0.1;
                trate = '10.00';
            } else {
                trate = '0.00';
            }
            var html = '<tr>';
            html += '<tr class="trData"><td class="serial"></td><td>' + item_name + '</td><td>' + parseFloat(
                    quantity).toFixed(2) + '</td><td>' + parseFloat(rate).toFixed(2) + '</td><td>' + parseFloat(
                    amount).toFixed(2) + '</td><td class="text-right">' + parseFloat(disc).toFixed(2) +
                '</td><td class="text-right">' + parseFloat(freight).toFixed(2) + '</td><td class="text-right">' +
                account + '</td><td class="text-right">' + trate + '</td><td class="text-right">' + parseFloat(
                    totalamount).toFixed(2) + '</td><td align="center">';
            html += '<input type="hidden" name="inv_id[]">';
            html += '<input type="hidden" name="item_id[]" value="' + item_id + '" />';
            html += '<input type="hidden" name="item_name[]" value="' + item_name + '" />';
            html += '<input type="hidden" name="amount[]" value="' + amount + '" />';
            html += '<input type="hidden" name="quantity[]" value="' + quantity + '" />';
            html += '<input type="hidden" name="rate[]" value="' + rate + '" />';
            html += '<input type="hidden" name="disc_rate[]" value="' + disc + '" />';
            html += '<input type="hidden" name="disc_amount[]" value="' + disc_amount + '" />';
            html += '<input type="hidden" name="freight_charge[]" value="' + freight + '" />';
            // html += '<input type="hidden" name="account[]" value="' + account + '" />';
            html += '<input type="hidden" name="is_tax[]" value="' + tax + '" />';
            html += '<input type="hidden" name="tax_rate[]" value="' + trate + '" />';
            if (tax == 'yes') {
                html += '<input type="hidden" name="gst_amt[]" value="' + gst + '" />';
            } else {
                html += '<input type="hidden" name="gst_amt[]" value="0" />';
            }
            html += '<input type="hidden" name="totalamount[]" value="' + totalamount + '" />';
            html += '<input type="hidden" name="chart_id[]" value="' + chart_id + '" />';
            html += '<input type="hidden" name="item_reg_name[]" value="' + item_reg_name + '" />';
            html += '<a class="item-delete" href="#"><i class="fas fa-trash"></i></a></td></tr>';
            toast('success', 'Added');
            $('.item-table tbody').append(html);
            $('#item_id').val('');
            $('#job_des').val('');
            $('#amount').val('');
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
    </script>
    <script>
        $("#item_id").on('change', function() {
            var item_id = $(this).val();
            if (item_id == 'new') {
                var url = '{{ route('inv_item.index') }}';
                location.replace(url);
            }
        });

        $("#customerid").on('change', function() {
            var item_id = $(this).val();
            if (item_id == 'new') {
                var url = '{{ route('add_card_select_activity') }}';
                location.replace(url);
            }
        });
        $("#item_id").on('change', function() {
            var account_name = $('#item_id option:selected').attr('account_name');
            var account_id = $('#item_id option:selected').attr('account_id');
            var type = $('#item_id option:selected').data('item_type');
            var item_reg_name = $('#item_id option:selected').data('item_reg_name');
            var gst_id = $('#item_id option:selected').attr('gst_id');
            $("#item_reg_name").val(item_reg_name);
            if (type == 2) {
                $("#accountName").val(account_name);
                $("#chart_id").val(account_id);
                if (gst_id == 'GST' || gst_id == 'INP' || gst_id == 'CAP') {
                    $("#is_tax").val('yes');
                } else {
                    $("#is_tax").val('no');
                }
                $("#salesCode").hide();
                $("#accountName").attr('type', 'text');
            } else {
                $("#salesCode").show();
                $("#accountName").attr('type', 'hidden');
                $("#is_tax").val('');
            }
        });
        $("#salesCode").on('change', function() {
            var account_name = $('#salesCode option:selected').html();
            var chart_id = $(this).val();
            var gst_code = $('#salesCode option:selected').data('gst');
            $("#accountName").val(account_name);
            $("#chart_id").val(chart_id);
            if (gst_code == 'GST' || gst_code == 'INP' || gst_code == 'CAP') {
                $("#is_tax").val('yes');
            } else {
                $("#is_tax").val('no');
            }
        });

        $('#item_id').on('change', function() {
            var sell_price = $('#item_id option:selected').attr('data-sell-price');
            var quntity = $('#quantity').val();
            $('#rate').val(sell_price);
            var total = quntity * sell_price;
            $("#amount").val(total);
        });
    </script>
    <script>
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
                url: '{{ route('recurring_item.edit', $invoice->inv_no) }}',
                method: 'get',
                success: res => {
                    if (res.status == 200) {
                        let jobData = '';
                        $.each(res.invoices, function(i, v) {
                            jobData += '<input type="hidden" name="inv_id[]" value="' + v.id +
                                '"><input type="hidden" name="item_id[]" value="' + v.item_no +
                                '"><input type="hidden" name="disc_amount[]" value="' + v.disc_amount +
                                '"><input type="hidden" name="item_reg_name[]" value="' + v.item
                                .item_name + ' ' + v.item.item_number +
                                '" /><div class="row mx-auto"><div class="form-group mx-1" style="width: 190px"><label class="">Item Name:<span class="t_red">*</span> </label><select name="item_name[]" class="form-control"><option value="' +
                                v.item_name + '">' + v.item_name +
                                '</option></select></div><div class="form-group mx-1" style="width: 100px"><label>Quantity</label><input type="number" name="quantity[]" oninput="this.value = this.value.replace(/[^\\d]/g,\'\');" class="form-control editQuantity' +
                                v.id + '" onkeyup="qur(' + v.id + ')" value="' + v.item_quantity +
                                '"></div><div class="form-group mx-1" style="width: 100px"><label style="font-size: 15px">Rate(Ex GST)</label><input type="number" name="rate[]" oninput="this.value = this.value.replace(/[^\\d]/g,\'\');" class="form-control editRate' +
                                v.id + '" onkeyup="qur(' + v.id + ')" value="' + (v.price / v
                                    .item_quantity) +
                                '"></div><div class="form-group mx-1" style="width: 100px"><label>Amount</label><input readonly type="number" name="amount[]" class="form-control editAmount' +
                                v.id + '" value="' + v.price +
                                '"></div><div class="form-group mx-1" style="width: 100px"><label>Disc %: </label><input class="form-control" type="Number" name="disc_rate[]" placeholder="Disc %" oninput="this.value = this.value.replace(/[^\\d]/g,\'\');" value="' +
                                v.disc_rate +
                                '"></div><div class="form-group mx-1" style="width: 100px"><label style="font-size: 14px">Freight Charge: </label><input class="form-control" type="Number" name="freight_charge[]" oninput="this.value = this.value.replace(/[^\\d]/g,\'\');" value="' +
                                v.freight_charge +
                                '"></div><div class="form-group mx-1" style="width: 150px"><label>Account Code: </label><input type="text" readonly class="form-control" name="chart_id[]" value="' +
                                v.chart_id +
                                '"></div><div class="form-group mx-1" style="width:100px"><label>Tax: </label><input type="text" name="is_tax[]" readonly class="form-control" value="' +
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

        function qur(id) {
            let rate = $(".editRate" + id).val();
            let quantity = $(".editQuantity" + id).val();
            var current_value = rate * quantity;
            $('.editAmount' + id).val(current_value.toFixed(2));
        }


        $('#recurringUpdate input').on('change', (e) => {
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
    </script>
@stop
