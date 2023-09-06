@extends('frontend.layout.master')
@section('title','Add Edit Period')
@section('content')
<?php $p="aep"; $mp="acccounts";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="" >
                                <div class="form-group">
                                    <label>Add Item On your Business<strong style="color:red;">*</strong></label>
                                    <select class="form-control">
                                      <option disabled selected value>Select Category</option>
                                      <option>Test</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Item Number</label>
                                        <input type="number" class="form-control">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label>Item Name</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="item_buy" id="item_buy" value="1">Buy Item
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="item_sell" id="item_sell" value="2">Sell Item
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="item_stock" id="item_stock" value="3">Stock Item
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="item_status" required value="1">Active Item
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="item_status" required value="2">Inactive Item
                                    </label>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Bin Number</label>
                                        <input type="number" class="form-control">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label>Barcode Number</label>
                                        <input type="number" class="form-control">
                                    </div>
                                </div>









                            <div style="padding:30px; display:none;" class="buy_details">
                                <strong style="color:green;">Details of Buy Item :</strong>
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label>Measure Unit</label>
                                        <select class="form-control unitmange" id="buy_measure_unit" name="buy_measure_unit" required>
                                            <option value="">--Select Measure Unit--</option>
                                            <option value="0">New Measure Unit</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label>Price(Ex Gst Rate)</label>
                                        <input type="text" name="buy_price" oninput="this.value = this.value.replace(/[^\d]/g,'');" id="buy_price" class="form-control" required />
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label>Tax Code</label>
                                        <select class="form-control" id="buy_tax_code" name="buy_tax_code" required>
                                            <option value="">--Select Tax Code--</option>
                                            <option value="GST">GST</option>
                                            <option value="INP">INP</option>
                                            <option value="FRE">FRE</option>
                                            <option value="FOA">FOA</option>
                                            <option value="CAP">CAP</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <select class="form-control" id="buy_supplier_name" name="buy_supplier_name" required>
                                                <option value="">--Select Supplier Name--</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label>Expense Account</label>
                                        <select class="form-control" id="expense_account" name="expense_account" required>
                                            <option value="">--Select Expense Account--</option>

                                            <option value="5475">Opening stock of Raw Material</option>

                                            <option value="5495">Purchase of Manufacturing/production Material</option>

                                            <option value="5496">Purchase of Material </option>

                                            <option value="5497">Purchase of Material NON GST</option>

                                            <option value="5498">Opening stock raw material for Trading account</option>

                                            <option value="5499">Purchase of Packing Material </option>

                                            <option value="5500">Purchase of Other Material </option>

                                            <option value="5501">Purchase of Other Material Non GST</option>

                                            <option value="5502">Closing Stock of Raw material (Manufacturing)</option>

                                            <option value="5503">Closing Stock of Raw Material ( Trading)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>




                            <div style="padding:30px; display:none;" class="sell_details">
                                <strong style="color:green;">Details of Sell Item :</strong>
                                <div class="row">


                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Measure Unit</label>
                                            <select class="form-control unitmange" id="sell_measure_unit" name="sell_measure_unit" required>
                                                <option value="">--Select Measure Unit--</option>
                                                <option value="0">New Measure Unit</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Price(Ex Gst Rate)</label>
                                            <input type="text" oninput="this.value = this.value.replace(/[^\d]/g,'');" name="sell_price" id="sell_price" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tax Code</label>
                                            <select class="form-control" id="sell_tax_code" name="sell_tax_code" required>
                                                <option value="">--Select Tax Code--</option>
                                                <option value="GST">GST</option>
                                                <option value="INP">INP</option>
                                                <option value="FRE">FRE</option>
                                                <option value="FOA">FOA</option>
                                                <option value="CAP">CAP</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Customer Name</label>
                                            <select class="form-control" id="sell_supplier_name" name="sell_supplier_name" required>
                                                <option value="">--Select Customer Name--</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Income Account</label>
                                            <select class="form-control" id="income_account" name="income_account" required>
                                                <option value="">--Select Expense Account--</option>

                                                <option value="5474">Finished Goods Sales</option>

                                                <option value="5489">Sales</option>

                                                <option value="5490">Sales NON GST Item</option>

                                                <option value="5491">Gross Received for provided services</option>

                                                <option value="5492">Commission Received</option>

                                                <option value="5493">Interest Received</option>

                                                <option value="5494">Profit/Loss Sales of Assets</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div style="padding:30px; display:none;" class="invontry_details">
                                <strong style="color:green;">Details of Inventory Item :</strong>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Measure Unit</label>
                                            <select class="form-control unitmange" id="inv_measure_unit" name="inv_measure_unit" required>
                                                <option value="">--Select Measure Unit--</option>
                                                <option value="0">New Measure Unit</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Price(Ex Gst Rate)</label>
                                            <input type="text" oninput="this.value = this.value.replace(/[^\d]/g,'');"  name="inv_price" id="inv_price" class="form-control"  required/>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tax Code</label>
                                            <select class="form-control" id="inv_tax_code" name="inv_tax_code" required>
                                                <option value="">--Select Tax Code--</option>
                                                <option value="GST">GST</option>
                                                <option value="INP">INP</option>
                                                <option value="FRE">FRE</option>
                                                <option value="FOA">FOA</option>
                                                <option value="CAP">CAP</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <select class="form-control" id="inv_supplier_name" name="inv_supplier_name" required>
                                                <option value="">--Select Supplier Name--</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Asset Account</label>
                                            <select class="form-control" id="asset_account" name="asset_account" required>
                                                <option value="">--Select Asset Account--</option>
                                                <option value="5478">Cash at Bank - 1</option>

                                                <option value="5479">Cash in Hand</option>

                                                <option value="5480">Trade Debtors</option>

                                                <option value="5481">Bank Account - 2</option>

                                                <option value="5482">Bank Account - 3</option>

                                                <option value="5483">Bank Account - 4</option>

                                                <option value="5484">Bank Account - 5</option>

                                                <option value="5485">Eftpos- 1  Received</option>

                                                <option value="5486">Payroll Clearing Account</option>

                                                <option value="5487">Suspense clearing account.</option>

                                                <option value="5488">Short Term Deposits</option>

                                                <option value="5600">Closing Stock of Raw Material</option>

                                                <option value="5601">Closing Stock of WIP</option>

                                                <option value="5602">Closing Stock of finished Goods</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Qun On hand date </label>
                                            <input type="text" name="on_hand_date" data-date-format="dd/mm/yyyy" id="on_hand_date" class="form-control date-picker" placholder="DD/MM/YYYY" required/>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Qun On hand </label>
                                            <input type="text" name="inv_qun_on_hand" id="inv_qun_on_hand" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Rate</label>
                                            <input type="text" name="qty_rate" id="qty_rate" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Current Value</label>
                                            <input type="text" name="current_value" id="current_value" class="form-control" readonly="" required />
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

    <script>
    $('#item_buy').on('change', function(){
        $('#buy_measure_unit').attr('required');
        $('#buy_price').attr('required');
        $('#buy_tax_code').attr('required');
        $('#buy_supplier_name').attr('required');
        $('#expense_account').attr('required');

        $('#sell_measure_unit').removeAttr('required');
        $('#sell_price').removeAttr('required');
        $('#sell_tax_code').removeAttr('required');
        $('#sell_supplier_name').removeAttr('required');
        $('#income_account').removeAttr('required');

        $('#inv_measure_unit').removeAttr('required');
        $('#inv_price').removeAttr('required');
        $('#inv_tax_code').removeAttr('required');
        $('#inv_supplier_name').removeAttr('required');
        $('#asset_account').removeAttr('required');

        $('#on_hand_date').removeAttr('required');
        $('#inv_qun_on_hand').removeAttr('required');
        $('#qty_rate').removeAttr('required');
        $('#current_value').removeAttr('required');
    });

    $('#item_sell').on('change', function(){
        $('#buy_measure_unit').removeAttr('required');
        $('#buy_price').removeAttr('required');
        $('#buy_tax_code').removeAttr('required');
        $('#buy_supplier_name').removeAttr('required');
        $('#expense_account').removeAttr('required');

        $('#sell_measure_unit').attr('required');
        $('#sell_price').attr('required');
        $('#sell_tax_code').attr('required');
        $('#sell_supplier_name').attr('required');
        $('#income_account').attr('required');

        $('#inv_measure_unit').removeAttr('required');
        $('#inv_price').removeAttr('required');
        $('#inv_tax_code').removeAttr('required');
        $('#inv_supplier_name').removeAttr('required');
        $('#asset_account').removeAttr('required');

        $('#on_hand_date').removeAttr('required');
        $('#inv_qun_on_hand').removeAttr('required');
        $('#qty_rate').removeAttr('required');
        $('#current_value').removeAttr('required');

    });

    $('#item_stock').on('change', function(){
        $('#buy_measure_unit').removeAttr('required');
        $('#buy_price').removeAttr('required');
        $('#buy_tax_code').removeAttr('required');
        $('#buy_supplier_name').removeAttr('required');
        $('#expense_account').removeAttr('required');

        $('#sell_measure_unit').removeAttr('required');
        $('#sell_price').removeAttr('required');
        $('#sell_tax_code').removeAttr('required');
        $('#sell_supplier_name').removeAttr('required');
        $('#income_account').removeAttr('required');

        $('#inv_measure_unit').attr('required');
        $('#inv_price').attr('required');
        $('#inv_tax_code').attr('required');
        $('#inv_supplier_name').attr('required');
        $('#asset_account').attr('required');

        $('#on_hand_date').attr('required');
        $('#inv_qun_on_hand').attr('required');
        $('#qty_rate').attr('required');
        $('#current_value').attr('required');
    });

$("#barcode").on('keyup', function(){
    var barcode = $('#barcode').val();
    var professionid = $('#professionid').val();
    var subgrouurl = "https://www.aarks.com.au/books/Add_item/uniqueBarcode";
    $.ajax({
        url:subgrouurl,
        type:"POST",
        data:{barcode:barcode, professionid:professionid},
        success:function(data){

            if(data=='notFound'){
                $('.barcodeCheck').removeAttr('disabled', 'disabled');
            } else {

                $('.barcodeCheck').attr('disabled', 'disabled');
                alert('Barcode matched with previous item!');
            }
        }
    });
});


$("#qty_rate, #inv_qun_on_hand").on('keyup', function(){
    var qty_rate = $('#qty_rate').val();
    var inv_qun_on_hand = $('#inv_qun_on_hand').val();
    var current_value   = inv_qun_on_hand * qty_rate;
    $('#current_value').val(current_value.toFixed(2));

});






$("#item_buy").click( function(){
   if( $(this).is(':checked') ){
   $('.buy_details').css('display', 'block');
   } else {
   $('.buy_details').css('display', 'none');
   }
});

$("#item_sell").click( function(){
   if( $(this).is(':checked') ){
   $('.sell_details').css('display', 'block');
   } else {
   $('.sell_details').css('display', 'none');
   }
});

$("#item_stock").click( function(){
   if( $(this).is(':checked') ){
   $('.invontry_details').css('display', 'block');
   } else {
   $('.invontry_details').css('display', 'none');
   }
});
</script>





<script>
$(".unitmange").on('change', function(){
    var buy_measure_unit = $(this).val();
    if(buy_measure_unit == "0"){
        $('#buy_meaaure_myModal').modal('show');
    }
});



$("#unit_manage_db").submit(function(e)
        {
            //alert("sdfsadf");
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            $.ajax(
            {
                url : formURL,
                timeout: 1000,
                type: "POST",
                async:false,
                crossDomain:true,
                data : postData,
                success:function(data){
                $("#buy_measure_unit").html(data);
                $("#sell_measure_unit").html(data);
                $("#inv_measure_unit").html(data);
                $(".job_success").text('Template saved successfully completed.');
                $("#unit_name").val("");
                $('#unit_details').val("");
                }

            });
            e.preventDefault();
        });
</script>

@stop


