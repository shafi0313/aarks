@extends('admin.layout.master')
@section('title','Complete Financial Report')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Reports</li>
                <li>Complete Financial Report</li>
                <li class="active">{{ $profession->name }}</li>
            </ul><!-- /.breadcrumb -->

            {{-- <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div> --}}
            <!-- /.nav-search -->
        </div>

        <div class="page-content">


            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">
                        <div class="col-xs-12">
                            <h3>For the year ended</h3>
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Report for the Activity : <strong>Ride
                                            Sharing(UBER)</strong></h3>
                                </div>
                                <div class="panel-body" style="padding:0px;">
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
                                                <td>{{$client->company}} </td>
                                                <td>{{$client->first_name}}</td>
                                                <td>{{$client->last_name}}</td>
                                                <td>{{$client->phone}}</td>
                                                <td>{{$client->email}}</td>
                                                <td>{{$client->abn_number}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <form
                                action="{{route('complete_financial_report.report',['client' => $client->id, 'profession' => $profession->id])}}"
                                method="get" autocomplete="off">
                                <div class="col-md-3"></div>
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="panel-body" style="padding:0px;">
                                            <div class="col-md-3" style="padding-top:7px;" align="right"><strong>As at :
                                                </strong></div>
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <input type="" class="form-control date-picker datepicker"
                                                        name="date" id="form_date" placeholder="DD/MM/YYYY"
                                                        data-date-format="dd/mm/yyyy" required />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <button type="submit" class="btn btn-info"
                                                        style="border:none; padding:7px;">Show Report</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
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

                                        {{-- <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="trial_balance" value="1">
                                                Trial Balance </label>
                                        </div> --}}


                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="cash_flow_statement" value="1">
                                                Statement of Cash Flows</label>
                                        </div>

                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="statement_of_receipts_and_payments"
                                                    value="1">
                                                Statement of receipts and payments </label>
                                        </div>

                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="statement_of_chanes_in_equity" value="1">
                                                Statement of change in equity </label>
                                        </div>

                                    </div>

                                    <div class="col-md-4" align="left">
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

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- Script -->
<script>
    $(".datepicker").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true
            });
</script>


@endsection
