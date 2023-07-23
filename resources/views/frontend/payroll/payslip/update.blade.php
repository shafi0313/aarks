@extends('frontend.layout.master')
@section('title','Prepare Payroll')
@section('content')
<?php $p="pp"; $mp="payroll";?>
<style>
    .form-group {
        margin-bottom: .3rem;
    }

    td input {
        border: none !important;
    }

    .table td,
    .table th {
        padding: .5rem;
        vertical-align: center;
        border-top: 1px solid #dee2e6;
    }

    .form-control:focus {
        color: #495057;
        background-color: #ff69b41f;
        border-color: hotpink;
        outline: 0;
        box-shadow: none;
    }
</style>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="panel-body" style="min-height:600px;">
                <div class="col-lg-6 text-center offset-lg-3">
                    @if ($errors->any())
                    <ul class="nav">
                    @foreach ($errors->all() as $error)
                    <li style="margin: 0 auto;"><span class="alert text-danger">{{$error}}</span> </li>
                    @endforeach
                    </ul>
                    @endif
                </div>
                <div class="col-md-12">

                    <div style="border: 1px solid #ebebeb; padding: 10px; min-height:700px;">

                        <form action="{{route('payslip.update',[$payslip->id,$payslip->tran_id])}}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" name="client_id" value="{{client()->id}}">
                            <input type="hidden" name="tran_id" value="{{$payslip->tran_id}}">
                            <input type="hidden" name="payslip_id" value="{{$payslip->id}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-static">Number: </label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="pay_serial" readonly value="{{$payslip->id}}">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label">Payment Date :</label>
                                        <div class="col-sm-6" align="left">
                                            <input type="text" class="form-control" id="payment_date"
                                                name="payment_date" readonly
                                                value="{{$payslip->pay_accum->payment_date->format('d/m/Y')}}">
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label">Pay Period Start :</label>
                                        <div class="col-sm-6" align="left">
                                            <input type="text" class="form-control" id="payperiod_start"
                                                name="payperiod_start" readonly
                                                value="{{$payslip->pay_accum->payperiod_start->format('d/m/Y')}}">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label">Pay Period Ending :</label>
                                        <div class="col-sm-6" align="left">
                                            <input type="text" class="form-control" id="payperiod_end"
                                                name="payperiod_end" readonly
                                                value="{{$payslip->pay_accum->payperiod_end->format('d/m/Y')}}">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label">Pay Cycle :</label>
                                        <div class="col-sm-6" align="left">
                                            <input type="text" class="form-control" id="pay_period" name="pay_period"
                                                readonly value="{{$payslip->pay_accum->pay_period}}">
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-6">

                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-static">Employee : </label>
                                        <div class="col-sm-6" align="left">
                                            <p class="form-control-static">{{$payslip->employee->fullname}}</p>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-static">Statement Text : </label>
                                        <div class="col-sm-8" align="left">
                                            <input type="text" class="form-control" name="statement"
                                                id="statement" value="{{$payslip->pay_accum->statement}}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-8 form-control-static">Is this final payroll for the current finalcial year?</label>
                                        <div class="col-sm-2" align="left">
                                            <input id="no" type="radio" class="" id="memo" name="memo" value="Pay Emloyee" checked>
                                            <label for="no">No</label>
                                        </div>
                                        <div class="col-sm-2" align="left">
                                            <input id="yes" type="radio" class="" id="memo" name="memo" value="Pay Emloyee">
                                            <label for="yes">Yes</label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label">Gross Pay :</label>
                                        <div class="col-sm-6" align="left">
                                            <p class="form-control-static subtotal">{{$payslip->pay_accum->gross}}</p>
                                            <input type="hidden" name="gross_pay" id="gross_pay" value="{{$payslip->pay_accum->gross}}">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-static">Net Pay : </label>
                                        <div class="col-sm-8" align="left">
                                            <p class="form-control-static net_pay">{{$payslip->pay_accum->net_pay}}</p>
                                            <input type="hidden" name="net_pay" id="net_pay" value="{{$payslip->pay_accum->net_pay}}">
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr style="background:#64b1ff; color:white;">
                                                <th style="text-align:center">Payroll Category</th>
                                                <th style="text-align:center">Hours</th>
                                                <th style="text-align:center">Account</th>
                                                <th style="text-align:center">Amount </th>
                                                <th style="text-align:center">Job </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr style="background:#ebebeb;">
                                                <td colspan="5">Wages</td>
                                            </tr>
@foreach ($payWages as $wages)
@php
$stanWages = \App\Models\StandardWages::where('name',$wages->name)->first();
if(!$stanWages){ continue; }
$lg = $stanWages->link_group;
@endphp
@if ($lg == '4')
<tr>
    <td style="text-align:right;">
        {{$wages->name}}
        <input type="hidden" name="wagesid[]" id="wagesid[]" value="{{$wages->name}}">
        <input type="hidden" name="typeofages" id="typeofages" class="typeofages" value="Hourly">
    </td>
    <td style="text-align:center; width:15%; padding:0px;">
        <input class="form-control text-center totalhour hourcount"
            onkeypress="myFunction('{{$wages->name}}')" autocomplete="off"
            type="text" name="emphour[]" id="emphour[]" value="{{$wages->hours}}">
    </td>
    <td>Wages &amp; Salaries Expenses</td>
    <td class="total">{{number_format($wages->amount,2, '.', '')}}</td>
    <td>
        <input type="hidden" id="hourly_amt"  name="hourly_amt[]" value="{{$wages->amount}}">
        <input type="hidden" id="hourlyamount" class="hourlyamount" name="hourlyamount[]"
            value="{{$stanWages->regular_rate}}">
    </td>
</tr>
@else
<tr>
    <td style="text-align:right;">
        {{$wages->name}}
        <input type="hidden" name="wagesid[]" id="wagesid[]" value="{{$wages->name}}">
        <input type="hidden" name="typeofages" id="typeofages" class="typeofages" value="Hourly">
    </td>
    <td style="text-align:center; width:15%; padding:0px;">
        <input class="form-control text-center totalhour hourcount @if ($lg == '1' ||$lg == '2' ||$lg == '3' ||$lg == '5' ||$lg == '7' ||$lg == '10')paygValHour @endif"
            onkeypress="myFunction('{{$wages->name}}')" autocomplete="off"
            type="text" name="emphour[]" id="emphour[]" value="{{$wages->hours}}">
    </td>
    <td>
        Wages &amp; Salaries Expenses
        @if ($lg == '1' ||$lg == '2' ||$lg == '3' ||$lg == '5' ||$lg == '7' ||$lg == '10')
        <input type="hidden" class="paygCal" value="{{$wages->amount}}">
        <input type="hidden"class="payghourlyamount totalhour"
            value="{{$stanWages->hourly_rate != ''?$stanWages->hourly_rate:$stanWages->regular_rate * $payslip->employee->hourly_rate}}">
        @endif
    </td>
    <td class="total">{{number_format($wages->amount,2, '.', '')}}</td>
    <td>
        <input type="hidden" id="hourly_amt"  name="hourly_amt[]" value="{{$wages->amount}}">
        <input type="hidden" id="hourlyamount" class="hourlyamount" name="hourlyamount[]" value="{{$wages->rate}}">
    </td>
</tr>
@endif
@endforeach
                                            <tr style="background:#ebebeb;">
                                                <td colspan="5">Deductions and Taxes</td>
                                            </tr>
@foreach ($payDeduc as $i => $deduction)
@if ($deduction->name == 'PAYG Withholding')
<tr>
    <td style="text-align:right;">
        {{$deduction->name}}
        <input type="hidden" name="deduc_id[]" value="{{$deduction->name}}">
    </td>
    <td style="text-align:center; width:15%; padding:0px;">
        <input class="form-control text-center" id="withholdingpay" name="withholdingpay" type="text" readonly value="{{$deduction->amount}}">
        <input type="hidden" name="deduc_amt[]" class="witholdingpay_input" readonly value="{{$deduction->amount}}">
        <input type="hidden" name="deduc_rate[]" value="0">
    </td>
    <td>{{$deduction->name}} Payable</td>
    <td class="witholdingpay">{{$deduction->amount}}</td>
    <td><input type="hidden" name="deduc" class="totalDeduc" value="0"></td>
</tr>
@else
<tr>
<td style="text-align:right;">
    {{$deduction->name}}
    <input type="hidden" name="deduc_id[]"  value="{{$deduction->name}}">
</td>
    @if ($deduction->rate == '')
    <td style="text-align:center; width:15%; padding:0px;">
        <input type="hidden" name="deduc_amt[]" id="deduc_amt{{$i}}" class="deduc_rate" readonly value="{{$deduction->fix_amt}}">
        <input type="hidden" name="fix_amt[]" readonly value="{{$deduction->fix_amt}}">
        <span>{{$deduction->fix_amt}}</span>
    </td>
    <td></td>
    <td class="totalLeave">{{number_format($deduction->fix_amt,2, '.', '')}}</td>
    @else
    <td style="text-align:center; width:15%; padding:0px;">
        <input type="hidden" name="deduc_rate[]" id="deduc_rate{{$i}}" class="deduc_rate" readonly value="{{$deduction->rate}}">
        <span class="union_fee{{$i}}">
            {{$deduction->amount}}
        </span>
    </td>
    <td>
        <input type="hidden" name="deduc_amt[]" id="deduc_amt{{$i}}" class="union_fee_input{{$i}}" readonly value="{{$deduction->amount}}">
    </td>
    <td class="union_fee{{$i}} totalLeave">
        {{number_format($deduction->amount,2, '.', '')}}
    </td>
    @endif
    <td>
    </td>
</tr>
@endif
@endforeach

                                            <tr style="background:#ebebeb;">
                                                <td colspan="5">Leave</td>
                                            </tr>

@foreach ($payLeave as $i => $leave)
@if ($leave->name=='Annual Leave Accrual')
    <tr>
        <td style="text-align:right;">
            Annual Leave Accrual
            <input type="hidden" name="leave_id[]" class="leave_id" id="leave_id" value="Annual Leave Accrual">
        </td>
        <td style="text-align:center; width:15%; padding:0px;">
            <input type="hidden" name="leave_rate[]" class="leave_rate" id="leave_rate{{$i+1}}" value="{{$leave->rate}}">
            <input class="form-control text-center leave_acrual{{$i+1}}" type="text" name="annual_leave_acrual" id="annual_leave_acrual" value="{{$leave->amount}}">
            <input class="form-control text-center annualLeave leave_acrual{{$i+1}}" type="hidden" name="leave_acrual[]" id="annual_leave_acrual" value="{{$leave->amount}}">
        </td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
@elseif ($leave->name=='Personal Leave Accrual')
    <tr>
        <td style="text-align:right;">
            Personal Leave Accrual
            <input type="hidden" name="leave_id[]" class="leave_id" id="leave_id" value="Personal Leave Accrual">
        </td>
        <td style="text-align:center; width:15%; padding:0px;">
            <input type="hidden" name="leave_rate[]" class="leave_rate" id="leave_rate{{$i+1}}" value="{{$leave->rate}}">
            <input class="form-control text-center totalhour leave_acrual{{$i+1}}" type="text" name="personal_leave_ac" id="personal_leave_ac" value="{{$leave->amount}}">
            <input class="form-control text-center totalhour personalLeave leave_acrual{{$i+1}}" type="hidden" name="leave_acrual[]" id="personal_leave_ac" value="{{$leave->amount}}">
        </td>
        <td> </td>
        <td></td>
        <td></td>
    </tr>
@else
    <tr>
        <td style="text-align:right;">
            {{$leave->name}}
            <input type="hidden" name="leave_id[]" class="leave_id" id="leave_id" value="{{$leave->name}}">
        </td>
        <td style="text-align:center; width:15%; padding:0px;">
            <input type="hidden" name="leave_rate[]" class="leave_rate" id="leave_rate{{$i+1}}" value="{{$leave->rate}}">
            <input class="form-control text-center leave_acrual{{$i+1}}" type="text" name="leave_acrual[]" id="leave_acrual" value="{{$leave->amount}}">
        </td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
@endif
@endforeach



                                            <tr style="background:#ebebeb;">
                                                <td colspan="5">Superannuation</td>
                                            </tr>

                                            @foreach ($paySuper as $i => $super)
                                            <tr>
                                                <td style="text-align:right;">
                                                    {{$super->name}}
                                                    <input type="hidden" name="super_id[]" value="{{$super->name}}" readonly>

                                                </td>
                                                <td style="text-align:center; width:15%; padding:0px;">
                                                    <input class="form-control text-center super superAmt{{$i+1}}" type="text" name="super[]" id="super" value="0" readonly>
                                                    <input class="superAmt{{$i+1}}" type="hidden" name="superAmt" id="super" value="{{$super->amount}} ">
                                                </td>
                                                <td>Supernnuation</td>
                                                <td><span class="superAmt{{$i+1}}">{{$super->amount}} </span></td>
                                                <td></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-7">
                                            <span style="color:red; font-weight:800;font-size:18px;">You must save all
                                                entered hours if you want to processed this pay run </span>
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="submit" class="btn btn-outline-info">Update</button>
                                            <a href="" class="btn btn-primary">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- main div end -->
            </div>
        </div>
    </div>
</section>

<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->
<script>
$('table tbody tr').find('.totalhour').on('keyup',function() {
    var parent = $(this).parents('tr');
    var quantity = parseFloat(parent.find('.totalhour').val())||0;
    var price = parseFloat(parent.find('.hourlyamount').val())||0;
    var paygquantity = parseFloat(parent.find('.paygValHour').val())||0;
    var paygprice = parseFloat(parent.find('.payghourlyamount').val())||0;
	var typeofages = parent.find('.typeofages').val();

	if(typeofages == 'Hourly'){
        var totalvlue = +quantity*price;
        var paygtotalvlue = +paygquantity*paygprice;
	}else{
		var totalvlue = +quantity;
		var paygtotalvlue = +paygquantity;
	}

	var totalamount = Math.round(totalvlue);
	var paygtotalamount = Math.round(paygtotalvlue);

    parent.find('.total').html(totalamount.toFixed(2));
    parent.find('.paygCal').val(paygtotalamount.toFixed(2));
    parent.find('#hourly_amt').val(totalamount.toFixed(2));

   // parent.find('.grsossamount').html(totalvlue.toFixed(2));
    totalsumvalu();
    deductionCal();
    leaveCal();
    superCal();

});
$(document).ready(function () {
	var at = autoTotal();
	// withholding(total); //??Need uncomment
	$('.subtotal').html(at.total.toFixed(2));
	$('#gross_pay').val(at.total.toFixed(2));
	advance_payment(at.total);
	employeePuchase(at.total);
	unionfee(at.total);
    payg(at.paygCalTotal);
	// totaldeducation(at.total); //??Need uncomment
	// hourcalculate(at.totalhourse); //??Need uncomment
    superCal();
});
// auto calclutate end
function autoTotal(){
    var total = paygCalTotal = totalhourse = 0;
    var annualLeave = $(".annualLeave").val();
    var personalLeave = $(".personalLeave").val();
    $('.total').each(function () {
        val = parseFloat($(this).html()) | 0;
        total = val ? (parseFloat(total + val)) : total;
    });

    $('.paygCal').each(function () {
        paygval = parseFloat($(this).val()) | 0;
        paygCalTotal = paygval ? (parseFloat(paygCalTotal + paygval)) : paygCalTotal;
    });
    paygCalTotal = paygCalTotal + parseFloat(annualLeave) + parseFloat(personalLeave);
    $(".hourcount").each(function () {
        totalhourse += parseFloat(this.value);
    });
    return {total:total,paygCalTotal:paygCalTotal,totalhourse:totalhourse};
}
function totalsumvalu(){
    var at = autoTotal();
    payg(at.paygCalTotal);
	// withholding(total);  //Need TO uncomment
	advance_payment(at.total);
	employeePuchase(at.total);
	unionfee(at.total);
	// totaldeducation(at.total); //Need TO uncomment
	$('.subtotal').html(at.total.toFixed(2));
	$('#gross_pay').val(at.total.toFixed(2));
	hourcalculate(at.totalhourse);
}


function payg(amt){
    // let amt = $('#hourly_amt').val();
    // let amt = $("#gross_pay").val();
    var totalLeave = 0;
    $('.totalLeave').each(function () {
        val = parseFloat($(this).html()) | 0;
        totalLeave = val ? (parseFloat(totalLeave + val)) : totalLeave;
        // console.log(val);
    });

    let taxTable = '{{$payslip->employee->tax_table}}';
    let payFrequency = '{{$payslip->employee->pay_frequency}}';
    let url = '{{route("prepayroll.payg")}}';
    $.ajax({
        url:url,
        method:'get',
        data:{
            amount:amt,
            taxTable:taxTable,
            payFrequency:payFrequency
        },
        success:res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("#withholdingpay").val(res.payg);
                $(".witholdingpay").html(res.payg.toFixed(2));
                $(".witholdingpay_input").val(res.payg);
                let grossPay = $("#gross_pay").val();
                $(".totalDeduc").val((totalLeave));
                $(".net_pay").html((grossPay - (totalLeave + res.payg)).toFixed(2));
                $("#net_pay").val((grossPay - (totalLeave + res.payg)).toFixed(2));
            }
        }
    });
}
function withholding(weekly){
	var method = 1;
	var extra_tax = 44.00;
	var url = "";
	var paymentmeth = "Weekly";
	if(paymentmeth == 'Weekly'){
		var mainvalue = +weekly;
	}else if(paymentmeth == 'Fortnightly'){
		var mainvalue = +weekly /2;
	}else{
		var mainvalue = +weekly*3/13;
	}

	$.ajax({
		url:url,
		type:"POST",
		data:{weekly:mainvalue, method:method},
		dataType:'json',
		success:function(data){

			if(paymentmeth == 'Weekly'){
				var total_tax = data + +extra_tax;
			 $('#withholdingpay').val(total_tax.toFixed(2));
			 $('.witholdingpay').html(total_tax.toFixed(2));
			}else if(paymentmeth == 'Fortnightly'){
				var withholding = data*2;
				var total_tax = withholding + +extra_tax;
				$('#withholdingpay').val(total_tax.toFixed(2));
				$('.witholdingpay').html(total_tax.toFixed(2));
			}else{
				var withholding = data*13/3;
				var total_tax = withholding + +extra_tax;
				$('#withholdingpay').val(total_tax.toFixed(2));
				$('.witholdingpay').html(total_tax.toFixed(2));
			}


		}
	});

}



function advance_payment(total){
        var ded_equlas    = 1;
        var ded_equalsval = 0;
		if(ded_equlas == 1){
			var payment = total / 100 * ded_equalsval;
			$('.advance_payment').html(payment.toFixed(2));
			$('#advance_repayment').val(payment.toFixed(2));
		}else{
            var payment = 0;
			$('.advance_payment').html(payment.toFixed(2));
			$('#advance_repayment').val(payment.toFixed(2));
		}

}
myFunction=function(d){
    wages_name = d;
}
function deductionCal(){
    // var amt = $('#hourly_amt').val();
    var amt = $('#gross_pay').val();
    // if(wages_name == 'Basic Wages' || wages_name == 'Basic Salary'){
        var el = document.getElementsByClassName("deduc_rate");
        for (var i=1;i<=el.length; i++){
            var deduc_rate = $('#deduc_rate'+i).val();
            $(".union_fee"+i).html((amt * (deduc_rate/100)).toFixed(2));
            $(".union_fee_input"+i).val(amt * (deduc_rate/100));
        }
    // }
}
function leaveCal(){
    var totalhourse = 0
    $(".hourcount").each(function () {
        totalhourse += parseFloat(this.value);
    });
    var el = document.getElementsByClassName("leave_rate");
    for (var i=1;i<=el.length; i++){
        var leave_rate = $('#leave_rate'+i).val();
        $(".leave_acrual"+i).val((totalhourse * (leave_rate/100)).toFixed(4));
    }
}
function superCal(){
    let grossAmt = $("#gross_pay").val();
    let dbGross = '{{$grossPayAmt}}';
    let grossPay = parseFloat(grossAmt) + parseFloat(dbGross);
    let grossCal = parseFloat(grossAmt) + parseFloat('{{$grossPayCal}}');
    let superAmt = grossPay * 0.095;
    if(grossPay >=450 || grossCal >=450){
    var el = $(".super");
    for (var i=1;i<=el.length; i++){
        $(".superAmt"+i).val(superAmt.toFixed(2));
        $(".superAmt"+i).html(superAmt.toFixed(2));
    }}else{
    var el = $(".super");
    for (var i=1;i<=el.length; i++){
        $(".superAmt"+i).val(0.00);
        $(".superAmt"+i).html(0.00);
    }
    }
    // console.log(grossPay);
}
function hourcalculate(totalhourse)
{
    var ent_annual_leave = 1;
    var equals 			 = 1;
    var equalvalue 		 = 7.6923;

    if(ent_annual_leave == 1){

        if(equals != null){
            var perchintige = totalhourse /100 * equalvalue;
            if(wages_name == "Personal Leave Pay"){
                $('#personal_leave_ac').val(0);
            }else if(wages_name == "Annual Leave pay"){
                $('#annual_leave_acrual').val(0);
            }else{
                $('#annual_leave_acrual').val(perchintige.toFixed(3));
            }

        }else{
            $('#annual_leave_acrual').val(0);
        }

    }else{
        $('#annual_leave_acrual').val(0);
    }
	var ent_personal_leave_acc = 1;
	var per_equals 			= 1;
	var per_equals_val 		= 3.8462;

	if(ent_personal_leave_acc == 1){

		if(per_equals != null){
			var personalperctive 	= totalhourse/100 * per_equals_val;
				if(wages_name == "Personal Leave Pay"){
				   	$('#personal_leave_ac').val(0);
				}else if(wages_name == "Annual Leave pay"){
				   	$('#annual_leave_acrual').val(0);
				}else{
				  	$('#personal_leave_ac').val(personalperctive.toFixed(3));
				}
		}else{
			$('#personal_leave_ac').val(0);
		}

	} else{
		$('#personal_leave_ac').val(0);
	}
}




function employeePuchase(total){
	var de_emp_equl    = +'1';
	var de_emp_equlval = 0;
	if(de_emp_equl == 1){
		var payment = total / 100 * de_emp_equlval;
		$('.employee_purchase').html(payment.toFixed(2));
		$('#employee_purchase').val(payment.toFixed(2));
	}else{
        var payment = 0;
		$('.employee_purchase').html(payment.toFixed(2));
		$('#employee_purchase').val(payment.toFixed(2));
	}
}


function unionfee(total){

	// var deunionequals    = +'1';

	// var deunionequalsval = 0;

	// if(deunionequals == 1){
	// 	var payment = total / 100 * deunionequalsval;
	// 	$('.union_fee').html(payment.toFixed(2));
	// 	$('#union_fee').val(payment.toFixed(2));
	// }else{
    //     var payment = 0;
	// 	$('.union_fee').html(payment.toFixed(2));
	// 	$('#union_fee').val(payment.toFixed(2));
	// }

}


function totaldeducation(total)
{

	var advance_re_status = +'1';
	var dedunionfee_re_status = +'1';
	var dedemppurc_re_status = +'1';

	var method = 1;
	var extra_tax = 44.00;

	var url = "";
	var paymentmeth = "Weekly";
	if(paymentmeth == 'Weekly'){
		var mainvalue = +total;
	}else if(paymentmeth == 'Fortnightly'){
		var mainvalue = +total /2;
	}else{
		var mainvalue = +total*3/13;
	}

	$.ajax({
		url:url,
		type:"POST",
		data:{weekly:mainvalue, method:method},
		dataType:'json',
		success:function(data){

			if(paymentmeth == 'Weekly'){
			var tax_amount = +data;
			}else if(paymentmeth == 'Fortnightly'){
				var tax_amount = +data*2;
			}else{
				var tax_amount = +data*13/3;
			}


		if(advance_re_status == 1){
		var advance_repayment = $('#advance_repayment').val();
		}else{
			var advance_repayment = 0;
		}

		if(dedunionfee_re_status == 1){
			var union_fee 		  = $('#union_fee').val();
		}else{
			var union_fee 		  = 0;
		}

		if(dedemppurc_re_status == 1){

		var employee_purchase = $('#employee_purchase').val();
		}else{
			var employee_purchase = 0;
		}

		var total_deducation  = +advance_repayment + +employee_purchase + +union_fee + +tax_amount + +extra_tax;
		var net_value   = total - total_deducation;
		$('.net_pay').html(net_value.toFixed(2));




		var oldgrossamount = 0;

		var oldvalue_totalvlu = total + oldgrossamount;



		if(oldgrossamount > 450){
			var supernationgrante = +'0.00';
			var supernationvalue  = total/100 * supernationgrante;

		}else{
			if(oldvalue_totalvlu >= 450){
				var supernationgrante = +'0.00';
				var supernationvalue  = oldvalue_totalvlu/100 * supernationgrante;
				}else{
					var supernationvalue  = 0;
				}
		}

		$('.supernationpay').html(supernationvalue.toFixed(2));
		$('#superannuatingrante').val(supernationvalue.toFixed(2));




		/*

		if(paymentmeth == 'Weekly'){

			if(total >= 103.84){
		    var supernationgrante = +'0.00';
			var supernationvalue  = total/100 * supernationgrante;
			}else{
				var supernationvalue  = 0;
			}

		} else if(paymentmeth == 'Fortnightly'){

			var weeklyamount = total/2;


			if(weeklyamount >= 103.84){
			var supernationgrante = +'0.00';
			var supernationvalue  = total/100 * supernationgrante;
			}else{
				var supernationvalue =0;
			}


		} else{
			var weeklyamount = total*13/3;
			if(weeklyamount >= 103.84){
				var supernationgrante = +'0.00';
				var supernationvalue  = total/100 * supernationgrante;
			} else{
				var supernationvalue  = 0;
			}

		}
		*/
		}
	});




}
</script>
@stop
