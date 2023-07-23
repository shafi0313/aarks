@extends('frontend.layout.master')
@section('title','Select Activity')
@section('content')
<?php $p="pp"; $mp="payroll";?>
<style>
    .form-group {
    margin-bottom: -6px;
}
</style>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label text-right">Number:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" readonly name="number" value="4">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label text-right">Payment Date:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" readonly name="payment_date" value="{{$prepay->payment_date->format('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label text-right">Pay Period Start:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" readonly name="pay_period_start" value="{{$prepay->pay_period_start->format('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label text-right">Pay Period Ending:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" readonly name="pay_period_end" value="{{$prepay->pay_period_end->format('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label text-right">Pay Cycle:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" readonly name="pay_period" value="{{$prepay->pay_period}} ">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label">Employee:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" readonly name="empName" value="{{$empCard->fullname}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label">Statement Text:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" name="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label">Memo:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" name="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label">Gross Pay:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" name="grossPay" disabled id="grossPay">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label">Net Pay:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" name="netPay" disabled id="netPay">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm">
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
                                    @foreach (json_decode($empCard->wages) as $wages)
                                    <tr>
                                        <td style="text-align:right;">{{$wages}} <input type="hidden" name="wagesid[]" id="wagesid[]" value="6">
                                            <input type="hidden" name="typeofages" id="typeofages" class="typeofages" value="Hourly">
                                        </td>
                                        <td style="text-align:center; width:15%; padding:0px;">
                                            <input class="form-control totalhour hourcount "
                                                onkeypress="myFunction('Base Hourly')" autocomplete="off" type="text"
                                                name="emphour[]" id="emphour[]" value="20.00">
                                        </td>
                                        <td>Wages &amp; Salaries Expenses</td>
                                        <td class="total">200.00</td>
                                        <td><input type="hidden" id="hourlyamount" class="hourlyamount"
                                                name="hourlyamount[]" value="10.00"></td>
                                    </tr>
                                    @endforeach

                                    <tr style="background:#ebebeb;">
                                        <td colspan="5">Deductions and Taxes</td>
                                    </tr>
                                    @forelse (json_decode($empCard->deduction) as $deduc)
                                    <tr>
                                        <td style="text-align:right;">{{$deduc}}</td>
                                        <td style="text-align:center; width:15%; padding:0px;">
                                            <input class="form-control" type="hidden" name="advance_repayment"
                                                readonly="" id="advance_repayment" value="0.00">
                                        </td>
                                        <td></td>
                                        <td class="advance_payment">0.00</td>
                                        <td></td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    <tr style="background:#ebebeb;">
                                        <td colspan="5">Leave</td>
                                    </tr>
                                    @forelse (json_decode($empCard->leave) as $leave)
                                    <tr>
                                        <td style="text-align:right;">{{$leave}}</td>
                                        <td style="text-align:center; width:15%; padding:0px;">
                                            <input class="form-control" type="text" name="annual_leave_acrual"
                                                id="annual_leave_acrual" value="1.53846">
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    <tr style="background:#ebebeb;">
                                        <td colspan="5">Superannuation</td>
                                    </tr>
                                    @forelse (json_decode($empCard->superannuation) as $sup)
                                    <tr>
                                        <td style="text-align:right;">{{$sup}}</td>
                                        <td style="text-align:center; width:15%; padding:0px;">
                                            <input class="form-control" type="text" name="superannuatingrante"
                                                readonly="" id="superannuatingrante" value="1.9">
                                        </td>
                                        <td>Supernnuation Payable</td>
                                        <td class="supernationpay">0.00</td>
                                        <td></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">Empty Superannuation</td>
                                    </tr>

                                    @endforelse
                                </tbody>
                            </table>

                            <div class="row justify-content-end" >
                                <span class="mr-3" style="color:red; font-weight:800;font-size:18px;">You must save all entered hours if you want to processed this pay run </span>
                                <button type="submit" class="btn btn-primary mr-2">Save</button>
                                <a href="" class="btn btn-primary">Back</a>
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

@stop
