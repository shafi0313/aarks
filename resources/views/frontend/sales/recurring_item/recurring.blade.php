@extends('frontend.layout.master')
@section('title','Recurring')
@section('content')
<?php $p="rs"; $mp="sales";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <form id="recurringStore" action="{{route('recurring_item.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <strong style="color:green; font-size:20px;">Recurring:
                                    </strong>
                                </div>
                            </div>
                            <hr>
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <input type="hidden" name="source" value="recurring_item">
                            <input type="hidden" name="profession_id" value="{{$profession->id}}">
                            <div class="row">
                                <div class="col-3 form-group">
                                    <label>Customer Name:</label>
                                    <select required class="form-control  form-control-sm" name="customer_card_id">
                                        <option disabled selected value>Select Customer</option>
                                        @foreach ($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <label>Recurring Date: </label>
                                    <input required class="form-control form-control-sm datepicker" type="text" name="start_date" data-date-format="dd/mm/yyyy">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Recurring No: </label>
                                    <input class="form-control form-control-sm" readonly type="text" name="inv_no" id="inv_no" value="{{str_pad(\App\Models\Frontend\Recurring::whereClientId($client->id)->whereProfessionId($profession->id)->max('inv_no')+1,8,'0',STR_PAD_LEFT)}}">
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
                                    <input class="form-control form-control-sm ourRefInput" type="text" name=""
                                        placeholder="Our Reference">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-11 form-group">
                                    <label>Quote terms and Conditions: </label>
                                    <textarea class="form-control" rows="2" placeholder="Quote terms and Conditions"
                                        id="tearms_area"
                                        style="margin-top: 0px;margin-bottom: 0px;height: 145px;resize: none;"
                                        name="quote_terms"></textarea>
                                </div>
                                <div class="col-sm-1">
                                    <br><br>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#quote"><i class="fas fa-sticky-note"></i></button>
                                </div>
                            </div>
                            @include('frontend.sales.item')
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Recurrening:</label>
                                        <select name="recurring" id="recurring" class="form-control">
                                            <option value="1">Dalily</option>
                                            <option value="2">Weekly </option>
                                            <option value="3">Forthnightly</option>
                                            <option value="4">Every four weeks</option>
                                            <option value="5">Every monthly</option>
                                            <option value="6">Every three month</option>
                                            <option value="7">Every yearly</option>
                                        </select>
                                    </div>
                                </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="recur_end" value="radio1" id="radio1">
                                            <label class="form-check-label" for="radio1">
                                                Untill  date
                                            </label>
                                            <input type="text" class="form-control datepicker" name="untill_date" id="untill_date" data-date-format="dd/mm/yyyy" disabled>
                                          </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="recur_end" value="radio2" id="no_date" checked>
                                            <label class="form-check-label" for="no_date">
                                                No end date
                                            </label>
                                            <input type="hidden" name="unlimited" id="unlimited" value="1">
                                          </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <div class="row">
                                                <input class="form-check-input" type="radio" name="recur_end" value="radio3" id="radio3">
                                                <label class="form-check-label" for="radio3">
                                                    Transation more with intilal transacton
                                                </label>
                                                <input type="text" class="form-control" name="recur_tran" id="recur_tran" disabled>
                                            </div>
                                          </div>
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5">
                                    <label class="form-check-label" for="dsa1" >
                                        Mail to:
                                    </label>
                                    <input type="email" class="form-control" placeholder="example@gmail.com" name="mail_to">
                                </div>
                            </div>
                            <br>
                            <div class="" align="right">
                                {{-- <input type="button" class="btn btn-info" value="Preview & Save"> --}}
                                <input type="submit" class="btn btn-success" value="Save">
                                <input type="button" class="btn btn-primary" value="E-mail & Save">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Start -->

<!-- Footer End -->

<!-- Page Content End -->
@include('frontend.sales.modal')
<!-- inline scripts related to this page -->
<script>
    $(document).ready(function() {
    readData();
});

$("#quantity, #rate").on('keyup', function(){
	var quantity      = $('#quantity').val();
	var rate          = $('#rate').val();
	var current_value = rate * quantity;
	$('#amount').val(current_value.toFixed(2));
});

//COPY FTOM ONLINE
$('.add-item').on('click', function() {
    var item_id   = $('#item_id').val();
    var quantity  = $('#quantity').val();
    var amount    = $('#amount').val();
    var rate      = $('#rate').val();
    var freight   = $('#freight_charge').val() == ''?0: $('#freight_charge').val();
    var disc      = $('#disc_rate').val() == ''?0: $('#disc_rate').val();
    var account   = $('#accountName').val();
    var chart_id  = $('#chart_id').val();
    var item_reg_name  = $('#item_reg_name').val();
    var tax       = $('#is_tax').val();
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
    var totalamount  = gst_total = amount;
    var gst          = trate     = disc_amount = 0;
    if (disc != '') {
        disc_amount = totalamount * (disc/100);
        totalamount = gst_total = (totalamount - (totalamount * (disc/100)));
    }
    if (freight != '') {
        totalamount = gst_total = parseFloat(freight) + parseFloat(totalamount);
        console.log(totalamount);
    }
    if (tax == 'yes') {
        totalamount = parseFloat(totalamount) + (totalamount * 0.1);
        gst = gst_total * 0.1;
        trate = '10.00';
    }else{
        trate = '0.00';
    }
    var html = '<tr>';
    html += '<tr class="trData"><td class="serial"></td><td>' + item_name + '</td><td>' + parseFloat(quantity).toFixed(2) + '</td><td>' + parseFloat(rate).toFixed(2) + '</td><td>' + parseFloat(amount).toFixed(2) + '</td><td class="text-right">' + parseFloat(disc).toFixed(2) + '</td><td class="text-right">' + parseFloat(freight).toFixed(2) + '</td><td class="text-right">' + account + '</td><td class="text-right">'+trate+'</td><td class="text-right">' + parseFloat(totalamount).toFixed(2) + '</td><td align="center">';
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
    }else{
    html += '<input type="hidden" name="gst_amt[]" value="0" />';
    }
    html += '<input type="hidden" name="totalamount[]" value="' + totalamount + '" />';
    html += '<input type="hidden" name="chart_id[]" value="' + chart_id + '" />';
    html += '<input type="hidden" name="item_reg_name[]" value="' + item_reg_name + '" />';
    html += '<a class="item-delete" href="#"><i class="fas fa-trash"></i></a></td></tr>';
    toast('success','Added');
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
    toast('warnig','item removed!');
    e.preventDefault();
    serialMaintain();
});

function serialMaintain() {
    var i = 1;
    var subtotal = gst_amt_subtotal=0;
    $('.serial').each(function(key, element) {
        $(element).html(i);
        var total = $(element).parents('tr').find('input[name="totalamount[]"]').val();
        var gst_amt = $(element).parents('tr').find('input[name="gst_amt[]"]').val();
        subtotal += + parseFloat(total);
        gst_amt_subtotal += + parseFloat(gst_amt);
        i++;
    });
    $('.sub-total').html(subtotal.toFixed(2));
    $('#total_amount').val(subtotal);
    $('#gst_amt_subtotal').val(gst_amt_subtotal);
};

function bankamount(){
    $("#payment_amount").removeAttr('disabled','disabled')
}
</script>
<script>
    $("#item_id").on('change', function(){
		var item_id = $(this).val();
		if(item_id == 'new'){
			var url = '{{route("inv_item.index")}}';
			location.replace(url);
		}
	});

	$("#customerid").on('change', function(){
		var item_id = $(this).val();
		if(item_id == 'new'){
			var url = '{{route("add_card_select_activity")}}';
			location.replace(url);
		}
	});

	$("#item_id").on('change', function(){
        var account_name  = $('#item_id option:selected').attr('account_name');
        var account_id    = $('#item_id option:selected').attr('account_id');
        var type          = $('#item_id option:selected').data('item_type');
        var item_reg_name = $('#item_id option:selected').data('item_reg_name');
        var gst_id        = $('#item_id option:selected').attr('gst_id');
        $("#item_reg_name").val(item_reg_name);
        if(type == 2){
            $("#accountName").val(account_name);
            $("#chart_id").val(account_id);
            if(gst_id == 'GST' || gst_id == 'INP' || gst_id == 'CAP'){
                $("#is_tax").val('yes');
            } else {
                $("#is_tax").val('no');
            }
            $("#salesCode").hide();
            $("#accountName").attr('type','text');
        } else {
            $("#salesCode").show();
            $("#accountName").attr('type','hidden');
            $("#is_tax").val('');
        }
    });
	$("#salesCode").on('change', function(){
        var account_name = $('#salesCode option:selected').html();
        var chart_id     = $(this).val();
        var gst_code     = $('#salesCode option:selected').data('gst');
        $("#accountName").val(account_name);
        $("#chart_id").val(chart_id);
        if(gst_code == 'GST' || gst_code == 'INP' || gst_code == 'CAP'){
            $("#is_tax").val('yes');
        } else {
            $("#is_tax").val('no');
        }
    });

	$('#item_id').on('change', function(){
        var sell_price = $('#item_id option:selected').attr('data-sell-price');
        var quntity	   = $('#quantity').val();
        $('#rate').val(sell_price);
        var total = quntity * sell_price;
        $("#amount").val(total);
    });

</script>
<script>
$("#recurringStore").on('submit',function(e){
    e.preventDefault();
    let data = $(this).serialize();
    let url = $(this).attr('action');
    let method = $(this).attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success:res=>{
            if(res.status == 200){
                $(".trData").remove();
                $(".sub-total").html('$ 0.00')
                $("#payment_amount").val('')
                $("#inv_no").val(res.inv_no.toString().padStart(8, '0'));
                toast('success',res.message);
            }else{
                toast('error',res.message);
            }
        },
        error:err=>{
            if(err.status == 500){
                toast('error',err.responseText);
            }
            $.each(err.responseJSON.errors, (i,v)=>{
                toast('error', v);
            })
        }
    });
});

$('#recurringStore input').on('change', (e)=> {
    var radioval = $('input[name=recur_end]:checked', '#recurringStore').val();
    if(radioval == 'radio1'){
        $('#untill_date').removeAttr('disabled', 'disabled');
        $('#recur_tran').attr('disabled', 'disabled');
        $('#unlimited').attr('disabled', 'disabled');
    }else if(radioval == 'radio2'){
        $('#unlimited').removeAttr('disabled', 'disabled');
        $('#recur_tran').attr('disabled', 'disabled');
        $('#untill_date').attr('disabled', 'disabled');
    }else {
        $('#recur_tran').removeAttr('disabled', 'disabled');
        $('#unlimited').attr('disabled', 'disabled');
        $('#untill_date').attr('disabled', 'disabled');
    }
});
</script>
@stop
