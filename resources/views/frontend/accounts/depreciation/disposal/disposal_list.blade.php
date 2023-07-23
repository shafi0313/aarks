@if ($get == 'list')
<tr>
    <td>{{$disposal->asset_name}}</td>
    <td>{{$disposal->purchase_date->format('d/m/Y')}}</td>
    <td>{{number_format($disposal->purchase_value, 2)}}</td>
    <td>{{number_format($disposal->dep_rate, 2)}}</td>
    <td>{{$disposal->disposal_date?$disposal->disposal_date->format('d/m/Y'):''}}</td>
    <td>{{number_format($disposal->disposal_value, 2)}}</td>
    <td>
        @if ($disposal->disposal_value)

        <a href="javascript:void(0)"
            class="btn btn-success">Asset Disposed</a>
        @else
        <a href="javascript:void(0)" onclick="return getDisposalModal({{$disposal->id}})"
            class="btn btn-info disposeModalBtn">Asset Dispose</a>
        @endif
    </td>
</tr>
@endif
@if ($get == 'modal')
<div class="modal fade" tabindex="-1" role="dialog" id="disposemodal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="assetupdate" method="POST" autocomplete="off" action="{{route('client.dep_disposal.postAsset',$disposal->id)}}">
            @csrf
            <input type="hidden" name="client_id" id="client_id" value="{{$disposal->client_id}}">
            <input type="hidden" name="profession_id" id="profession_id" value="{{$disposal->profession_id}}">
            <input type="hidden" name="depreciation_id" id="depreciation_id" value="{{$disposal->depreciation_id}}">
            <input type="hidden" name="asset_name" id="asset_name" value="{{$disposal->id}}">
            <input type="hidden" name="financial_year" id="financial_year" value="{{$disposal->year}}">
            <input type="hidden" name="add_just_amunt" id="add_just_amunt" value="0.00">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-dark">{{$disposal->asset_name}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Purchase Date</label>
                        <input type="text" class="form-control" name="purchase_date"
                            value="{{$disposal->purchase_date->format('d/m/Y')}}" id="purchase_date" readonly>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Disposal Date</label>
                        <input type="text" data-date-format="dd/mm/yyyy" class="form-control disposal_date datepicker"
                            value="{{optional($disposal->disposal_date)->format('d/m/Y')}}" name="disposal_date"
                            id="disposal_date" placeholder="dd/mm/yyyy" onkeyup="getDisposalAmount(this,{{$disposal->id}}, '{{route("depreciation.disposal.getAmount", $disposal->id)}}')" required>
                        <span class="wrongmgs" style="color:red"></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Disposal Price</label>
                        <input type="text" class="form-control" name="disposal_price" id="disposal_price" required placeholder="0.00">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Accum. Dep. Account</label>
                                <select required class="form-control" id="first_account" name="ac_code[]" readonly>
                                    <option value="{{$disposal->depreciation->ada_code->id}}">{{$disposal->depreciation->ada_code->name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Debit Amount</label>
                                <input type="number" step="any" name="debit[]" id="dep_debit" class="form-control debit"
                                    value="{{$disposal->dep_amt}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Credit Amount</label>
                                <input type="number" step="any" name="credit[]" id="dep_credit" class="form-control credit">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Select asset purchase account</label>
                                <select required class="form-control" id="purchase_account" name="ac_code[]">
                                    <option value="">--Select Account--</option>
                                    @forelse ($nonCurrents as $non)
                                    <option value="{{$non->id}}">{{$non->name}} -- {{$non->code}}</option>
                                    @empty
                                    <option value disabled>--Empty--</option>
                                    @endforelse
                                </select>
                            </div>
                            {{-- <p style="color:red;">(Select asset purchase account)</p> --}}
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="number" step="any" name="debit[]" id="purchase_debit" class="form-control debit" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="number" step="any" name="credit[]" id="purchase_credit" class="form-control credit" value="{{$disposal->purchase_value}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Select bank/cash account for sales of asset</label>
                                <select required class="form-control" id="bank_account" name="ac_code[]">
                                    <option value="">--Select Account--</option>
                                    @forelse ($assets as $asset)
                                    <option value="{{$asset->id}}">{{$asset->name}} -- {{$asset->code}}</option>
                                    @empty
                                    <option value disabled>--Empty--</option>
                                    @endforelse
                                </select>
                            </div>
                            {{-- <p style="color:red;">(Select bank/cash account for sales of asset)</p> --}}
                        </div>
                        <br>
                        <div class="col-md-3" style="padding-top:8px">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="number" step="any" name="debit[]" onkeyup="loss_cal()" id="bank_debit" class="form-control debit bank_input">
                            </div>
                        </div>
                        <div class="col-md-3" style="padding-top:8px">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="number" step="any" name="credit[]" onkeyup="loss_cal()" id="bank_credit" class="form-control credit bank_input">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">GST on sale</label>
                                <select required class="form-control" id="gst_account" name="ac_code[]">
                                <option selected value="{{$payable->id}}">{{$payable->name}} -- {{$payable->code}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="number" step="any" name="debit[]" onkeyup="loss_cal()" id="gst_debit" class="form-control debit">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="number" step="any" name="credit[]" onkeyup="loss_cal()" id="gst_credit" class="form-control credit">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Sales of assets profit and loss a/c</label>
                                <select required class="form-control" id="four_account" name="ac_code[]">
                                    <option value="1300" selected>Profi /Loss on Sales of Assets -- 191300</option>
                                </select>
                            </div>
                            {{-- <p style="color:red;">(Sales of assets profit and loss a/c)</p> --}}
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="number" step="any" name="debit[]"  id="loss_debit" class="form-control debit">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="number" step="any" name="credit[]"  id="loss_credit" class="form-control credit">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                Total :
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <strong class="totaldebit"></strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <strong class="totalcredit"></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" value="Save" class="btn btn-primary saveBtn" disabled>
                </div>

            </div>
        </form>
    </div>
</div>
<script>
$(function() {
    $(document).on('keyup', 'input[type=number]',function(e){
        sum_cal();
    });
    $(document).on('keyup', '#disposal_price',function(e){
        const price = $(this).val()??0;
        const gst = (price / 11).toFixed(2);
        $("#gst_debit").val(gst);
        sum_cal();
        loss_cal()
    });
});
    function loss_cal(){
        $("#loss_debit").val(0);
        $("#loss_credit").val(0);
        let debit = $(".debit");
        let credit = $(".credit");
        let sum_debit = sum_credit = 0;
        $.each(debit, function(i,cr){
            let value = $(cr).val()==''? 0:$(cr).val();
            sum_debit += parseFloat(value);
        });
        $.each(credit, function(i,dr){
            let value = $(dr).val()==''? 0:$(dr).val();
            sum_credit += parseFloat(value);
        });
        if(sum_credit != sum_debit) {
            let net_amt = sum_debit - sum_credit;
            if(net_amt > 0) {
                $("#loss_debit").val('');
                $("#loss_credit").val(Math.abs(net_amt).toFixed(2));
            } else{
                $("#loss_debit").val(Math.abs(net_amt).toFixed(2));
                $("#loss_credit").val('');
            }
            sum_cal();
        }
    };
    function sum_cal(){
        let debit = $(".debit");
        let credit = $(".credit");
        let sum_debit = sum_credit = 0;
        $.each(debit, function(i,dr){
            let value = $(dr).val()==''? 0:$(dr).val();
            sum_debit += parseFloat(value);
        });
        $.each(credit, function(i,cr){
            let value = $(cr).val()==''? 0:$(cr).val();
            sum_credit += parseFloat(value);
        });
        $(".totaldebit").html(sum_debit.toFixed(2));
        $(".totalcredit").html(sum_credit.toFixed(2));
        if(sum_credit == sum_debit) {
            $('.saveBtn').prop('disabled', false);
        } else{
            $('.saveBtn').prop('disabled', true);
        }
    };
</script>
@endif
