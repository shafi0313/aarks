@extends('admin.layout.master')
@section('title','Bank Statement Edit Page')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>
                    <a href="{{ route('general_ledger.index') }}">Bank Statement Edit</a>
                </li>
                <li>
                    <a href="#">{{$profession->name}}</a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">

            <!-- Settings -->
            {{--            @include('admin.layout.settings')--}}
            <!-- /Settings -->
            <div class="row">
                <div class="col-md-12">
                    <span>Entering Account Name:- </span>
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
                    </style>

                    <table class="tbl">
                        <tr style="">
                            <th class="center" style="width: 10%;">A/c Code</th>
                            <th class="center" style="width: 10%;">Trn. Date</th>
                            <th class="center" style="width: 25%;">Narration</th>
                            <th class="center" style="width: 6%;">Tx Code</th>
                            <th class="center">Debit</th>
                            <th class="center">Credit</th>
                            <th class="center">(Excl Tax)</th>
                            <th class="center">Tax</th>
                            <th class="center">Action</th>
                        </tr>
                        <tr>
                            <td>
                                <select class="form-control" name="" id="account_code" required>
                                    <option value="">--</option>
                                    @foreach($codes as $code)
                                    <option value="{{$code->id}}" data-gst="{{$code->gst_code}}" data-code="{{intval($code->code/100000)}}"
                                        data-type="{{$code->type}}">
                                        {{$code->name}} => {{$code->code}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input class="form-control datepicker" id="date" type="text" required autocomplete="off"
                                    data-date-format="dd/mm/yyyy">
                            </td>
                            <td>
                                <input class="form-control" id="narration" type="text" required>
                            </td>
                            <td>
                                <select name="" id="gst_code"
                                    style="width: 100%; appearance:none;-moz-appearance: none;" disabled
                                    class="form-control">
                                    @foreach(aarks('gst_code') as $gst_code)
                                    <option value="{{ $gst_code }}">{{$gst_code}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input step="any" id="debit" class="form-control" type="number" min="0" disabled>
                            </td>
                            <td>
                                <input step="any" id="credit" class="form-control" type="number" min="0" disabled>
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
                <div class="col-md-12">
                    <form action="" style="margin-top: 50px;">
                        <table class="tbl" style="">
                            <tr style=" color:#fff;background-color: #438eb9;">
                                <th class="center">A/c Code</th>
                                <th class="center">Trn. Date</th>
                                <th class="center">Narration</th>
                                <th class="center">Tx Code</th>
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
                                    <td class="center">
                                        <a title="BS Delete" class="fa fa-trash-o bigger-130 red"  style="cursor: pointer" onclick="confirm('Are you sure to delete?') ? deleteBankStatement('{{$input->id}}') : ''"></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                    <br>
                </div>

                <div class="col-md-12">
                    <form action="{{route('abs_input.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="bank_account" id="post_bank_account"
                            value="{{$bank->client_account_code_id}} ">
                        <input type="hidden" name="tran_id" id="post_tran_id" value="{{$tran_id}} ">
                        <input type="hidden" name="client_id" value="{{$client->id}}">
                        <input type="hidden" name="profession_id" value="{{$profession->id}}">
                        <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-success"
                            style="float: right; "> Post</button>
                    </form>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('abs_input.update',$tran_id)}}" autocomplete="off" method="post">
                                @csrf @method('put')
                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <input type="hidden" name="profession_id" value="{{$profession->id}}">
                                <input type="hidden" name="tran_id" value="{{$tran_id}}">
                                <table class="tbl">
                                    <tr style="">
                                        <th class="center">A/c Code</th>
                                        <th class="center">Trn. Date</th>
                                        <th class="center">Narration</th>
                                        <th class="center">Tx Code</th>
                                        <th class="center">Debit</th>
                                        <th class="center">Credit</th>
                                        <th class="center">Action</th>
                                    </tr>
                                    @foreach ($ledgers as $ledger)
                                    <tr>
                                        <td>
                                            <select class="form-control" name="account_id[]" required>
                                                @php
                                                $code = $codes->where('code', $ledger->chart_id)->first();
                                                @endphp
                                                <option value="{{$code->id}}">{{$code->name}} => {{$code->code}}
                                                </option>
                                                {{-- @foreach($codes as $code)
                                                                <option value="{{$code->id}}"
                                                data-gst="{{$code->gst_code}}"
                                                data-type="{{$code->type}}"
                                                {{$code->code == $ledger->chart_id?'selected':''}}>
                                                {{$code->name}} => {{$code->code}}</option>
                                                @endforeach --}}

                                            </select>
                                        </td>
                                        <td>
                                            <input name="date[]" class="form-control datepicker" type="text" required
                                                autocomplete="off" data-date-format="dd/mm/yyyy"
                                                value="{{$ledger->date->format('d/m/Y')}}">
                                        </td>
                                        <td>
                                            <input name="narration[]" class="form-control" type="text" required
                                                value="{{$ledger->narration}}">
                                        </td>
                                        <td>
                                            <input type="text" name="gst_code[]" value="{{$code->gst_code}}" readonly
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input name="debit[]" class="form-control" type="number" min="0"
                                                value="{{number_format($ledger->debit,2,'.','',)}}"
                                                {{$ledger->debit == ''?'disabled':''}}>
                                        </td>
                                        <td>
                                            <input name="credit[]" class="form-control" type="number" min="0"
                                                value="{{number_format($ledger->credit,2,'.','',)}}"
                                                {{$ledger->credit == ''?'disabled':''}}>
                                        </td>
                                        <td class="center">
                                            <a class="" href="{{route('abs_input.destroy',[ $client->id,$profession->id,$ledger->chart_id,$tran_id])}} "
                                                onclick="return confirm('Are you sure to delete?')">Delete this line</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                                <button onclick="return confirm('Are you sure?')" type="submit"
                                    class="btn btn-info mt-4" style="float: right; "> Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<script>
    $(".datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });
</script>
<script>
$('#bank_account').change(function () {
    $('#post_bank_account').val($(this).val());
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
    let ac_group = $('#account_code option:selected').data('code');
    $("#gst_code option").each(function(){
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
        url: "{{route('bank-statement.store')}}",
        method: "GET",
        data: data,
    });

    request.done(function( response ) {
        let body = `<tr id="input_row_${response.id}">`+
                        '<td style="width: 20%;">'+response.client_account_code.name+'</td>'+
                        '<td>'+moment(response.date).format("DD/MM/YYYY")+'</td>'+
                        '<td>'+response.narration+'</td>'+
                        '<td style="width: 10%;">'+response.gst_code+'</td>'+
                        '<td style="text-align: right">'+response.debit+'</td>'+
                        '<td style="text-align: right">'+response.credit+'</td>'+
                        '<td class="center">'+
                        `<a title="BS Delete" class="fa fa-trash-o bigger-130 red" onclick="confirm('Are you sure to delete?') ? deleteBankStatement(${response.id}) : '' "></a>`+
                        '</td>'+
                    '</tr>';
        $('#result').append(body);
    });

    request.fail(function( jqXHR, textStatus, errorThrown) {
    //   alert( "Request failed: " + textStatus + jqXHR.responseText );
        toast('error', jqXHR.responseText);
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
@endsection
