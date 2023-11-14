@extends('frontend.layout.master')
@section('title','Update Service Order')
@section('content')
<?php $p="so"; $mp="purchase"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="color:green; font-size:20px;">Update Service Order:
                                </strong>
                            </div>
                        </div>
                        <hr>
                        <form action="{{route('service_order.update',$service->inv_no)}}" method="POST" autocomplete="off">
                            @csrf @method('put')
                            @if ($errors->any())
                            <span class="text-danger">{{$errors->first()}} </span>
                            @endif
                            <input type="hidden" name="client_id" value="{{$service->client_id}}">
                            <input type="hidden" name="profession_id" value="{{$service->profession_id}}">
                            <div class="row">
                                <div class="col-2 form-group">
                                    <label>Customer Name:</label>
                                    <select required class="form-control  form-control-sm" name="customer_card_id">
                                        <option disabled selected value>Select Customer</option>
                                        @foreach ($suppliers as $customer)
                                        <option {{$service->customer_card_id == $customer->id?'selected':''}}
                                            value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <label>Start Date: </label>
                                    <input required class="form-control form-control-sm datepicker" type="text"
                                        name="start_date" data-date-format="dd/mm/yyyy"
                                        value="{{$service->start_date->format('d/m/Y')}}">
                                </div>
                                <div class="col-2 form-group">
                                    <label>End Date: </label>
                                    <input required class="form-control form-control-sm datepicker" type="text"
                                        name="end_date" data-date-format="dd/mm/yyyy"
                                        value="{{$service->end_date->format('d/m/Y')}}">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Invoice No: </label>
                                    <input class="form-control form-control-sm" readonly type="text" name="inv_no"
                                        value="{{$service->inv_no}}">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Your Reference: </label>
                                    <input class="form-control form-control-sm" type="text" name="your_ref"
                                        value="{{$service->your_ref}}">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Our Reference: <button type="button" class="btn btn-warning btn-sm"
                                            style="padding:0 13px; font-size:12px" data-toggle="modal"
                                            data-target="#ourReference">
                                            <i class="far fa-clipboard"></i></button>
                                    </label>
                                    <input class="form-control form-control-sm ourRefInput" type="text" name=""
                                        placeholder="{{$service->customer->customer_ref}}">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-11 form-group">
                                    <label>Quote terms and Conditions: </label>
                                    <textarea class="form-control" rows="2" placeholder="Quote terms and Conditions"
                                        id="tearms_area"
                                        style="margin-top: 0px;margin-bottom: 0px;height: 145px;resize: none;"
                                        name="quote_terms">{{$service->quote_terms}}</textarea>
                                </div>
                                <div class="col-sm-1">
                                    <br><br>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#quote"><i class="far fa-clipboard"></i></button>
                                </div>
                            </div>
                            @include('frontend.sales.job')
                            <div class="jobContent"></div>
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
@include('frontend.purchase.modal')

<!-- inline scripts related to this page -->
<script>
$(document).ready(function() {
    readData();
    jobReadData();
    Quotes();
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
    html += '<input type="hidden" name="inv_id[]">';
    html += '<input type="hidden" name="job_des[]" value="' + job_description + '" />';
    html += '<input type="hidden" name="price[]" value="' + price + '" />';
    html += '<input type="hidden" name="disc_rate[]" value="' + disc + '" />';
    html += '<input type="hidden" name="disc_amount[]" value="' + disc_amount + '" />';
    html += '<input type="hidden" name="freight_charge[]" value="' + freight + '" />';
    // html += '<input type="hidden" name="account[]" value="' + account + '" />';
    html += '<input type="hidden" name="is_tax[]" value="' + tax + '" />';
    if (tax == 'yes') {
    html += '<input type="hidden" name="gst_amt[]" value="' + gst + '" />';
    }else{
    html += '<input type="hidden" name="gst_amt[]" value="0" />';
    }
    html += '<input type="hidden" name="totalamount[]" value="' + totalamount + '" />';
    html += '<input type="hidden" name="chart_id[]" value="' + chart_id + '" />';
    html += '<a class="item-delete" href="#"><i class="fas fa-trash"></i></a></td></tr>';
    toast('success','Code Added');
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
$("#invoiceStore").on('submit',function(e){
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
                $("#payment_amount").val('0.00')
                $("#inv_no").val(res.inv_no.toString().padStart(8, '0'));
                toast('success',res.message);
            }else{
                toast('error',res.message);
            }
        },
    });
});
function deleteData(id){
    if(confirm('Are You sure?') == true){
        $.ajax({
            url: '{{route("service_order.delete")}}',
            method:'get',
            data:{id:id},
            success:res=>{
                if(res.status == 200){
                    toast('success', res.message);
                    Quotes();
                }else{
                    toast('error', res.message);
                }
            },
        });
    }
};
function Quotes() {
    $.ajax({
        url: '{{route("service_order.edit",[$service->profession_id, $service->customer_card_id, $service->inv_no])}}',
        method:'get',
        success:res=>{
            if(res.status == 200){
                let jobData = '';
                $.each(res.services,function(i,v){
                    jobData +='<input type="hidden" name="inv_id[]" value="'+v.id+'"><div class="row mx-auto"><div class="form-group mx-1"><label class="">Job Title: </label><input class="form-control form-control-sm" type="text" name="job_title[]" placeholder="Job Title" id="job_title"value="'+v.job_title+'"></div><div class="form-group mx-1" style="width: 250px"><label>Job Description: <button type="button" class="btn btn-warning btn-sm"style="padding:0 13px; font-size:12px" data-toggle="modal" data-target="#job"><i class="far fa-clipboard"></i></button></label> <textarea class="form-control form-control-sm" rows="1" name="job_des[]" placeholder="Job Description" id="job_des">'+v.job_des+'</textarea> </div><div class="form-group mx-1" style="width: 100px"><label>Price: </label><input class="form-control form-control-sm" type="Number" name="price[]" value="'+v.price+'"></div><div class="form-group mx-1" style="width: 100px"><label>Disc %: </label><input class="form-control form-control-sm" type="Number" name="disc_rate[]" value="'+v.disc_rate+'"></div><div class="form-group mx-1" style="width: 120px"><label>Freight Charge: </label><input class="form-control form-control-sm" type="Number" name="freight_charge[]"value="'+v.freight_charge+'"></div><div class="form-group mx-1" style="width: 130px"><label>Income Account: </label><input type="hidden" name="chart_id[]" id="chart_id" value="'+v.chart_id+'"><input class="form-control form-control-sm" type="text" readonly id="`ac_code_name" value="'+v.chart_id+'"></div><div class="form-group mx-1" style="width:70px"><label>Tax: </label><input type="text" name="is_tax[]" id="is_tax" readonly class="form-control form-control-sm" value="'+v.is_tax+'"></div><div style="margin-top: 32px;margin-left:7px"><button class="btn btn-danger btn-sm" type="button" onclick="deleteData('+v.id+')"><i class="far fa-trash-alt"></i></button></div></div>';
                });
                $(".jobContent").html(jobData);
            }else{
                toast('error', 'No Data found!');
            }
        },
    });
}


</script>
@stop
