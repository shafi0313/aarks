@extends('frontend.layout.master')
@section('title','Cash Entry')
@section('content')
<?php $p="cb"; $mp="acccounts";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 text-center">
                                <div style="font-size:22px; color:#ff5733;"> <u>Cash Book :
                                        <span style="color:green;">{{$office->name}}</span></u>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-4" align="left"><strong style="font-size:16px; color:red;">Date :
                                    {{now()->format('d/m/Y')}}</strong></div>
                            <div class="col-md-4" align="center"
                                style="padding-right:15px; font-size:20px; color:#ff5733;">Opening
                                Balance :
                                @if ($office->id == $first_office->id)
                                $ {{number_format($open_balance,2)}}
                                @else
                                $ {{ number_format($open_balance = ($openbl->credit - $openbl->debit),2)}}
                                @endif
                            </div>
                            <div class="col-md-4" align="right"
                                style="padding-right:15px; font-size:20px; color:green;"></div>
                            <div class="col-md-12">
                                <table width="100%" border="1" cellspacing="5" cellpadding="5">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;" width="20%">A/c Code</th>
                                            <th style="text-align:center;" width="20%">Narration and
                                                Note</th>
                                            <th style="text-align:center;" width="6%">Txcode</th>
                                            <th style="text-align:center;" width="11%">Payment</th>
                                            <th style="text-align:center;" width="11%">Recevied</th>
                                            <th style="text-align:center;" width="11%">GST</th>
                                            <th style="text-align:center;" width="11%">RL</th>
                                            <th style="text-align:center;" width="2%">Action</th>
                                        </tr>
                                    </thead>
                                    <form id="entrydata" action="{{route('cashbook.store')}}" method="post"
                                        autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="source" id="source" value="CBE">
                                        <input type="hidden" name="ac_type" id="ac_type">
                                        <input type="hidden" name="cash_office_id" id="cash_office_id"
                                            value="{{$office->id}}">
                                        <input type="hidden" name="profession_id" id="profession_id"
                                            value="{{$profession->id}}">
                                        <input type="hidden" name="client_id" id="client_id" value="{{$client->id}}">
                                        <input type="hidden" name="gst_method" id="gst_method"
                                            value="{{$client->gst_method}}">
                                        <input type="hidden" name="is_gst_enabled" id="is_gst_enabled"
                                            value="{{$client->is_gst_enabled}}">
                                        <tbody>
                                            <tr>
                                                <td style="width:12%">
                                                    <select class="form-control" id="account_name" name="chart_id"
                                                        required>
                                                        <option value="">--</option>
                                                        <option value="0">Transfer From/To Other centre</option>
                                                        @foreach ($codes as $code)
                                                        @if ($code->type ==1)
                                                        <option style="color: green;" value="{{$code->code}}"
                                                            tax-code="{{$code->gst_code}}" data-name="{{$code->name}}"
                                                            data-type="{{$code->type}}" account-code="{{$code->code}}">
                                                            {{$code->name}} => ({{$code->code}})
                                                        </option>
                                                        @else
                                                        <option style="color: hotpink;" value="{{$code->code}}"
                                                            tax-code="{{$code->gst_code}}" data-name="{{$code->name}}"
                                                            data-type="{{$code->type}}" account-code="{{$code->code}}">
                                                            {{$code->name}} => ({{$code->code}})
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="narration" id="narration"
                                                        onkeydown="return (event.keyCode!=13);" class="form-control">
                                                </td>
                                                <td>
                                                    <select class="form-control" id="tax_code" name="gst_code">
                                                        <option value=""></option>
                                                        <option value="CAP">CAP</option>
                                                        <option value="FOA">FOA</option>
                                                        <option value="FRE">FRE</option>
                                                        <option value="GST">GST</option>
                                                        <option value="INP">INP</option>
                                                        <option value="Nil">Nil</option>
                                                        <option value="SUP">SUP</option>
                                                        <option value="W1">W1</option>
                                                        <option value="W2">W2</option>
                                                        <option value="ADS">ADS</option>
                                                        <option value="ITS">ITS</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control price" id="payment"
                                                        onkeydown="return (event.keyCode!=13);" name="payment"
                                                        onfocusout="gstCal(this.value)" min="0" step="any">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" id="recevied"
                                                        onkeydown="return (event.keyCode!=13);" name="recevied"
                                                        disabled="disabled" onfocusout="gstCal(this.value)" min="0"
                                                        step="any">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="gst" readonly
                                                        onkeydown="return (event.keyCode!=13);" name="gst">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="inventory"
                                                        onkeydown="return (event.keyCode!=13);" name="inventory">
                                                </td>


                                                <td>
                                                    <button type="submit" class="btn btn-primary">Add</button>
                                                </td>

                                            </tr>
                                            @foreach ($cashbooks as $cashbook)
                                            <tr>
                                                <td align="center">
                                                    {{$codes->where('code', $cashbook->chart_id)->first()->name}}</td>
                                                <td align="center">{{$cashbook->narration}} </td>
                                                <td align="center">{{$cashbook->gst_code}}</td>
                                                <td align="center">
                                                    {{$cashbook->ac_type ==
                                                    1?number_format($cashbook->amount_debit,2):'0.00'}}
                                                </td>
                                                <td align="center">
                                                    {{$cashbook->ac_type ==
                                                    2?number_format($cashbook->amount_credit,2):'0.00'}}
                                                </td>

                                                <td align="center">
                                                    @if($client->gst_method == 1)
                                                    {{$cashbook->ac_type ==
                                                    1?number_format($cashbook->gst_cash_debit,2):number_format($cashbook->gst_cash_credit,2)}}
                                                    @else
                                                    {{$cashbook->ac_type ==
                                                    2?number_format($cashbook->gst_accrued_credit,2):number_format($cashbook->gst_accrued_debit,2)}}
                                                    @endif
                                                </td>
                                                <td align="center">0.00</td>
                                                <td align="center">
                                                    <a href="{{route('cashbook.destroy',$cashbook->id)}}"
                                                        class="btn btn-danger btn-sm btn-sm px-3"
                                                        onclick="return confirm('are you sure')"><i
                                                            class="far fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </form>
                                    <tbody id="cashbook">
                                        <tr>
                                            <td colspan="3" align="right" style="font-size:20px; color:green;">Total
                                            </td>
                                            <td align="center" style="color:green; font-size:18px;">
                                                {{number_format($total_payment = $cashbooks->sum('amount_debit'),2)}}
                                            </td>
                                            <td align="center" style="color:green; font-size:18px;">
                                                {{number_format($total_received = $cashbooks->sum('amount_credit'),2)}}
                                            </td>
                                            <td colspan="3">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <form action="{{route('cashbook.massUpdate')}} " method="POST">
                                    @csrf
                                    <input type="hidden" name="openginbalance" value="0.00">
                                    <input type="hidden" name="cash_office_id" id="cash_office_id"
                                        value="{{$office->id}}">
                                    <input type="hidden" name="profession_id" id="profession_id"
                                        value="{{$profession->id}}">
                                    <input type="hidden" name="client_id" id="client_id" value="{{$client->id}}">
                                    <input type="hidden" name="cash_hand"
                                        value="{{$cashbooks->sum('amount_credit')-$cashbooks->sum('amount_debit')}}">
                                    <div align="right" style="padding-top:20px;">
                                        <span>
                                            <button type="button" class="btn btn-primary btn-sm" data-backdrop="static"
                                                data-keyboard="false" data-toggle="modal" data-target="#myModal">NOTE of
                                                Cash
                                                Details.
                                            </button>
                                        </span>

                                        <span style="color:red;"> &nbsp;&nbsp;&nbsp; Find your current closing balance
                                            please click save </span>
                                        <span style="color:green; font-size:18px; padding-right:100px;">
                                            Closing Balance : &nbsp;&nbsp;&nbsp; $ {{number_format((($open_balance +
                                            $total_received) - $total_payment),2)}} </span>
                                        <input type="submit" value="Save" name="save" class="btn btn-primary"
                                            style="background:#ff5733 !important; color:white !important;">
                                        <input type="submit" value="Post" name="post" class="btn btn-primary finalpost"
                                            style="background:#ff5733 !important; color:white !important;">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel" style="color:red !important;">NOTE of Cash Details. </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <form action="{{route('gst_recon.save_note', [$client->id, $profession->id])}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="model" value="cashbook">
                    <input type="hidden" name="year" value="{{now()->format('Y')}}">
                    <div class="form-group">
                        <label style="color:red;">Note : </label>
                        <textarea name="content" id="note" cols="30" rows="10" class="form-control summernote"
                            placeholder="Add your notes">{{optional($note)->content}}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"
                        style="background:#ff5733 !important; color:white !important;">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="swal-icon swal-icon--warning">
                    <span class="swal-icon--warning__body">
                        <span class="swal-icon--warning__dot"></span>
                    </span>
                </div>
                <div class="swal-title" style="">Are you sure?</div>
                <div class="swal-text" style="">Before post please check all transaction and your closing balance is
                    correct, As you cannot alter/delete the transaction after post?</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Make Changes</button>
                <button type="submit" class="btn btn-primary" id="postOk"
                    style="background:#ff5733 !important; color:white !important;">Ok</button>
            </div>
        </div>
    </div>
</div>
<!-- Footer Start -->

<!-- Footer End -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $('.cashdelete').on('click', function(e){
	e.preventDefault();
	var id = $(this).attr('data-id');
	var posturl = "https://aarks.net/books/Petty_cash/deletecash";
    $.ajax({
        url : posturl,
        type: "POST",
        data:{id:id},
        success:function(data){
        $('#cashbook').html(data);
        }
    });
});

$('#account_name').on('change', function(e){
	var taxtcode 	 = $('#account_name option:selected').attr('tax-code');
	var account_code = $('#account_name option:selected').attr('account-code');
	var data_type = $('#account_name option:selected').data('type');
	$('#tax_code').val(taxtcode);
	$('#ac_type').val(data_type);
	if(account_code >= 110000 && account_code <= 199999){
		$('#narration').focus();
		$('#payment').attr('disabled', 'disabled');
		$('#recevied').removeAttr('disabled', 'disabled').attr('required', 'required');
	} else if(account_code >= 210000 && account_code <= 299999){
		$('#narration').focus();
		$('#payment').removeAttr('disabled', 'disabled').attr('required', 'required');
		$('#recevied').attr('disabled', 'disabled');
	}else{
		$('#narration').focus();
		$('#payment').removeAttr('disabled', 'disabled').attr('required', 'required').attr('onkeyup', 'dis("payment")');
		$('#recevied').removeAttr('disabled', 'disabled').attr('required', 'required').attr('onkeyup', 'dis("recevied")');
	}
	e.preventDefault();
});

function dis(type){
    if(type == 'payment'){
        if($('#payment').val().length > 0){
            $('#recevied').attr('disabled', 'disabled');
        }else{
            $('#recevied').removeAttr('disabled', 'disabled');
        }
    }else{
        if($('#recevied').val().length > 0){
            $('#payment').attr('disabled', 'disabled');
        }else{
            $('#payment').removeAttr('disabled', 'disabled');
        }
    }
}


/*
$('input').on("keypress", function(e) {

	if (e.keyCode == 13) {
		var inputs = $(this).parents("form").eq(0).find(":input");
		var idx = inputs.index(this);

		if (idx == inputs.length - 1) {
			inputs[0].select()

		} else {
			inputs[idx + 1].focus(); //  handles submit buttons
			inputs[idx + 1].select();
		}
		return false;
	}
});
*/
function gstCal(value){
    let tax = $('#tax_code').val();
    if((tax == 'GST' || tax == 'CAP' || tax == 'INP')){
        $('#gst').val((value/11).toFixed(2))
    }else{
        $('#gst').val('0.00')
    }
}
// $("#entrydata").submit(function(e){
// 	e.preventDefault();
//     var postData = $(this).serializeArray();
//     var formURL = $(this).attr("action");
//     $.ajax({
//         url : formURL,
//         timeout:1000,
//         type: "POST",
//         async:false,
//         crossDomain:true,
//         data : postData,
//         success:function(data){
//         $('#narration').val('');
//         $('#payment').val('');
//         $('#recevied').val('');
//         $('#account_name').focus();
//         $('#cashbook').html(data);
//         }
//     });
// });

$(document).on('click', '.finalpost', function (e){
    e.preventDefault();
    let form = $(this).parents('form');
    $("#postModal").modal('show');
    $('#postOk').on('click', function(e){
        form.submit();
    });
    // swal({
    //     title: 'Are you sure?',
    //     text: 'Before post please check all transaction and your closing balance is correct, As you cannot alter/delete the transaction after post?',
    //     icon: 'warning',
    //     buttons: ["Make Changes", "Yes!"],
    // }).then(function(value) {
    //     if(value){
    //         form.submit();
    //     }
    // });
});
</script>
@stop
