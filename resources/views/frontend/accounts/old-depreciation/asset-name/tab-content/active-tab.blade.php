<form id="asset_entryvalue" action="{{route('client.dep_name.active_update')}}" method="post">
    @csrf @method('put')
    <div class="row">
        <div class="col-md-6">
            <input id="clientid" name="clientid" type="hidden" value="{{$depreciation->client_id}}" />
            <span class="txtmsg"></span>
            <input type="hidden" name="dep_grupid" id="dep_grupid" value="{{$depreciation->id}}" />
            <div class="form-group row" style="text-align: right;">
                <label for="inputEmail3" class="col-sm-3 control-label">Asset
                    Name</label>
                <div class="col-sm-9">
                    <select class="form-control" id="dep_subgrup" name="dep_subgrup">
                        <option value="">--Plase Select Asset Name--</option>
                    </select>
                </div>
            </div>

            <div style="padding-top: 30px;"></div>

            <div style="border: 1px solid #000000; padding: 10px;">
                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Financial
                        Year</label>
                    <div class="col-sm-7">
                        <select class="form-control" id="financial_year" name="financial_year">
                            <option value="">Please Select Financial Year</option>
                            @foreach ($periods as $period)
                            <option value="{{$period->year}}">{{$period->year}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Purchase
                        Date</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control datepicker" name="purchasedate"
                            data-date-format="dd/mm/yyyy" id="purchasedate" placeholder="dd/mm/yyyy" autocomplete="off">
                        <strong class="datelock"></strong>
                        <small id="taxMsg" style="display: none;color: red"></small>
                    </div>
                </div>

                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Purchase
                        Rate</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="purchase_rate" id="purchase_rate"
                            placeholder="0.00" autocomplete="off">
                    </div>
                </div>

                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Purchase
                        Value</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="purchaseprince" id="purchaseprince"
                            placeholder="0.00" autocomplete="off">
                    </div>
                </div>

                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Adjusted
                        deprecation</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="balancingin" id="balancingin" placeholder="0.00"
                            autocomplete="off">
                    </div>
                </div>
            </div>

            <div style="padding-top: 10px;"></div>
            <div style="border: 1px solid #000000; padding: 10px; min-height: 150px;">
                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Original
                        Value</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="originalvalue" id="originalvalue"
                            autocomplete="off" readonly>
                    </div>
                </div>

                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Disposal
                        Date</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy"
                            autocomplete="off" placeholder="dd/mm/yyyy" autocomplete="off" name="disposal_date"
                            id="disposal_date" placeholder="0.00">
                    </div>
                </div>

                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Disposal
                        Price</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="disposal_price" id="disposal_price"
                            autocomplete="off" placeholder="0.00">
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="padding-top:10px;">
                <label class="checkbox-inline">
                    <input type="checkbox" value=""> Luxury car limit applies
                </label>
            </div>
        </div>
        <!-- ednd top col-md-6 -->

        <div class="col-md-6">
            <label>Category : <span style="color:green;">{{$depreciation->asset_group}}</span></label>
            <div style="padding-top: 20px;"></div>
            <div style="padding-top: 30px;"></div>

            <div style="border: 1px solid #000000; padding: 10px; min-height: 150px;">

                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Depreciation
                        Method</label>
                    <div class="col-sm-7">
                        <select class="form-control" name="depreciation_method" id="depreciation_method">
                            <option value="D">Diminishing</option>
                            <option value="P">Prime cost</option>
                            <option value="W">Write Off</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Effective
                        life</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="effectivelife" autocomplete="off"
                            id="effectivelife" placeholder="0.00">
                    </div>
                </div>


                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Depreciation
                        Rate %</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="depreciation_rate" autocomplete="off"
                            id="depreciation_rate" placeholder="0.00">
                    </div>
                </div>


                <div class="form-group row" style="text-align: right;">
                    <label for="inputEmail3" class="col-sm-5 control-label">Private Use
                        %</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="private_use" autocomplete="off" id="private_use"
                            placeholder="0.00">
                    </div>
                </div>
            </div>
            <div style="border: 1px solid #000000; padding: 10px; min-height: 150px;margin-top:25px">
            <div class="row">
                <div class="col-lg-6">
                    <h3>Actual Cost</h3>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-5 control-label">OWDV</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="actual_owdv" autocomplete="off"
                                id="actual_owdv">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-5 control-label">Depreciation</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="actual_depreciation" autocomplete="off"
                                id="actual_depreciation">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-5 control-label">CWDV</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="actual_cwdv" autocomplete="off"
                                id="actual_cwdv" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3>Undeducted cost</h3>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" autocomplete="off" name="undeducted_owdv"
                                id="undeducted_owdv">
                        </div>
                    </div>
                    <div class="form-group row">

                        <div class="col-sm-12">
                            <input type="text" class="form-control" autocomplete="off" name="undeducted_depreciation"
                                id="undeducted_depreciation" placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" autocomplete="off" name="undeducted_cwdv"
                                id="undeducted_cwdv" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <!-- end col-md-6 -->

        <div class="col-md-12">
            <div class="form-group row" style="text-align: right;">
                <label for="inputEmail3" class="col-sm-5 control-label">Deductible</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="deductible" autocomplete="off" id="deductible"
                        placeholder="0.00">
                </div>
            </div>

            <div class="form-group row" style="text-align: right;">
                <label for="inputEmail3" class="col-sm-5 control-label">Assessable</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="assessable" autocomplete="off" id="assessable"
                        placeholder="0.00">
                </div>
            </div>

            <div class="form-group row" style="text-align: right;">
                <label for="inputEmail3" class="col-sm-5 control-label">Balancing
                    Out</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="balancing_out" autocomplete="off" id="balancing_out"
                        placeholder="0.00">
                </div>
            </div>

            <div class="form-group row" style="text-align: right;">
                <label for="inputEmail3" class="col-sm-5 control-label">Net
                    Depreciation</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="net_depreciation" autocomplete="off"
                        id="net_depreciation" placeholder="0.00">
                </div>
            </div>

            <div class="form-group row" style="text-align: right;">
                <label for="inputEmail3" class="col-sm-5 control-label">Net Share of
                    depreciation</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="net_shareof_depreciation" autocomplete="off"
                        id="net_shareof_depreciation" placeholder="0.00">
                </div>
            </div>

            <div style="padding-top:10px;">
                <div class="form-group row loading" style="text-align:center; display:none;">
                    <img width="15%" src="{{asset('frontend/assets/images/loading.svg')}}" />
                </div>
            </div>
            <div class="form-group row pull-right">
                <button type="reset" class="btn btn-danger btn-lg">Close</button> &nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-success btn-lg chekloc checkdate">Save</button>
            </div>
        </div>
    </div>
</form>
