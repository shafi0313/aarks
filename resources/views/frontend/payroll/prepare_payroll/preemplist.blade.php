@extends('frontend.layout.master')
@section('title','Prepare Payroll')
@section('content')
<?php $p="pp"; $mp="payroll";?>

<!-- Page Content Start -->
<style>
label {
    display: inline-block;
    margin-bottom: 0px;
}
</style>
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form method="POST" action="{{route('prepayroll.payslipStore')}}">
                @csrf
                    <div class="row" style="border: 1px solid #ebebeb; padding: 10px; min-height:355px;">
                        <div class="col-md-3" align="center">
                            <strong style="font-size:18px; color:#9e9e9e;">Payroll processing <br> <span
                                    style="color:#424bc8;">{{$accumAmt->pay_period}}</span></strong>

                            <input type="hidden" name="pay_period" value="{{$accumAmt->pay_period}}">
                            <input type="hidden" name="pay_accum_amt_id" value="{{$accumAmt->id}}">
                            <input type="hidden" name="tran_id" value="{{$tran_id}}">
                            <input type="hidden" name="employee_card_id" value="{{$accumAmt->employee_card_id}}">
                            <input type="hidden" name="client_id" value="{{$accumAmt->client_id}}">
                        </div>
                        <div class="col-md-2">
                            <strong style="font-size:18px; color:#9e9e9e;">Payment Date <br> <span
                                    style="color:#424bc8;">{{ \Carbon\Carbon::parse($accumAmt->payment_date)->format('d/m/Y')}}</span></strong>
                            <input type="hidden" name="payment_date" value="{{$accumAmt->payment_date}}">
                        </div>
                        <div class="col-md-2">
                            <strong style="font-size:18px; color:#9e9e9e;">Start Period <br> <span
                                    style="color:#424bc8;">{{\Carbon\Carbon::parse($accumAmt->pay_period_start)->format('d/m/Y')}}</span></strong>
                            <input type="hidden" name="start_period" value="{{$accumAmt->pay_period_start}}">
                        </div>
                        <div class="col-md-2">
                            <strong style="font-size:18px; color:#9e9e9e;">End Period <br> <span
                                    style="color:#424bc8;">{{\Carbon\Carbon::parse($accumAmt->pay_period_end)->format('d/m/Y')}}</span></strong>
                            <input type="hidden" name="end_period" value="{{$accumAmt->pay_period_end}}">
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;">
                                            <label class="checkbox-inline"><input type="checkbox" class="allcheck"
                                                    value="1"> &nbsp; All
                                                checked Employee</label>
                                        </th>
                                        <th style="text-align: center;">T F N</th>
                                        <th style="text-align: center;">Gross Amount</th>
                                        <th style="text-align: center;">Tax Amount</th>
                                        <th style="text-align: center;">Net Pay</th>
                                        <th style="text-align: center;">Edit Hrs./Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="empid[]" class="emptyid" value="163"> &nbsp;
                                                {{$accumAmt->employee->fullname}}
                                            </label>
                                        </td>
                                        <td style="text-align: center;">777</td>
                                        <td style="text-align: right;">{{number_format($accumAmt->payg,2)}}</td>
                                        <td style="text-align:right;">0.00</td>
                                        <td style="text-align:right;">{{number_format($accumAmt->net_pay,2)}}</td>
                                        <td align="center">
                                            <a href="{{Route("prepayroll.payaccumedit",$accumAmt->id)}}" title="Edit Hours" onclick="return check()">edit</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="offset-lg-10 col-lg-2">
<div class="pull-right row">
    <div class="col-md-6 pull-center">
    </div>
    <div class="col-md-6">
        <button type="submit" class="btn btn-primary btn-sm pull-right" onclick="return CheckForm()">Proceed </button>
    </div>
</div>
                        </div>
                    </div><!-- main div end -->
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->

<script>
$('.datepicker').datepicker({
    autoclose: true,
    todayHighlight: true
});
function check(){
    if ($(".emptyid").is(':checked')) {
        return true;
    }else{
        alert('Please Check one Employee');
        return false;
    }
}
$(".allcheck").click(e=>{
    if ($(".allcheck").is(':checked')) {
        $('input:checkbox').prop("checked", true);
    } else {
        $('input:checkbox').prop("checked", false);
        var emptyid = 0;
        var counturl = "https://www.aarks.com.au/payroll/Prepare_payroll/allcheckout";
        $.ajax({
            url:counturl,
            type:"POST",
            data:{emptyid:emptyid},
            success:res=>{
            }
        });
    }
});
function CheckForm(){
    var checked=false;
    var elements = document.getElementsByName("empid[]");
    for(var i=0; i < elements.length; i++){
        if(elements[i].checked) {
            checked=true; }
    }
    if (!checked) {
        alert('Please Check at least one employee');
        return false;
    }
    return checked;
}
</script>

@stop
