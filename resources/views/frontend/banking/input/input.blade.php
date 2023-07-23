@extends('frontend.layout.master')
@section('title','Bankstatement Input')
@section('content')
<?php $p="in"; $mp="bank";?>

<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="card ">
            <div class="card-heading">
                <h3>Bank statement input</h3>
            </div>
            <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    Select Bank Account:
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="" id="bank_account" @error('bank_account')
                        style="border: 1px solid red;" @enderror>
                        <option>Select Bank Account</option>
                        @foreach($liquid_asset_account_codes as $liquid_asset_account_code)
                        <option value="{{$liquid_asset_account_code->id}}">{{$liquid_asset_account_code->name}}</option>
                        @endforeach
                    </select>
                    @error('bank_account')
                    <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <span>Entering Account Name:-</span>
                </div>
                <div class="col-md-12">
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
                        .tbl tr th {
                            text-align: center;
                            font-size: 13px;
                        }
                        input[type] {
                            padding: 0 3px;
                        }
                    </style>

                    <table class="tbl">
                        <tr style="">
                            <th style="width: 10%;">A/c Code</th>
                            <th style="width: 10%;">Trn. Date</th>
                            <th style="width: 25%;">Narration</th>
                            <th style="width: 6%;">Tx Code</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>(Excl Tax)</th>
                            <th>Tax</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>
                                <select class="form-control form-control-sm" name="" id="account_code" required>
                                    <option value="" selected>Please Select Bank Account!</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control form-control-sm datepicker" id="date" type="text" required data-date-format="dd/mm/yyyy"
                                    autocomplete="off">
                            </td>
                            <td>
                                <input class="form-control form-control-sm" id="narration" type="text" required>
                            </td>
                            <td>
                                <select name="gst_code" id="gst_code"  class="form-control form-control-sm"
                                    style="width: 100%; appearance:none;-moz-appearance: none;" disabled>
                                    @foreach(aarks('gst_code') as $gst_code)
                                    <option value="{{ $gst_code }}">{{$gst_code}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input step="any" id="debit" class="form-control form-control-sm" type="number" disabled>
                            </td>
                            <td>
                                <input step="any" id="credit" class="form-control form-control-sm" type="number" disabled>
                            </td>
                            <td>
                                <input id="exTax" class="form-control form-control-sm" type="text" disabled>
                            </td>
                            <td>
                                <input id="tax" class="form-control form-control-sm" type="text" disabled>
                            </td>
                            <td class="center">
                                <button id="add" class="btn btn-sm btn-secondary">Add</button>
                            </td>
                        </tr>
                    </table>
                </div>


                <div class="col-md-12">
                    <form action="" style="margin-top: 50px;">
                        <table class="tbl" style="">
                            <tr style=" color:#fff; background: #337ab7;">
                                <th class="center" style="width: 20%;">A/c Code</th>
                                <th class="center" style="width: 10%;">Trn. Date</th>
                                <th class="center" style="width: 25%;">Narration</th>
                                <th class="center" style="width: 6%;">Tx Code</th>
                                <th class="center">Debit</th>
                                <th class="center">Credit</th>
                                <th class="center" style="width: 5%;">Action</th>
                            </tr>
                            <tbody id="result">
                                @foreach($inputs as $input)
                                <tr id="input_row_{{$input->id}}">
                                    <td>{{$input->client_account_code->name}}</td>
                                    <td>{{ $input->date->format(aarks('frontend_date_format')) }}</td>
                                    <td>{{ $input->narration }}</td>
                                    <td>{{ $input->gst_code }}</td>
                                    <td style="text-align: right">{{ number_format($input->debit,2) }}</td>
                                    <td style="text-align: right">{{ number_format($input->credit,2) }}</td>
                                    <td class="text-center">
                                        <a title="BS Delete" class="fa fa-trash bigger-130" style="cursor: pointer; color:red"
                                            onclick="confirm('Are you sure to delete?') ? deleteBankStatement('{{$input->id}}') : ''"></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                    <br>
                </div>

                <div class="col-md-12">
                    <form action="{{route('cbs_input.post')}}" method="POST">
                        @csrf
                        <input type="hidden" name="bank_account" id="post_bank_account">
                        <input type="hidden" name="client_id" value="{{$client->id}}">
                        <input type="hidden" name="profession_id" value="{{$profession->id}}">
                        <button type="submit" class="btn btn-info" onclick="confirm('Are you sure to post?')" style="float: right; "> Post</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->


<script>
    $('#bank_account').change(function () {
        $('#post_bank_account').val($(this).val());
        $.ajax({
            url:"{{route('cbs_input.getcodes')}}",
            data:{client:"{{$client->id}}",profession:"{{$profession->id}}",id:$(this).val()},
            method:'get',
            success:res=>{
                let opt = '<option disabled selected>- -</option>';
                if(res.status == 200){
                $.each(res.codes,function(i,v){
                    opt += '<option value="'+v.id+'" data-gst="'+v.gst_code+'" data-type="'+v.type+'" data-code="'+ parseInt(v.code/100000) +'">'+v.name+' => '+v.code+'</option>';
                });
                $("#account_code").html(opt);
                }else{
                    toast('error', 'No Codes found')
                }
            },
            error:err=>{
                toast('error', 'No Codes found')
            }
        });
    });
    function deleteBankStatement(id) {
        var request = $.ajax({
            url: "{{route('cbs_input.delete')}}",
            method: "GET",
            data: {id : id},
        });

        request.done(function( response ) {
            toast('success','Successfully Deleted');
        });

        request.fail(function( jqXHR, textStatus ) {
            toast( "error", jqXHR.responseText );
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
        let type = $('#account_code option:selected').data('type');
        let gst_code = $('#account_code option:selected').data('gst');
        let ac_group = $('#account_code option:selected').data('code');
        $("#gst_code option").each(function()
        {
            if($(this).val() == gst_code){
                $(this).prop('selected', true);
            }
        });
        if(ac_group == 9 || ac_group == 5) {
            $('#debit').prop('disabled',false);
            $('#credit').prop('disabled',false);
        } else {
            if(type == 1){
                $('#debit').prop('disabled',false);
                $('#credit').prop('disabled',true);
            }else{
                $('#debit').prop('disabled',true);
                $('#credit').prop('disabled',false);
            }
        }
    });

    $('#add').click(function () {
        var data = getData();
        clear();
        var request = $.ajax({
            url: "{{route('cbs_input.store')}}",
            method: "GET",
            data: data,
        });

        request.done(function( response ) {
            let body = `<tr id="input_row_${response.id}">`+
                            '<td style="width: 20%;">'+response.client_account_code.name+'</td>'+
                            '<td>'+moment(response.date).format("DD/MM/YYYY")+'</td>'+
                            '<td>'+response.narration+'</td>'+
                            '<td style="width: 10%;">'+response.gst_code+'</td>'+
                            '<td>'+response.debit+'</td>'+
                            '<td>'+response.credit+'</td>'+
                            '<td class="center">'+
                            `<a class="fa fa-trash text-danger bigger-130 red" onclick="confirm('Are you sure to delete?') ? deleteBankStatement(${response.id}) : '' "></a>`+
                            '</td>'+
                        '</tr>';
            $('#result').append(body);

        });

        request.fail(function( jqXHR, textStatus ) {
            toast('error', jqXHR.responseText );
        });

    });

    function getData() {
        return {
            'account_code': $('#account_code').val(),
            'narration' : $('#narration').val(),
            'date' : $('#date').val(),
            'debit': $('#debit').val(),
            'credit': $('#credit').val(),
            'client_id': '{{ $client->id }}',
            'profession_id': '{{ $profession->id }}',
            'gst_code': $('#gst_code').val(),
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

<!-- Footer End -->

@stop
