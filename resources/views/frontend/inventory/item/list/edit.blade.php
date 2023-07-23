@extends('frontend.layout.master')
@section('content')
@section('title','Add Item')
<?php $p="invAdd"; $mp="inventory"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12" style="padding-top:10px;">
                <form action="{{route('inv_item.update',$inv_item->id)}}" method="post" autocomplete="off">
                    @csrf @method('put')
                    @if ($errors->any())
                    <span class="text-danger">{{$errors->first()}} </span>
                    @endif
                    <div>
                        <strong style="color:green; font-size:25px;">Add Item</strong>
                        <samp style="padding-left:50px; font-size:20px; color:#9933FF;">

                            <label class="radio-inline">
                                <input checked type="radio" name="profession_id" id="profession_id" value="{{$inv_item->profession->id}}">
                                {{$inv_item->profession->name}}
                            </label>

                        </samp>
                        <input type="hidden" name="client_id" id="client_id" value="{{$inv_item->client_id}}">
                    </div>

                    <div class="col-md-12" style="padding-top:20px;">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Select category</label>
                                <select class="form-control" name="category" id="categoryName" >
                                    <option value="{{$inv_item->category}}" selected>{{$inv_item->category}}</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-top:20px;">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Alige</label>
                                    <input type="text" name="alige" id="alige" class="form-control" value="{{$inv_item->alige}}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Item Number</label>
                                    <input type="number" name="item_number" id="item_number"  oninput="this.value = this.value.replace(/[^\d]/g,'');" class="form-control" value="{{$inv_item->item_number}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Item Name</label>
                                    <input type="text" name="item_name" id="item_name"  class="form-control" value="{{$inv_item->item_name}}">
                                </div>
                            </div>
                            <div class="col-md-4" style="padding-top:25px;">
                                <div class="form-group">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="type[]" id="item_buy" value="1"  {{$inv_item->type == '1'?'checked':''}}> &nbsp;&nbsp; Buy
                                        Item
                                    </label>&nbsp;&nbsp;
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="type[]" id="item_sell" value="2"  {{$inv_item->type == '2'?'checked':''}}>&nbsp;&nbsp;
                                        Sell Item
                                    </label>&nbsp;&nbsp;
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="type[]" id="item_stock" value="3"  {{$inv_item->type == '3'?'checked':''}}>&nbsp;&nbsp;
                                        Stock Item
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3" style="padding-top:25px;">
                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input {{$inv_item->status == '1'?'checked':''}} type="radio" name="status"  value="1"> Active Item
                                    </label>&nbsp;&nbsp;
                                    <label class="radio-inline">
                                        <input  {{$inv_item->status == '0'?'checked':''}}type="radio" name="status"  value="0"> Inactive Item
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Bin Number</label>
                                    <input type="number" name="bin_number" id="binnumber"
                                        oninput="this.value = this.value.replace(/[^\d]/g,'');" class="form-control"
                                         value="{{$inv_item->bin_number}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Barcode Number</label>
                                    <input type="number" name="barcode_number" id="barcode"
                                        oninput="this.value = this.value.replace(/[^\d]/g,'');" class="form-control"
                                         value="{{$inv_item->barcode_number}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('frontend.inventory.item.list.details')
                    <div class="row">
                        <div class="col-md-11"></div>
                        <div class="col-md-1" style="padding-top:22px;">
                            <button type="submit" class="btn btn-primary barcodeCheck">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
<script>

$('#item_buy').on('change', function(){
    $('#buy_measure_unit').removeAttr('disabled','disabled').attr('required');
    $('#buy_price').removeAttr('disabled','disabled').attr('required');
    $('#buy_tax_code').removeAttr('disabled','disabled').attr('required');
    $('#buy_supplier_name').removeAttr('disabled','disabled').attr('required');
    $('#expense_account').removeAttr('disabled','disabled').attr('required');

    $('#sell_measure_unit').removeAttr('required');
    $('#sell_price').removeAttr('required');
    $('#sell_tax_code').removeAttr('required');
    $('#sell_supplier_name').removeAttr('required');
    $('#income_account').removeAttr('required');

    $('#inv_measure_unit').removeAttr('required');
    $('#inv_price').removeAttr('required');
    $('#inv_tax_code').removeAttr('required');
    $('#inv_supplier_name').removeAttr('required');
    $('#asset_account').removeAttr('required');

    $('#on_hand_date').removeAttr('required');
    $('#inv_qun_on_hand').removeAttr('required');
    $('#qty_rate').removeAttr('required');
    $('#current_value').removeAttr('required');
});

$('#item_sell').on('change', function(){
    $('#buy_measure_unit').removeAttr('required');
    $('#buy_price').removeAttr('required');
    $('#buy_tax_code').removeAttr('required');
    $('#buy_supplier_name').removeAttr('required');
    $('#expense_account').removeAttr('required');

    $('#sell_measure_unit').removeAttr('disabled','disabled').attr('required');
    $('#sell_price').removeAttr('disabled','disabled').attr('required');
    $('#sell_tax_code').removeAttr('disabled','disabled').attr('required');
    $('#sell_supplier_name').removeAttr('disabled','disabled').attr('required');
    $('#income_account').removeAttr('disabled','disabled').attr('required');

    $('#inv_measure_unit').removeAttr('required');
    $('#inv_price').removeAttr('required');
    $('#inv_tax_code').removeAttr('required');
    $('#inv_supplier_name').removeAttr('required');
    $('#asset_account').removeAttr('required');

    $('#on_hand_date').removeAttr('required');
    $('#inv_qun_on_hand').removeAttr('required');
    $('#qty_rate').removeAttr('required');
    $('#current_value').removeAttr('required');

});

$('#item_stock').on('change', function(){
    $('#buy_measure_unit').removeAttr('required');
    $('#buy_price').removeAttr('required');
    $('#buy_tax_code').removeAttr('required');
    $('#buy_supplier_name').removeAttr('required');
    $('#expense_account').removeAttr('required');

    $('#sell_measure_unit').removeAttr('required');
    $('#sell_price').removeAttr('required');
    $('#sell_tax_code').removeAttr('required');
    $('#sell_supplier_name').removeAttr('required');
    $('#income_account').removeAttr('required');

    $('#inv_measure_unit').removeAttr('disabled','disabled').attr('required');
    $('#inv_price').removeAttr('disabled','disabled').attr('required');
    $('#inv_tax_code').removeAttr('disabled','disabled').attr('required');
    $('#inv_supplier_name').removeAttr('disabled','disabled').attr('required');
    $('#asset_account').removeAttr('disabled','disabled').attr('required');

    $('#on_hand_date').removeAttr('disabled','disabled').attr('required');
    $('#inv_qun_on_hand').removeAttr('disabled','disabled').attr('required');
    $('#qty_rate').removeAttr('disabled','disabled').attr('required');
    $('#current_value').removeAttr('disabled','disabled').attr('required');
});

// $("#barcode").on('keyup', function(){
// 	var barcode = $('#barcode').val();
// 	var professionid = $('#professionid').val();
// 	var subgrouurl = "https://aarks.net.au/books/Add_item/uniqueBarcode";
// 	$.ajax({
// 		url:subgrouurl,
// 		type:"POST",
// 		data:{barcode:barcode, professionid:professionid},
// 		success:function(data){

// 			if(data=='notFound'){
// 				$('.barcodeCheck').removeAttr('disabled', 'disabled');
// 			} else {
// 				$('.barcodeCheck').attr('disabled', 'disabled');
// 				alert('Barcode matched with previous item!');
// 			}
// 		}
// 	});
// });


$("#qty_rate, #inv_qun_on_hand").on('keyup', function(){
	var qty_rate = $('#qty_rate').val();
	var inv_qun_on_hand = $('#inv_qun_on_hand').val();
	var current_value 	= inv_qun_on_hand * qty_rate;
	$('#current_value').val(current_value.toFixed(2));

});

$("#item_buy").click( function(){
   if( $(this).is(':checked') ){
   $('.buy_details').css('display', 'block');
   } else {
   $('.buy_details').css('display', 'none');
   }
});

$("#item_sell").click( function(){
   if( $(this).is(':checked') ){
   $('.sell_details').css('display', 'block');
   } else {
   $('.sell_details').css('display', 'none');
   }
});

$("#item_stock").click( function(){
   if( $(this).is(':checked') ){
   $('.invontry_details').css('display', 'block');
   } else {
   $('.invontry_details').css('display', 'none');
   }
});
</script>

<script>
$(".unitmange").on('change', function(){
	var buy_measure_unit = $(this).val();
	if(buy_measure_unit == "0"){
		$('#buy_meaaure_myModal').modal('show');
	}
});
$("#unit_manage_db").submit(function(e){
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");
    console.log(formURL);
    $.ajax(
    {
        url : formURL,
        timeout: 1000,
        type: "POST",
        async:false,
        crossDomain:true,
        data : postData,
        success:function(res){
            if(res.status == 200){
                let measure = '<option value="" selected="" disabled="">--Select Measure Unit--</option> <option value="0">New Measure Unit</option>';
                $.each(res.measures, function(i,v){
                    measure += '<option value="'+v.id+'">'+v.name+'</option>';
                });
                $("#buy_measure_unit").html(measure);
                $("#sell_measure_unit").html(measure);
                $("#inv_measure_unit").html(measure);
                $(".job_success").text('Template saved successfully completed.');
                $("#unit_name").val("");
                $('#unit_details').val("");
                toast('success', res.message);
                $('#buy_meaaure_myModal').modal('hide');
            } else {
                toast('error', res.message);
            }
        },
        error:err=>{
            toast('error', 'Error');
        }

    });
    e.preventDefault();
});
</script>
        </div>
    </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->

@stop
