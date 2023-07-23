<div class="row mx-auto">
    <div class="form-group mx-1" style="width: 190px">
        <label class="">Item Name:<span class="t_red">*</span> </label>
        <select name="item_id" id="item_id" class="form-control">
            <option value>Select An Item</option>
            @foreach ($categories as $category)
            @foreach ($category->items as $item)
            <option value="{{$item->id}}" account_id="{{$item->code->code}}" account_name="{{$item->code->name}} " data-item_type="{{$item->type}}" data-sell-price='{{$item->price}}' gst_id='{{$item->gst_code}}' >{{$category->name}} => {{$item->item_name}}</option>
            @endforeach
            @endforeach
            <option value="new" style="color: pink">Add New Item</option>
        </select>
    </div>

    <div class="form-group mx-1" style="width: 100px">
        <label>Quantity</label>
        <input type="number" name="quantity" id="quantity" oninput="this.value = this.value.replace(/[^\d]/g,'');" class="form-control">
    </div>
    <div class="form-group mx-1" style="width: 100px">
        <label style="font-size: 15px">Rate(Ex GST)</label>
        <input type="number" name="rate" id="rate" oninput="this.value = this.value.replace(/[^\d]/g,'');" class="form-control">
    </div>
    <div class="form-group mx-1" style="width: 100px">
        <label>Amount</label>
        <input readonly type="number" name="amount" id="amount" class="form-control">
    </div>
    <div class="form-group mx-1" style="width: 100px">
        <label>Disc %: </label>
        <input class="form-control" type="Number" name="disc_rate" id="disc_rate" placeholder="Disc %" oninput="this.value = this.value.replace(/[^\d]/g,'');">
    </div>
    <div class="form-group mx-1" style="width: 100px">
        <label style="font-size: 14px">Freight Charge: </label>
        <input class="form-control" type="Number" name="freight_charge" id="freight_charge" oninput="this.value = this.value.replace(/[^\d]/g,'');">
    </div>
    <div class="form-group mx-1" style="width: 150px">
        <label>Account Code: </label>
        <input type="hidden" name="chart_id" id="chart_id">
        <input class="form-control" type="text" readonly id="accountName">
        <select id="salesCode" class="form-control" style="display: none">
            <option value>Select a code</option>
            @foreach ($codes as $code)
            <option value="{{$code->code}}" data-gst="{{$code->gst_code}}">{{$code->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group mx-1" style="width:100px">
        <label>Tax: </label>
        <input type="text" name="is_tax" id="is_tax" readonly value class="form-control">
    </div>
    <div style="margin-top: 32px;margin-left:7px">
        <button class="btn btn-success add-item" type="button">Add</button>
    </div>
</div>
<hr>

<table class="table table-striped table-bordered table-hover table-sm item-table">
    <thead class="text-center" style="font-size: 15px;">
        <tr>
            <th width="4%">SN</th>
            <th width="%">Description</th>
            <th width="8%">Quantity </th>
            <th width="8%">Price </th>
            <th width="8%">Amount (Ex GST) </th>
            <th width="7%">Disc %</th>
            <th width="11%">Freight Chrg</th>
            <th width="10%">Account</th>
            <th width="9%">Tax Rate</th>
            <th width="12%">Amount AUD</th>
            <th width="3%">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="9" class="text-right">Total:</th>
            <th class="text-center sub-total">$ 0.00 </th>
            <th>
                <input type="hidden" name="total_amount" id="total_amount">
                <input type="hidden" name="gst_amt_subtotal" id="gst_amt_subtotal">
            </th>
        </tr>
    </tfoot>
</table>
<br>
