@extends('frontend.layout.master')
@section('title','Employee Create')
@section('content')
<?php $p="acard"; $mp="cf";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <form novalidate action=" {{route('employee.store')}} " autocomplete="off" method="POST">
                        @csrf
                        <div class="card-body">
                            <nav>

                                <div class="nav nav-tabs" id="nav-tab" role="tablist" style="font-size: 15px;">

                                    <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab"
                                        href="#nav-profile" role="tab" aria-controls="nav-profile"
                                        aria-selected="true">Profile</a>

                                    <a class="nav-item nav-link" id="nav-Wages-tab" data-toggle="tab" href="#nav-Wages"
                                        role="tab" aria-controls="nav-Wages" aria-selected="false">Wages and Tax</a>

                                    <a class="nav-item nav-link" id="nav-pay-tab" data-toggle="tab" href="#nav-pay"
                                        role="tab" aria-controls="nav-pay" aria-selected="false">Payment Method</a>

                                    <a class="nav-item nav-link" id="nav-ent-tab" data-toggle="tab" href="#nav-ent"
                                        role="tab" aria-controls="nav-ent" aria-selected="false">Entatilement</a>

                                    <a class="nav-item nav-link" id="nav-sup-tab" data-toggle="tab" href="#nav-sup"
                                        role="tab" aria-controls="nav-sup" aria-selected="false">Superannuation</a>

                                    <a class="nav-item nav-link" id="nav-ded-tab" data-toggle="tab" href="#nav-ded"
                                        role="tab" aria-controls="nav-ded" aria-selected="false">Deduction</a>

                                    <a class="nav-item nav-link" id="nav-standered-tab" data-toggle="tab"
                                        href="#nav-standered" role="tab" aria-controls="nav-standered"
                                        aria-selected="false">Standered Slip</a>

                                    {{-- <a class="nav-item nav-link" id="nav-pay_hostory-tab" data-toggle="tab" href="#nav-pay_hostory" role="tab" aria-controls="nav-pay_hostory" aria-selected="false">Pay Hostory</a> --}}

                                    {{-- <a class="nav-item nav-link" id="nav-other-tab" data-toggle="tab" href="#nav-other" role="tab" aria-controls="nav-other" aria-selected="false">Other Info</a> --}}

                                </div>
                            </nav>

                            <div class="tab-content" id="nav-tabContent">
                                <!-- Profile Start -->
                                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <br>
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <br>
                                    <div class="row justify-content-center">
                                        <label class="col-sm-2">Employee Status <span class="t_red">*</span> </label>
                                        <div class="col-1 form-check">
                                            <input class="form-check-input" type="radio" name="status" id="active"
                                                value="1" checked>
                                            <label class="form-check-label" for="active">Active</label>
                                        </div>
                                        <div class="col-1 form-check">
                                            <input class="form-check-input" type="radio" name="status" id="inactive"
                                                value="2">
                                            <label class="form-check-label" for="inactive">Inactive</label>
                                        </div>
                                        <input type="hidden" name="profession_id" value="{{$profession->id}}">
                                        <input type="hidden" name="client_id"
                                            value="{{client()->id}}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">First Name: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="text"
                                                    name="first_name" value="{{old('first_name')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Date of Birth: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="date" name="dob"
                                                    value="{{old('dob')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Last Name: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="text"
                                                        name="last_name" value="{{old('last_name')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Gender: <span class="t_red">*</span>
                                                    </label>
                                                    <select class="col-8 form-control" name="gender">
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
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
                                                    <label class="col-4 text-right">Address: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="text"
                                                        name="address" value="{{old('address')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">City: <span class="t_red">*</span>
                                                    </label>
                                                    <input required class="col-8 form-control" type="text" name="city" value="{{old('city')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">State: <span class="t_red">*</span>
                                                    </label>
                                                    <input required class="col-8 form-control" type="text" name="state" value="{{old('state')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Postcode: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="text"
                                                        name="post_code" value="{{old('post_code')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Country: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="text"
                                                        name="country" value="{{old('country')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Phone No:</label>
                                                    <input class="col-8 form-control" type="text" name="phone" value="{{old('phone')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Mobile Number: <span
                                                            class="t_red">*</span> </label>
                                                    <input class="col-8 form-control" type="text" name="mobile" value="{{old('mobile')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Email:</label>
                                                    <input class="col-8 form-control" type="text" name="email" value="{{old('email')}}">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div style="text-align:center; margin-left: 180px;">Employment Details</div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Start Date: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="date"
                                                        name="start_date"
                                                        value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Termination Date: </label>
                                                    <input class="col-8 form-control" type="date" name="term_date"
                                                        id="term_date" disabled title="Please Check Inactive">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Employment Basis: <span
                                                            class="t_red">*</span> </label>
                                                    <select class="col-8 form-control" name="emp_basis">
                                                        <option disabled selected value>Select Employment Basis</option>
                                                        <option>Individual</option>
                                                        <option>Labor Hire</option>
                                                        <option>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Employment Category: <span
                                                            class="t_red">*</span> </label>
                                                    <select class="col-8 form-control" name="emp_category">
                                                        <option disabled selected value>Select Employment Category
                                                        </option>
                                                        <option>Payment</option>
                                                        <option>Temporary</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Employment Status: <span
                                                            class="t_red">*</span> </label>
                                                    <select class="col-8 form-control" name="emp_status">
                                                        <option disabled selected value>Select Employment Status
                                                        </option>
                                                        <option>Full Time</option>
                                                        <option>Part Time</option>
                                                        <option>Casual</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Employment Classification: <span
                                                            class="t_red">*</span> </label>
                                                    <select class="col-8 form-control" name="emp_classification">
                                                        <option disabled selected value>Select Employment Classification
                                                        </option>
                                                        @foreach ($ECS as $item)
                                                        <option value="{{$item->name}} ">{{$item->name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Profile End -->

                                <!-- Wages and Tax Start -->
                                <div class="tab-pane fade show" id="nav-Wages" role="tabpanel"
                                    aria-labelledby="nav-Wages-tab">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Pay Basis: <span
                                                            class="t_red">*</span> </label>
                                                    <select class="col-8 form-control" id="pay_basis" name="pay_basis">
                                                        <option disabled selected value>Select Pay Basis</option>
                                                        <option value="Salary">Salary</option>
                                                        <option value="Hourly">Hourly</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Annual Salary: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="number"
                                                        id="annual_salary" name="annual_salary" value="{{old('annual_salary')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Hourly Rate: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="number"
                                                        id="hourly_rate" name="hourly_rate" value="{{old('hourly_rate')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Pay Frequency: <span
                                                            class="t_red">*</span> </label>
                                                    <select class="col-8 form-control" name="pay_frequency"
                                                        id="pay_frequency">
                                                        <option value="Weekly">Weekly</option>
                                                        <option value="Fortnightly">Fortnightly</option>
                                                        <option value="Monthly">Monthly</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Hours in Pay Frequency: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="text"
                                                        id="hour_pay_frequency" name="hour_pay_frequency" value="38">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Netpay Wages Account <span
                                                            class="t_red">*</span> :</label>
                                                    <select class="col-8 form-control" name="netpay_wages_ac">
                                                        @php
                                                        $nwa = $codes->where('code',340001)->first();
                                                        $tw_exp = $codes->where('code',340002)->first();
                                                        $tw_payable = $codes->where('code',720030)->first();
                                                        @endphp
                                                        <option selected value="340001">340001 =&gt; Wages Net pay
                                                            amount to Employee</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">T/Withhold exp A/c <span
                                                            class="t_red">*</span> :</label>
                                                    <select class="col-8 form-control" name="tw_exp_ac">
                                                        <option selected value="340002">340002 =&gt; PAYG Withholding
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">T/Withhold Paybale A/c: <span
                                                            class="t_red">*</span> </label>
                                                    <select class="col-8 form-control" name="tw_payable_ac">
                                                        <option selected value="720030">720030 =&gt; PAYG Withholding
                                                            Payable</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Tax File Number: <span
                                                            class="t_red">*</span> </label>
                                                    <input required class="col-8 form-control" type="text"
                                                        name="tax_number" value="{{old('tax_number')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Tax Table: <span
                                                            class="t_red">*</span> </label>
                                                    <select class="col-8 form-control" type="text" name="tax_table">
                                                        <option disabled selected value>Select Tax Table</option>
                                                        <option value="No tax-free threshold">No tax-free threshold(SC-1)</option>
                                                        <option value="Tax-free threshold">Tax-free threshold(SC-2)</option>
                                                        <option value="Non Resident/Foregin resident">Non Resident/Foregin resident(SC-3)</option>
                                                        <option value="Full medicare levy Exemption">Full medicare levy Exemption(SC-5)</option>
                                                        <option value="Half medicare levy Exemption">Half medicare levy Exemption(SC-6)</option>
                                                        <option value="Tax file not decleared residence">Tax file not decleared residence</option>
                                                        <option value="Tax file not decleared non residence">Tax file not decleared non residence</option>
                                                        <option value="Backpacker">Backpacker</option>
                                                        <option value="Withhold Tax Offsets">Withhold Tax Offsets</option>
                                                        <option value="HELP  and TSL">HELP and TSL</option>
                                                        <option value="Student Loan">Student Loan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Withholding Variation Rate:</label>
                                                    <input class="col-8 form-control" type="number" name="wv_rate" value="{{old('wv_rate')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Total Rebates:</label>
                                                    <input class="col-8 form-control" type="text" name="total_rebate" value="{{old('total_rebate')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Extra Tax:</label>
                                                    <input class="col-8 form-control" type="text" name="extra_tax" value="{{old('extra_tax')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @php $i=1; @endphp
                                            @foreach ($standWages as $standWage)
                                            <label class="checkbox-inline px-2">
                                                <input type="checkbox" id="wages{{$i++}}" name="wages[]"
                                                    value="{{$standWage->name}} "> {{$standWage->name}}
                                            </label>
                                            @endforeach
                                            @foreach ($wages as $wage)
                                            <label class="checkbox-inline px-2">
                                                <input type="checkbox" id="wages{{$i++}}" name="wages[]"
                                                    value="{{$wage->name}} "> {{$wage->name}}
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Wages and Tax End -->

                                <!-- Payment Details Start -->
                                <div class="tab-pane fade show" id="nav-pay" role="tabpanel"
                                    aria-labelledby="nav-pay-tab">
                                    <div class="card">
                                        <div class="card-heading">
                                            <h3>Customer Payment Method</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Payment Method <span class="t_red">*</span> </label>
                                                        <select class="form-control" name="payment_method" id="payment_method">
                                                            <option disabled selected value>Select Payment Method
                                                            </option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Cheque">Cheque</option>
                                                            <option value="Electronic">Electronic</option>

                                                            {{-- <option value="Bank Transfer">Bank Transfer</option>
                                                            <option value="Money Order">Money Order</option>
                                                            <option value="Eftpos">Eftpos</option>
                                                            <option value="Bank Card">Bank Card</option>
                                                            <option value="Master Card">Master Card</option>
                                                            <option value="Visa Card">Visa Card</option>
                                                            <option value="AMEX">AMEX</option>
                                                            <option value="Other">Other</option> --}}
                                                        </select>
                                                    </div>

                                                    <div class="row bsboraccount" style="display:none;">
                                                        <div class="col-md-6">
                                                           <div class="form-group">
                                                           <label>Emplyee BSB Number</label>
                                                           <input type="text" id="bsb_number" name="bsb_number" class="form-control">
                                                           </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                           <div class="form-group">
                                                           <label>Employee Account Number</label>
                                                           <input type="text" id="account_number" name="account_number" class="form-control">
                                                           </div>
                                                        </div>
                                                    </div>





                                                    <div class="form-group">
                                                        <label>Payroll Payment linked Acccount: <span
                                                                class="t_red">*</span> </label>
                                                        <select class="form-control" name="payment_ac">
                                                            <option value="510021">510021 => Cash at Bank</option>
                                                            <option value="510050">510050 => Cash in Hand</option>
                                                            <option value="510045">510045 => Eftpos- 1 Received</option>
                                                            <option value="510048" selected>510048 => Payroll Clearing
                                                                Account</option>
                                                            <option value="510100">510100 => Suspense clearing account.
                                                            </option>
                                                            <option value="510049">510049 => Focus Taxation Bank Account
                                                            </option>
                                                            <option value="510049">510049 => F&F Bank account</option>
                                                            <option value="510047">510047 => Fee From Refund</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Note:</label>
                                                        <textarea class="form-control" name="payment_note"
                                                            rows="8"></textarea>
                                                    </div>
                                                </div>
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
                                                @foreach ($standLeaves as $standleave)
                                                <div class="col-2 form-check">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="leave[]"
                                                            value="{{$standleave->name}} "> {{$standleave->name}}
                                                    </label>
                                                </div>
                                                @endforeach
                                                @foreach ($leaves as $leave)
                                                <div class="col-2 form-check">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="leave[]" value="{{$leave->name}} ">
                                                        {{$leave->name}}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>

                                            <div class="row mt-2" style="">
                                                <div class="col-md-4">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="annual_calulaton"
                                                                name="annual_calulaton" value="1"> Annual Leave
                                                            Calulation Basis : <a href="#" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Please do not tick this box unless you are confident with the tax law"><i
                                                                    style="color:red;"
                                                                    class="fa fa-question-circle fa-lg"
                                                                    aria-hidden="true"></i></a>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="annual_cal" style="display:none;">
                                                    <div class="row">
                                                    @php
                                                    $annualLeave = $standLeaves->where('name','Annual Leave Accrual')->first();
                                                    @endphp
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label for="e_rate" class="col-sm-4 control-label"
                                                                    align="right">Equals</label>
                                                                <div class="col-sm-8">
                                                                    <input type="number"
                                                                        class="form-control form-control-sm" id="e_rate"
                                                                        name="e_rate" value="{{$annualLeave->rate}}">
                                                                    @error('e_rate')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label for="e_tools" class="col-sm-5 control-label"
                                                                    align="right">Percent of</label>
                                                                <div class="col-sm-7">
                                                                    <select name="e_tools" id="e_tools"
                                                                        class="form-control form-control-sm">
                                                                        <option {{$annualLeave->tools == 'BS'?'selected':''}} value="BS" >Basic Sallary
                                                                        </option>
                                                                        <option {{$annualLeave->tools == 'GH'?'selected':''}} value="GH">Gross Hours</option>
                                                                    </select>
                                                                    @error('e_tools')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label for="e_fix_amt" class="col-sm-4 control-label"
                                                                    align="right">Equals</label>
                                                                <div class="col-sm-8">
                                                                    <input disabled type="number"
                                                                        class="form-control form-control-sm"
                                                                        value="{{$annualLeave->fix_amt}}"
                                                                        id="e_fix_amt" name="e_fix_amt">
                                                                    @error('e_fix_amt')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">

                                                            <div class="form-group row">
                                                                <label for="e_period" class="col-sm-5 control-label"
                                                                    align="right">Dollars Per</label>
                                                                <div class="col-sm-7">
                                                                    <select disabled name="e_period" id="e_period"
                                                                        class="form-control form-control-sm">
                                                                        <option>{{$annualLeave->period}}</option>
                                                                        <option value="Pay Period" selected>Pay Period
                                                                        </option>
                                                                        <option value="Per Hours">Per Hours</option>
                                                                        <option value="Per Month">Per Month</option>
                                                                        <option value="Per Forthnighty">Per Forthnighty
                                                                        </option>
                                                                    </select>
                                                                    @error('e_period')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-check ml-3">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="exampleCheck1">
                                                                <label class="form-check-label"
                                                                    for="exampleCheck1">Carry Remaining Entitlement Over
                                                                    to Next Year</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group row ml-2 mt-2">
                                                                <label for="e_period"
                                                                    class="col-sm-5 control-label">Linked Wages
                                                                    Category:</label>
                                                                <div class="col-sm-7">
                                                                    <select name="e_period" id="e_period"
                                                                        class="form-control form-control-sm">
                                                                        <option value="Pay Period" selected>Select
                                                                        </option>
                                                                        <option value="Per Hours">Personal Leave Pay
                                                                        </option>
                                                                    </select>
                                                                    @error('e_period')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row mt-2" style="">
                                                <div class="col-md-4">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="personal_calulation"
                                                                name="personal_calulation" value="1"> Personal Leave
                                                            Calulation: <a href="#" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Please do not tick this box unless you are confident with the tax law"><i
                                                                    style="color:red;"
                                                                    class="fa fa-question-circle fa-lg"
                                                                    aria-hidden="true"></i></a>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="personal_cal" style="display:none;">
                                                    <div class="row">
                                                    @php
                                                    $personalLeave = $standLeaves->where('name','Personal Leave Accrual')->first();
                                                    @endphp
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label for="e_rate" class="col-sm-4 control-label"
                                                                    align="right">Equals</label>
                                                                <div class="col-sm-8">
                                                                    <input type="number"
                                                                        class="form-control form-control-sm" id="e_rate"
                                                                        name="e_rate" value="{{$personalLeave->rate}}">
                                                                    @error('e_rate')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label for="e_tools" class="col-sm-5 control-label"
                                                                    align="right">Percent of</label>
                                                                <div class="col-sm-7">
                                                                    <select name="e_tools" id="e_tools"
                                                                        class="form-control form-control-sm">
                                                                        <option {{$personalLeave->tools == "BS"?"selected":""}} value="BS" selected>Basic Sallary
                                                                        </option>
                                                                        <option {{$personalLeave->tools == "GH"?"selected":""}} value="GH">Gross Hours</option>
                                                                    </select>
                                                                    @error('e_tools')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label for="e_fix_amt" class="col-sm-4 control-label"
                                                                    align="right">Equals</label>
                                                                <div class="col-sm-8">
                                                                    <input disabled type="number"
                                                                        class="form-control form-control-sm"
                                                                        id="e_fix_amt" name="e_fix_amt" value="{{$personalLeave->fix_amt}}">
                                                                    @error('e_fix_amt')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">

                                                            <div class="form-group row">
                                                                <label for="e_period" class="col-sm-5 control-label"
                                                                    align="right">Dollars Per</label>
                                                                <div class="col-sm-7">
                                                                    <select disabled name="e_period" id="e_period"
                                                                        class="form-control form-control-sm">
                                                                        <option>{{$personalLeave->period}} </option>
                                                                        <option value="Pay Period" selected>Pay Period
                                                                        </option>
                                                                        <option value="Per Hours">Per Hours</option>
                                                                        <option value="Per Month">Per Month</option>
                                                                        <option value="Per Forthnighty">Per Forthnighty
                                                                        </option>
                                                                    </select>
                                                                    @error('e_period')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-check ml-3">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="exampleCheck1">
                                                                <label class="form-check-label"
                                                                    for="exampleCheck1">Carry Remaining Entitlement Over
                                                                    to Next Year</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group row ml-2 mt-2">
                                                                <label for="e_period"
                                                                    class="col-sm-5 control-label">Linked Wages
                                                                    Category:</label>
                                                                <div class="col-sm-7">
                                                                    <select name="e_period" id="e_period"
                                                                        class="form-control form-control-sm">
                                                                        <option value="Pay Period" selected>Select
                                                                        </option>
                                                                        <option value="Per Hours">Personal Leave Pay
                                                                        </option>
                                                                    </select>
                                                                    @error('e_period')<span
                                                                        class="text-danger">{{$message}}
                                                                    </span>@enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <!-- Entatilement Information End -->

                                <!-- Superannuation Start -->
                                <div class="tab-pane fade show" id="nav-sup" role="tabpanel"
                                    aria-labelledby="nav-sup-tab">
                                    <div class="card">
                                        <div class="card-heading">
                                            <h3>Supperannuation</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group row ">
                                                        <label class="col-sm-4 col-form-label-sm">Superannuation
                                                            Fund</label>
                                                        <div class="col-sm-8">
                                                            <select type="email" class="form-control form-control-sm"
                                                                name="sup_fund">
                                                                <option disabled selected value>Select Superannuation
                                                                    Fund</option>
                                                                <option id="addNewSup">AddNew</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row ">
                                                        <label class="col-sm-4 col-form-label-sm">Superannuation
                                                            Exp.Account <span class="t_red">*</span></label>
                                                        <div class="col-sm-8">
                                                            <select type="email" class="form-control form-control-sm"
                                                                name="sup_exp_ac">
                                                                <option selected value="340003">340003 =&gt;
                                                                    Superannuation</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group row ">
                                                        <label class="col-sm-4 col-form-label-sm">Employee Membersip No:
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control form-control-sm"
                                                                name="emp_membership">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label-sm">Superannuation Payable
                                                            Account <span class="t_red">*</span></label>
                                                        <div class="col-sm-8">
                                                            <select name="sup_payable_ac"
                                                                class="form-control form-control-sm">
                                                                <option selected value="730001">730001 =&gt;
                                                                    Superannuation Payable Liability</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center">
                                                <label class="col-md-4">Select Superannuation for this Employee: <span
                                                        class="t_red">*</span> </label>
                                                {{-- <div class="col-2">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="superannuation[]" value="Superannuation Guarantee" checked > Superannuation Guarantee
                                                </label>
                                                </div> --}}
                                                @foreach ($standSups as $standsup)
                                                <div class="col-2">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="superannuation[]"
                                                            value="{{$standsup->name}} "> {{$standsup->name}}
                                                    </label>
                                                </div>
                                                @endforeach
                                                @foreach ($sups as $sup)
                                                <div class="col-2">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="superannuation[]"
                                                            value="{{$sup->name}} "> {{$sup->name}}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Superannuation End -->

                                <!-- Deduction Start -->
                                <div class="tab-pane fade show" id="nav-ded" role="tabpanel"
                                    aria-labelledby="nav-ded-tab">
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
                                                            <select name="link_dd_ac" class="form-control">
                                                                <option selected value="340001">340001 => Wages Net pay
                                                                    amount to Employee</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center">
                                                <label class="mr-4">Select Deduction for this Employee:</label>
                                                @foreach ($standDeducs as $standdeduc)
                                                <div class="col-2">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="deduction[]"
                                                            value="{{$standdeduc->name}} "> {{$standdeduc->name}}
                                                    </label>
                                                </div>
                                                @endforeach
                                                @foreach ($deducs as $deduc)
                                                <div class="col-2">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="deduction[]"
                                                            value="{{$deduc->name}} "> {{$deduc->name}}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                </div>
                                <!-- Deduction End -->

                                <!-- Standered Slip Start -->
                                <div class="tab-pane fade show" id="nav-standered" role="tabpanel"
                                    aria-labelledby="nav-standered-tab">
                                    <div class="row">

                                    </div>
                                </div>
                                <!-- Standered Slip End -->


                            </div>

                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-3">
                                <input type="submit" value="Submit" class=" btn btn-success px-5" name="submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->

<script>
    $("#inactive").on('change', (e) => {
        $("#term_date").removeAttr('disabled', 'disabled');
        $("#term_date").removeAttr('title', 'You Can\'t do this');
    });
    $("#active").on('change', (e) => {
        $("#term_date").attr('disabled', 'disabled');
        $("#term_date").attr('title', 'You Can\'t do this');
    });
    $("#pay_basis").on('change', (e) => {
        const payBasis = $("#pay_basis").val();
        if (payBasis == 'Salary') {
            $("#hourly_rate").attr('readonly', 'readonly');
            $("#annual_salary").removeAttr('readonly', 'readonly');
            $("#wages1").attr('checked', 'checked');
            $("#wages2").removeAttr('checked', 'checked');
            $("#wages2").attr('disabled', 'disabled');
            $("#wages1").removeAttr('disabled', 'disabled');
            salarybase();
        } else {
            $("#wages1").attr('disabled', 'disabled');
            $("#wages2").removeAttr('disabled', 'disabled');
            $("#wages2").attr('checked', 'checked');
            $("#wages1").removeAttr('checked', 'checked');
            $("#hourly_rate").removeAttr('readonly', 'readonly');
            $("#annual_salary").attr('readonly', 'readonly');
            hourlybase();
        }
    });

    $('#hourly_rate').on('keyup', (e) => {
        var pay_basis = $('#pay_basis').val();
        if (pay_basis == 'Salary') {
            salarybase();
        } else {
            hourlybase();
        }
    });
    $('#hour_pay_frequency').on('keyup', (e) => {
        var pay_basis = $('#pay_basis').val();
        if (pay_basis == 'Salary') {
            salarybase();
        } else {
            hourlybase();
        }
    });

    $("#pay_frequency").on('change', (e) => {
        var pay_frequency = $("#pay_frequency").val();
        if (pay_frequency == 'Weekly') {
            $('#hour_pay_frequency').val(38.00);
        } else if (pay_frequency == 'Fortnightly') {
            $('#hour_pay_frequency').val(76.00);
        } else if (pay_frequency == 'Monthly') {
            $('#hour_pay_frequency').val(164.667);
        }
        var pay_basis = $('#pay_basis').val();
        if (pay_basis == 'Salary') {
            salarybase();
        } else {
            hourlybase();
        }
    });


    $('#annual_salary').on('keyup', (e) => {
        var pay_basis = $('#pay_basis').val();
        if (pay_basis == 'Salary') {
            salarybase();
        } else {
            hourlybase();
        }
    });

    function salarybase() {
        var annual_salary = $('#annual_salary').val();
        var pay_frequency = $('#pay_frequency').val();
        var hour_pay_frequency = $('#hour_pay_frequency').val();
        var frequnday = frequencycal(pay_frequency);
        var dividbyfreq = +annual_salary / frequnday;
        var finaldivid = dividbyfreq / hour_pay_frequency;
        $('#hourly_rate').val(finaldivid.toFixed(4));


    };

    function hourlybase() {
        var hourly_rate = $('#hourly_rate').val();
        var pay_frequency = $('#pay_frequency').val();
        var hour_pay_frequency = $('#hour_pay_frequency').val();
        var frequnday = frequencycal(pay_frequency);
        var totalsalary = hour_pay_frequency * +hourly_rate * frequnday;
        $('#annual_salary').val(totalsalary.toFixed(2));
    }

    function frequencycal(pay_frequency) {
        if (pay_frequency == 'Weekly') {
            return 52;
        } else if (pay_frequency == 'Fortnightly') {
            return 26;
        } else if (pay_frequency == 'Monthly') {
            return 12;
        }
    }
</script>
<script>
    $("#union_fee").click(function () {
        if ($(this).is(':checked')) {
            $('.union_fee').css('display', 'block');
        } else {
            $('.union_fee').css('display', 'none');
        }
    });
</script>

<script>
    $("#personal_calulation").click(function () {
        if ($(this).is(':checked')) {
            $('.personal_cal').css('display', 'block');
        } else {
            $('.personal_cal').css('display', 'none');
        }
    });

    $("#annual_calulaton").click(function () {
        if ($(this).is(':checked')) {
            $('.annual_cal').css('display', 'block');
        } else {
            $('.annual_cal').css('display', 'none');
        }
    });
</script>
<script>
    $('#payment_method').on('change', function(){
        var pay_method = $(this).val();
        if(pay_method == 'Electronic'){
           $('.bsboraccount').css('display', 'block');
        } else{
             $('.bsboraccount').css('display', 'none');
        }

    });
</script>

@stop
