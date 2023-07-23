<div class="reportH mt-5">
    <div class="text-center">
        <h3 class="company_name">{{$client->fullname}}</h3>
        <h5 class="report_name">STATEMENT OF RECEIPTS AND PAYMENTS</h5>
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
        <div class="col-lg-2"></div>
        <div class="col-md-8 mx-auto  cash_flow">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="center" style="width:50%;text-align:center">Cheque Account</td>
                        <td class="center" style="width:20%">{{$data['year']}}</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Opening Account Balance
                        </td>
                        <td class="text-right">
                            @php($opening_bl = $data['srp_opening_ledger']->debit - $data['srp_opening_ledger']->credit)
                            {{number_format($opening_bl, 2)}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            RECEIPTS
                        </td>
                        <td class="text-right">
                            {{number_format($data['srp_ledger']->debit, 2)}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>
                            Total Receipts
                        </td>
                        <td class="text-right">
                            <span style="border-top:1px solid">
                            {{number_format($opening_bl + $data['srp_ledger']->debit, 2)}}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            PAYMENTS
                        </td>
                        <td class="text-right">
                            {{number_format($data['srp_ledger']->credit, 2)}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>
                            Total Payments
                        </td>
                        <td class="text-right">
                            <span style="border-top:1px solid">
                            {{number_format($data['srp_ledger']->credit, 2)}}
                            </span>
                        </td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>
                            Closing Account Balance
                        </td>
                        <td class="text-right">
                            <span style="border-bottom-style:double;border-top:1px solid">
                                {{number_format($opening_bl + $data['srp_ledger']->debit - $data['srp_ledger']->credit,
                                2)}}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
