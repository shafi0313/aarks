<div style="padding:30px; display:none;" class="buy_details">
    <strong style="color:green;">Details of Buy Item :</strong>
    <div class="col-md-12 thumbnail">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Measure Unit<span class="t_red">*</span></label>
                    <select class="form-control unitmange" id="buy_measure_unit" name="measure_id[]" required="">
                        <option value="" selected disabled>--Select Measure Unit--</option>
                        <option value="0">New Measure Unit</option>
                        @foreach ($measures as $measure)
                        <option value="{{$measure->id}}">{{$measure->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Price(Ex Gst Rate)<span class="t_red">*</span></label>
                    <input type="text" name="price[]" required disabled oninput="this.value = this.value.replace(/[^\d]/g,'');"
                        id="buy_price" class="form-control" required="">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Tax Code<span class="t_red">*</span></label>
                    <select class="form-control" id="buy_tax_code" disabled name="gst_code[]" required="">
                        <option value="">--Select Tax Code--</option>
                        @foreach (aarks('gst_code') as $gst)
                        <option value="{{$gst}}">{{$gst}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Supplier Name<span class="t_red">*</span></label>
                    <select class="form-control" id="buy_supplier_name" disabled name="customer_card_id[]" required="">
                        <option value="">--Select Supplier Name--</option>
                        @foreach ($customers->where('type', 2) as $supplier)
                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Expense Account<span class="t_red">*</span></label>
                    <select class="form-control" id="expense_account" disabled name="client_account_code_id[]" required="">
                        <option value="">--Select Expense Account--</option>
                        @foreach ($expences as $code)
                        <option value="{{$code->id}}">{{$code->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="padding:30px; display:none;" class="sell_details">
    <strong style="color:green;">Details of Sell Item :</strong>
    <div class="col-md-12 thumbnail">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Measure Unit<span class="t_red">*</span></label>
                    <select class="form-control unitmange" id="sell_measure_unit" name="measure_id[]" required="">
                        <option value="" selected disabled>--Select Measure Unit--</option>
                        <option value="0">New Measure Unit</option>
                        @foreach ($measures as $measure)
                        <option value="{{$measure->id}}">{{$measure->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Price(Ex Gst Rate)<span class="t_red">*</span></label>
                    <input type="text" oninput="this.value = this.value.replace(/[^\d]/g,'');" name="price[]" disabled
                        id="sell_price" class="form-control" required="">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Tax Code<span class="t_red">*</span></label>
                    <select class="form-control" id="sell_tax_code" disabled name="gst_code[]" required="">
                        <option value="">--Select Tax Code--</option>
                        @foreach (aarks('gst_code') as $gst)
                        <option value="{{$gst}}">{{$gst}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Customer Name<span class="t_red">*</span></label>
                    <select class="form-control" id="sell_supplier_name" disabled name="customer_card_id[]"
                        disabled name="customer_card_id[]" required="">
                        <option value="">--Select Customer Name--</option>
                        @foreach ($customers->where('type', 1) as $customer)
                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Income Account<span class="t_red">*</span></label>
                    <select class="form-control" id="income_account" disabled name="client_account_code_id[]" required="">
                        <option value="">--Select Expense Account--</option>
                        @foreach ($incomes as $income)
                        <option value="{{$income->id}}">{{$income->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="padding:30px; display:none;" class="invontry_details">
    <strong style="color:green;">Details of Invontory Item :</strong>
    <div class="col-md-12 thumbnail">
        <div class="row">


            <div class="col-md-2">
                <div class="form-group">
                    <label>Measure Unit<span class="t_red">*</span></label>
                    <select class="form-control unitmange" id="inv_measure_unit" name="measure_id[]" required="">
                        <option value="" selected disabled>--Select Measure Unit--</option>
                        <option value="0">New Measure Unit</option>
                        @foreach ($measures as $measure)
                        <option value="{{$measure->id}}">{{$measure->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Price(Ex Gst Rate)<span class="t_red">*</span></label>
                    <input type="text" oninput="this.value = this.value.replace(/[^\d]/g,'');" name="price[]" disabled
                        id="inv_price" class="form-control" required="">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Tax Code<span class="t_red">*</span></label>
                    <select class="form-control" id="inv_tax_code" disabled name="gst_code[]" required="">
                        <option value="">--Select Tax Code--</option>
                        @foreach (aarks('gst_code') as $gst)
                        <option value="{{$gst}}">{{$gst}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Supplier Name<span class="t_red">*</span></label>
                    <select class="form-control" id="inv_supplier_name" disabled name="customer_card_id[]" required="">
                        <option value="">--Select Supplier Name--</option>
                        @foreach ($customers->where('type', 2) as $supplier)
                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Asset Account<span class="t_red">*</span></label>
                    <select class="form-control" id="asset_account" disabled name="client_account_code_id[]" required="">
                        <option value="">--Select Asset Account--</option>
                        @foreach ($assets as $asset)
                        <option value="{{$asset->id}}">{{$asset->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Qun On hand date<span class="t_red">*</span></label>
                    <input type="text" name="qun_date" data-date-format="dd/mm/yyyy" id="on_hand_date"
                        class="form-control datepicker" placholder="DD/MM/YYYY" required="">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Qun On hand<span class="t_red">*</span></label>
                    <input type="text" name="qun_hand" id="inv_qun_on_hand" class="form-control" required=""
                        oninput="this.value = this.value.replace(/[^\d]/g,'');">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Rate<span class="t_red">*</span></label>
                    <input type="text" name="qun_rate" id="qty_rate" class="form-control" required=""
                        oninput="this.value = this.value.replace(/[^\d]/g,'');">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Current Value<span class="t_red">*</span></label>
                    <input type="text" name="current_value" id="current_value" class="form-control" readonly=""
                        required="">
                </div>
            </div>
        </div>
    </div>
</div>
