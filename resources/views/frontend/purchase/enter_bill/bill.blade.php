@extends('frontend.layout.master')
@section('title','Enter BILL')
@section('content')
<?php $p="eb"; $mp="purchase"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <form id="serviceStore" action="{{route('service_bill.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <strong style="color:green; font-size:20px;">Create Bill:
                                    </strong>
                                </div>
                            </div>
                            <hr>
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <input type="hidden" name="source" value="PBP">
                            <input type="hidden" name="profession_id" value="{{$profession->id}}">
                            <div class="row">
                                <div class="col-3 form-group">
                                    <label>Supplier Name: <span class="t_red">*</span></label>
                                    <select required class="form-control  form-control-sm" name="customer_card_id">
                                        <option disabled selected value>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <label>Bill/Invoice Date: <span class="t_red">*</span> </label>
                                    <input required class="form-control form-control-sm datepicker" type="text"
                                        name="start_date" data-date-format="dd/mm/yyyy">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Bill/Invoice No: </label>
                                    <input class="form-control form-control-sm" readonly type="text" name="inv_no" id="inv_no" value="{{str_pad(\App\Models\Frontend\Creditor::whereClientId($client->id)->whereProfessionId($profession->id)->max('inv_no')+1,8,'0',STR_PAD_LEFT)}}">
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
                            @include('frontend.purchase.job')
                            <div class="row justify-content-end">
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <label for="" class="col-sm-5 col-form-label">Payment
                                            received:</label>
                                        <div class="col">
                                            <select class="form-control" name="bank_account" id="bank_account"
                                                onchange="bankamount()">
                                                <option value="" selected>Select Bank Account</option>
                                                @foreach ($liquid_codes as $liquid_code)
                                                <option value="{{$liquid_code->code}} "> {{$liquid_code->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="" class="col-sm-3 col-form-label">Amount:</label>
                                        <div class="col-sm-8">
                                            <input type="number" disabled min="0" class="form-control"
                                                placeholder="0.00" id="payment_amount" step="any" name="payment_amount">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="" align="right">
                                <input type="submit" class="btn btn-info" value="Preview & Save">
                                <input type="submit" class="btn btn-success" id="save" value="Save">
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
@include('frontend.purchase.modal')

<!-- inline scripts related to this page -->
<script>
    $(document).ready(function() {
        readData();
        jobReadData();
    });

//COPY FTOM ONLINE
$('.add-item').on('click', function() {
    var job_title       = $('#job_title').val();
    var job_description = $('#job_des').val();
    var price           = $('#price').val();
    var disc            = $('#disc_rate').val()==''?0:$('#disc_rate').val();
    var freight         = $('#freight_charge').val()==''?0:$('#freight_charge').val();
    var account         = $('#ac_code_name').val();
    var chart_id        = $('#chart_id').val();
    var tax             = $('#is_tax').val();

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
    var gst = trate = disc_amount = 0;
    if (disc != '') {
        disc_amount = totalamount * (disc/100);
        totalamount = gst_total = (totalamount - (totalamount * (disc/100)));
    }
    if (freight != '') {
        totalamount = gst_total = parseFloat(freight) + parseFloat(totalamount);
    }
    if (tax == 'yes') {
        totalamount = parseFloat(totalamount) + (totalamount * 0.1);
        gst = gst_total * 0.1;
        trate = '10.00';
    }else{
        trate = '0.00'
    }
    var pro_name = $('#ac_code_name').val();
    var html = '<tr>';
    html += '<tr class="trData"><td class="serial"></td><td>' + job_description + '</td><td>' + parseFloat(price).toFixed(2) + '</td><td class="text-right">' + parseFloat(disc).toFixed(2) + '</td><td class="text-right">' + parseFloat(freight).toFixed(2) + '</td><td class="text-right">' + pro_name + '</td><td class="text-right">'+trate+'</td><td class="text-right">' + parseFloat(totalamount).toFixed(2) + '</td><td align="center">';
    html += '<input type="hidden" name="job_title[]" value="' + job_title + '" />';
    html += '<input type="hidden" name="job_des[]" value="' + job_description + '" />';
    html += '<input type="hidden" name="price[]" value="' + price + '" />';
    html += '<input type="hidden" name="disc_rate[]" value="' + disc + '" />';
    html += '<input type="hidden" name="disc_amount[]" value="' + disc_amount + '" />';
    html += '<input type="hidden" name="freight_charge[]" value="' + freight + '" />';
    // html += '<input type="hidden" name="account[]" value="' + account + '" />';
    html += '<input type="hidden" name="tax_rate[]" value="' + trate + '" />';
    html += '<input type="hidden" name="is_tax[]" value="' + tax + '" />';
    if (tax == 'yes') {
    html += '<input type="hidden" name="gst_amt[]" value="' + gst + '" />';
    }else{
    html += '<input type="hidden" name="gst_amt[]" value="0" />';
    }
    html += '<input type="hidden" name="totalamount[]" value="' + totalamount + '" />';
    html += '<input type="hidden" name="chart_id[]" value="' + chart_id + '" />';
    html += '<a class="item-delete" href="#"><i class="fas fa-trash"></i></a></td></tr>';
    toast('success','Added');
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
$("#save").on('click',function(e){
    e.preventDefault();
    let data   = $('#serviceStore').serialize();
    let url    = $('#serviceStore').attr('action');
    let method = $('#serviceStore').attr('method');
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


</script>
@stop
