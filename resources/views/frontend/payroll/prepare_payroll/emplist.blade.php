@extends('frontend.layout.master')
@section('title','Prepare Payroll')
@section('content')
<?php $p="pp"; $mp="payroll";?>
<style>
    label {
        display: inline-block;
        margin-bottom: 0px;
    }
    </style>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form method="POST" action="{{route('payslip.store')}}">
                    @csrf
                    <input type="hidden" name="tran_id" value="{{$payProcess->first()->tran_id}}">
                    <div class="row" style="border: 1px solid #ebebeb; padding: 10px; min-height:355px;">
                        <div class="col-md-3" align="center">
                            <strong style="font-size:18px; color:#9e9e9e;">Payroll processing <br> <span
                                    style="color:#424bc8;">{{$payProcess->first()->pay_period}}</span></strong>

                            <input type="hidden" name="pay_period" value="{{$payProcess->first()->pay_period}}">
                        </div>
                        <div class="col-md-2">
                            <strong style="font-size:18px; color:#9e9e9e;">Payment Date <br> <span
                                    style="color:#424bc8;">{{ \Carbon\Carbon::parse($payProcess->first()->payment_date)->format('d/m/Y')}}</span></strong>
                            <input type="hidden" name="payment_date" value="{{$payProcess->first()->payment_date}}">
                        </div>
                        <div class="col-md-2">
                            <strong style="font-size:18px; color:#9e9e9e;">Start Period <br> <span
                                    style="color:#424bc8;">{{\Carbon\Carbon::parse($payProcess->first()->payperiod_start)->format('d/m/Y')}}</span></strong>
                            <input type="hidden" name="start_period" value="{{$payProcess->first()->payperiod_start}}">
                        </div>
                        <div class="col-md-2">
                            <strong style="font-size:18px; color:#9e9e9e;">End Period <br> <span
                                    style="color:#424bc8;">{{\Carbon\Carbon::parse($payProcess->first()->payperiod_end)->format('d/m/Y')}}</span></strong>
                            <input type="hidden" name="end_period" value="{{$payProcess->first()->payperiod_end}}">
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
                                    @forelse ($payProcess as $process)
                                    <tr>
                                        <td>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="empid[]" class="emptyid" value="{{$process->id}}"> &nbsp;
                                                {{$process->employee_card->fullname}}
                                            </label>
                                        </td>
                                        <td style="text-align: center;">{{$process->employee_card->tax_number}}</td>
                                        <td style="text-align: right;">{{number_format($process->gross,2)}}</td>
                                    <td style="text-align:right;">{{number_format($process->gross - $process->net_pay,2)}}</td>
                                        <td style="text-align:right;">{{number_format($process->net_pay,2)}}</td>
                                        <td align="center">
                                            <a title="Payroll Edit" href="{{Route("prepayroll.preedit",[$process->employee_card_id,$process->id])}}"  title="Edit Hours"><i class="fa fa-edit"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">
                                            <h1 class="display-1 text-danger text-center">
                                                Empty Table
                                            </h1>
                                        </td>
                                    </tr>

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="offset-lg-10 col-lg-2">
<div class="pull-right row">
    <div class="col-md-6 pull-center">
        <a href="{{route('prepayroll.empselect',[$payProcess->first()->client_id,$payProcess->first()->profession_id])}}" class="btn btn-warning btn-sm pull-right previous1">Previous</a>
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
    }
});
function CheckForm(){
    var checked=false;
    var elements = document.getElementsByName("empid[]");
    for(var i=0; i < elements.length; i++){
        if(elements[i].checked) {
            checked=true;
        }
    }
    if (!checked) {
        alert('Please Check at least one employee');
        return false;
    }
    return checked;
}
</script>

@stop
