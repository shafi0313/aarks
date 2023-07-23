<div class="reportH mt-5">
    <div class="row justify-content-center">
    <div class="col-12 text-center">
        <h3 class="company_name">{{$client->fullname}}</h3>
        <h5 class="report_name">Directors' report</h5>
        <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">
            For the year ended {{$data['date']->format('d-M-Y')}}</h5>
    </div>

        <div class="col-12 card" style="padding-top:40px; ">
            <div>
                <p>Your directors present their report on the company and its controlled entity for
                    the
                    financial year ended {{$data['date']->format('d-M-Y')}}.</p>
                <p>The names of the directors in office at the date of this report are:</p>
                <p>Director:{{$client->director_name}}</p>
                <p>Directors have been in office since the start of the financial year to the date
                    of
                    this report unless otherwise
                    stated.</p>
                <p>The profit of the economic entity for the financial year after providing for
                    income
                    tax amounted to $ {{number_format($data['directors_pl']??0,2)}}</p>
                <p>A review of the operations of the economic entity during the financial year and
                    the
                    results of those operations
                    found that the changes in market demand and competition have seen an increase in
                    sales of nan% to $0.00. The
                    increase in sales has contributed to an increase in the economic entity 's
                    operating
                    profit before tax.</p>
                <p>No significant changes in the economic entity's state of affairs occurred during
                    the
                    financial year.</p>
                <p>The principal activities of the economic entity during the financial year were
                    "--".
                </p>
                <p>No significant change in the nature of these activities occurred during the year.
                </p>
                <p>No matters or circumstances have arisen since the end of the financial year which
                    significantly affected or may
                    significantly affect the operations of the economic entity, the results of those
                    operations, or the state of
                    affairs of the economic entity in future financial years.</p>
                <p>Likely developments in the operations of the economic entity and the expected
                    results
                    of those operations in
                    future financial years have not been included in this report as the inclusion of
                    such information is likely to
                    result in unreasonable prejudice to the economic entity.</p>
                <p>The economic entity ' s operations are not regulated by any significant
                    environmental
                    regulation under a law of
                    the Commonwealth or of a state or territory.</p>
                <p> No dividends were declared or paid since the start of the financial year. No
                    recommendation for payment of
                    dividends has been made.</p>
                <p>No indemnities have been given or insurance premiums paid, during or since the
                    end of
                    the financial year, for any
                    person who is or has been an officer or auditor of the economic entity.</p>
                <p>No person has applied for leave of Court to bring proceedings on behalf of the
                    company or intervene in any
                    proceedings to which the company is a party for the purpose of taking
                    responsibility
                    on behalf of the company
                    for all or any part of those proceedings.</p>
                <p>The company was not a party to any such proceedings during the year.</p>
                <p>Auditor's Independence Declaration</p>
                <p>A copy of the auditor's independence declaration as required under section 307C
                    of
                    the Corporations Act 2001 is
                    set out further in the report.</p>
                <p>Signed in accordance with a resolution of the Board of Directors:</p>
                <p>b. Reconciliation of CashFlow from Operations with Profit from Ordinary
                    Activities
                    after Income Tax</p><br><br>
                @if ($data['is_notes_to_financial_statements']==true)
                @include('admin.reports.complete_financial_report.report.report_footer_note')
                @endif
                <p>Dated : {{$data['date']->format('d-M-Y')}}</p>
            </div>
        </div>
    </div>
</div>
