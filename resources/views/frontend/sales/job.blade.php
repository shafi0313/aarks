<div class="row mx-auto">
    <div class="form-group mx-1">
        <label class="">Job Title:<span class="t_red">*</span> </label>
        <input class="form-control form-control-sm" type="text" name="job_title" placeholder="Job Title" id="job_title">
    </div>

    <div class="form-group mx-1" style="width: 250px">
        <label>Job Description:<span class="t_red">*</span> <button type="button" class="btn btn-warning btn-sm"
                style="padding:0 13px; font-size:12px" data-toggle="modal" data-target="#job"><i
                    class="fas fa-sticky-note"></i></button>
        </label>
        <textarea class="form-control form-control-sm" rows="1" name="job_des" placeholder="Job Description"
            id="job_des"></textarea>
    </div>
    <div class="form-group mx-1" style="width: 100px">
        <label>Price:<span class="t_red">*</span> </label>
        <input class="form-control form-control-sm" step="any" type="Number" name="price" id="price"
            placeholder="Price">
    </div>
    <div class="form-group mx-1" style="width: 100px">
        <label>Disc %: </label>
        <input class="form-control form-control-sm" step="any" type="Number" name="disc_rate" id="disc_rate"
            placeholder="Disc %">
    </div>
    @if (!in_array('Services', $profession->industryCategories->pluck('name')->toArray()))
        <div class="form-group mx-1" style="width: 120px">
            <label>Freight Charge: </label>
            <input class="form-control form-control-sm" step="any" type="Number" name="freight_charge"
                id="freight_charge">
        </div>
    @else
        <div class="form-group mx-1" style="width: 120px">
            <label>Freight Charge: </label>
            <input class="form-control form-control-sm" disabled placeholder="0.00" step="any" type="Number"
                id="freight_charge">
        </div>
    @endif
    <div class="form-group mx-1" style="width: 130px">
        <label>Income Account: </label>
        <input type="hidden" name="chart_id" id="chart_id">
        <input class="form-control form-control-sm" type="text" readonly id="ac_code_name">
    </div>

    <div class="form-group mx-1" style="width:70px">
        <label>Tax: </label>
        <input type="text" name="is_tax" step="any" id="is_tax" readonly value
            class="form-control form-control-sm">
    </div>
    <div style="margin-top: 32px;margin-left:7px">
        <button class="btn btn-success btn-sm add-item" type="button">Add</button>
    </div>
</div>
<hr>

<table class="table table-striped table-bordered table-hover table-sm item-table">
    <thead class="text-center" style="font-size: 15px;">
        <tr>
            <th width="4%">SN</th>
            <th width="%">Description</th>
            <th width="8%">Price </th>
            <th width="7%">Disc %</th>
            <th width="11%">Freight Chrg</th>
            <th width="10%">Account</th>
            <th width="9%">Tax Rate</th>
            <th width="12%">Amount AUD</th>
            <th width="3%"></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" class="text-right">Total:</th>
            <th class="text-center sub-total">$ 0.00 </th>
            <th>
                <input type="hidden" name="total_amount" id="total_amount">
                <input type="hidden" name="gst_amt_subtotal" id="gst_amt_subtotal">
            </th>
        </tr>
    </tfoot>
</table>
<br>
