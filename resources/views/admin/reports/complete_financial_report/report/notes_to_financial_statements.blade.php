<div class="reportH mt-5">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h3 class="company_name">{{$client->fullname}}</h3>
            <h4 class="company_name">
                ABN {{$client->abn_number}}
                {{ $client->charitable_number?', Charitable License Number '.$client->charitable_number:'' }}
                {{ $client->iran_number?', IRAN Number '.$client->iran_number:'' }}
            </h4>
            <h5 class="report_name">NOTES TO THE FINANCIAL STATEMENTS</h5>
            <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the year
                ended
                {{$data['date']->format('d-M-Y')}}</h5>
        </div> <br>
        <div class="col-12">
            <h4><b>NOTE 1: STATEMENT OF SIGNIFICANT ACCOUNTING POLICIES</b></h4>
            <table>
                <tr>
                    <td style="width: 30px; vertical-align: top">a.</td>
                    <td>
                        <p>This financial report is a accounting purpose financial report prepared for use by directors
                            and members of the
                            company. The directors have determined that the company is not a reporting entity.</p>
                        <p>The financial report has been prepared in accordance with the requirements of the following
                            Australian Accounting
                            Standards:</p>
                        <p>AASB 1031: Materiality <br>
                            AASB 110: Events after the Balance Sheet Date</p>
                        <p>The financial report is prepared on an accruals basis and is based on historic costs and does
                            not take into account
                            changing money values or, except where specifically stated, current valuations of
                            non-current assets.</p>
                        <p>The following specific accounting policies, which are consistent with the previous period
                            unless otherwise stated,
                            have been adopted in the preparation of this report:</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30px; vertical-align: top">b.</td>
                    <td>
                        <p><b>Property, Plant and Equipment</b></p>
                        <p>Property, plant and equipment are carried at cost, independent or directors' valuation. All
                            assets, excluding
                            freehold land and buildings, are depreciated over their useful lives to the company.</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30px; vertical-align: top">b.</td>
                    <td>
                        <p><b>Inventories</b></p>
                        <p>Inventories are measured at the lower of cost and net realizable value. Costs are assigned on
                            a first-in first-out
                            basis and include direct materials, direct labour and an appropriate proportion of variable
                            and fixed overhead
                            expenses.</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
