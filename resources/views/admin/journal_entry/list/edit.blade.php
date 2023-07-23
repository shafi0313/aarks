@extends('admin.layout.master')
@section('title','Journal Entry Input')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Add/Edit Data</li>
                    <li>Journal Entry</li>
                    <li><a href="{{ route('journal_entry_client') }}">Client List</a></li>
                    <li>
                      <?php
                        if($client->first_name!=""){
                          echo '<a style="color:red;">Name: '.$client->first_name.'</a>';
                        }else{
                          echo '<a style="color:red; font-weight:bold;">Company: '.$client->company.'</a>';
                        }
                      ?>

                    </li>
                    <li>{{$profession->name}}</li>
                    <li class="active">Journal Input</li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">

                

                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                          <div style="margin: 5px">
                            <div class="row" style="padding-bottom: 10px;">
                                <div class="col-12">
                                    @if ($errors->any())
                                    @foreach ($errors->all() as $err)
                                    <p class="text-danger">{{$err}}</p>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="col-md-4 row">
                                    <div class="form-group form-group-sm">
                                        <label for="journal_date" class="col-sm-5 control-label">Journal Date</label>
                                        <div class="col-sm-7">
                                            <input disabled type="text" class="form-control datepicker " value="{{$journal->date->format('d/m/Y')}}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 row">
                                    <div class="form-group form-group-sm">
                                        <label for="journal_number" class="col-sm-6 control-label">Journal Number</label>
                                        <div class="col-sm-6">
                                        <input type="text" disabled class="form-control" value="{{invoice($journal->journal_number)}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 row">
                                    <div class="form-group form-group-sm">
                                        <label for="journal_reference" class="col-sm-5 control-label">Journal Reference</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" disabled value="{{$journal->narration}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                  <div class="col-md-12">
                                      <style>
                                          table,td,tr,th{
                                              border: 1px solid #dfe3eb;
                                              padding: 0;
                                              margin: 0;
                                          }
                                          .form-control{
                                              padding: 0;
                                              margin: 0;
                                          }
                                          .tbl { width: 100%; }
                                        .tbl tr td,.tbl tr th { padding: 4px 8px;}
                                      </style>

                                    <table class="tbl">
                                        <tr style="">
                                            <th class="center" style="width: 20%;">A/c Code</th>
                                            <th class="center" style="width: 25%;">Narration</th>
                                            <th class="center" style="width: 7%;">Tx Code</th>
                                            <th class="center">Debit</th>
                                            <th class="center">Credit</th>
                                            <th class="center">(Excl Tax)</th>
                                            <th class="center">Tax</th>
                                            <th class="center">Action</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="form-control is-invalid" id="account_code" required>
                                                    <option value="">--</option>
                                                    @foreach($client_account_codes as $client_account_code)
                                                    <option value="{{$client_account_code->id}}" data-gst="{{$client_account_code->gst_code}}"
                                                        data-type="{{$client_account_code->type}}" data-code="{{$client_account_code->code}}">

                                                        {{$client_account_code->name}} => {{$client_account_code->code}}

                                                    </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="type" id="type">
                                            </td>
                                            <td>
                                                <input class="form-control" id="narration" type="text" required>
                                            </td>
                                            <td>
                                                <select name="" id="gst_code" style="width: 100%;">
                                                    @foreach(aarks('gst_code') as $gst_code)
                                                    <option value="{{ $gst_code }}">{{$gst_code}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input id="debit" class="form-control" type="number">
                                            </td>
                                            <td>
                                                <input id="credit" class="form-control" type="number">
                                            </td>
                                            <td>
                                                <input id="exTax" class="form-control" type="text" disabled>
                                            </td>
                                            <td>
                                                <input id="tax" class="form-control" type="text" disabled>
                                            </td>
                                            <td class="center">
                                                <button id="add" class="btn btn-sm btn-secondary">Add</button>
                                            </td>
                                        </tr>
                                        <form action="{{route('journal_list_update',$journal->id)}}" method="POST">
                                        @csrf @method('put')
                                        <input type="hidden" name="narration" value="{{$journal->narration}}">
                                        <input type="hidden" name="journal_number" value="{{$journal->journal_number}}">
                                        <input type="hidden" name="date" value="{{$journal->date->format('d/m/Y')}}">
                                        <input type="hidden" name="client_id" value="{{$journal->client_id}}">
                                        <input type="hidden" name="profession_id" value="{{$journal->profession_id}}">
                                        <input type="hidden" name="tran_id" value="{{$journal->tran_id}}">
                                        @php
                                        $i = 1;
                                        $totalD = $totalC = $totalB = $totalG = 0;
                                        @endphp
                                        @foreach ($journals as $i=>$jnl)
                                        @php

                                        $Idebit   = $jnl->debit;
                                        $Icredit  = $jnl->credit;

                                        if ($jnl->credit < 0) {
                                            $Idebit = $Icredit;
                                            $Icredit = 0;
                                        }
                                        if ($jnl->debit < 0) {
                                            $Icredit = $Idebit;
                                            $Idebit = 0;
                                        }

                                        $totalD += abs($Idebit);
                                        $totalC += abs($Icredit);
                                        $totalG += abs($jnl->gst);
                                        $totalB += abs($jnl->net_amount);
                                        $code = $jnl->client_account_code;
                                        @endphp
                                        <tr>
                                            <td>

                                                <input type="text" disabled class="form-control" value="{{$code->name}}" title="{{$code->name}} => {{$code->code}}">

                                                <input type="hidden" name="account_code[]" value="{{$code->code}}" id="account_code{{$i}}">
                                                <input type="hidden" name="code_id[]" value="{{$code->id}}" id="code_id{{$i}}">
                                                <input type="hidden" name="type[]" value="{{$code->type}}" id="type{{$i}}">
                                                <input type="hidden" name="gst_code[]" value="{{$jnl->gst_code}}" class="gst_code">
                                                <input type="hidden" name="id[]" value="{{$jnl->id}}">
                                            </td>
                                            <td>
                                                <input value="{{$jnl->narration}}" class="form-control" id="narration{{$i}}" type="text" required name="narration[]">
                                            </td>
                                            <td>
                                                <input type="text" disabled  value="{{$jnl->gst_code}}" class="form-control">
                                            </td>
                                            <td>
                                                <input value="{{number_format(abs($Idebit),2,'.','')}}" name="debit[]" class="form-control idebit" data-tt="debit" type="number" min="0" step=".01">
                                            </td>
                                            <td>
                                                <input value="{{number_format(abs($Icredit),2,'.','')}}" name="credit[]" class="form-control icredit" data-tt="credit" type="number" min="0" step=".01">
                                            </td>
                                            <td>
                                                <input value="{{number_format(abs($jnl->net_amount),2,'.','')}}" name="net_amount[]" class="form-control iexTax" type="text" readonly>
                                            </td>
                                            <td>
                                                <input value="{{number_format(abs($jnl->gst),2,'.','')}}"  name="gst[]" class="form-control itax" type="text" readonly>
                                            </td>
                                            <td class="center">
                                                @if ($jnl->is_edited == 1)
                                                <a title="journal_entry delete" data-id="{{$jnl->id}}" onclick="return confirm('are you sure?')" class="red" id="delete" href="{{route('journal_entry.delete', $jnl->id)}}"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-right">
                                                Total:
                                            </td>
                                            <td>
                                                <input value="{{number_format(abs($totalD),2)}}" class="form-control totalD" type="text" disabled>
                                            </td>
                                            <td>
                                                <input value="{{number_format(abs($totalC),2)}}" class="form-control totalC" type="text" disabled>
                                            </td>
                                            <td>
                                                <input value="{{number_format(abs($totalB),2)}}" class="form-control totalB" type="text" disabled>
                                            </td>
                                            <td>
                                                <input value="{{number_format(abs($totalG),2)}}" class="form-control totalG" type="text" disabled>
                                            </td>
                                            <td class="center">
                                            <input type="submit" value="Journal Update" class="btn btn-danger form-control d-hide" style="float: right; " id="journal_post" {{$totalD == $totalC?'':'disabled'}} >
                                            </td>
                                        </tr>
                                        </form>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
<script>
    // tab key disable
    $(document).keydown(function(objEvent) {
        if (objEvent.keyCode == 9) {  //tab pressed
            objEvent.preventDefault(); // stops its action
        }
    })
    function openField(){
        let debit = $('#debit').val();
        let credit = $('#credit').val();
        if (debit == '' && credit == ''){
            $('#debit').prop('disabled',false);
            $('#credit').prop('disabled',false);
        }
        if (!debit == ''){
            $('#credit').prop('disabled',true);
        }
        if(!credit == ''){
            $('#debit').prop('disabled',true);
        }

    }
    $('#debit,#credit').keyup(function () {
        openField();
        let gst_code = $('#gst_code').val();
            let tax = $(this).val() && (gst_code == 'GST' || gst_code == 'INP' || gst_code == 'CAP') ? parseFloat($(this).val())/11 : 0;
            let exTax = $(this).val() ? parseFloat($(this).val())-tax : 0;
            $('#tax').val(tax.toFixed(2));
            $('#exTax').val(exTax.toFixed(2));
    });

    $('table tbody tr').find('.idebit,.icredit').on('change',function() {
        let parent = $(this).parents('tr');
        let gst_code = parent.find('.gst_code').val();
        let tax = $(this).val() && (gst_code == 'GST' || gst_code == 'INP' || gst_code == 'CAP') ? parseFloat($(this).val())/11 : 0;
        let exTax = $(this).val() ? parseFloat($(this).val())-tax : 0;
        parent.find('.itax').val(tax.toFixed(2));
        parent.find('.iexTax').val(exTax.toFixed(2));

        if($(this).data('tt') == 'debit') {
            if($(this).val() > 0) {
                parent.find('.icredit').val('0.00').prop('readonly',true);
            } else {
                parent.find('.icredit').val('0.00').prop('readonly',false);
            }
        } else {
            if($(this).val() > 0) {
                parent.find('.idebit').val('0.00').prop('readonly',true);
            } else {
                parent.find('.idebit').val('0.00').prop('readonly',false);
            }
        }
        let totalD = totalC = totalB = totalG = 0;
        $('.idebit').each(function(){
            totalD += parseFloat(this.value);
        });
        $('.icredit').each(function(){
            totalC += parseFloat(this.value);
        });
        $('.itax').each(function(){
            totalG += parseFloat(this.value);
        });
        $('.iexTax').each(function(){
            totalB += parseFloat(this.value);
        });
        $(".totalB").val(totalB.toFixed(2));
        $(".totalC").val(totalC.toFixed(2));
        $(".totalD").val(totalD.toFixed(2));
        $(".totalG").val(totalG.toFixed(2));
        if(totalD.toFixed(2) == totalC.toFixed(2)) {
            $("#journal_post").prop('disabled',false);
        } else {
            $("#journal_post").prop('disabled',true);
        }
    });


    $('#account_code').change(function () {
        let type     = $('#account_code option:selected').data('type');
        let gst_code = $('#account_code option:selected').data('gst');
        let ac_code  = $('#account_code option:selected').data('code');
        let code     = ac_code.toString().substring(0,1);
        if(code == 1){
            var j = ["GST","FREE"];
            var options = '';

            for (var i = 0; i < j.length; i++) {
                options +='<option value="' + j[i]+ '">' + j[i] + '</option>' ;
            }
            $("#gst_code").html(options);
        }
        if(code == 2){
            var j = ["INP","FOA","W1","W2","SUP"];
            var options = '';

            for (var i = 0; i < j.length; i++) {
                options +='<option value="' + j[i]+ '">' + j[i] + '</option>' ;
            }
            $("#gst_code").html(options);
        }
        if(code == 5){
            var j = ["CAP","NILL"];
            var options = '';

            for (var i = 0; i < j.length; i++) {
                options +='<option value="' + j[i]+ '">' + j[i] + '</option>' ;
            }
            $("#gst_code").html(options);
        }
        if(code == 9){
            var j = ["NILL"];
            var options = '';

            for (var i = 0; i < j.length; i++) {
                options +='<option value="' + j[i]+ '">' + j[i] + '</option>' ;
            }
            $("#gst_code").html(options);
        }
        $("#type").val(type);
        // $("#gst_code option").each(function()
        // {
        //     if($(this).val() == gst_code){
        //         $(this).prop('selected', true);
        //     }
        // });
        // if(type == 1){
        //     $('#debit').prop('disabled',false);
        //     $('#credit').prop('disabled',true);
        // }else{
        //     $('#debit').prop('disabled',true);
        //     $('#credit').prop('disabled',false);
        // }
    });

    $('#add').click(function () {
        var data = getData();
        var request = $.ajax({
            url: "{{route('journal_entry.store')}}",
            method: "GET",
            data: data,
        });
        request.done(function( response ) {
            clear();
            toast('success', 'Journal Code added success!')
            $('#debit').prop('disabled',false);
            $('#credit').prop('disabled',false);
            location.reload();
        });

        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus + jqXHR.responseText );
        });

    });

    function getData() {
        return {
            'account_code'  : $('#account_code').val(),
            'narration'     : $('#narration').val(),
            'date'          : $('#date').val(),
            'debit'         : $('#debit').val(),
            'credit'        : $('#credit').val(),
            'client_id'     : '{{ $client->id }}',
            'profession_id' : '{{ $profession->id }}',
            'gst_code'      : $('#gst_code').val(),
            'net_amount'    : $('#exTax').val(),
            'gst'           : $('#tax').val(),
            'type'          : $('#type').val(),
            'is_edited'     : 1,
            'tran_id'       : '{{ $journal->tran_id }}',
            'date'          : '{{ $journal->date }}',
            'journal_number': '{{ $journal->journal_number }}',
        };
    }
    function clear() {
        $('#account_code').val('');
        $('#narration').val('');
        $('#date').val('');
        $('#debit').val('');
        $('#credit').val('');
        $('#gst_code').val('');
        $('#exTax').val('');
        $('#tax').val('');
    }
</script>
@stop

