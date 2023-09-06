<div style="padding:30px; display:{{$inv_item->type==1?'block':'none'}};" class="{{$inv_item->type==1?'':'buy_details'}}">
    <strong style="color:green;">Details of Buy Item :</strong>
    <div class="col-md-12 thumbnail">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Measure Unit</label>
                    <select class="form-control unitmange" id="buy_measure_unit" name="measure_id[]" required=""{{$inv_item->type==1?'':'disabled'}}>
                        @foreach ($measures as $measure)
                        <option value="{{$measure->id}}" {{$inv_item->measure_id == $measure->id?'selected':''}}>{{$measure->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Price(Ex Gst Rate)</label>
                    <input type="text" name="price[]" {{$inv_item->type==1?'':'disabled'}} id="buy_price" class="form-control" required value="{{$inv_item->price}}" >
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Tax Code</label>
                    <select class="form-control" id="buy_tax_code" {{$inv_item->type==1?'':'disabled'}} name="gst_code[]" required="">
                        <option value="">--Select Tax Code--</option>
                        @foreach (aarks('gst_code') as $gst)
                        <option value="{{$gst}}" {{$inv_item->gst_code == $gst?'selected':''}}>{{$gst}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Supplier Name</label>
                    <select class="form-control" id="buy_supplier_name" {{$inv_item->type==1?'':'disabled'}} name="customer_card_id[]" required="">
                        <option value="">--Select Supplier Name--</option>
                        @foreach ($customers->where('type', 2) as $supplier)
                        <option value="{{$supplier->id}}" {{$inv_item->customer_card_id == $supplier->id ?'selected':''}}>{{$supplier->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Expense Account</label>
                    <select class="form-control" id="expense_account" {{$inv_item->type==1?'':'disabled'}} name="client_account_code_id[]"
                        required="">
                        <option value="">--Select Expense Account--</option>
                        @foreach ($expences as $code)
                        <option value="{{$code->id}}" {{$inv_item->client_account_code_id ==$code->id ?'selected':''}}>{{$code->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="padding:30px; display:{{$inv_item->type==2?'block':'none'}};" class="{{$inv_item->type==2?'':'sell_details'}}">
    <strong style="color:green;">Details of Sell Item :</strong>
    <div class="col-md-12 thumbnail">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Measure Unit</label>
                    <select class="form-control unitmange" id="sell_measure_unit" name="measure_id[]" required=""{{$inv_item->type==2?'':'disabled'}}>
                        <option value="0">New Measure Unit</option>
                        @foreach ($measures as $measure)
                        <option value="{{$measure->id}}" {{$inv_item->measure_id == $measure->id?'selected':''}}>{{$measure->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Price(Ex Gst Rate)</label>
                    <input type="text" oninput="this.value = this.value.replace(/[^\d.]/g,'');" name="price[]" {{$inv_item->type==2?'':'disabled'}}
                        id="sell_price" class="form-control" required="" value="{{$inv_item->price}}">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Tax Code</label>
                    <select class="form-control" id="sell_tax_code" {{$inv_item->type==2?'':'disabled'}} name="gst_code[]" required="">
                        <option value="">--Select Tax Code--</option>
                        @foreach (aarks('gst_code') as $gst)
                        <option value="{{$gst}}" {{$inv_item->gst_code == $gst?'selected':''}}>{{$gst}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Customer Name</label>
                    <select class="form-control" id="sell_supplier_name" {{$inv_item->type==2?'':'disabled'}} name="customer_card_id[]" {{$inv_item->type==2?'':'disabled'}}required="">
                        <option value="">--Select Customer Name--</option>
                        @foreach ($customers->where('type', 1) as $customer)
                        <option value="{{$customer->id}}" {{$inv_item->customer_card_id == $customer->id ?'selected':''}}>{{$customer->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Income Account</label>
                    <select class="form-control" id="income_account" {{$inv_item->type==2?'':'disabled'}} name="client_account_code_id[]"
                        required="">
                        <option value="">--Select Expense Account--</option>
                        @foreach ($incomes as $income)
                        <option value="{{$income->id}}" {{$inv_item->client_account_code_id ==$income->id ?'selected':''}}>{{$income->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="padding:30px; display:{{$inv_item->type==3?'block':'none'}};" class="{{$inv_item->type==3?'':'invontry_details'}}">
    <strong style="color:green;">Details of Inventory Item :</strong>
    <div class="col-md-12 thumbnail">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Measure Unit</label>
                    <select class="form-control unitmange" id="inv_measure_unit" name="measure_id[]" required="" {{$inv_item->type==3?'':'disabled'}}>
                        @foreach ($measures as $measure)
                        <option value="{{$measure->id}}" {{$inv_item->measure_id == $measure->id?'selected':''}}>{{$measure->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Price(Ex Gst Rate)</label>
                    <input type="text" oninput="this.value = this.value.replace(/[^\d.]/g,'');" name="price[]" {{$inv_item->type==3?'':'disabled'}}
                        id="inv_price" class="form-control" required="" value="{{$inv_item->price}}">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Tax Code</label>
                    <select class="form-control" id="inv_tax_code" {{$inv_item->type==3?'':'disabled'}} name="gst_code[]" required="">
                        <option value="">--Select Tax Code--</option>
                        @foreach (aarks('gst_code') as $gst)
                        <option value="{{$gst}}" {{$inv_item->gst_code == $gst?'selected':''}}>{{$gst}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Supplier Name</label>
                    <select class="form-control" id="inv_supplier_name" {{$inv_item->type==3?'':'disabled'}} name="customer_card_id[]" required="">
                        <option value="">--Select Supplier Name--</option>
                        @foreach ($customers->where('type', 2) as $supplier)
                        <option value="{{$supplier->id}}" {{$inv_item->customer_card_id == $supplier->id ?'selected':''}}>{{$supplier->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Asset Account</label>
                    <select class="form-control" id="asset_account" {{$inv_item->type==3?'':'disabled'}} name="client_account_code_id[]"
                        required="">
                        <option value="">--Select Asset Account--</option>
                        @foreach ($assets as $asset)
                        <option value="{{$asset->id}}" {{$inv_item->client_account_code_id ==$asset->id ?'selected':''}}>{{$asset->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2 offset-1">
        <div class="form-group">
            <label>Qun On hand date </label>
            <input type="text" name="qun_date" data-date-format="dd/mm/yyyy" id="on_hand_date"
                class="form-control datepicker" placholder="DD/MM/YYYY">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Qun On hand </label>
            <input type="text" name="qun_hand" id="inv_qun_on_hand" class="form-control"
                oninput="this.value = this.value.replace(/[^\d.]/g,'');">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Rate</label>
            <input type="text" name="rate" id="qty_rate" class="form-control"
                oninput="this.value = this.value.replace(/[^\d.]/g,'');">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Current Value</label>
            <input type="text" name="current_value" id="current_value" class="form-control" readonly="">
        </div>
    </div>
</div>
