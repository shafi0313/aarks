@extends('frontend.layout.master')
@section('title','Select Activity')
@section('content')
<?php $p="bp"; $mp="purchase";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9" style="border:1px solid #CCCCCC; min-height:500px; padding: 5px 30px">
                <div class="row" style="padding:10px; font-size:20px; color:green;">
                    Name: {{$customer->name}} <span class="txtmsg"></span>
                </div>
                <form action="{{route('spayment.store')}}" method="post" autocomplete="off" id="payStore">
                    @csrf
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                    <input type="hidden" name="profession_id" value="{{$profession->id}}">
                    <input type="hidden" name="customer_id" value="{{$customer->id}}">
                    <input type="hidden" name="new_inv" value="{{$creditors->max('inv_no')+1}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-inline">
                                <label for="inputEmail3" class="col-sm-3 control-label"
                                    style="padding-top:5px;">Date:</label>
                                <div class="col-sm-9">
                                    <input required class="form-control form-control-sm datepicker" type="text"
                                        name="pay_date" data-date-format="dd/mm/yyyy">
                                    <strong class="datelock"></strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-inline" align="right">
                                <label for="inputEmail3" class="col-sm-4 control-label"
                                    style="text-align:right; padding-top:5px;">Received: </label>
                                <div class="col-sm-8">
                                    <input required type="text" class="form-control form-control-sm" id="received_amount"
                                        name="received_amount">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td align="center">SL</td>
                                    <td align="center">Invoice</td>
                                    <td align="center">Date</td>
                                    <td align="center">Amount</td>
                                    <td align="center">Disc</td>
                                    <td align="center">Outstanding</td>
                                    <td align="center">Apply</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="serial">{{$invsl = 1}}</td>
                                    <td align="center">Opening Balance</td>
                                    <td align="center">{{$customer->opening_blnc_date?$customer->opening_blnc_date->format('d/m/Y'):'check card file'}}</td>
                                    <td align="center">
                                        {{number_format($due_open = $customer->opening_blnc - $openPayment,2)}}
                                        <input type="hidden" name="due_amount[]" value="{{$due_open}}">
                                    </td>
                                    <td align="center">
                                        <input type="text" class="form-control"
                                            name="disc_amount[]" {{$customer->opening_blnc?'':'readonly'}} placeholder="0.00">
                                        <input type="hidden" name="inv_no[]" value="">
                                    </td>
                                    <td align="center">
                                        <input type="text" class="form-control" readonly=""
                                            name="outstanding[]"  placeholder="0.00">
                                    </td>
                                    <td align="center">
                                        <input type="text" {{$customer->opening_blnc?'':'readonly'}} class="form-control apply pay_amt" name="pay_amount[]"
                                            id="bl_pay_amount" placeholder="0.00">
                                        <input type="hidden" name="openbl" id="openbl">
                                    </td>
                                </tr>
                                @foreach ($creditors->groupBy('inv_no') as $creditor)
                                @php
                                    $dueAmt = $creditor->sum('amount') - $creditor->first()->payments->sum('payment_amount');
                                @endphp
                                @if($dueAmt > 0)
                                <tr>
                                    <td class="serial">{{$invsl+=1}}</td>
                                    <td align="center">
                                        <a href="#">
                                            {{invoice($creditor->first()->inv_no, 8, 'BILL')}}
                                            <input type="hidden" name="inv_no[]" value="{{$creditor->first()->inv_no}}">
                                        </a>
                                    </td>
                                    <td align="center">
                                        {{$creditor->first()->tran_date->format('d/m/Y')}}
                                    </td>
                                    <td align="center">
                                        {{number_format($dueAmt,2)}}
                                        <input type="hidden" name="due_amount[]" value="{{$dueAmt}}">
                                    </td>
                                    <td align="center">
                                        <input type="text" class="form-control"
                                            name="disc_amount[]"  placeholder="0.00">
                                    </td>
                                    <td align="center">
                                        <input type="text" class="form-control" readonly=""
                                            name="outstanding[]"  placeholder="0.00">
                                    </td>
                                    <td align="center">
                                        <input type="text" class="form-control pay_amt apply" name="pay_amount[]" placeholder="0.00">
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" align="right">Total</td>
                                    <td class="sub_total">0.00</td>
                                    <input type="hidden" name="total_pay_amount" id="total_pay_amount">
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <div class="row">
                        <div class="col-md-8" align="right">
                            <label style="font-size:20px;">Received Account</label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <select required class="form-control" name="bank_account" id="bank_account">
                                    <option value="" selected>Select Bank Account</option>
                                    @foreach ($liquid_codes as $liquid_code)
                                    <option value="{{$liquid_code->code}} "> {{$liquid_code->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div style="padding-top:10px;" class="row">

                        <div class="col-md-1" style="padding-top:5px;">
                            <div class="row"><label>Memo:</label></div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <input type="text" class="form-control" id="meno_details" name="meno_details">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <span class="btn btn-sm btn-warning">*</span>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary save-btn pull-right">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->
<script>
    $("#pay_amount").on('keyup',function(e){
        $("#openbl").val($(this).val())
    });
    $("#inv_pay_amount").on('keyup',function(e){
        $("#invbl").val($(this).val())
    });
    $(document).on('keyup', '.pay_amt',function(e){
        let received_amount = $("#received_amount").val();
        let apply = 0;
        $('.apply').each(function(key,val){
            let value = $(val).val()==''? 0:$(val).val();
            apply += parseFloat(value);
        });
        $(".sub_total").html(apply.toFixed(2));
        $("#total_amount_pay_amount").val(apply);
        if(received_amount != apply){
            $(".sub_total").addClass('text-danger');
        }else {
            $(".sub_total").addClass('text-success').removeClass('text-danger');
        }
    });
    $(".save-btn").on('click', function(e){
        let received_amount = $("#received_amount").val();
        let apply = 0;
        $(".apply").each(function(key,val){
            let value = $(val).val()==''? 0:$(val).val();
            apply += parseFloat(value);
        });
        if(received_amount != apply){
            toast('warning', 'received amount and apply amount not match!');
            $("#received_amount").focus();
            return false;
        }
    });
</script>
@stop
