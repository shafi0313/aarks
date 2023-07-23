<div class="reportH mt-5">
    <div class="text-center">
        <h3 class="company_name">{{$client->fullname}}</h3>
        <h5 class="report_name">STATEMENT OF CASH FLOW</h5>
        <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the year ended
            {{$data['date']->format('d-M-Y')}}</h5>
    </div>
    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 0 8px !important;

        }
    </style>

    <div class="row justify-content-center" style="margin-top: 35px;">
        <div class="col-md-12 cash_flow">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="center" style="width:30%"></td>
                        <td class="center" style="width:50%">Note</td>
                        <td class="center t-right" style="width:20%">{{$data['year']}}</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3">
                            <h5 style="font-weight: bold;">CASH FLOW FROM OPERATING ACTIVITIES</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Receipts from customers
                        </td>
                        <td></td>
                        <td class="text-right">
                            {{number_format($data['cfs_ledger']->debit, 2)}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Payments to suppliers and employees
                        </td>
                        <td></td>
                        <td class="text-right" style="border-bottom: 2px solid black">
                            {{number_format($data['cfs_ledger']->credit, 2)}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Net Cash provided by operating activities
                        </td>
                        <td></td>
                        <td class="text-right">
                            {{number_format($data['cfs_ledger']->debit - $data['cfs_ledger']->credit, 2)}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <h5 style="font-weight: bold;">CASH FLOW FROM FINANCIAL ACTIVITIES</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Net Increase in cash hand
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Cash at end of year</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
