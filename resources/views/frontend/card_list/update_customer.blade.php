@extends('frontend.layout.master')
@section('title','Card List')
@section('content')
<?php $p="cl"; $mp="cf";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{route('customer.update',$customer->id)}}" method="POST" autocomplete="off">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab"
                                        href="#nav-profile" role="tab" aria-controls="nav-profile"
                                        aria-selected="true">Profile</a>
                                    <a class="nav-item nav-link" id="nav-selling-tab" data-toggle="tab"
                                        href="#nav-selling" role="tab" aria-controls="nav-selling"
                                        aria-selected="false">Selling Details</a>
                                    <a class="nav-item nav-link" id="nav-selling-t-tab" data-toggle="tab"
                                        href="#nav-selling-t" role="tab" aria-controls="nav-selling-t"
                                        aria-selected="false">Selling Terms</a>
                                    <a class="nav-item nav-link" id="nav-pay-tab" data-toggle="tab" href="#nav-pay"
                                        role="tab" aria-controls="nav-pay" aria-selected="false">Payment Method</a>
                                    {{-- <a class="nav-item nav-link" id="nav-work-tab" data-toggle="tab" href="#nav-work" role="tab" aria-controls="nav-work" aria-selected="false">Work Status</a> --}}
                                    <a class="nav-item nav-link" id="nav-transaction-tab" data-toggle="tab"
                                        href="#nav-transaction" role="tab" aria-controls="nav-transaction"
                                        aria-selected="false">Transaction History</a>
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
                                    {{-- <input type="hidden" name="profession_id" value="{{$profession->id}}"> --}}
                                    <input type="hidden"  name="client_id"
                                        value="{{client()->id}}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Customer Type:<span
                                                            class="t_red">*</span> </label>
                                                    <select class="form-control form-control-sm col-6"name="customer_type"
                                                        id="customer_type" required>
                                                        <option {{$customer->customer_type == 'Company'?'selected':''}} value="Company">Company</option>
                                                        <option {{$customer->customer_type == 'Individual'?'selected':''}} value="Individual">Individual</option>
                                                        <option {{$customer->customer_type == 'default'?'selected':''}} value="default">One Of Customer</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-4 text-right">Status: <span class="t_red">*</span></div>
                                                <div class="col-4">
                                                    <div class=" form-group form-check">
                                                        <input type="radio" class="form-check-input"  name="status" value="1" id="active"{{$customer->status == '1'?'checked':''}} >
                                                        <label class="form-check-label" for="active">Active</label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group form-check">
                                                        <input type="radio" class="form-check-input" name="status"
                                                            value="2" id="inactive"{{$customer->status == '2'?'checked':''}}>
                                                        <label class="form-check-label" for="inactive">Inactive</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Name: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$customer->name}}" name="name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Customer Ref: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$customer->customer_ref}}" name="customer_ref">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div style="text-align:center; margin-left: 180px;">Billing Address</div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Address:<span class="t_red">*</span>
                                                    </label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$customer->b_address}}" name="b_address" id="b_address">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">City: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$customer->b_city}}" name="b_city" id="b_city">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">State: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$customer->b_state}}" name="b_state" id="b_state">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Postcode: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$customer->b_postcode}}" name="b_postcode" id="b_postcode">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Country: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$customer->b_country}}" name="b_country" id="b_country">

                                                </div>
                                            </div>
                                            <br>
                                            <hr>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Phone No: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->phone}}" name="phone">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Mobile Number: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->mobile}}" name="mobile">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Email: </label>
                                                    <input class="col-8 form-control form-control-sm" type="email"
                                                        value="{{$customer->email}}" name="email">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div style="text-align:center; margin-left: 180px;">Shipping Address</div>
                                            <div style="text-align:center; margin-left: 180px;">
                                                <input type="checkbox" id="same_as_billing">
                                                <label for="same_as_billing">Same Billing Address</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Address: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->s_address}}" name="s_address" id="s_address">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">City: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->s_city}}" name="s_city" id="s_city">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">State: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->s_state}}" name="s_state" id="s_state">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Postcode: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->s_postcode}}" name="s_postcode" id="s_postcode">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Country: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->s_country}}" name="s_country" id="s_country">
                                                </div>
                                            </div>

                                            <br>
                                            <hr>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">XXXXXXXXXX:</label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->xxx}}" name="xxx">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Greeting:</label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->greeting}}" name="greeting">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Profile End -->

                                <!-- Selling Details Start -->
                                <div class="tab-pane fade show" id="nav-selling" role="tabpanel"
                                    aria-labelledby="nav-selling-tab">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Invoice Layout: <span
                                                            class="t_red">*</span></label>
                                                    <select required class="col-8 form-control form-control-sm" name="inv_layout">
                                                        <option {{$customer->inv_layout == 'Service'?'selected':''}} value="Service">Service</option>
                                                        <option {{$customer->inv_layout == 'Item'?'selected':''}} value="Item">Item</option>
                                                        <option {{$customer->inv_layout == 'Pfosssional'?'selected':''}} value="Pfosssional">Pfosssional</option>
                                                        <option {{$customer->inv_layout == 'Time Billing'?'selected':''}} value="Time Billing">Time Billing</option>
                                                        <option {{$customer->inv_layout == 'Miscellanious'?'selected':''}} value="Miscellanious">Miscellanious</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Invoice Delivery: <span
                                                            class="t_red">*</span></label>
                                                    <select required class="col-8 form-control form-control-sm" name="inv_delivery">
                                                        <option {{$customer->inv_delivery == 'Printed & Post'?'selected':''}} value="Printed & Post">Printed & Post</option>
                                                        <option {{$customer->inv_delivery == 'E-mail'?'selected':''}} value="E-mail">E-mail</option>
                                                        <option {{$customer->inv_delivery == 'Printed an E-mail'?'selected':''}} value="Printed an E-mail">Printed an E-mail</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Item/Barcode Price: </label>
                                                    <select class="col-8 form-control form-control-sm"  name="barcode_price">
                                                        <option {{$customer->barcode_price == 'Future opttion1'?'selected':''}} value="Future opttion1">Future opttion1</option>
                                                        <option {{$customer->barcode_price == 'Future option2'?'selected':''}} value="Future option2">Future option2</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Shipping Method: </label>
                                                    <select class="col-8 form-control form-control-sm" name="shipping_method">
                                                        <option {{$customer->shipping_method == 'Air'?'selected':''}} value="Air">Air</option>
                                                        <option {{$customer->shipping_method == 'Airfreight'?'selected':''}} value="Airfreight">Airfreight</option>
                                                        <option {{$customer->shipping_method == 'Australia Post'?'selected':''}} value="Australia Post">Australia Post</option>
                                                        <option {{$customer->shipping_method == 'Best Way'?'selected':''}} value="Best Way">Best Way</option>
                                                        <option {{$customer->shipping_method == 'C.O.D'?'selected':''}} value="C.O.D">C.O.D</option>
                                                        <option {{$customer->shipping_method == 'Courier'?'selected':''}} value="Courier">Courier</option>
                                                        <option {{$customer->shipping_method == 'Federal Express'?'selected':''}} value="Federal Express">Federal Express</option>
                                                        <option {{$customer->shipping_method == 'Freight'?'selected':''}} value="Freight">Freight</option>
                                                        <option {{$customer->shipping_method == 'International'?'selected':''}} value="International">International</option>
                                                        <option {{$customer->shipping_method == 'Kwikasair'?'selected':''}} value="Kwikasair">Kwikasair</option>
                                                        <option {{$customer->shipping_method == 'Pick Up'?'selected':''}} value="Pick Up">Pick Up</option>
                                                        <option {{$customer->shipping_method == 'Road Freight'?'selected':''}} value="Road Freight">Road Freight</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Invoice Rate: </label>
                                                    <input class="col-8 form-control form-control-sm" type="number"
                                                        value="{{$customer->inv_rate}}" name="inv_rate" placeholder="$00.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Credit Limit: </label>
                                                    <input class="col-8 form-control form-control-sm" type="number"
                                                        value="{{$customer->card_limit}}" name="card_limit" placeholder="$00.00">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">A.B.N: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$customer->abn}}" name="abn">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">VAT/GST Code: </label>
                                                    <select class="col-8 form-control form-control-sm"   name="gst_code">
                                                        <option disabled selected value>Select Item/Bracode Price
                                                        </option>
                                                        <option {{$customer->gst_code == 'GST'?'selected':''}} value="GST">GST</option>
                                                        <option {{$customer->gst_code == 'FRE'?'selected':''}} value="FRE">FRE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Freight VAT/GST Code: </label>
                                                    <select class="col-8 form-control form-control-sm" name="freight_code">
                                                        <option disabled selected value>Select Shipping Method</option>
                                                        <option {{$customer->freight_code == 'GST'?'selected':''}} value="GST">GST</option>
                                                        <option {{$customer->freight_code == 'FRE'?'selected':''}} value="FRE">FRE</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Selling Details End -->

                                <!-- Selling Terms Start -->
                                <div class="tab-pane fade" id="nav-selling-t" role="tabpanel"
                                    aria-labelledby="nav-selling-t-tab">
                                    <div class="card">
                                        <div class="card-header"
                                            style="background: #337ab7; color: #fff; height: 35px !important; padding: 5px 10px">
                                            Customer Terms Information
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-4">Customer Contact Person:</label>
                                                            <input class="col-8 form-control form-control-sm"
                                                                value="{{$customer->contact_person}}" name="contact_person">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-4">Invoice Comment: </label>
                                                            <select class="col-8 form-control form-control-sm" name="inv_comment">
                                                                <option {{$customer->inv_comment == 'Final Sales'?'selected':''}} value="Final Sales">Final Sales.</option>
                                                                <option {{$customer->inv_comment == 'Happy Holidays'?'selected':''}} value="Happy Holidays">Happy Holidays!.</option>
                                                                <option {{$customer->inv_comment == 'Happy New Year'?'selected':''}} value="Happy New Year">Happy New Year!</option>
                                                                <option {{$customer->inv_comment == 'Pick up ASAP'?'selected':''}} value="Pick up ASAP">Pick up ASAP.</option>
                                                                <option {{$customer->inv_comment == 'Thank Your'?'selected':''}} value="Thank Your">Thank Your!</option>
                                                                <option {{$customer->inv_comment == 'We appreciate your business'?'selected':''}} value="We appreciate your business">We
                                                                    appreciate your business.</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">% Early Payment discount: </label>
                                                            <input class="col-6 form-control form-control-sm"
                                                                value="{{$customer->early_discount}}" name="early_discount" placeholder="%">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">% Late Payment Fee: </label>
                                                            <input class="col-6 form-control form-control-sm"
                                                                value="{{$customer->late_fee}}" name="late_fee" placeholder="%">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">% Overall Discount on the Invoice:
                                                            </label>
                                                            <input class="col-6 form-control form-control-sm"
                                                                value="{{$customer->overall_discount}}" name="overall_discount" placeholder="%">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-4">Payment Due: </label>
                                                            <select onchange="getDays($(this).find(':selected').data('id'))" class="col-8 form-control form-control-sm" name="payment_due">
                                                                <option {{$customer->payment_due == 'Prepaid'?'selected':''}} value="Prepaid">Prepaid</option>
                                                                <option {{$customer->payment_due == 'Cash on delivery'?'selected':''}} value="Cash on delivery">Cash on delivery
                                                                </option>
                                                                <option data-id="1" {{$customer->payment_due == 'No of days from the Invoice Date'?'selected':''}} value="No of days from the Invoice Date">No of
                                                                    days from the Invoice Date</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group  {{$customer->days?'':'d-none'}}  daySelect">
                                                        <div class="row">
                                                            <label class="col-4">Days: </label>
                                                            <select class="col-8 form-control form-control-sm" name="days">
                                                                <option value="">Select Date</option>
                                                                <option {{$customer->days == 7?'selected':''}} value="7">7 Days</option>
                                                                <option {{$customer->days == 15?'selected':''}} value="15">15 Days</option>
                                                                <option {{$customer->days == 21?'selected':''}} value="21">21 Days</option>
                                                                <option {{$customer->days == 30?'selected':''}} value="30">1 Month</option>
                                                                <option {{$customer->days == 90?'selected':''}} value="90">3 Month</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">By the date: </label>
                                                            <input class="col-6 form-control form-control-sm datepicker"
                                                                type="text" data-date-format="dd/mm/yyyy" value="{{$customer->by_date}}" name="by_date">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">After the date: </label>
                                                            <input class="col-6 form-control form-control-sm datepicker"
                                                                type="text" data-date-format="dd/mm/yyyy" value="{{$customer->after_date}}" name="after_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Selling Terms End -->

                                <!-- Payment Method Start -->
                                <div class="tab-pane fade show" id="nav-pay" role="tabpanel"
                                    aria-labelledby="nav-pay-tab">
                                    <div class="card">
                                        <div class="card-header"
                                            style="background: #337ab7; color: #fff; height: 35px !important; padding: 5px 10px">
                                            Customer Payment Method</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-4">Payment Method</label>
                                                            <select class="col-8 form-control form-control-sm" name="payment_method" onchange="getBsb(this.value)">
                                                                <option {{$customer->payment_method == 'Cash'?'selected':''}} value="Cash">Cash</option>
                                                                <option {{$customer->payment_method == 'Cheque'?'selected':''}} value="Cheque">Cheque</option>
                                                                <option {{$customer->payment_method == 'Bank Transfer'?'selected':''}} value="Bank Transfer">Bank Transfer</option>
                                                                <option {{$customer->payment_method == 'Money Order'?'selected':''}} value="Money Order">Money Order</option>
                                                                <option {{$customer->payment_method == 'Eftpos'?'selected':''}} value="Eftpos">Eftpos</option>
                                                                <option {{$customer->payment_method == 'Bank Card'?'selected':''}} value="Bank Card">Bank Card</option>
                                                                <option {{$customer->payment_method == 'Master Card'?'selected':''}} value="Master Card">Master Card</option>
                                                                <option {{$customer->payment_method == 'Visa Card'?'selected':''}} value="Visa Card">Visa Card</option>
                                                                <option {{$customer->payment_method == 'AMEX'?'selected':''}} value="AMEX">AMEX</option>
                                                                <option {{$customer->payment_method == 'Other'?'selected':''}} value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <p style="color:red;"> Disclamier : Customer card details is very
                                                        sensitive for the security reason ,<br /> ARKS is not taking any
                                                        responsibilty for any misuse or fradulant of Customer card. </p>
                                                    <div id="bsb-container" @if($customer->payment_method != 'Bank Transfer')style="display:none"@endif >
                                                        <table class="table table-bordered table-hover">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Check</th>
                                                                    <th>BSB Number</th>
                                                                    <th>Account Number</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="bsb-content">
                                                                @foreach ($bsbs as $bsb)
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <input class="bsb_check" style="height: 20px;width: 20px;" type="radio" value="{{$bsb->id}}" name="bsb_table_id" {{$bsb->id == $customer->bsb_table_id ? 'checked':''}}>
                                                                    </td>
                                                                    <td> {{$bsb->bsb_number}} </td>
                                                                    <td> {{$bsb->account_number}} </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-3">Note: </label>
                                                        <textarea class="form-control col-9 form-control-sm" rows="8" name="payment_note">{{$customer->payment_note}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Payment Method End -->

                                <!-- Transaction History Start -->
                                <div class="tab-pane fade show" id="nav-transaction" role="tabpanel"
                                    aria-labelledby="nav-transaction-tab">
                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                            <label>Opening Balance <span style="font-size: 12px">(wright 0 if there is no amount)</span><span class="t_red">*</span></label>
                                            <input class="form-control form-control-sm" type="number" step="any" id="opening_blnc" value="{{$customer->opening_blnc}}" name="opening_blnc" required>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <label>Opening Balance Date <span style="color:red; font-size:10px">(Date must be Last Financial year closing date.)</span> </label>
                                            <input class="form-control form-control-sm datepicker" data-date-format="dd/mm/yyyy" type="text"
                                                value="{{$customer->opening_blnc_date == true?$customer->opening_blnc_date->format('d/m/Y'):''}}" name="opening_blnc_date" id="opening_blnc_date" readonly>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Credit Account</label>
                                            <select class="form-control form-control-sm" type="text"  name="credit_account" id="credit_account" readonly>
                                                <option value>Selec One Item</option>
                                                @foreach ($creditCodes as $item)
                                                <option {{$customer->credit_account == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Transaction History End -->
                            </div>
                        </div>
                        <div class="text-center my-3">
                            <button type="submit" class="btn btn-info" style="width: 300px">Update</button>
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
    $("#opening_blnc").on('keyup',e=>{
        const obl = $("#opening_blnc").val();
        if(obl.length < 1){
            $("#opening_blnc_date").attr('readonly','readonly')
            $("#credit_account").attr('readonly','readonly')
        }else{
            $("#opening_blnc_date").removeAttr('readonly').attr('required','required')
            $("#credit_account").removeAttr('readonly').attr('required','required')
        }
    });
    $("#same_as_billing").on('change',e=>{
        if($("#same_as_billing").attr('checked',true)){
            $("#s_address").val($("#b_address").val());
            $("#s_city").val($("#b_city").val());
            $("#s_state").val($("#b_state").val());
            $("#s_postcode").val($("#b_postcode").val());
            $("#s_country").val($("#b_country").val());
        }
    });
</script>
<script>
function getBsb(val){
    let bsb_container = $("#bsb-container")
    if(val == 'Bank Transfer'){
        bsb_container.slideDown().show();
    } else {
        bsb_container.slideUp().hide();
        $.each($('.bsb_check'), function(i,v){
            if(v.checked == true){
                $('.bsb_check').prop('checked', false)
            }
        })
    }
}
function getDays(day){
    let days = $(".daySelect");
    if(day == 1){
        days.removeClass('d-none')
    }else{
        days.addClass('d-none')
    }
}
</script>
{{-- @include('frontend.add_card.getBsb_js') --}}
@stop
