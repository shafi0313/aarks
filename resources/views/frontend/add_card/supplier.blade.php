@extends('frontend.layout.master')
@section('title','Create')
@section('content')
<?php $p="acard"; $mp="cf";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
<form action="{{route('supplier.store')}}" method="POST" autocomplete="off">
    @csrf
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">Profile</a>
                <a class="nav-item nav-link" id="nav-selling-tab" data-toggle="tab" href="#nav-selling" role="tab" aria-controls="nav-selling" aria-selected="false">Buying Details</a>
                <a class="nav-item nav-link" id="nav-selling-t-tab" data-toggle="tab" href="#nav-selling-t" role="tab" aria-controls="nav-selling-t" aria-selected="false">Buying Terms</a>
                <a class="nav-item nav-link" id="nav-pay-tab" data-toggle="tab" href="#nav-pay" role="tab" aria-controls="nav-pay" aria-selected="false">Payment Method</a>
                {{-- <a class="nav-item nav-link" id="nav-work-tab" data-toggle="tab" href="#nav-work" role="tab" aria-controls="nav-work" aria-selected="false">Work Status</a> --}}
                <a class="nav-item nav-link" id="nav-transaction-tab" data-toggle="tab" href="#nav-transaction" role="tab" aria-controls="nav-transaction" aria-selected="false">Transaction History</a>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <!-- Profile Start -->
            <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
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
                <input type="hidden" name="type" value="2">
                <input type="hidden" name="profession_id" value="{{$profession->id}}">
                <input type="hidden" name="client_id" value="{{client()->id}}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Customer Type:<span class="t_red">*</span> </label>
                                <select class="form-control col-6" name="customer_type" id="customer_type" required>
                                    <option value="Company">Company</option>
                                    <option value="Individual">Individual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4 text-right">Status: <span class="t_red">*</span></div>
                            <div class="col-4">
                                <div class=" form-group form-check">
                                    <input type="radio" class="form-check-input" name="status" value="1" id="active" checked>
                                    <label class="form-check-label" for="active" >Active</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group form-check">
                                    <input type="radio" class="form-check-input" name="status" value="2" id="inactive">
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
                                <label class="col-4 text-right">Name: <span class="t_red">*</span></label>
                                <input required class="col-8 form-control form-control-sm" type="text" name="name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Customer Ref: <span class="t_red">*</span></label>
                                <input required class="col-8 form-control form-control-sm" type="text" name="customer_ref">
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div  style="text-align:center; margin-left: 180px;">Billing Address</div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Address:<span class="t_red">*</span> </label>
                                <input required class="col-8 form-control form-control-sm" type="text" name="b_address" id="b_address">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">City: <span class="t_red">*</span></label>
                                <input required class="col-8 form-control form-control-sm" type="text" name="b_city" id="b_city">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">State: <span class="t_red">*</span></label>
                                <input required class="col-8 form-control form-control-sm" type="text" name="b_state" id="b_state">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Postcode: <span class="t_red">*</span></label>
                                <input required class="col-8 form-control form-control-sm" type="text" name="b_postcode" id="b_postcode">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Country: <span class="t_red">*</span></label>
                                <input required class="col-8 form-control form-control-sm" type="text" name="b_country" id="b_country">

                            </div>
                        </div>
                        <br>
                        <hr>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Phone No: </label>
                                <input class="col-8 form-control form-control-sm" type="text" name="phone">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Mobile Number: </label>
                                <input class="col-8 form-control form-control-sm" type="text" name="mobile">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Email: </label>
                                <input class="col-8 form-control form-control-sm" type="text" name="email">
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div  style="text-align:center; margin-left: 180px;">Shipping Address</div>
                        <div style="text-align:center; margin-left: 180px;">
                            <input type="checkbox" id="same_as_billing">
                            <label for="same_as_billing">Same Billing Address</label>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Address: </label>
                                <input class="col-8 form-control form-control-sm" type="text" name="s_address" id="s_address">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">City: </label>
                                <input class="col-8 form-control form-control-sm" type="text" name="s_city" id="s_city">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">State: </label>
                                <input class="col-8 form-control form-control-sm" type="text" name="s_state" id="s_state">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Postcode: </label>
                                <input class="col-8 form-control form-control-sm" type="text" name="s_postcode" id="s_postcode">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Country: </label>
                                <input class="col-8 form-control form-control-sm" type="text" name="s_country" id="s_country">
                            </div>
                        </div>

                        <br>
                        <hr>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">XXXXXXXXXX: <span class="t_red">*</span></label>
                                <input class="col-8 form-control form-control-sm" type="text" name="xxx">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Greeting: <span class="t_red">*</span></label>
                                <input class="col-8 form-control form-control-sm" type="text" name="greeting">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Profile End -->

            <!-- Selling Details Start -->
            <div class="tab-pane fade show" id="nav-selling" role="tabpanel" aria-labelledby="nav-selling-tab">
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Invoice Layout: <span class="t_red">*</span></label>
                                <select required class="col-8 form-control form-control-sm" name="inv_layout">
                                    <option value="Service">Service</option>
                                    <option value="Item">Item</option>
                                    <option value="Pfosssional">Pfosssional</option>
                                    <option value="Time Billing">Time Billing</option>
                                    <option value="Miscellanious">Miscellanious</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Invoice Delivery: <span class="t_red">*</span></label>
                                <select required class="col-8 form-control form-control-sm" name="inv_delivery">
                                    <option value="Printed & Post">Printed & Post</option>
                                    <option value="E-mail">E-mail</option>
                                    <option value="Printed an E-mail">Printed an E-mail</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Item/Barcode Price: </label>
                                <select class="col-8 form-control form-control-sm" name="barcode_price">
                                    <option value="Future opttion1">Future opttion1</option>
                                    <option value="Future option2">Future option2</option>
                                </select>
                            </div>
                        </div>
                            <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Shipping Method: </label>
                                <select class="col-8 form-control form-control-sm" name="shipping_method">
                                    <option value="Air">Air</option>
                                    <option value="Airfreight">Airfreight</option>
                                    <option value="Australia Post">Australia Post</option>
                                    <option value="Best Way">Best Way</option>
                                    <option value="C.O.D">C.O.D</option>
                                    <option value="Courier">Courier</option>
                                    <option value="Federal Express">Federal Express</option>
                                    <option value="Freight">Freight</option>
                                    <option value="International">International</option>
                                    <option value="Kwikasair">Kwikasair</option>
                                    <option value="Pick Up">Pick Up</option>
                                    <option value="Road Freight">Road Freight</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Invoice Rate: </label>
                                <input class="col-8 form-control form-control-sm" type="number" name="inv_rate" placeholder="$00.00">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Credit Limit: </label>
                                <input class="col-8 form-control form-control-sm" type="number" name="card_limit" placeholder="$00.00">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">A.B.N: </label>
                                <input class="col-8 form-control form-control-sm" type="text" name="abn">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">VAT/GST Code: </label>
                                <select class="col-8 form-control form-control-sm" name="gst_code">
                                    <option disabled selected value>Select Item/Bracode Price</option>
                                    <option value="GST">GST</option>
                                    <option value="FRE">FRE</option>
                                </select>
                            </div>
                        </div>
                            <div class="form-group">
                            <div class="row">
                                <label class="col-4 text-right">Freight VAT/GST Code: </label>
                                <select class="col-8 form-control form-control-sm" name="freight_code">
                                    <option disabled selected value>Select Shipping Method</option>
                                    <option value="GST">GST</option>
                                    <option value="FRE">FRE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Selling Details End -->

            <!-- Selling Terms Start -->
            <div class="tab-pane fade" id="nav-selling-t" role="tabpanel" aria-labelledby="nav-selling-t-tab">
                <div class="card">
                    <div class="card-header" style="background: #337ab7; color: #fff; height: 35px !important; padding: 5px 10px">
                        Customer Terms Information
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-4">Customer Contact Person:</label>
                                        <input class="col-8 form-control form-control-sm" name="contact_person">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-4">Invoice Comment: </label>
                                        <select class="col-8 form-control form-control-sm" name="inv_comment">
                                            <option value="Final Sales">Final Sales.</option>
                                            <option value="Happy Holidays">Happy Holidays!.</option>
                                            <option value="Happy New Year">Happy New Year!</option>
                                            <option value="Pick up ASAP">Pick up ASAP.</option>
                                            <option value="Thank Your">Thank Your!</option>
                                            <option value="We appreciate your business">We appreciate your business.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-6">% Early Payment discount: </label>
                                        <input class="col-6 form-control form-control-sm" name="early_discount" placeholder="%">
                                    </div>
                                </div>
                                    <div class="form-group">
                                    <div class="row">
                                        <label class="col-6">% Late Payment Fee: </label>
                                        <input class="col-6 form-control form-control-sm" name="late_fee" placeholder="%">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-6">% Overall Discount on the Invoice: </label>
                                        <input class="col-6 form-control form-control-sm" name="overall_discount" placeholder="%">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-4">Payment Due: </label>
                                        <select class="col-8 form-control form-control-sm" name="payment_due">
                                            <option value="Prepaid">Prepaid</option>
                                            <option value="Cash on delivery">Cash on delivery</option>
                                            <option value="No of days from the Invoice Date">No of days from the Invoice Date</option>
                                        </select>
                                    </div>
                                </div>
                                    <div class="form-group">
                                    <div class="row">
                                        <label class="col-6">By the date: </label>
                                        <input class="col-6 form-control form-control-sm" type="date" name="by_date" >
                                    </div>
                                </div>
                                    <div class="form-group">
                                    <div class="row">
                                        <label class="col-6">After the date: </label>
                                        <input class="col-6 form-control form-control-sm" type="date" name="after_date" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            <!-- Selling Terms End -->

            <!-- Payment Method Start -->
            <div class="tab-pane fade show" id="nav-pay" role="tabpanel" aria-labelledby="nav-pay-tab">
                <div class="card">
                    <div class="card-header" style="background: #337ab7; color: #fff; height: 35px !important; padding: 5px 10px">Customer Payment Method</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-4">Payment Method</label>
                                        <select class="col-8 form-control form-control-sm" name="payment_method">
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Money Order">Money Order</option>
                                            <option value="Eftpos">Eftpos</option>
                                            <option value="Bank Card">Bank Card</option>
                                            <option value="Master Card">Master Card</option>
                                            <option value="Visa Card">Visa Card</option>
                                            <option value="AMEX">AMEX</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <p style="color:red;"> Disclamier : Customer card details is very sensitive for the security reason ,<br /> ARKS is not taking any responsibilty for any misuse or fradulant of Customer card. </p>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-3">Note: </label>
                                    <textarea class="form-control col-9 form-control-sm" name="" rows="8" name="payment_note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Payment Method End -->

            <!-- Transaction History Start -->
            <div class="tab-pane fade show" id="nav-transaction" role="tabpanel" aria-labelledby="nav-transaction-tab">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Opening Balance <span style="font-size: 12px">(wright 0 if there is no amount)</span><span class="t_red">*</span></label>
                        <input class="form-control form-control-sm" type="number" min="0" step="any" id="opening_blnc" name="opening_blnc" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Opening Balance Date <span class="t_red">*</span></label>
                        <input class="form-control form-control-sm" type="date" name="opening_blnc_date" id="opening_blnc_date"
                            disabled>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Credit Account <span class="t_red">*</span></label>
                        <select class="form-control form-control-sm" name="credit_account" id="credit_account" disabled>
                            @foreach ($creditCodes as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="m-auto">
                        <div class="alert alert-danger" style="color:black; padding: 2px; margin: 5px">Caution!! Supplier opening balance date must be previous year closing date. i.e 30/06/prevous financial year.</div>
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
            $("#opening_blnc_date").attr('disabled','disabled')
            $("#credit_account").attr('disabled','disabled')
        }else{
            $("#opening_blnc_date").removeAttr('disabled').attr('required','required')
            $("#credit_account").removeAttr('disabled').attr('required','required')
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
