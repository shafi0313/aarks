@extends('frontend.layout.master')
@section('title', 'Comperative Financial Report')
@section('content')
    <?php $p = 'ccfr';
    $mp = 'advr'; ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">For the year ended</h3>
                    </div>
                    <div class="card-body" style="padding:0px;">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Company /Trust/Partner ship Name</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Email Address</th>
                                    <th>ABN Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $client->company }} </td>
                                    <td>{{ $client->first_name }}</td>
                                    <td>{{ $client->last_name }}</td>
                                    <td>{{ $client->phone }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->abn_number }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <form action="{{ route('cr_console_financial.report') }}" method="get"
                    autocomplete="off">
                    <div class="row mt-5">
                        <div class="col-md-3"></div>
                        <div class="col-md-1" style="padding-top:7px;" align="right"><strong>As at : </strong>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <input type="" class="form-control date-picker datepicker" name="date" id="form_date"
                                    placeholder="DD/MM/YYYY" data-date-format="dd/mm/yyyy" required />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <button type="submit" class="btn btn-info" style="border:none; padding:7px;">Show
                                    Report</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-2"></div>
                        <div class="col-md-4" align="left">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="balance_sheet" value="1">
                                    Balance Sheet </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="incomestatment_note" value="1">
                                    Income statement </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="details_balance_sheet" value="1">
                                    Detailed Balance Sheet </label>
                            </div>


                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="trading_profit_loss" value="1">
                                    Detailed Trading, Profit & Loss </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="trial_balance" value="1">
                                    Trial Balance </label>
                            </div>



                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="cash_flow_statement" value="1">
                                    Statement of Cash Flows</label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="statement_of_receipts_and_payments" value="1">
                                    Statement of receipts and payments </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="statement_of_chanes_in_equity" value="1">
                                    Statement of change in equity </label>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="depreciation" value="1">
                                    Depreciation Report </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="notes_to_financial_statements" value="1">
                                    Notes to financial statements </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="directors_report" value="1">
                                    Directors' report </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="directors_declaration" value="1">
                                    Directors' declaration </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="audit_report" value="1">
                                    Audit report </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="compilation_report" value="1">
                                    Compilation report </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="contents" value="1">
                                    Comments </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.page-content -->
    <script>
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
    </script>
@endsection
