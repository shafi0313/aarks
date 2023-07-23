@extends('frontend.layout.master')
@section('title','Select Activity')
@section('content')
<?php $p="cje"; $mp="acccounts"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
<div class="row" style="padding-bottom: 10px;">
    <div class="col-4">
        <div class="form-group row">
            <label for="journal_date" class="col-5 control-label">Journal Date</label>
            <div class="col-7">
                <input type="text" class="form-control datepicker " @error('journal_date')
                    style="border: 1px solid red;" @enderror id="journal_date" name="journal_date"
                    placeholder="dd/mm/yyyy" data-date-format="dd/mm/yyyy" autocomplete="off">
                <small id="taxMsg" style="display: none;color: red">Message</small>
                @error('journal_date')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group row">
            <label for="journal_number" class="col-5 control-label">Journal Number</label>
            <div class="col-7">
                <input type="text" readonly class="form-control" id="journal_number" name="journal_number"
                    value="{{str_pad($journal_number->max('journal_number')+1, 8, '0', STR_PAD_LEFT)}}">
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group row">
            <label for="journal_reference" class="col-5 control-label">Journal Reference</label>
            <div class="col-7">
                <input type="text" class="form-control" id="journal_reference" @error('journal_reference')
                    style="border: 1px solid red;" @enderror name="journal_reference" placeholder="journal Reference"
                    autocomplete="off">
                @error('journal_reference')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <style>
            table,
            td,
            tr,
            th {
                border: 1px solid #dfe3eb;
                padding: 0;
                margin: 0;
            }

            .tbl {
                width: 100%;
            }

            .tbl tr td,
            .tbl tr th {
                padding: 4px 8px;
            }
        </style>

        <table class="tbl">
            <tr style="">
                <th class="center" style="width: 10%;">A/c Code</th>
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
                    <select class="form-control" id="account_code" required>
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
                    <select name="gst_code" class="form-control" id="gst_code" style="width: 100%;">
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
        </table>
    </div>


    <div class="col-12">
        <form action="" style="margin-top: 50px;">
            <table class="tbl" style="" id="readData"></table>
        </form>
        <br>
    </div>

    <div class="col-12">
        <form action="{{route('client.je_post')}}" method="POST">
            @csrf
            <input type="hidden" name="journal_date" id="post_journal_date">
            <input type="hidden" name="journal_number" id="post_journal_number"
                value="{{str_pad($journal_number->max('journal_number')+1, 8, '0', STR_PAD_LEFT)}}">
            <input type="hidden" name="journal_reference" id="post_journal_reference">
            <input type="hidden" name="client_id" value="{{$client->id}}">
            <input type="hidden" name="profession_id" value="{{$profession->id}}">
            <input type="submit" value="Journal Post" class="btn btn-danger d-hide" style="float: right; "
                id="journal_post" disabled>
        </form>
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

<script>
    readData();
    $('#journal_date').change(function () {
        $('#post_journal_date').val($(this).val());
        $('#post_journal_number').val($('#journal_number').val());
    });
    $('#journal_reference').change(function () {
        $('#post_journal_reference').val($(this).val());
    });

    function deleteBankStatement(id) {
        var request = $.ajax({
            url: "{{route('bank-statement.delete')}}",
            method: "GET",
            data: {id : id},
        });

        request.done(function( response ) {
            alert('Successfully Deleted');
        });

        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });
        $('#input_row_'+id).hide();
    }

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

    $('#account_code').change(function () {
        let type     = $('#account_code option:selected').data('type');
        let gst_code = $('#account_code option:selected').data('gst');
        let ac_code  = $('#account_code option:selected').data('code');
        let code     = ac_code.toString().substring(0,1);
        // if(code == 1){
        //     var j = ["GST","FREE"];
        //     var options = '';

        //     for (var i = 0; i < j.length; i++) {
        //         options +='<option value="' + j[i]+ '">' + j[i] + '</option>' ;
        //     }
        //     $("#gst_code").html(options);
        // }
        // if(code == 2){
        //     var j = ["INP","FOA","W1","W2","SUP"];
        //     var options = '';

        //     for (var i = 0; i < j.length; i++) {
        //         options +='<option value="' + j[i]+ '">' + j[i] + '</option>' ;
        //     }
        //     $("#gst_code").html(options);
        // }
        // if(code == 5){
        //     var j = ["CAP","NILL"];
        //     var options = '';

        //     for (var i = 0; i < j.length; i++) {
        //         options +='<option value="' + j[i]+ '">' + j[i] + '</option>' ;
        //     }
        //     $("#gst_code").html(options);
        // }
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
            url: "{{route('client.je_store')}}",
            method: "GET",
            data: data,
        });
        request.done(function( response ) {
            clear();
            readData()
            $('#debit').prop('disabled',false);
            $('#credit').prop('disabled',false);
        });

        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus + jqXHR.responseText );
        });

    });

    function getData() {
        return {
            'account_code' : $('#account_code').val(),
            'narration'    : $('#narration').val(),
            'date'         : $('#date').val(),
            'debit'        : $('#debit').val(),
            'credit'       : $('#credit').val(),
            'client_id'    : '{{ $client->id }}',
            'profession_id': '{{ $profession->id }}',
            'gst_code'     : $('#gst_code').val(),
            'net_amount'   : $('#exTax').val(),
            'gst'          : $('#tax').val(),
            'type'         : $('#type').val(),
        };
    }
    function readData(){
        let client_id = "{{$client->id}}";
        let profession_id = "{{$profession->id}}";
        $.ajax({
            url:"{{route('client.je_read')}}",
            method: 'get',
            data:{
                client_id:client_id,
                profession_id:profession_id
            },
            success: function (data) {
                data = $.parseJSON(data);
                if (data.status == 'success') {
                    $('#readData').html(data.html);
                    console.log(data.totalDebit +'=='+ data.totalCredit);

                    if(data.totalDebit == data.totalCredit){
                        $('#journal_post').prop('disabled',false);
                    }else{
                        $('#journal_post').prop('disabled',true);
                    }
                }
            }
        });
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

