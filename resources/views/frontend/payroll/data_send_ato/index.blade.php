@extends('frontend.layout.master')
@section('title','SEND DATA TO STP')
@section('content')
<?php $p="sda"; $mp="payroll";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('generate_stp')}}" method="POST">
                            @csrf
                            <table id="example" class="table table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 13px">
                                    <tr>
                                        <th>
                                            <input id="check" type="checkbox" class="allcheck mr-1">
                                            <label for="check" class="checkbox-inline" style="margin: 0;padding:0">All Check Employee Name</label>
                                        </th>
                                        <th>Payment Date</th>
                                        <th style="text-align:right;">Translation ID</th>
                                        <th style="text-align:right;">Gross Pay</th>
                                        <th style="text-align:right;">Tax Amount</th>
                                        <th style="text-align:right;">Deduction</th>
                                        <th style="text-align:right;">Net Pay</th>
                                        <th>Pay Cycle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payslips as $payslip)
                                    <tr>
                                        <td>&nbsp;
                                            <label class="checkbox-inline" style="margin: 0;padding:0">
                                                <input type="checkbox" name="check_id[]" class="check_id"
                                                    value="{{$payslip->id}}" class="allcheck" >
                                                {{$payslip->employee->fullname}}
                                            </label>
                                        </td>
                                        <td class="text-center">{{$payslip->pay_accum->tran_date->format('d/m/Y')}}</td>
                                        <td class="text-center">{{$payslip->tran_id}}</td>
                                        <td class="text-right">{{number_format($payslip->pay_accum->gross,2)}}</td>
                                        <td class="text-right">{{number_format($payslip->pay_accum->payg,2)}}</td>
                                        <td class="text-right">{{number_format($payslip->pay_accum->deduc,2)}}</td>
                                        <td class="text-right">{{number_format($payslip->pay_accum->net_pay,2)}}</td>
                                        <td class="text-center">{{$payslip->pay_accum->pay_period}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9">
                                            <h1 class="display-1 text-danger text-center">EMPTY</h1>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table><br><br>
                            <div class="row" style="">
                                <div class="col-md-1">
                                </div>
                                <input type="hidden" id="sub_val" name="sub_val" value="" />
                                <div class="col-md-10" align="center">
                                    <b>To Generate STP File </b>
                                    <button type="submit" class="btn btn-primary btn-sm check btnClick tooltip-slide"
                                        onclick="return CheckForm()" aria-label="I move a lot!">
                                        <i class="fa fa-print" aria-hidden="true"></i> Click Here
                                    </button>
                                    <b>after saving the file send data to ATO upload the File</b>
                                    <a class="btn btn-primary btn-sm "
                                        href="https://dashboard-stp.ozedi.com.au/upload?link=10211199117-U" target="_blank">
                                        <i style="color:violt" class="fa fa-hand-o-right fa-1x" aria-hidden="true">Click
                                            Here</i>
                                    </a>
                                </div>
                                <div class="col-md-1" align="right">
                                </div>
                            </div>
                        </form>
                        <style>
                            .img-responsive,
                            .thumbnail a>img,
                            .thumbnail>img {
                                display: block;
                                max-width: 100%;
                                height: auto;
                            }
                        </style>


                        <div class="container" style="padding-top:20px; ">
                            <div class="" style=" margin:50px;">
                                <div style="background: #337ab7; padding:8px;color:white">Follow the procedure:-</div>
                                <div style="border:1px solid #337ab7;padding:8px">
                                    <img src="https://www.aarks.com.au/upload/1.PNG" class="img-responsive" />

                                    1.Before you click check credentials,Please contact your software provider to get
                                    all those informations.

                                    <img src="https://www.aarks.com.au/upload/2.PNG" class="img-responsive" /> 2. Select
                                    email address from dropdown and click generate authentication button. Check your
                                    email when you get authentication code fill out the authentication code. <br />
                                    3. FIll out with your correct ABN and click Intermediary tick box. Do not tick
                                    multiple ABN. <br />
                                    4. Select messege type, "PAYVENT-(submit v 3)". <br />
                                    5. Click choose file to upload and browse where you downloded the file previously.
                                    <br />

                                    <img src="https://www.aarks.com.au/upload/3.PNG" class="img-responsive" />
                                    6: Before you click upload button make sure you have ticked the declaration
                                    box<br />
                                    <img src="https://www.aarks.com.au/upload/4.PNG" class="img-responsive" />
                                    7: Wait for ato response by above mention email<br />

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->


<!-- inline scripts related to this page -->
<!-- Data Table -->
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
    $(".allcheck").click(e=>{
        if ($(".allcheck").is(':checked')) {
            $('input:checkbox').prop("checked", true);
        } else {
            $('input:checkbox').prop("checked", false);
        }
    });
    function CheckForm(){
        var checked=false;
        var elements = document.getElementsByName("check_id[]");
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
