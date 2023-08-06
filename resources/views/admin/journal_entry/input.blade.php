@extends('admin.layout.master')
@section('title', 'Journal Entry Input')
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
                        <a
                            href="#">{{ $client->company ? $client->company : $client->first_name . ' ' . $client->last_name }}</a>
                    </li>
                    <li>{{ $profession->name }}</li>
                    <li class="active">Journal Input</li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div style="margin: 5px">
                            <div class="row" style="padding-bottom: 10px;">
                                <div class="col-md-4 row">
                                    <div class="form-group form-group-sm">
                                        <label for="journal_date" class="col-sm-5 control-label required">Journal Date
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control datepicker "
                                                @error('journal_date') style="border: 1px solid red;" @enderror
                                                id="journal_date" name="journal_date" placeholder="dd/mm/yyyy"
                                                autocomplete="off" required>
                                            <small id="taxMsg" style="display: none;color: red">Message</small>
                                            @error('journal_date')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 row">
                                    <div class="form-group form-group-sm">
                                        <label for="journal_number" class="col-sm-5 control-label required">Journal Number
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" readonly class="form-control" id="journal_number"
                                                name="journal_number" value="{{ invoice($journal_number) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 row">
                                    <div class="form-group form-group-sm">
                                        <label for="journal_reference" class="col-sm-5 control-label required">Journal
                                            Reference </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="journal_reference"
                                                @error('journal_reference') style="border: 1px solid red;" @enderror
                                                name="journal_reference" placeholder="journal Reference" autocomplete="off"
                                                required>
                                            @error('journal_reference')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
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

                                        .form-control {
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
                                        <tr>
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
                                                <select class="form-control is-invalid" id="account_code" required>
                                                    <option value="">--</option>
                                                    @foreach ($client_account_codes as $client_account_code)
                                                        <option value="{{ $client_account_code->id }}"
                                                            data-gst="{{ $client_account_code->gst_code }}"
                                                            data-type="{{ $client_account_code->type }}"
                                                            data-code="{{ $client_account_code->code }}">

                                                            {{ $client_account_code->name }} =>
                                                            {{ $client_account_code->code }}

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
                                                    @foreach (aarks('gst_code') as $gst_code)
                                                        <option value="{{ $gst_code }}">{{ $gst_code }}</option>
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


                                <div class="col-md-12">
                                    <form action="" style="margin-top: 50px;">
                                        <table class="tbl" style="" id="readData"></table>
                                    </form>
                                    <br>
                                </div>

                                <div class="col-md-12">
                                    <form action="{{ route('journal_entry.post') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="journal_date" id="post_journal_date">
                                        <input type="hidden" name="journal_number" id="post_journal_number"
                                            value="{{ $journal_number }}">
                                        <input type="hidden" name="journal_reference" id="post_journal_reference">
                                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                                        <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                                        <input type="submit" value="Journal Post" class="btn btn-primary d-hide"
                                            style="float: right; " id="journal_post" disabled>
                                    </form>
                                </div>

                                <p class="text-danger" style="font-size:15px">***To enter historical records, please enter
                                    last year' full trial balance(including Income and Expenses exclude retained earning)***
                                </p>
                            </div>
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <!-- Modal -->
    
    <script>
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
    </script>
    <script>
        readData();

        $('#journal_date').change(function() {
            $('#post_journal_date').val($(this).val());
            $('#post_journal_number').val($('#journal_number').val());
        });
        $('#journal_reference').change(function() {
            $('#post_journal_reference').val($(this).val());
        });

        function deleteBankStatement(id) {
            var request = $.ajax({
                url: "{{ route('bank-statement.delete') }}",
                method: "GET",
                data: {
                    id: id
                },
            });

            request.done(function(response) {
                alert('Successfully Deleted');
            });

            request.fail(function(jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
            $('#input_row_' + id).hide();
        }

        function openField() {
            let debit = $('#debit').val();
            let credit = $('#credit').val();
            if (debit == '' && credit == '') {
                $('#debit').prop('disabled', false);
                $('#credit').prop('disabled', false);
            }
            if (!debit == '') {
                $('#credit').prop('disabled', true);
            }
            if (!credit == '') {
                $('#debit').prop('disabled', true);
            }

        }
        $('#debit,#credit').keyup(function() {
            openField();
            let gst_code = $('#gst_code').val();
            let tax = $(this).val() && (gst_code == 'GST' || gst_code == 'INP' || gst_code == 'CAP') ? parseFloat($(
                this).val()) / 11 : 0;
            let exTax = $(this).val() ? parseFloat($(this).val()) - tax : 0;
            $('#tax').val(tax.toFixed(2));
            $('#exTax').val(exTax.toFixed(2));
        });

        $('#account_code').change(function() {
            let type = $('#account_code option:selected').data('type');
            let gst_code = $('#account_code option:selected').data('gst');
            let ac_code = $('#account_code option:selected').data('code');
            let code = ac_code.toString().substring(0, 1);
            $("#type").val(type);
        });

        $('#add').click(function() {
            var data = getData();
            var request = $.ajax({
                url: "{{ route('journal_entry.store') }}",
                method: "GET",
                data: data,
            });
            request.done(function(response) {
                clear();
                readData()
                $('#debit').prop('disabled', false);
                $('#credit').prop('disabled', false);
            });

            request.fail(function(jqXHR, textStatus) {
                console.log(textStatus, jqXHR);
                alert("Request failed: " + jqXHR.responseJSON.message);
            });

        });

        function getData() {
            return {
                'account_code': $('#account_code').val(),
                'narration': $('#narration').val(),
                'date': $('#date').val(),
                'debit': $('#debit').val(),
                'credit': $('#credit').val(),
                'client_id': '{{ $client->id }}',
                'profession_id': '{{ $profession->id }}',
                'gst_code': $('#gst_code').val(),
                'net_amount': $('#exTax').val(),
                'gst': $('#tax').val(),
                'type': $('#type').val(),
            };
        }

        function readData() {
            let client_id = "{{ $client->id }}";
            let profession_id = "{{ $profession->id }}";
            $.ajax({
                url: "{{ route('journal_entry.read') }}",
                method: 'get',
                data: {
                    client_id: client_id,
                    profession_id: profession_id
                },
                success: function(data) {
                    data = $.parseJSON(data);
                    if (data.status == 'success') {
                        $('#readData').html(data.html);
                        if (Math.round(data.totalDebit) == Math.round(data.totalCredit)) {
                            $('#journal_post').prop('disabled', false);
                        } else {
                            $('#journal_post').prop('disabled', true);
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
