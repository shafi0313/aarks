@extends('frontend.layout.master')
@section('title', 'Comperative Financial Report')
@section('content')
<?php $p = 'cconfr';
    $mp = 'advr'; ?>
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
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            {{-- <a target="_blank" onclick="print()" class="btn btn-info">PRINT</a> --}}
            <a target="_blank"
                href="{{ request()->fullUrl().'&print=true' }}"
                class="btn btn-info pull-right">PRINT</a>
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
                    <h4>Prepared by <br>
                        {{$client->agent_name}}
                    </h4>
                    {{$client->agent_address}}
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
                    @include('admin.reports.console_financial_report.report.top')
                </div>
            </div>
            {{-- Balance Sheet REPORT --}}
            @if ($data['is_balance_sheet']==true)
            @include('admin.reports.console_financial_report.report.balance-sheet')
            @endif
            {{-- Income statement REPORT --}}
            @if ($data['is_incomestatment_note']==true)
            @include('admin.reports.console_financial_report.report.incomestatment_note')
            @endif
            {{--Details Balance Sheet REPORT --}}
            @if ($data['is_details_balance_sheet']==true)
            @include('admin.reports.console_financial_report.report.details_balance_sheet')
            @endif
            {{--Details Balance Sheet REPORT --}}
            @if ($data['is_trading_profit_loss']==true)
            @include('admin.reports.console_financial_report.report.trading_profit_loss')
            @endif
            {{--Trail Balance Sheet REPORT --}}
            @if ($data['is_trial_balance']==true)
            @include('admin.reports.console_financial_report.report.trial_balance')
            @endif
            {{--cash_flow_statement REPORT --}}
            @if ($data['is_cash_flow_statement']==true)
            @include('admin.reports.console_financial_report.report.cash_flow_statement')
            @endif
            {{--statement_of_receipts_and_payments --}}
            @if ($data['is_statement_of_receipts_and_payments']==true)
            @include('admin.reports.console_financial_report.report.statement_of_receipts_and_payments')
            @endif
            {{--Depriciations REPORT --}}
            @if ($data['is_depreciation']==true)
            @include('admin.reports.console_financial_report.report.depreciation')
            @endif

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
                <div class="col-lg-12 text-center">
                    <p><b>Powered by <a href="https://aarks.com.au">AARKS</a> <a href="https://aarks.net.au">(ADVANCED
                                ACCOUNTING & RECORD KEEPING SOFTWARE)</a></b></p>
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>
@stop
