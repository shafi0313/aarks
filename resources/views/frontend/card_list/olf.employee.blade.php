@extends('frontend.layout.master')
@section('title','Create')
@section('content')
<?php $p="cl"; $mp="cf";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <nav>

                                <div class="nav nav-tabs" id="nav-tab" role="tablist" style="font-size: 15px;">

                                    <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">Profile</a>

                                    <a class="nav-item nav-link" id="nav-Wages-tab" data-toggle="tab" href="#nav-Wages" role="tab" aria-controls="nav-Wages" aria-selected="false">Wages and Tax</a>

                                    <a class="nav-item nav-link" id="nav-pay-tab" data-toggle="tab" href="#nav-pay" role="tab" aria-controls="nav-pay" aria-selected="false">Payment Method</a>

                                    <a class="nav-item nav-link" id="nav-ent-tab" data-toggle="tab" href="#nav-ent" role="tab" aria-controls="nav-ent" aria-selected="false">Entatilement</a>

                                    <a class="nav-item nav-link" id="nav-sup-tab" data-toggle="tab" href="#nav-sup" role="tab" aria-controls="nav-sup" aria-selected="false">Superannuation</a>

                                    <a class="nav-item nav-link" id="nav-ded-tab" data-toggle="tab" href="#nav-ded" role="tab" aria-controls="nav-ded" aria-selected="false">Deduction</a>

                                    <a class="nav-item nav-link" id="nav-standered-tab" data-toggle="tab" href="#nav-standered" role="tab" aria-controls="nav-standered" aria-selected="false">Standered Slip</a>

                                    <a class="nav-item nav-link" id="nav-pay_hostory-tab" data-toggle="tab" href="#nav-pay_hostory" role="tab" aria-controls="nav-pay_hostory" aria-selected="false">Pay Hostory</a>

                                    <a class="nav-item nav-link" id="nav-other-tab" data-toggle="tab" href="#nav-other" role="tab" aria-controls="nav-other" aria-selected="false">Other Info</a>

                                </div>
                            </nav>

                            <div class="tab-content" id="nav-tabContent">
                                <!-- Profile Start -->
                                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <br>
                                    <div class="row justify-content-center">
                                        <label class="col-sm-2 t_red">Employee Status</label>

                                        <div class="col-1 form-check">
                                            <input class="form-check-input" type="radio" name="1" id="exampleRadios1" value="1">
                                            <label class="form-check-label" for="exampleRadios1">Active</label>
                                        </div>
                                        <div class="col-1 form-check">
                                            <input class="form-check-input t_red" type="radio" name="1" id="exampleRadios2" value="2">
                                            <label class="form-check-label" for="exampleRadios2">Inactive</label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">First Name: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Date of Birth: </label>
                                                    <input class="col-8 form-control" type="date" name="" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Last Name: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Gender: </label>
                                                    <select class="col-8 form-control" name="" value="">
                                                        <option value="">Male</option>}
                                                        <option value="">Female</option>}
                                                        option
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Address: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">City: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">State: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Postcode: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Country: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Phone No: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Mobile Number: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Email: </label>
                                                    <input class="col-8 form-control" type="" name="" value="">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div  style="text-align:center; margin-left: 180px;">Employment Details</div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Start Date: </label>
                                                    <input class="col-8 form-control" type="date" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Termination Date: </label>
                                                    <input class="col-8 form-control" type="date" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Employment Basis: </label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select Employment Basis</option>
                                                        <option value="">Individual</option>
                                                        <option value="">Labor Hire</option>
                                                        <option value="">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Employment Category: </label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select Employment Category</option>
                                                        <option value="">Payment</option>
                                                        <option value="">Temporary</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Employment Status: </label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select Employment Status</option>
                                                        <option value="">Full Time</option>
                                                        <option value="">Part Time</option>
                                                        <option value="">Casual</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Employment Classification: </label>
                                                    {{-- <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select Employment Classification</option>
                                                        @foreach ($ECS as $item)
                                                            <option value="{{$item->id}} ">{{$item->name}} </option>
                                                        @endforeach
                                                    </select> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div align="center">
                                        <button type="submit" class="btn btn-success" style="width: 300px">Submit</button>
                                    </div>
                                </div>
                                <!-- Profile End -->

                                <!-- Wages and Tax Start -->
                                <div class="tab-pane fade show" id="nav-Wages" role="tabpanel" aria-labelledby="nav-Wages-tab">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Pay Basis: </label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select Pay Basis</option>
                                                        <option value="">Salary</option>
                                                        <option value="">Hourly</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Annual Salary: </label>
                                                    <input class="col-8 form-control"  type="number" name="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Hourly Rate: </label>
                                                    <input class="col-8 form-control" type="number" name="">
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Pay Frequency: </label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select Pay Frequency</option>
                                                        <option value="">Weekly</option>
                                                        <option value="">Fortnightly</option>
                                                        <option value="">Monthly</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Hours in Pay Frequency:</label>
                                                    <input class="col-8 form-control" type="text" name="" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">Netpay Wages Account:</label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select Netpay Wages Account</option>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">T/Withhold exp A/c:</label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select T/Withhold exp A/c</option>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right t_red">T/Withhold Paybale A/c:</label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select T/Withhold Paybale A/c</option>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Tax File Number: </label>
                                                    <input class="col-8 form-control" type="text" name="" value="">
                                                </div>
                                            </div>
                                           <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Tax Table: </label>
                                                    <select class="col-8 form-control" type="text" name="" value="">
                                                        <option disabled selected value>Select Tax Table</option>
                                                        <option value="2">tax-free threshold</option>
                                                        <option value="">No tax-free threshold</option>
                                                        <option value="">Non Resident/Foregin resident</option>
                                                        <option value="">Tax file not decleared residence</option>
                                                        <option value="">Tax file not decleared non residence</option>
                                                        <option value="">Full medicare levy Exemption</option>
                                                        <option value="">Half medicare levy Exemption</option>
                                                        <option value="">Backpacker</option>
                                                        <option value="">Withhold Tax Offsets</option>
                                                        <option value="">HELP  and TSL</option>
                                                        <option value="">Student Loan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Withholding Variation Rate: </label>
                                                    <input class="col-8 form-control" type="text" name="">
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Total Rebates: </label>
                                                    <input class="col-8 form-control" type="text" name="">
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Extra Tax: </label>
                                                    <input class="col-8 form-control" type="text" name="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{-- @foreach ($wages as $wage)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="wageId[]" value="{{$wage->id}} "> {{$wage->name}}
                                            </label>
                                            @endforeach --}}
                                        </div>
                                    </div>
                                    <div align="center">
                                        <button type="submit" class="btn btn-success" style="width: 300px;">Submit</button>
                                    </div>
                                </div>
                                <!-- Wages and Tax End -->

                                <!-- Payment Details Start -->
                                <div class="tab-pane fade show" id="nav-pay" role="tabpanel" aria-labelledby="nav-pay-tab">
                                    <div class="card">
                                        <div class="card-heading">
                                            <h3>Customer Payment Method</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Payment Method</label>
                                                        <select class="form-control" name="">
                                                            <option disabled selected value>Select Payment Method</option>
                                                            <option value="1">Cash</option>
                                                            <option value="2">Cheque</option>
                                                            <option value="3">Bank Transfer</option>
                                                            <option value="4">Money Order</option>
                                                            <option value="5">Eftpos</option>
                                                            <option value="6">Bank Card</option>
                                                            <option value="7">Master Card</option>
                                                            <option value="8">Visa Card</option>
                                                            <option value="9">AMEX</option>
                                                            <option value="10">Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Payroll Payment linked Acccount:</label>
                                                        <select class="form-control" name="">
                                                             <option value="1934" >510021 => Cash at Bank</option>
                                                             <option value="1935" >510050 => Cash in Hand</option>
                                                             <option value="2565" >510045 => Eftpos- 1  Received</option>
                                                             <option value="3179"  selected >510048 => Payroll Clearing Account</option>
                                                             <option value="3739" >510100 => Suspense clearing account.</option>
                                                             <option value="4181" >510049 => Focus Taxation Bank Account</option>
                                                             <option value="4771" >510049 => F&F Bank account</option>
                                                             <option value="5868" >510047 => Fee From Refund</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Note: </label>
                                                        <textarea class="form-control" name="" rows="8"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div align="center">
                                                <button type="submit" class="btn btn-success btn3">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Payment Details End -->

                                <!-- Entatilement Information Start -->
                                <div class="tab-pane fade" id="nav-ent" role="tabpanel" aria-labelledby="nav-ent-tab">
                                    <div class="card">
                                        <div class="card-heading">
                                            <h3>Entatilement Information</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row justify-content-center">
                                                <label class="col-md-4">Select Entitlement for this Employee:</label>
                                                <div class="col-3 form-check">
                                                    <input class="form-check-input" id="c1" type="checkbox" name="">
                                                    <label class="form-check-label" for="c1">Annual Leave Accrual </label>
                                                </div>
                                                 <div class="col-3 form-check">
                                                    <input class="form-check-input" id="c2" type="checkbox" name="">
                                                    <label class="form-check-label" for="c2">Personal Leave Accrual</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4 checkbox">
                                                    <label>
                                                        <input type="checkbox" id="annual_calulaton" name="annual_calulaton" value="1"> Annual Leave Calulation Basis: <a href="#" data-toggle="tooltip" data-placement="top" title="Please do not tick this box unless you are confident with the tax law"><i  style="color:red;" class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                                                    </label>
                                                </div>
                                                <div class="col-md-8 annual_cal" style="display: none">
                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control" type="text" name="" value="7.6923">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label>Percent of:</label>
                                                            <select class="form-control mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary" >Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label>Hours Per:</label>
                                                            <select class="form-control mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Weekly" >Weekly</option>
                                                                <option value="Fortnightly" >Fortnightly</option>
                                                                <option value="Monthly" >Monthly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-8 my-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="">
                                                                <label class="form-check-label">Carry Remaining Entitlement Over to Next Year</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="inputPassword" class="col-sm-4 col-form-label">Linked Wages Category:</label>
                                                        <div class="col-sm-8">
                                                             <select class="form-control">
                                                                <option value="">Annual Leave Pay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="row">
                                                <div class="col-md-4 checkbox">
                                                    <label>
                                                        <input type="checkbox" id="personal_calulation" name="personal_calulation" value="1"> Personal  Leave Calulation  : <a href="#" data-toggle="tooltip" data-placement="top" title="Please do not tick this box unless you are confident with the tax law"><i  style="color:red;" class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                                                    </label>
                                                </div>
                                                <div class="col-md-8 personal_cal" style="display: none">
                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control" type="text" name="" value="7.6923">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label>Percent of:</label>
                                                            <select class="form-control mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary" >Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label>Hours Per:</label>
                                                            <select class="form-control mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Weekly" >Weekly</option>
                                                                <option value="Fortnightly" >Fortnightly</option>
                                                                <option value="Monthly" >Monthly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-8 my-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="">
                                                                <label class="form-check-label">Carry Remaining Entitlement Over to Next Year</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="inputPassword" class="col-sm-4 col-form-label">Linked Wages Category:</label>
                                                        <div class="col-sm-8">
                                                             <select class="form-control">
                                                                <option value="">Personal Leave Pay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                            <div align="center">
                                                <button type="submit" class="btn btn-success" style="width: 300px">Submit</button>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <!-- Entatilement Information End -->

                                <!-- Superannuation Start -->
                                <div class="tab-pane fade show" id="nav-sup" role="tabpanel" aria-labelledby="nav-sup-tab">
                                    <div class="card">
                                        <div class="card-heading">
                                            <h3></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group row ">
                                                        <label class="col-sm-4 col-form-label-sm">Superannuation Fund</label>
                                                        <div class="col-sm-8">
                                                            <select type="email" class="form-control form-control-sm" id="inputEmail3">
                                                                <option disabled selected value>Select Superannuation Fund</option>
                                                                <option value="">AddNew</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row ">
                                                        <label class="col-sm-4 col-form-label-sm t_red">Superannuation Exp.Account</label>
                                                        <div class="col-sm-8">
                                                            <select type="email" class="form-control form-control-sm" id="inputEmail3">
                                                                <option disabled selected value>Select Account</option>
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group row ">
                                                        <label class="col-sm-4 col-form-label-sm">Employee Membersip No:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label-sm">Superannuation Payable Account</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control form-control-sm">
                                                                <option disabled selected value>Select Account</option>
                                                                <option value="">AddNew</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center">
                                                <label class="col-md-4">Select Superannuation for this Employee:</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" id="c3" type="checkbox" name="">
                                                    <label class="form-check-label" for="c3">Employee Additional</label>
                                                </div>
                                                 <div class="form-check mx-3">
                                                    <input class="form-check-input" id="c4" type="checkbox" name="">
                                                    <label class="form-check-label" for="c4">Salary Sacrifice</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" id="c5" type="checkbox" name="">
                                                    <label class="form-check-label active" for="c5">Superannuation Guarantee</label>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-4 checkbox">
                                                    <label>
                                                        <input type="checkbox" id="employeadditional" name="" value="1">Employee Additional: <a href="#" data-toggle="tooltip" data-placement="top" title="Please do not tick this box unless you are confident with the tax law"><i  style="color:red;" class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                                                    </label>
                                                </div>
                                                <div class="col-md-8 employeeaddinal" style="display: none">
                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of: </label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary">Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row my-3">
                                                        <label class="col-sm-8 col-form-label t_b">Exclusions: Exclude the first of eligible wages from</label>
                                                        <div class="col-sm-4">
                                                            <input type="email" class="form-control form-control-sm">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label ">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="7.6923">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary" >Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row my-3">
                                                        <label class="col-sm-8 col-form-label t_b">Hreshold: Calculate once eligible wages of paid p/month</label>
                                                        <div class="col-sm-4">
                                                            <input type="email" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-4 checkbox">
                                                    <label>
                                                        <input type="checkbox" id="salary_sacrifice" name="salary_sacrifice"> Salary Sacrifice <a href="#" data-toggle="tooltip" data-placement="top" title="Please do not tick this box unless you are confident with the tax law"><i  style="color:red;" class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                                                    </label>
                                                </div>
                                                <div class="col-md-8 salary_sacrifice" style="display: none">
                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of: </label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary">Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row my-3">
                                                        <label class="col-sm-8 col-form-label t_b">Exclusions: Exclude the first of eligible wages from</label>
                                                        <div class="col-sm-4">
                                                            <input type="email" class="form-control form-control-sm">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label ">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="7.6923">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary" >Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row my-3">
                                                        <label class="col-sm-8 col-form-label t_b">Hreshold: Calculate once eligible wages of paid p/month</label>
                                                        <div class="col-sm-4">
                                                            <input type="email" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-4 checkbox">
                                                    <label>
                                                        <input type="checkbox" id="superannuatin_guarante" name="superannuatin_guarante"> Superannuation Guarantee <a href="#" data-toggle="tooltip" data-placement="top" title="Please do not tick this box unless you are confident with the tax law"><i  style="color:red;" class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                                                    </label>
                                                </div>
                                                <div class="col-md-8 superannuatin_guarante" style="display: none">
                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of: </label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary">Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row my-3">
                                                        <label class="col-sm-8 col-form-label t_b">Exclusions: Exclude the first of eligible wages from</label>
                                                        <div class="col-sm-4">
                                                            <input type="email" class="form-control form-control-sm">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label ">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="7.6923">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary" >Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row my-3">
                                                        <label class="col-sm-8 col-form-label t_b">Hreshold: Calculate once eligible wages of paid p/month</label>
                                                        <div class="col-sm-4">
                                                            <input type="email" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div align="center">
                                            <button type="submit" class="btn btn-success" style="width: 300px">Submit</button>
                                        </div>
                                    </div>

                                    <br>
                                </div>
                                <!-- Superannuation End -->

                                <!-- Deduction Start -->
                                <div class="tab-pane fade show" id="nav-ded" role="tabpanel" aria-labelledby="nav-ded-tab">
                                    <div class="card">
                                        <div class="card-heading">
                                            <h3></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row justify-content-center">
                                                <div class="col-sm-8">
                                                    <div class="form-group row ">
                                                        <label class="col-sm-4 t_b">Linked Payable Account:</label>
                                                        <div class="col-sm-8">
                                                            <select type="email" class="form-control">
                                                                <option disabled selected value>Select Superannuation Fund</option>
                                                                <option value="">340001 => Wages Net pay amount to Employee</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center">
                                                <label class="mr-4">Select Deduction for this Employee:</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" id="c6" type="checkbox" name="">
                                                    <label class="form-check-label" id="c6">Advance Repayment</label>
                                                </div>
                                                 <div class="form-check mx-3">
                                                    <input class="form-check-input" id="c7" type="checkbox" name="">
                                                    <label class="form-check-label" for="c7">Employee Puchase</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" id="c8" type="checkbox" name="">
                                                    <label class="form-check-label active" for="c8">Employee Puchase</label>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-4 checkbox">
                                                    <label>
                                                        <input type="checkbox" id="advnce_repayment"> Advance Repayment : <a href="#" data-toggle="tooltip" data-placement="top" title="Please do not tick this box unless you are confident with the tax law"><i  style="color:red;" class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                                                    </label>
                                                </div>
                                                <div class="col-md-8 advnce_repayment" style="display: none">
                                                    <div class="row">
                                                        <div class="col-sm-8 my-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="c9" type="checkbox" name="">
                                                                <label class="form-check-label" for="c9">Calculation Basis: User -Entered Amount per Pay Period</label>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" id="c10" type="radio" name="">
                                                                <label class="form-check-label" for="c10">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of: </label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary">Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check my-3">
                                                            <label class="form-check-label">Limit: </label>
                                                            <input type="radio" class="form-check-input mx-2">
                                                            <label class="form-check-label ml-5">No Limit</label>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label ">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="7.6923">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary" >Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-4 checkbox">
                                                    <label>
                                                        <input type="checkbox" id="employee_purchase" name="employee_purchase"> Employee Puchase : <a href="#" data-toggle="tooltip" data-placement="top" title="Please do not tick this box unless you are confident with the tax law"><i  style="color:red;" class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                                                    </label>
                                                </div>
                                                <div class="col-md-8 employee_purchase" style="display: none">
                                                    <div class="row">
                                                        <div class="col-sm-8 my-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="">
                                                                <label class="form-check-label">Calculation Basis: User -Entered Amount per Pay Period</label>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of: </label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary">Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check my-3">
                                                            <label class="form-check-label">Limit: </label>
                                                            <input type="radio" class="form-check-input mx-2">
                                                            <label class="form-check-label ml-5">No Limit</label>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label ">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="7.6923">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary" >Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-4 checkbox">
                                                    <label>
                                                        <input type="checkbox"id="union_fee" name="union_fee" value="1"> Union Fee: <a href="#" data-toggle="tooltip" data-placement="top" title="Please do not tick this box unless you are confident with the tax law"><i  style="color:red;" class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a>
                                                    </label>
                                                </div>
                                                <div class="col-md-8 union_fee" style="display: none">
                                                    <div class="row">
                                                        <div class="col-sm-8 my-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="">
                                                                <label class="form-check-label">Calculation Basis: User -Entered Amount per Pay Period</label>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of: </label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary">Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check my-3">
                                                            <label class="form-check-label">Limit: </label>
                                                            <input type="radio" class="form-check-input mx-2">
                                                            <label class="form-check-label ml-5">No Limit</label>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label ">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="7.6923">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Percent of:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Gross Hours"  selected >Gross Hours</option>
                                                                <option value="Basic Sallary" >Basic Sallary</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row my-1">
                                                        <div class="col-sm-2 form-inline">
                                                            <div class="form-check">
                                                                <input class="form-check-input form-control-sm" type="radio" name="">
                                                                <label class="form-check-label">Equals</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 mx-sm-auto form-inline">
                                                            <input class="form-control form-control-sm" type="text" name="" value="2.0000">
                                                        </div>
                                                        <div class="col-sm-6 form-inline">
                                                            <label class="t_b">Dollars Per:</label>
                                                            <select class="form-control form-control-sm mx-sm-2" id="percent_of" name="percent_of">
                                                                <option value="Pay Period" >Pay Period</option>
                                                                <option value="Per Hours" >Per Hours</option>
                                                                <option value="Per Month" >Per Month</option>
                                                                <option value="Per Forthnightly" >Per Forthnightly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div align="center">
                                            <button type="submit" class="btn btn-success" style="width: 300px">Submit</button>
                                        </div>
                                    </div>

                                    <br>
                                </div>
                                <!-- Deduction End -->

                                <!-- Standered Slip Start -->
                                <div class="tab-pane fade show" id="nav-standered" role="tabpanel" aria-labelledby="nav-standered-tab">
                                    <div class="row">

                                    </div>
                                    <div align="center">
                                        <button type="submit" class="btn btn-success" style="width: 300px">Submit</button>
                                    </div>
                                </div>
                                <!-- Standered Slip End -->

                                <!--  Pay History Start -->
                                <div class="tab-pane fade show" id="nav-pay_hostory" role="tabpanel" aria-labelledby="nav-pay_hostory-tab">
                                    <div class="row">


                                    </div>
                                    <div align="center">
                                        <button type="submit" class="btn btn-success" style="width: 300px">Submit</button>
                                    </div>
                                </div>
                                <!-- Pay History End -->

                                <!--  Other Info Start -->
                                <div class="tab-pane fade show" id="nav-other" role="tabpanel" aria-labelledby="nav-other-tab">
                                    <br>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 t_b">Customer Contact Person: </label>
                                                    <input class="col-8 form-control" name="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 t_b">Invoice Comment: </label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select Invoice Delivery</option>
                                                        <option value="">Item</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-6 t_b">% Early Payment discount: </label>
                                                    <input class="col-6 form-control" name="" placeholder="%">
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                    <label class="col-6 t_b">% Late Payment Fee: </label>
                                                    <input class="col-6 form-control" name="" placeholder="%">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-6 t_b">% Overall Discount on the Invoice: </label>
                                                    <input class="col-6 form-control" name="" placeholder="%">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 t_b">Payment Due: </label>
                                                    <select class="col-8 form-control" name="">
                                                        <option disabled selected value>Select Payment Due</option>
                                                        <option value="">Item</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <br>
                                             <div class="form-group">
                                                <div class="row">
                                                    <label class="col-6 t_b">By the date: </label>
                                                    <input class="col-6 form-control" type="date" name="" >
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <div class="row">
                                                    <label class="col-6 t_b">After the date: </label>
                                                    <input class="col-6 form-control" type="date" name="" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div align="center">
                                        <button type="submit" class="btn btn-success" style="width: 300px">Submit</button>
                                    </div>
                                </div>
                                <!-- Other Info End -->


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

    <script>
        $("#personal_calulation").click( function(){
           if( $(this).is(':checked') ){
             $('.personal_cal').css('display', 'block');
           } else {
            $('.personal_cal').css('display', 'none');
           }
        });

        $("#annual_calulaton").click( function(){
           if( $(this).is(':checked') ){
            $('.annual_cal').css('display', 'block');
           } else {
            $('.annual_cal').css('display', 'none');
           }
        });

    </script>

   <script>
        $("#employeadditional").click( function(){
           if( $(this).is(':checked') ){
           $('.employeeaddinal').css('display', 'block');
           } else {
           $('.employeeaddinal').css('display', 'none');
           }
        });
    </script>

    <script>
        $("#salary_sacrifice").click( function(){
           if( $(this).is(':checked') ){
           $('.salary_sacrifice').css('display', 'block');
           } else {
           $('.salary_sacrifice').css('display', 'none');
           }
        });




    </script>
    <script>
         $("#superannuatin_guarante").click( function(){
           if( $(this).is(':checked') ){
           $('.superannuatin_guarante').css('display', 'block');
           } else {
           $('.superannuatin_guarante').css('display', 'none');
           }
        });
    </script>

    <script>
        $("#advnce_repayment").click( function(){
           if( $(this).is(':checked') ){
           $('.advnce_repayment').css('display', 'block');
           } else {
           $('.advnce_repayment').css('display', 'none');
           }
        });

    </script>
    <script>
        $("#employee_purchase").click( function(){
           if( $(this).is(':checked') ){
           $('.employee_purchase').css('display', 'block');
           } else {
           $('.employee_purchase').css('display', 'none');
           }
        });

    </script>

    <script>
        $("#union_fee").click( function(){
           if( $(this).is(':checked') ){
           $('.union_fee').css('display', 'block');
           } else {
           $('.union_fee').css('display', 'none');
           }
        });

    </script>

@stop
