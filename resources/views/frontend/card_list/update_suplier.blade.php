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
                    <form action="{{route('supplier.update',$supplier->id)}}" method="POST" autocomplete="off">
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
                                                        <option {{$supplier->customer_type == 'Company'?'selected':''}} value="Company">Company</option>
                                                        <option {{$supplier->customer_type == 'Individual'?'selected':''}} value="Individual">Individual</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-4 text-right">Status: <span class="t_red">*</span></div>
                                                <div class="col-4">
                                                    <div class=" form-group form-check">
                                                        <input type="radio" class="form-check-input"  name="status" value="1" id="active"{{$supplier->status == '1'?'checked':''}} >
                                                        <label class="form-check-label" for="active">Active</label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group form-check">
                                                        <input type="radio" class="form-check-input" name="status"
                                                            value="2" id="inactive"{{$supplier->status == '2'?'checked':''}}>
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
                                                        type="text" value="{{$supplier->name}}" name="name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Customer Ref: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$supplier->customer_ref}}" name="customer_ref">
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
                                                        type="text" value="{{$supplier->b_address}}" name="b_address" id="b_address">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">City: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$supplier->b_city}}" name="b_city" id="b_city">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">State: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$supplier->b_state}}" name="b_state" id="b_state">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Postcode: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$supplier->b_postcode}}" name="b_postcode" id="b_postcode">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Country: <span
                                                            class="t_red">*</span></label>
                                                    <input required class="col-8 form-control form-control-sm"
                                                        type="text" value="{{$supplier->b_country}}" name="b_country" id="b_country">

                                                </div>
                                            </div>
                                            <br>
                                            <hr>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Phone No: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$supplier->phone}}" name="phone">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Mobile Number: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$supplier->mobile}}" name="mobile">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Email: </label>
                                                    <input class="col-8 form-control form-control-sm" type="email"
                                                        value="{{$supplier->email}}" name="email">
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
                                                        value="{{$supplier->s_address}}" name="s_address" id="s_address">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">City: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$supplier->s_city}}" name="s_city" id="s_city">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">State: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$supplier->s_state}}" name="s_state" id="s_state">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Postcode: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$supplier->s_postcode}}" name="s_postcode" id="s_postcode">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Country: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$supplier->s_country}}" name="s_country" id="s_country">
                                                </div>
                                            </div>

                                            <br>
                                            <hr>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">XXXXXXXXXX:</label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$supplier->xxx}}" name="xxx">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Greeting:</label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$supplier->greeting}}" name="greeting">
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
                                                        <option {{$supplier->inv_layout == 'Service'?'selected':''}} value="Service">Service</option>
                                                        <option {{$supplier->inv_layout == 'Item'?'selected':''}} value="Item">Item</option>
                                                        <option {{$supplier->inv_layout == 'Pfosssional'?'selected':''}} value="Pfosssional">Pfosssional</option>
                                                        <option {{$supplier->inv_layout == 'Time Billing'?'selected':''}} value="Time Billing">Time Billing</option>
                                                        <option {{$supplier->inv_layout == 'Miscellanious'?'selected':''}} value="Miscellanious">Miscellanious</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Invoice Delivery: <span
                                                            class="t_red">*</span></label>
                                                    <select required class="col-8 form-control form-control-sm" name="inv_delivery">
                                                        <option {{$supplier->inv_delivery == 'Printed & Post'?'selected':''}} value="Printed & Post">Printed & Post</option>
                                                        <option {{$supplier->inv_delivery == 'E-mail'?'selected':''}} value="E-mail">E-mail</option>
                                                        <option {{$supplier->inv_delivery == 'Printed an E-mail'?'selected':''}} value="Printed an E-mail">Printed an E-mail</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Item/Barcode Price: </label>
                                                    <select class="col-8 form-control form-control-sm"  name="barcode_price">
                                                        <option {{$supplier->barcode_price == 'Future opttion1'?'selected':''}} value="Future opttion1">Future opttion1</option>
                                                        <option {{$supplier->barcode_price == 'Future option2'?'selected':''}} value="Future option2">Future option2</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Shipping Method: </label>
                                                    <select class="col-8 form-control form-control-sm" name="shipping_method">
                                                        <option {{$supplier->shipping_method == 'Air'?'selected':''}} value="Air">Air</option>
                                                        <option {{$supplier->shipping_method == 'Airfreight'?'selected':''}} value="Airfreight">Airfreight</option>
                                                        <option {{$supplier->shipping_method == 'Australia Post'?'selected':''}} value="Australia Post">Australia Post</option>
                                                        <option {{$supplier->shipping_method == 'Best Way'?'selected':''}} value="Best Way">Best Way</option>
                                                        <option {{$supplier->shipping_method == 'C.O.D'?'selected':''}} value="C.O.D">C.O.D</option>
                                                        <option {{$supplier->shipping_method == 'Courier'?'selected':''}} value="Courier">Courier</option>
                                                        <option {{$supplier->shipping_method == 'Federal Express'?'selected':''}} value="Federal Express">Federal Express</option>
                                                        <option {{$supplier->shipping_method == 'Freight'?'selected':''}} value="Freight">Freight</option>
                                                        <option {{$supplier->shipping_method == 'International'?'selected':''}} value="International">International</option>
                                                        <option {{$supplier->shipping_method == 'Kwikasair'?'selected':''}} value="Kwikasair">Kwikasair</option>
                                                        <option {{$supplier->shipping_method == 'Pick Up'?'selected':''}} value="Pick Up">Pick Up</option>
                                                        <option {{$supplier->shipping_method == 'Road Freight'?'selected':''}} value="Road Freight">Road Freight</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Invoice Rate: </label>
                                                    <input class="col-8 form-control form-control-sm" type="number"
                                                        value="{{$supplier->inv_rate}}" name="inv_rate" placeholder="$00.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Credit Limit: </label>
                                                    <input class="col-8 form-control form-control-sm" type="number"
                                                        value="{{$supplier->card_limit}}" name="card_limit" placeholder="$00.00">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">A.B.N: </label>
                                                    <input class="col-8 form-control form-control-sm" type="text"
                                                        value="{{$supplier->abn}}" name="abn">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">VAT/GST Code: </label>
                                                    <select class="col-8 form-control form-control-sm"   name="gst_code">
                                                        <option disabled selected value>Select Item/Bracode Price
                                                        </option>
                                                        <option {{$supplier->gst_code == 'GST'?'selected':''}} value="GST">GST</option>
                                                        <option {{$supplier->gst_code == 'FRE'?'selected':''}} value="FRE">FRE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-4 text-right">Freight VAT/GST Code: </label>
                                                    <select class="col-8 form-control form-control-sm" name="freight_code">
                                                        <option disabled selected value>Select Shipping Method</option>
                                                        <option {{$supplier->freight_code == 'GST'?'selected':''}} value="GST">GST</option>
                                                        <option {{$supplier->freight_code == 'FRE'?'selected':''}} value="FRE">FRE</option>
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
                                                                value="{{$supplier->contact_person}}" name="contact_person">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-4">Invoice Comment: </label>
                                                            <select class="col-8 form-control form-control-sm" name="inv_comment">
                                                                <option {{$supplier->inv_comment == 'Final Sales'?'selected':''}} value="Final Sales">Final Sales.</option>
                                                                <option {{$supplier->inv_comment == 'Happy Holidays'?'selected':''}} value="Happy Holidays">Happy Holidays!.</option>
                                                                <option {{$supplier->inv_comment == 'Happy New Year'?'selected':''}} value="Happy New Year">Happy New Year!</option>
                                                                <option {{$supplier->inv_comment == 'Pick up ASAP'?'selected':''}} value="Pick up ASAP">Pick up ASAP.</option>
                                                                <option {{$supplier->inv_comment == 'Thank Your'?'selected':''}} value="Thank Your">Thank Your!</option>
                                                                <option {{$supplier->inv_comment == 'We appreciate your business'?'selected':''}} value="We appreciate your business">We
                                                                    appreciate your business.</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">% Early Payment discount: </label>
                                                            <input class="col-6 form-control form-control-sm"
                                                                value="{{$supplier->early_discount}}" name="early_discount" placeholder="%">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">% Late Payment Fee: </label>
                                                            <input class="col-6 form-control form-control-sm"
                                                                value="{{$supplier->late_fee}}" name="late_fee" placeholder="%">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">% Overall Discount on the Invoice:
                                                            </label>
                                                            <input class="col-6 form-control form-control-sm"
                                                                value="{{$supplier->overall_discount}}" name="overall_discount" placeholder="%">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-4">Payment Due: </label>
                                                            <select class="col-8 form-control form-control-sm" name="payment_due">
                                                                <option {{$supplier->payment_due == 'Prepaid'?'selected':''}} value="Prepaid">Prepaid</option>
                                                                <option {{$supplier->payment_due == 'Cash on delivery'?'selected':''}} value="Cash on delivery">Cash on delivery
                                                                </option>
                                                                <option {{$supplier->payment_due == 'No of days from the Invoice Date'?'selected':''}} value="No of days from the Invoice Date">No of
                                                                    days from the Invoice Date</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">By the date: </label>
                                                            <input class="col-6 form-control form-control-sm"
                                                                type="date" value="{{$supplier->by_date?$supplier->by_date->format('Y-m-d'):''}}" name="by_date">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-6">After the date: </label>
                                                            <input class="col-6 form-control form-control-sm"
                                                                type="date" value="{{$supplier->after_date?$supplier->after_date->format('Y-m-d'):''}}" name="after_date">
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
                                                            <select class="col-8 form-control form-control-sm" name="payment_method">
                                                                <option {{$supplier->payment_method == 'Cash'?'selected':''}} value="Cash">Cash</option>
                                                                <option {{$supplier->payment_method == 'Cheque'?'selected':''}} value="Cheque">Cheque</option>
                                                                <option {{$supplier->payment_method == 'Bank Transfer'?'selected':''}} value="Bank Transfer">Bank Transfer</option>
                                                                <option {{$supplier->payment_method == 'Money Order'?'selected':''}} value="Money Order">Money Order</option>
                                                                <option {{$supplier->payment_method == 'Eftpos'?'selected':''}} value="Eftpos">Eftpos</option>
                                                                <option {{$supplier->payment_method == 'Bank Card'?'selected':''}} value="Bank Card">Bank Card</option>
                                                                <option {{$supplier->payment_method == 'Master Card'?'selected':''}} value="Master Card">Master Card</option>
                                                                <option {{$supplier->payment_method == 'Visa Card'?'selected':''}} value="Visa Card">Visa Card</option>
                                                                <option {{$supplier->payment_method == 'AMEX'?'selected':''}} value="AMEX">AMEX</option>
                                                                <option {{$supplier->payment_method == 'Other'?'selected':''}} value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <p style="color:red;"> Disclamier : Customer card details is very
                                                        sensitive for the security reason ,<br /> ARKS is not taking any
                                                        responsibilty for any misuse or fradulant of Customer card. </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-3">Note: </label>
                                                        <textarea class="form-control col-9 form-control-sm" rows="8" name="payment_note">{{$supplier->payment_note}}</textarea>
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
                                            <input class="form-control form-control-sm" type="text" id="opening_blnc" step="any" value="{{$supplier->opening_blnc}}" name="opening_blnc" required>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <label>Opening Balance Date <span style="color:red; font-size:10px">(Date must be Last Financial year closing date.)</span></label>
                                            <input class="form-control form-control-sm" type="date"
                                                value="{{$supplier->opening_blnc_date == true?$supplier->opening_blnc_date->format('d/m/Y'):''}}" name="opening_blnc_date" id="opening_blnc_date" readonly>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Credit Account</label>
                                            <select class="form-control form-control-sm" type="text"  name="credit_account" id="credit_account" readonly>
                                                <option value>Selec One Item</option>
                                                @foreach ($creditCodes as $item)
                                                <option {{$supplier->credit_account == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Transaction History End -->
                            </div>
                        </div>
                        <div align="center">
                            <button type="submit" class="btn btn-info" style="width: 300px">Submit</button>
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
@stop
