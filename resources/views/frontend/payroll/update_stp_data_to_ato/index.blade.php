@extends('frontend.layout.master')
@section('title','Update STP Report')
@section('content')
<?php $p="upd"; $mp="payroll";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('SendDataAto.update',$ato->id)}}" method="POST">
                            @csrf
                            @method('put')
                            <table id="example" class="table table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th>
                                            <input id="check" type="checkbox" class="allcheck mr-1">
                                            <label for="check" class="checkbox-inline">All Check Employee Name</label>
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
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="check_id[]" class="check_id"
                                                    value="{{$payslip->id}}" class="allcheck">
                                                {{$payslip->employee->fullname}}
                                            </label>
                                        </td>
                                        <td>{{$payslip->pay_accum->tran_date->format('d/m/Y')}}</td>
                                        <td>
                                            {{$payslip->tran_id}}
                                        </td>
                                        <td class="text-right">{{number_format($payslip->pay_accum->gross,2)}}</td>
                                        <td class="text-right">{{number_format($payslip->pay_accum->payg,2)}}</td>
                                        <td>{{$payslip->pay_accum->deduc}}</td>
                                        <td class="text-right">{{number_format($payslip->pay_accum->net_pay,2)}}</td>
                                        <td>{{$payslip->pay_accum->pay_period}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9">
                                            <h1 class="display-1 text-danger text-center">EMPTY</h1>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-sm-5" ><b>Is it last payrun?</b></label>
                                        <div class="col-sm-4">
                                            <select name="payrun" id="payrun" class="form-control">
                                                <option value="0" selected="selected">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 text-right">
                                    <b>Generate STP Update File </b>
                                    <button style=" background-color:#CC6600;" type="submit"
                                        class=" btn-info btn-sm check btnClick2" onclick="return CheckForm()">
                                        <i class="fa fa-print" aria-hidden="true"></i> Update STP File
                                    </button>
                                </div>

                            </div>
                        </form>
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
