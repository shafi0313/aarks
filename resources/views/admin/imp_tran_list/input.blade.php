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
            <div class="row justify-content-center mt-4">
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

                    <div class="card">
                        <div class="card-body">
                            @if(!request()->only_view)
                            <form action="{{route('bs_tran_list.input.update',[$client->id,$profession->id,$tran_id])}}" autocomplete="off" method="post">
                                @csrf @method('put')


                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <input type="hidden" name="profession_id" value="{{$profession->id}}">
                                <input type="hidden" name="tran_id" value="{{$tran_id}}">
                                <input type="hidden" name="bank_account" value="{{$bank->client_account_code_id}}">
                            @else
                            <form>
                            @endif

                                <table class="tbl">
                                    <tr style=" color:#fff;background-color: #438eb9;padding:15px">
                                        <th class="center">A/c Code</th>
                                        <th class="center">Trn. Date</th>
                                        <th class="center">Narration</th>
                                        <th class="center">Tx Code</th>
                                        <th class="center">Debit</th>
                                        <th class="center">Credit</th>
                                        @if(!request()->only_view)
                                        <th class="center">Action</th>
                                        @endif
                                    </tr>
                                    @php
                                        $debit = $credit = 0;
                                    @endphp
                                    @foreach ($inputs as $input)
                                    <tr>
                                        <td>
                                            <select class="form-control" name="account_id[]" title="If you want to update the code,please delete the line and re-enter but you can edit date,narration,amount.Caution! Be aware that same account code data in this transaction will be deleted" required>
                                                @php
                                                $code = $input->client_account_code;
                                                @endphp
                                                <option value="{{$code->id}}">{{$code->name}} => {{$code->code}}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="chart_id[]" value="{{$code->code}}">
                                            <input type="hidden" name="inp[]" value="{{$input->id}}">
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
                                            @php($debit += abs($input->debit))
                                            <input name="debit[]" step="any" class="form-control" type="number" min="0" step="any"
                                                value="{{number_format($input->debit,2,'.','',)}}"
                                                {{$input->debit == ''?'readonly':''}}>
                                        </td>
                                        <td>
                                            @php($credit += abs($input->credit))
                                            <input name="credit[]" class="form-control" type="number" min="0" step="any"
                                                value="{{number_format($input->credit,2,'.','',)}}"
                                                {{$input->credit == ''?'readonly':''}}>
                                        </td>
                                        @if(!request()->only_view)
                                        <td class="center">
                                            <a class="btn btn-sm btn-danger text-light fa fa-trash"
                                                style="cursor: pointer"
                                                href="{{route('bs_tran_list.input.destroy',[ $client->id,$profession->id,$input->id])}}"
                                                onclick="return confirm('Are you sure to delete the Transaction?')"></a>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="4"></td>
                                        <td>
                                            {{number_format($debit, 2)}}
                                        </td>
                                        <td>
                                            {{number_format($credit, 2)}}
                                        </td>
                                        <td></td>
                                    </tr>
                                    @if(!request()->only_view)
                                    <tr>
                                        <td colspan="7">
                                            <input onclick="return confirm('Are you sure?')" type="submit" value="Update" class="form-control btn btn-primary" style="width:200px; float:right">
                                        </td>
                                    </tr>
                                    @endif
                                </table>
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
@endsection
