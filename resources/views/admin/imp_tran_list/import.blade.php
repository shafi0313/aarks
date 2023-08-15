@extends('admin.layout.master')
@section('title', 'Bank Statement Edit Page')
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
                        <a href="{{ route('bs_tran_list.index') }}">Bank Statement Transaction</a>
                    </li>
                    <li>
                        <a href="{{ route('bs_tran_list.profession', $client->id) }}">{{ $client->fullname }}</a>
                    </li>
                    <li>
                        <a
                            href="{{ route('bs_tran_list.report', [$client->id, $profession->id]) }}">{{ $profession->name }}</a>
                    </li>
                </ul><!-- /.breadcrumb -->
            </div>

            <div class="page-content">

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
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                @if (!request()->only_view)
                                    <form
                                        action="{{ route('bs_tran_list.import.update', [$client->id, $profession->id, $tran_id]) }}"
                                        autocomplete="off" method="post">
                                        @csrf @method('put')
                                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                                        <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                                        <input type="hidden" name="tran_id" value="{{ $tran_id }}">
                                        <input type="hidden" name="bank_account"
                                            value="{{ $bank->client_account_code_id }}">
                                    @else
                                        <form>
                                @endif
                                <table class="tbl">
                                    <tr style="">
                                        <th class="center">A/c Code</th>
                                        <th class="center">Trn. Date</th>
                                        <th class="center">Narration</th>
                                        <th class="center">Debit</th>
                                        <th class="center">Credit</th>
                                        @if (!request()->only_view)
                                            <th class="center">Action</th>
                                        @endif
                                    </tr>
                                    @php
                                        $debit = $credit = 0;
                                    @endphp
                                    @foreach ($imports as $import)
                                        <tr>
                                            <td>
                                                {{-- <select class="form-control" name="account_id[]"
                                                    title="If you want to update the code,please delete the line and re-enter but you can edit date,narration & amount.Caution! Be aware that same account code data in this transaction will be deleted."
                                                    required> --}}
                                                    @php
                                                        $selectedCode = $import->client_account_code;
                                                    @endphp
                                                    {{-- <option value="{{ $code->id }}">{{ $code->name }} =>
                                                        {{ $code->code }}
                                                    </option> --}}
                                                    {{-- @foreach ($codes as $code)
                                                        <option value="{{ $code->id }}" @selected($selectedCode->id == $code->id)>{{ $code->name }} =>
                                                            {{ $code->code }}</option>
                                                    @endforeach --}}





                                                    <select class="form-control account_code" name="account_code[]" data-route="{{ route('bs_import.updateCode', $import->id) }}">
                                                    
                                                    @foreach ($codes as $code)
                                                        <option value="{{ $code->id }}" style="color:{{ $code->type == 1 ? '#f542e9' : 'blue' }}" @selected($selectedCode->id == $code->id)>
                                                            {{ $code->name }} => {{ $code->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{-- </select> --}}
                                            </td>
                                            <td>
                                                <input type="hidden" name="chart_id[]" value="{{ $code->code }}">
                                                <input type="hidden" name="inp[]" value="{{ $import->id }}">
                                                <input name="date[]" class="form-control datepicker" type="text"
                                                    required autocomplete="off" data-date-format="dd/mm/yyyy"
                                                    value="{{ $import->date->format('d/m/Y') }}">
                                            </td>
                                            <td>
                                                <input name="narration[]" class="form-control" type="text" required
                                                    value="{{ $import->narration }}">
                                            </td>
                                            <td>
                                                @php($debit += abs($import->debit))
                                                <input name="debit[]" class="form-control" type="number" min="0"
                                                    step="any" value="{{ number_format($import->debit, 2, '.', '') }}"
                                                    {{ $import->debit == '' ? 'readonly' : '' }}>
                                            </td>
                                            <td>
                                                @php($credit += abs($import->credit))
                                                <input name="credit[]" class="form-control" type="number" min="0"
                                                    step="any" value="{{ number_format($import->credit, 2, '.', '') }}"
                                                    {{ $import->credit == '' ? 'readonly' : '' }}>
                                            </td>
                                            @if (!request()->only_view)
                                                <td class="center">
                                                    <a title="BS Tran List Delete"
                                                        class="btn btn-sm btn-danger text-light fa fa-trash"
                                                        style="cursor: pointer"
                                                        href="{{ route('bs_tran_list.import.destroy', [$client->id, $profession->id, $import->id]) }}"
                                                        onclick="return confirm('Are you sure to delete the Transaction?')"></a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3"></td>
                                        <td>
                                            {{ number_format($debit, 2) }}
                                        </td>
                                        <td>
                                            {{ number_format($credit, 2) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                    @if (!request()->only_view)
                                        <tr>
                                            <td colspan="7">
                                                <button onclick="return confirm('Are you sure?')" type="submit"
                                                    class="btn btn-info mt-4 form-control" style="float: right; ">
                                                    Update</button>
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

        $(function() {
            $('.account_code').change(function() {
                var accountCode = $(this).val();
                var url = $(this).data('route');
                $.ajax({
                    url: url,
                    data: {
                        "accountCode": accountCode
                    },
                    method: "GET",
                    success: function(res) {
                        if (res.status == 200) {
                            toast('success', res.message);
                        } else {
                            toast('error', res.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection
