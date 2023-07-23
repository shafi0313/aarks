@extends('frontend.layout.master')
@section('title','Items')
@section('content')
<?php $p="epeb"; $mp="purchase";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="color:green; font-size:20px;">Update Items:
                                </strong>
                            </div>
                        </div>
                        <hr>
                        <form action="{{route('enter_item.billupdate',[$service->inv_no,$service->client_id])}}" method="POST" autocomplete="off">
                            @csrf @method('put')
                            @if ($errors->any())
                            <span class="text-danger">{{$errors->first()}} </span>
                            @endif
                            <input type="hidden" name="client_id" value="{{$service->client_id}}">
                            <input type="hidden" name="profession_id" value="{{$service->profession_id}}">
                            <input type="hidden" name="source" value="CBI">
                            <div class="row">
                                <div class="col-2 form-group">
                                    <label>Supplier Name:</label>
                                    <select required class="form-control  form-control-sm" name="customer_card_id">
                                        <option disabled selected value>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                        <option {{$service->customer_card_id == $supplier->id?'selected':''}}
                                            value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <label>Services Date: </label>
                                    <input required class="form-control form-control-sm" type="text" readonly name="start_date" value="{{$service->tran_date->format('d/m/Y')}}">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Services No: </label>
                                    <input class="form-control form-control-sm" readonly type="text" name="inv_no" value="{{$service->inv_no}}">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Your Reference: </label>
                                    <input class="form-control form-control-sm" type="text" name="your_ref" value="{{$service->your_ref}}">
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
                            @include('frontend.purchase.item.item')
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
@include('frontend.purchase.item.modal')

<!-- inline scripts related to this page -->
<script src="{{asset('frontend/assets/js/item.js')}}"></script>
<script>
    $(document).ready(function() {
    readData();
    Quotes();
});

function deleteData(id){
    if(confirm('Are You sure?') == true){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{route("invoice.delete")}}',
            method:'delete',
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
        url: '{{route("enter_item.billedit",[$service->inv_no,$service->client_id])}}',
        method:'get',
        success:res=>{
            if(res.status == 200){
                let jobData = '';
                $.each(res.services,function(i,v){
                    console.log(v);

                    // jobData +='<input type="hidden" name="inv_id[]" value="'+v.id+'"><input type="hidden" name="item_id[]" value="'+v.item_no+'"><input step="any" type="hidden" name="disc_amount[]" value="'+v.disc_amount+'"><input type="hidden" name="item_reg_name[]" value="'+v.item.item_name+'-'+v.item.item_number+'"><div class="row mx-auto"><div class="form-group mx-1" style="width: 190px"><label class="">Item Name:<span class="t_red">*</span> </label><select name="item_name[]" class="form-control"><option value="'+v.item_name+'">'+v.item_name+'</option></select></div><div class="form-group mx-1" style="width: 100px"><label>Quantity</label><input type="number" name="quantity[]" oninput="this.value = this.value.replace(/[^\\d]/g,\'\');" class="form-control editQuantity'+v.id+'" onkeyup="qur('+v.id+')" value="'+v.item_quantity+'"></div><div class="form-group mx-1" style="width: 100px"><label style="font-size: 15px">Rate(Ex GST)</label><input type="number" name="rate[]" oninput="this.value = this.value.replace(/[^\\d]/g,\'\');" class="form-control editRate'+v.id+'" onkeyup="qur('+v.id+')" value="'+v.ex_rate+'"></div><div class="form-group mx-1" style="width: 100px"><label>Amount</label><input readonly type="number" name="amount[]" class="form-control editAmount'+v.id+'" value="'+v.ex_rate * v.item_quantity+'"></div><div class="form-group mx-1" style="width: 100px"><label>Disc %: </label><input class="form-control" type="Number" name="disc_rate[]" placeholder="Disc %" oninput="this.value = this.value.replace(/[^\\d]/g,\'\');" value="'+v.disc_rate+'"></div><div class="form-group mx-1" style="width: 100px"><label style="font-size: 14px">Freight Charge: </label><input class="form-control" type="Number" name="freight_charge[]" oninput="this.value = this.value.replace(/[^\\d]/g,\'\');" value="'+v.freight_charge+'"></div><div class="form-group mx-1" style="width: 150px"><label>Account Code: </label><input type="text" readonly class="form-control" name="chart_id[]" value="'+v.chart_id+'"></div><div class="form-group mx-1" style="width:100px"><label>Tax: </label><input type="text" name="is_tax[]" readonly class="form-control" value="'+v.is_tax+'"></div><div style="margin-top: 32px;margin-left:7px"><button class="btn btn-danger btn-sm" type="button" onclick="deleteData('+v.id+')"><i class="far fa-trash-alt"></i></button></div></div>';
                    jobData +='<input type="hidden" name="inv_id[]" value="'+v.id+'"><input type="hidden" name="item_id[]" value="'+v.item_no+'"><input step="any" type="hidden" name="disc_amount[]" value="'+v.disc_amount+'"><input type="hidden" name="item_reg_name[]" value="'+v.item.item_name+'-'+v.item.item_number+'"><div class="row mx-auto"><div class="form-group mx-1" style="width: 190px"><label class="">Item Name:<span class="t_red">*</span> </label><select name="item_name[]" class="form-control"><option value="'+v.item_name+'">'+v.item_name+'</option></select></div><div class="form-group mx-1" style="width: 100px"><label>Quantity</label><input step="any" type="number" name="quantity[]" oninput="this.value = this.value.replace(/[^\\d+.]/g,\'\');" class="form-control editQuantity'+v.id+'" onkeyup="qur('+v.id+')" value="'+v.item_quantity+'"></div><div class="form-group mx-1" style="width: 100px"><label style="font-size: 15px">Rate(Ex GST)</label><input step="any" type="number" name="rate[]" oninput="this.value = this.value.replace(/[^\\d+.]/g,\'\');" class="form-control editRate'+v.id+'" onkeyup="qur('+v.id+')" value="'+v.ex_rate+'"></div><div class="form-group mx-1" style="width: 100px"><label>Amount</label><input readonly type="number" name="amount[]" class="form-control editAmount'+v.id+'" value="'+v.ex_rate * v.item_quantity+'"></div><div class="form-group mx-1" style="width: 100px"><label>Disc %: </label><input step="any" class="form-control" type="Number" name="disc_rate[]" placeholder="Disc %" oninput="this.value = this.value.replace(/[^\\d+.]/g,\'\');" value="'+v.disc_rate+'"></div><div class="form-group mx-1" style="width: 100px"><label style="font-size: 14px">Freight Charge: </label><input class="form-control" type="Number" step="any" name="freight_charge[]" oninput="this.value = this.value.replace(/[^\\d+.]/g,\'\');" value="'+v.freight_charge+'"></div><div class="form-group mx-1" style="width: 150px"><label>Account Code: </label><input type="text" readonly class="form-control" name="chart_id[]" value="'+v.chart_id+'"></div><div class="form-group mx-1" style="width:100px"><label>Tax: </label><input type="text" name="is_tax[]" readonly class="form-control" value="'+v.is_tax+'"></div><div style="margin-top: 32px;margin-left:7px"><button class="btn btn-danger btn-sm" type="button" onclick="deleteData('+v.id+')"><i class="far fa-trash-alt"></i></button></div></div>';
                });
                $(".jobContent").html(jobData);
            }else{
                toast('error', 'No Data found!');
            }
        },
    });
}

function qur(id){
    let rate = $(".editRate"+id).val();
    let quantity = $(".editQuantity"+id).val();
    var current_value = rate * quantity;
    $('.editAmount'+id).val(current_value.toFixed(2));
}
</script>
@stop
