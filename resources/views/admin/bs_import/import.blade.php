@extends('admin.layout.master')
@section('title', 'Import Bank Statement')
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
                        <a href="{{ route('select_method') }}">Add/Edit Entry</a>
                    </li>
                    <li>
                        <a href="{{ route('bs_import.index') }}">Import Bank Statement (BST)</a>
                    </li>
                    <li>
                        {{ $profession->name }}
                    </li>
                    <li class="active">{{ clientName($client) }}</li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row" style="margin-top: 20px;">
                            <form action="{{ route('bs_import.store') }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="col-md-2"></div>
                                <div class="col-md-4">
                                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                                    <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                                    <input type="file" name="file" class="form-control">

                                    @if ($errors->has('file'))
                                        <small class="text-danger">* {{ $errors->first() }}</small>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <ul>
                                        <li style="list-style: none;">
                                            <button type="button" data-toggle="modal" data-target="#myModal">Data Upload Policy.</button>
                                        </li>
                                        <li style="list-style: none">
                                            <a href="{{ asset('example.csv') }}">Download file Format</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-primary" type="submit">Import</button>
                                </div>
                            </form>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <p style="color: green; font-size: 22px"><span id="bank_code_name"></span> Balance: <span
                                        id="bank_balance"></span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="color: #8A6641; font-size: 22px">Current Balance: <span
                                        id="current_bank_balance"></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="center" style="width: 15%;">Account Name</th>
                                        <th class="center" style="width: 8%;">Date</th>
                                        <th class="center">Narration</th>
                                        <th class="center">Debit</th>
                                        <th class="center">Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bank_statements as $bank_statement)
                                        <tr>
                                            <td>
                                                <select class="form-control account_code" name="account_code[]"
                                                    data-route="{{ route('bs_import.updateCode', $bank_statement->id) }}">
                                                    <option value="" style="color: red">No Account Selected</option>
                                                    @foreach ($account_codes as $account_code)
                                                        <option value="{{ $account_code->id }}"
                                                            style="color:{{ $account_code->type == 1 ? '#f542e9' : 'blue' }}"
                                                            @if ($bank_statement->client_account_code && $bank_statement->client_account_code->code == $account_code->code) selected @endif>
                                                            {{ $account_code->name }} - {{ $account_code->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{ $bank_statement->date->format(aarks('frontend_date_format')) }}</td>
                                            <td>{{ $bank_statement->narration }}</td>
                                            <td class="text-right">{{ number_format($bank_statement->debit, 2) }}</td>
                                            <td class="text-right">{{ number_format($bank_statement->credit, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td style="text-align: right;font-weight:bold;" colspan="3">Total: </td>
                                        <td class="text-right">{{ $bank_statements->sum('debit') }}</td>
                                        <td class="text-right">{{ $bank_statements->sum('credit') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            {{-- <span style="float: right;padding: 5px;"> {{ $bank_statements->links() }}</span> --}}
                        </div>
                        <hr>
                        <div class="row">
                            <form action="{{ route('bs_import.post') }}" method="POST">
                                @csrf
                                <div class="col-md-4"></div>
                                <div class="col-md-2 text-success">
                                    Select Bank Account
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="bank_account" id="bank_account" style="width: 100%;">
                                        <option value="">Select Bank Account</option>
                                        @foreach ($liquid_asset_account_codes as $liquid_asset_account_code)
                                            <option value="{{ $liquid_asset_account_code->id }}"
                                                data-chart_id="{{ $liquid_asset_account_code->code }}">
                                                {{ $liquid_asset_account_code->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('bank_account'))
                                        <small class="text-danger">* {{ $errors->first() }}</small>
                                    @endif
                                </div>
                                <div class="col-md-1 text-right">
                                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                                    <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                                    <input type="hidden" name="period_id" value="{{ $period->id }}">
                                    <input type="hidden" name="startDate" value="{{ $period->start_date }}">
                                    <input type="hidden" name="endDate" value="{{ $period->end_date }}">
                                    <input type="hidden" name="gstMethod" value="{{ $client->gst_method }}">
                                    <input type="hidden" name="is_gst_enabled" value="{{ $client->is_gst_enabled }}">
                                    <input type="hidden" name="sba_debit" value="{{ $bank_statements->sum('debit') }}">
                                    <input type="hidden" name="sba_credit"
                                        value="{{ $bank_statements->sum('credit') }}">
                                    <button type="submit" class="btn btn-primary" name="action" value="post"
                                        onclick="return confirmPost()">Post</button>
                                </div>
                            </form>

                            <div class="col-md-1" style="">
                                <form action="{{ route('bs_import.delete') }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                                    <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                                    <button type="submit" class="btn btn-danger" name="action" value="delete"
                                        onclick="return confirm('Are you sure?')">Delete
                                        All</button>
                                </form>
                            </div>
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Data Upload Policy</h4>
                </div>
                <div class="modal-body">
                    <ol>
                        <li>File can only import if it is CSV(MS-DOS)/CSV(COMMA-delimited) and heading must be same as sample file.</li>
                        <li>Any negative amount in the debit/ credit colum can be issue with importing.</li>
                        <li>Any row information not completed marcuse file not be importing.</li>
                        <li>Total amount in the CSV file may cause file cannot importing.</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        $('#bank_account').change(function() {
            const chart_id = $(this).find(":selected").data('chart_id');
            const bank_account_name = $(this).find(":selected").text();
            $('#bank_code_name').text(bank_account_name);
            ledgerBalance(chart_id);
        });

        const ledgerBalance = (chart_id) => {
            $.ajax({
                url: "{{ route('bs_tran_list.getBalance') }}",
                data: {
                    client: "{{ $client->id }}",
                    profession: "{{ $profession->id }}",
                    chart_id: chart_id
                },
                method: 'get',
                success: res => {
                    if (res.status == 200) {
                        $('#bank_balance').text(res.balance);
                        $('#current_bank_balance').text(res.current_balance);
                    } else {
                        $('#bank_balance').text(0);
                        $('#current_bank_balance').text(0);
                    }
                },
                error: err => {
                    toast('error', 'Balance not found')
                }
            });
        }

        function toast(status, header, msg) {
            //$.toast('Here you can put the text of the toast')
            Command: toastr[status](header, msg)
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }

        function confirmPost() {
            var r = confirm(
                "Please check you are posting from the correct bank account. If correct press 'ok' and if not, 'cancel'!"
            );
            if (r == false) {
                return false;
            }
        }
    </script>
@stop
