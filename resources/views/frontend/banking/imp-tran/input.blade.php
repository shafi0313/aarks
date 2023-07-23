@extends('frontend.layout.master')
@section('title','Bank Statement Input Transaction Update')
@section('content')
<?php $p="impt"; $mp="bank"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3>Bank Statement Input Transaction Update</h3>
                    </div>
                    <div class="card-body">

                        <div class="">
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
                            <form action="{{route('cbs_tranlist.input.update',[$client->id,$profession->id,$tran_id])}}"
                                autocomplete="off" method="post">
                                @csrf @method('put')


                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <input type="hidden" name="profession_id" value="{{$profession->id}}">
                                <input type="hidden" name="tran_id" value="{{$tran_id}}">
                                <input type="hidden" name="bank_account" value="{{$bank->client_account_code_id}}">


                                <table class="tbl">
                                    <tr style=" color:#fff;background-color: #438eb9;padding:15px">
                                        <th class="center">A/c Code</th>
                                        <th class="center">Trn. Date</th>
                                        <th class="center">Narration</th>
                                        <th class="center">Tx Code</th>
                                        <th class="center">Debit</th>
                                        <th class="center">Credit</th>
                                        <th class="center">Action</th>
                                    </tr>
                                    @foreach ($inputs as $input)
                                    <tr>
                                        <td>
                                            <select class="form-control" name="account_id[]" title="If you want to update the code,please delete line and re-enter.Caution ! make sure same code data will delete also in this transaction" required>
                                                @php
                                                $code = $input->client_account_code;
                                                @endphp
                                                <option value="{{$code->id}}">{{$code->name}} => {{$code->code}}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="chart_id[]" value="{{$code->code}}">
                                            <input name="date[]" class="form-control datepicker" type="text" required
                                                autocomplete="off" data-date-format="dd/mm/yyyy"
                                                value="{{$input->date->format('d/m/Y')}}">
                                        </td>
                                        <td>
                                            <input name="narration[]" class="form-control" type="text" required
                                                value="{{$input->narration}}">
                                        </td>
                                        <td>
                                            <input type="text" name="gst_code[]" value="{{$code->gst_code}}" readonly
                                                class="form-control">
                                        </td>
                                        <td>
                                            <input name="debit[]" class="form-control" type="number" min="0" step="any"
                                                value="{{number_format($input->debit,2,'.','',)}}"
                                                {{$input->debit == ''?'readonly':''}}>
                                        </td>
                                        <td>
                                            <input name="credit[]" class="form-control" type="number" min="0" step="any"
                                                value="{{number_format($input->credit,2,'.','',)}}"
                                                {{$input->credit == ''?'readonly':''}}>
                                        </td>

                                        <td class="center">
                                            <a title="CBS Tran delete" class="btn btn-sm btn-danger text-light fa fa-trash"
                                                style="cursor: pointer"
                                                href="{{route('cbs_tranlist.input.destroy',[ $client->id,$profession->id,$input->id])}}"
                                                onclick="return confirm('After Delete please click on UPDATE. Are you sure?')"></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="7">
                                            <input onclick="return confirm('Are you sure?')" type="submit"
                                                value="Update" class="form-control btn btn-primary" style="width:200px; float:right">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
    $('#example').DataTable( {
        "order": [[ 0, "asc" ]]
    } );
} );
</script>
@stop
