@extends('admin.layout.master')
@section('title','Comparative Financial Reports')
@section('style')
<style>
    .reportH {
        margin: 50px 0;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 0 8px;
    }
</style>
@endsection
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Report</li>
                <li>Comparative Financial Report</li>
                <li class="active">{{ clientName($client) }}</li>
            </ul>
            <!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
        </div>

        <div class="page-content">
            <!-- /Settings -->
            <div class="row">
                <div class="col-lg-2 pull-right">
                    {{-- <a target="_blank" onclick="print()" class="btn btn-info">PRINT</a> --}}

                    <a target="_blank"
                        href="{{route('comperative_financial_report_print', $client->id)}}{{str_replace(request()->url(), '',request()->fullUrl())}}"
                        class="btn btn-info">PRINT</a>
                </div>
                <div class="col-lg-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row" style="height:100vh;display:flex;align-items:center;justify-content:center;">
                        <div class="col-md-12 text-center">
                            <h1>{{$client->fullname}}</h1>
                            <h4>ABN {{$client->abn_number}}</h4>
                            <br>

                            <h2>FINANCIAL REPORT</h2>
                            <h3 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">
                                For the year ended {{$data['date']->format('d-M-Y')}}</h3>
                            <br>
                            <br>
                            <h4>Prepared by</h4>
                            <h4>{{ $client->agent_name }}</h4>
                            <h5>{{ $client->agent_address }}</h5>
                        </div>
                    </div>

                    <div class="row" style="height:100vh;display:flex;align-items:center;justify-content:center;">
                        <div class="col-md-12 text-center">
                            <h1>{{$client->fullname}}</h1>
                            <h4>ABN {{$client->abn_number}}</h4>
                            <h4> FINANCIAL REPORT</h4>
                            <h3>Contents</h3>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            @include('admin.reports.comperative_financial_report.reports.top')
                        </div>
                    </div>
                    {{-- Balance Sheet REPORT --}}
                    @if ($data['is_balance_sheet']==true)
                    @include('admin.reports.comperative_financial_report.reports.balance-sheet')
                    @endif
                    <div class="page-break"></div>

                    {{-- Income statement REPORT --}}
                    @if ($data['is_incomestatment_note']==true)
                    @include('admin.reports.comperative_financial_report.reports.incomestatment_note')
                    @endif
                    <div class="page-break"></div>

                    {{--Details Balance Sheet REPORT --}}
                    @if ($data['is_details_balance_sheet']==true)
                    @include('admin.reports.comperative_financial_report.reports.details_balance_sheet')
                    @endif
                    <div class="page-break"></div>
                    {{--Details Balance Sheet REPORT --}}
                    @if ($data['is_trading_profit_loss']==true)
                    @include('admin.reports.comperative_financial_report.reports.trading_profit_loss')
                    @endif
                    <div class="page-break"></div>

                    {{--Trail Balance Sheet REPORT --}}
                    @if ($data['is_trial_balance']==true)
                    @include('admin.reports.comperative_financial_report.reports.trial_balance')
                    @endif

                    {{--statement_of_receipts_and_payments --}}
                    @if ($data['is_statement_of_receipts_and_payments']==true)
                    @include('admin.reports.comperative_financial_report.reports.statement_of_receipts_and_payments')
                    @endif
                    {{--Depriciations REPORT --}}
                    {{-- @if ($data['is_depreciation']==true)
                    @include('admin.reports.comperative_financial_report.reports.depreciation')
                    @endif --}}

                    {{--Notes to financial statements --}}
                    @if ($data['is_notes_to_financial_statements']==true)
                    @include('admin.reports.complete_financial_report.report.notes_to_financial_statements')
                    @endif
                    {{--Directors' report REPORT --}}
                    @if ($data['is_directors_report']==true)
                    @include('admin.reports.comperative_financial_report.reports.director_report')
                    @endif
                    {{--DIRECTORS' DECLARATION REPORT --}}
                    @if ($data['is_directors_declaration']==true)
                    @include('admin.reports.complete_financial_report.report.director_declaration')
                    @endif
                    {{--Audit REPORT --}}
                    @if ($data['is_audit_report']==true)
                    @include('admin.reports.complete_financial_report.report.audit')
                    @endif
                    {{--compilation REPORT --}}
                    @if ($data['is_compilation_report']==true)
                    @include('admin.reports.complete_financial_report.report.compilation')
                    @endif
                    <div class="row">
                        <div class="col-lg-3" style="margin: 0 auto;float: none;">
                            <table style="margin: 0 auto;">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <b>Powered by <a href="https://aarks.com.au">AARKS</a> <a
                                                    href="https://aarks.net.au">(ADVANCED ACCOUNTING & RECORD KEEPING
                                                    SOFTWARE)</a></b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
@stop
