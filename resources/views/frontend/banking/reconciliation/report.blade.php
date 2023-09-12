@extends('frontend.layout.master')
@section('title','Reconciliation')
@section('content')
<?php $p="brec"; $mp="bank";?>
<style>
    .table td,
    .table th {
        padding: 8px;
        line-height: 1;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }
    .form-control:disabled, .form-control[readonly] {
    background-color: transparent;
    opacity: 1;
    border: none;
}
</style>
<section class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h2 class="text-center font-weight-bolder">{{ clientName($client)}}</h2>
                <h4 class="text-center">
                    <u>Recorded Transaction  upto {{$end_date->format('d/m/Y')}} For Reconciliation.</u>
                </h4>
                <p class="text-center text-danger">
                    <i>NB: if you find the transaction in the your  bank statement( dr or Cr Side) which is not recorded on your ledger book please record it first before you reconciled transaction posting.</i>
                </p>
                {{-- show errors --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{route('bank_reconciliation.post')}}" method="post">
                @csrf
                <input type="hidden" name="client_id" value="{{$client->id}}">
                <input type="hidden" name="profession_id" value="{{$profession->id}}">
                <input type="hidden" name="date" value="{{$end_date->format('d/m/Y')}}">
                <input type="hidden" name="chart_id" value="{{$account_code->code}}">
                <table class="table" style="margin: 10px;">
                    <tr>
                        <td colspan="9" class="font-weight-bolder" style="margin: 0;padding: 4px">
                            {{$account_code->name}}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>Particular</td>
                        <td class="center">Transaction Id</td>
                        <td>JFL</td>
                        <td>Dr.amount</td>
                        <td>Cr.amount</td>
                        <td>Balance</td>
                        <td>Reconcile</td>
                    </tr>
<tr>
    <td colspan="7">Physical Bank Account balance</td>
    <td>
        <input type="hidden" id="physical_balance" name="physical_balance" value="{{$physical_balance}}">
        {{$physical}}
    </td>
</tr>
<tr>
    <td colspan="6">Opening Balance</td>
    <td>
        0
        <input type="hidden" id="open_bl" value="0">
    </td>
    <td>
        <input type="checkbox" name="check[]" class="check" id="ch_0" value="0">
    </td>
</tr>
@php
$Sdebit = $Scredit = $balance = 0;
@endphp

@foreach($ledgers as $i => $generalLedger)
    @php
    if($generalLedger->credit != 0 || $generalLedger->debit != 0){
        $balance += $generalLedger->balance;
    }else{
        $balance -= $generalLedger->balance;
    }
    $debit  = 0;
    $credit = 0;
    if($generalLedger->credit < 0){ $debit=$generalLedger->credit;
        $Sdebit += $generalLedger->credit;
    }elseif($generalLedger->debit < 0){ $credit=$generalLedger->debit;
        $Scredit += $generalLedger->debit;
    }else{
        $debit = $generalLedger->debit;
        $credit = $generalLedger->credit;
        $Sdebit += $generalLedger->debit;
        $Scredit += $generalLedger->credit;
    }
    if($generalLedger->balance_type == 2){
        $blncType = $balance<0 ? 'Dr' : 'Cr' ;
    }elseif($generalLedger->balance_type == 1){
        $blncType = $balance<0 ? 'Cr' : 'Dr' ;
    }
    @endphp
        <tr>
            <td>{{$generalLedger->date->format(aarks('frontend_date_format'))}}</td>
            <td>{{$generalLedger->narration}}</td>
            <td class="center"><a
                    href="{{route('ledger.transaction',[ $generalLedger->transaction_id,$generalLedger->source])}}"
                    style="color: green;text-decoration: underline">{{$generalLedger->transaction_id}}</a>
            </td>
            <td>{{$generalLedger->source}}</td>
            <td>{{abs($debit)}}</td>
            <td>{{abs($credit)}}</td>
            <td>
                {{-- {{number_format(abs($balance), 2) . ' ' . ($blncType)}} --}}
                <input type="text" disabled class="form-control balance" value="0.00" id="bl_{{$i+1}}" data-sl="{{$i+1}}">
                <input hidden type="checkbox" name="balance[]" value="{{$debit > 0 ? $debit: - $credit}}" id="bl_chk_{{$i+1}}" {{in_array($generalLedger->id, $recons_saved)?'checked':''}}>
            </td>
            <td>
                {{-- <input type="checkbox" name="check[]" value="{{$generalLedger->id}}" data-value="{{$debit > 0 ? $debit: - $credit}}" class="check" id="ch_{{$i+1}}" data-sl="{{$i+1}}"> --}}
                <input type="checkbox" name="check[]" value="{{$debit > 0 ? $debit: - $credit}}" class="check" id="ch_{{$i+1}}" data-sl="{{$i+1}}" {{in_array($generalLedger->id, $recons_saved)?'checked':''}}>
                <input hidden type="checkbox" name="ledger_id[]" id="gl_{{$i+1}}" value="{{$generalLedger->id}}" {{in_array($generalLedger->id, $recons_saved)?'checked':''}}>
            </td>
        </tr>
@endforeach
<tr>
    <td colspan="4">Ledger total(including ledger opening balance)</td>
    <td style="color: red">{{abs($Sdebit)}}
    </td>
    <td colspan="4" style="color: red">{{abs($Scredit)}}
    </td>
</tr>

<tr>
    <td colspan="4">Reconciled total</td>
    <td>
        <input style="color: red" type="text" readonly class="form-control" id="reconDebit" name="reconciled_dr" value="0.00">
    </td>
    <td colspan="4">
        <input style="color: red" type="text" readonly class="form-control" id="reconCredit" name="reconciled_cr" value="0.00">
    </td>
</tr>
<tr>
    <td colspan="4">Unreconciled total</td>
    <td>
        <input style="color: red" type="text" readonly class="form-control" id="unreconDebit" name="unreconciled_dr" value="0.00">
    </td>
    <td colspan="4">
        <input style="color: red" type="text" readonly class="form-control" id="unreconCredit" name="unreconciled_cr" value="0.00">
    </td>
</tr>
<tr>
    <td colspan="4">Difference between bank statement balance and General Ledger Balance</td>
    <td colspan="5">
        <input style="color: red" type="text" readonly class="form-control diff_balance" id="balance_diff" name="balance_diff" value="0.00">
    </td>
</tr>
<tr>
    <td colspan="9" class="text-center">

        @if (count($recons_saved) > 0)
        <input type="submit" name="type" value="Print/PDF" class="btn text-light" style="background: blue !important; border-color: blue !important;">
        <input type="submit" name="type" value="Export to excel" class="btn text-light" style="background: blue !important; border-color: blue !important">
        <input type="submit" name="type" value="Post" class="btn btn-success">
        @endif
        <input type="submit" name="type" value="Save" class="btn btn-info">
    </td>
</tr>
                </table>
                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
@endsection
@section('script')
<script>
    $(function(){
        @if (count($recons_saved) > 0)
        setBl();
        reconCal();
        balanceDiff();
        @endif
        $(".check").on("click", function(){
            let sl     = parseInt($(this).data("sl"));
            let amount = parseFloat($("#bl_"+(sl-1)).val()??0);
            // let value  = parseFloat($(this).data("value")??0);
            let value  = parseFloat($(this).val()??0);
            if($(this).is(":checked")){
                amount = value + amount;
                $("#gl_"+sl).prop("checked", true);
                $("#bl_chk_"+sl).prop("checked", true);
            } else {
                $("#gl_"+sl).prop("checked", false);
                $("#bl_chk_"+sl).prop("checked", false);
            }
            for (let i = sl; i <= $(".balance").length; i++) {
                if($("#ch_"+i).is(":checked") && sl != i){
                    amount = amount + parseFloat($("#ch_"+i).val());
                }
                $("#bl_"+i).val(amount.toFixed(2));
                $("#bl_chk_"+i).val(amount.toFixed(2));
            }
            reconCal();
            balanceDiff();
        });
    });
    function setBl(){
        $.each($(".check"), function(i, v){
            if($(v).is(":checked")){
                let sl     = parseInt($(v).data("sl"));
                let amount = parseFloat($("#bl_"+(sl-1)).val()??0);
                let value  = parseFloat($(v).val()??0);
                amount = value + amount;
                for (let i = sl; i <= $(".balance").length; i++) {
                    if($("#ch_"+i).is(":checked") && sl != i){
                        amount = amount + parseFloat($("#ch_"+i).val());
                    }
                    $("#bl_"+i).val(amount.toFixed(2));
                    $("#bl_chk_"+i).val(amount.toFixed(2));
                }
            }
        });
    }
    function reconCal(){
        let reconDebit = unreconDebit = reconCredit = unreconCredit = 0;
        $(".check").each(function(i,v){
            v = $(v);
            let amount = parseFloat(v.val()??0);
            if(v.is(":checked")){
                if(amount > 0) {
                    reconDebit += amount;
                } else {
                    reconCredit += amount;
                }
            } else {
                if(amount > 0) {
                    unreconDebit += amount;
                } else {
                    unreconCredit += amount;
                }
            }
        });
        $("#reconDebit").val(reconDebit.toFixed(2));
        $("#reconCredit").val(Math.abs(reconCredit.toFixed(2)));
        $("#unreconDebit").val(unreconDebit.toFixed(2));
        $("#unreconCredit").val(Math.abs(unreconCredit.toFixed(2)));
    }
    function balanceDiff(){
        let physical    = parseFloat($("#physical_balance").val()??0);
        let lastBalance = parseFloat($("#bl_"+$(".balance").length).val()??0);
        let diff        = 0;
        if(physical > 0 && lastBalance > 0)
        {
            diff = physical - lastBalance;
        } else {
            diff = physical + lastBalance;
        }
        $("#balance_diff").val(diff.toFixed(2));
    }
</script>
@endsection
