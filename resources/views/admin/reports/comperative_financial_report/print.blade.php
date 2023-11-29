<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comparative Financial Report - {{ now() }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    @include('frontend.print-css')
    <style>
        /* .reportH {
            margin: 50px 0;
        } */

        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 0 8px;
            border: none !important;
        }

        .table {
            border: none !important;
        }

        .t-right {
            text-align: right !important;
        }

        .dep-tbl thead tr th,
        .dep-tbl thead tr td {
            font-size: 12px !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid">

        <!-- PAGE CONTENT BEGINS -->
        <div class="row" style="height:100vh;display:flex;align-items:center;justify-content:center;">
            <div class="col-md-12 text-center">
                <h1>{{ clientName($client) }}</h1>
                <h4>ABN {{ $client->abn_number }}</h4>
                <br>

                <h2>FINANCIAL REPORT</h2>
                <h3 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">
                    For the year ended {{ $data['date']->format('d-M-Y') }}</h3>
                <br>
                <br>
                <h4>Prepared by</h4>
                <h4>{{ $client->agent_name }}</h4>
                <h5>{{ $client->agent_address }}</h5>
            </div>
        </div>
        <div class="page-break"></div>
        
        <div class="row" style="height:100vh;display:flex;align-items:center;justify-content:center;">
            <div class="col-md-12 text-center">
                <h1>{{ clientName($client) }}</h1>
                <h4>ABN {{ $client->abn_number }}</h4>
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

                <ul style="list-style: none;">
                    <li>{{ $data['is_balance_sheet'] == true ? 'Balance Sheet' : '' }}</li>
                    <li>{{ $data['is_incomestatment_note'] == true ? $data['incomestatment_note'] : '' }}</li>
                    <li>{{ $data['is_details_balance_sheet'] == true ? $data['details_balance_sheet'] : '' }}</li>
                    <li>{{ $data['is_trading_profit_loss'] == true ? $data['trading_profit_loss'] : '' }}</li>
                    <li>{{ $data['is_trial_balance'] == true ? $data['trial_balance'] : '' }}</li>
                    <li>{{ $data['is_cash_flow_statement'] == true ? $data['cash_flow_statement'] : '' }}</li>
                    <li>{{ $data['is_statement_of_receipts_and_payments'] == true ? $data['statement_of_receipts_and_payments'] : '' }}
                    </li>
                    <li>{{ $data['is_statement_of_chanes_in_equity'] == true ? $data['statement_of_chanes_in_equity'] : '' }}
                    </li>
                    <li>{{ $data['is_depreciation'] == true ? $data['depreciation'] : '' }}</li>
                    <li>{{ $data['is_notes_to_financial_statements'] == true ? $data['notes_to_financial_statements'] : '' }}
                    </li>
                    <li>{{ $data['is_directors_report'] == true ? $data['directors_report'] : '' }}</li>
                    <li>{{ $data['is_directors_declaration'] == true ? $data['directors_declaration'] : '' }}</li>
                    <li>{{ $data['is_audit_report'] == true ? $data['audit_report'] : '' }}</li>
                    <li>{{ $data['is_compilation_report'] == true ? $data['compilation_report'] : '' }}</li>
                    <li>{{ $data['is_contents'] == true ? $data['contents'] : '' }}</li>
                </ul>

            </div>
        </div>
        {{-- Balance Sheet REPORT --}}
        @if ($data['is_balance_sheet'] == true)
            @include('admin.reports.comperative_financial_report.reports.balance-sheet')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif

        {{-- Income statement REPORT --}}
        @if ($data['is_incomestatment_note'] == true)
            @include('admin.reports.comperative_financial_report.reports.incomestatment_note')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif

        {{-- Details Balance Sheet REPORT --}}
        @if ($data['is_details_balance_sheet'] == true)
            @include('admin.reports.comperative_financial_report.reports.details_balance_sheet')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif
        {{-- Details Balance Sheet REPORT --}}
        @if ($data['is_trading_profit_loss'] == true)
            @include('admin.reports.comperative_financial_report.reports.trading_profit_loss')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif

        {{-- Trail Balance Sheet REPORT --}}
        @if ($data['is_trial_balance'] == true)
            @include('admin.reports.comperative_financial_report.reports.trial_balance')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif

        {{-- statement_of_receipts_and_payments --}}
        @if ($data['is_statement_of_receipts_and_payments'] == true)
            @include('admin.reports.comperative_financial_report.reports.statement_of_receipts_and_payments')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif
        {{-- Depriciations REPORT --}}
        {{-- @if ($data['is_depreciation'] == true)
            @include('admin.reports.comperative_financial_report.reports.depreciation')
            <div class="page-break"></div>
            @endif --}}

        {{-- Notes to financial statements --}}
        @if ($data['is_notes_to_financial_statements'] == true)
            @include('admin.reports.complete_financial_report.report.notes_to_financial_statements')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif
        {{-- Directors' report REPORT --}}
        @if ($data['is_directors_report'] == true)
            @include('admin.reports.complete_financial_report.report.director_report')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif
        {{-- DIRECTORS' DECLARATION REPORT --}}
        @if ($data['is_directors_declaration'] == true)
            @include('admin.reports.complete_financial_report.report.director_declaration')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif
        {{-- Audit REPORT --}}
        @if ($data['is_audit_report'] == true)
            @include('admin.reports.complete_financial_report.report.audit')
            @include('admin.reports.financial-report-footer')
            <div class="page-break"></div>
        @endif
        {{-- compilation REPORT --}}
        @if ($data['is_compilation_report'] == true)
            @include('admin.reports.complete_financial_report.report.compilation')
            @include('admin.reports.financial-report-footer')
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
    </div>
    </div>
    </div>
    <script>
        document.getElementsByClassName('grand_total')[0].innerHTML = "Total";
        document.getElementsByClassName('grand_total')[1].innerHTML = "Total";
    </script>
</body>

</html>
