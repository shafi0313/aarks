@extends('frontend.layout.master')
@section('title','Customar')
@section('content')
<?php $p="acard"; $mp="cf";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
<form action=" {{route('customer.store')}} " method="POST">
@csrf
<div class="card-body">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">

            <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">Profile</a>

            <a class="nav-item nav-link" id="nav-selling-tab" data-toggle="tab" href="#nav-selling" role="tab" aria-controls="nav-selling" aria-selected="false">Buying Details</a>

            <a class="nav-item nav-link" id="nav-selling-t-tab" data-toggle="tab" href="#nav-selling-t" role="tab" aria-controls="nav-selling-t" aria-selected="false">Buying Terms</a>


            <a class="nav-item nav-link" id="nav-pay-tab" data-toggle="tab" href="#nav-pay" role="tab" aria-controls="nav-pay" aria-selected="false">Payment Method</a>

            <a class="nav-item nav-link" id="nav-work-tab" data-toggle="tab" href="#nav-work" role="tab" aria-controls="nav-work" aria-selected="false">Work Status</a>

            <a class="nav-item nav-link" id="nav-transaction-tab" data-toggle="tab" href="#nav-transaction" role="tab" aria-controls="nav-transaction" aria-selected="false">Transaction History</a>

        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <!-- Profile Start -->
        <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Customer Type: </label>
                            <input class="col-8 form-control" type="" name="" value="">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-4 text-right">State: </div>
                        <div class="col-4">
                            <div class=" form-group form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Name: </label>
                            <input class="col-8 form-control" type="" name="" value="">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Cust Ref: </label>
                            <input class="col-8 form-control" type="text" name="">
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
                            <label class="col-4 text-right">Country: </label>
                            <input class="col-8 form-control" type="" name="" value="">
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Phone No: </label>
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
                    <div  style="text-align:center; margin-left: 180px;">Shipping Address</div>
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
                            <label class="col-4 text-right">Country: </label>
                            <input class="col-8 form-control" type="" name="" value="">
                        </div>
                    </div>

                    <br>
                    <hr>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">XXXXXXXXXX: </label>
                            <input class="col-8 form-control" type="" name="" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Greeting: </label>
                            <input class="col-8 form-control" type="" name="" value="">
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
                            <label class="col-4 text-right">Invoice Layout: </label>
                            <select class="col-8 form-control" name="">
                                <option disabled selected value>Select Invoice Design</option>
                                <option value="">Item</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Invoice Delivery: </label>
                            <select class="col-8 form-control" name="">
                                <option disabled selected value>Select Invoice Delivery</option>
                                <option value="">Item</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Item/Barcode Price: </label>
                            <select class="col-8 form-control" name="">
                                <option disabled selected value>Select Item/Bracode Price</option>
                                <option value="">Item</option>
                            </select>
                        </div>
                    </div>
                        <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Shipping Method: </label>
                            <select class="col-8 form-control" name="">
                                <option disabled selected value>Select Shipping Method</option>
                                <option value="">Item</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Invoice Rate: </label>
                            <input class="col-8 form-control" type="number" name="" value="">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Credit Limit: </label>
                            <input class="col-8 form-control" type="text" name="" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">A.B.N: </label>
                            <input class="col-8 form-control" type="text" name="" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">VAT/GST Code: </label>
                            <select class="col-8 form-control" name="">
                                <option disabled selected value>Select Item/Bracode Price</option>
                                <option value="">Item</option>
                            </select>
                        </div>
                    </div>
                        <div class="form-group">
                        <div class="row">
                            <label class="col-4 text-right">Freight VAT/GST Code: </label>
                            <select class="col-8 form-control" name="">
                                <option disabled selected value>Select Shipping Method</option>
                                <option value="">Item</option>
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

                </style>
                    Customer Terms Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-4">Customer Contact Person: </label>
                                    <input class="col-8 form-control" name="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-4">Invoice Comment: </label>
                                    <select class="col-8 form-control" name="">
                                        <option disabled selected value>Select Invoice Delivery</option>
                                        <option value="">Item</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-6">% Early Payment discount: </label>
                                    <input class="col-6 form-control" name="" placeholder="%">
                                </div>
                            </div>
                                <div class="form-group">
                                <div class="row">
                                    <label class="col-6">% Late Payment Fee: </label>
                                    <input class="col-6 form-control" name="" placeholder="%">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-6">% Overall Discount on the Invoice: </label>
                                    <input class="col-6 form-control" name="" placeholder="%">
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-4">Payment Due: </label>
                                    <select class="col-8 form-control" name="">
                                        <option disabled selected value>Select Payment Due</option>
                                        <option value="">Item</option>
                                    </select>
                                </div>
                            </div>
                                <div class="form-group">
                                <div class="row">
                                    <label class="col-6">By the date: </label>
                                    <input class="col-6 form-control" type="date" name="" >
                                </div>
                            </div>
                                <div class="form-group">
                                <div class="row">
                                    <label class="col-6">After the date: </label>
                                    <input class="col-6 form-control" type="date" name="" >
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
                                    <select class="col-8 form-control" name="">
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
                            </div>
                            <p style="color:red;"> Disclamier : Customer card details is very sensitive for the security reason ,<br /> ARKS is not taking any responsibilty for any misuse or fradulant of Customer card. </p>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Note: </label>
                                <textarea class="form-control" name="" rows="8"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Payment Method End -->

        <!-- Work Satus Start -->
        <div class="tab-pane fade show" id="nav-work" role="tabpanel" aria-labelledby="nav-work-tab">
            <div class="card">
                <div class="card-header" style="background: #337ab7; color: #fff; height: 35px !important; padding: 5px 10px">Job Work</div>
                <div class="card-body">
                    <div class="row">
                        <label class="col-4">Work Status</label>
                        <select class="col-6 form-control" name="">
                            <option disabled selected value>Select Work Status</option>
                            <option value="1">Incomplete Jobs</option>
                            <option value="2">Partial complete</option>
                            <option value="3">100% complete</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <!-- Work Satus End -->

        <!-- Transaction History Start -->
        <div class="tab-pane fade show" id="nav-transaction" role="tabpanel" aria-labelledby="nav-transaction-tab">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Opening Balance</label>
                    <input class="form-control" type="text" name="">
                </div>
                <div class="col-md-4 form-group">
                    <label>Opening Balance Date</label>
                    <input class="form-control" type="date" name="">
                </div>
                <div class="col-md-4 form-group">
                    <label>Credit Account</label>
                    <select class="form-control" type="text" name="">
                        <option value="">Select Credit Account</option>
                    </select>
                </div>

            </div>
        </div>
            <div align="center">
                <button type="submit" class="btn btn-info px-4" style="width: 300px;">Submit</button>
            </div>
        <!-- Transaction History End -->
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

@stop
