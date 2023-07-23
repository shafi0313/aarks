<div class="reportH mt-5">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h3 class="company_name">{{$client->fullname}}</h3>
            <h4 class="company_name">ABN {{$client->abn_number}}</h4>
            <h5 class="report_name">INDEPENDENT AUDIT REPORT TO THE MEMBERS</h5>
            {{-- <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the
                year
                ended
                {{$data['date']->format('d-M-Y')}}</h5> --}}
        </div> <br>
        <div class="col-12">
            <p><b>Scope</b></p>
            <p>We have audited the attached financial report, being a accounting purpose financial report of
                {{$client->fullname}} for the year
                ended {{$data['date']->format('d/m/Y')}}. The company's directors are responsible for the financial
                report
                and have determined that the
                accounting policies used and described in Note 1 to the financial statements which form part of the
                financial report are
                consistent with the financial reporting requirements of the company's constitution and are appropriate
                to
                meet the needs
                of the members. We have conducted an independent audit of the financial report in order to express an
                opinion on it to
                the members of {{$client->fullname}} . No opinion is expressed as to whether the accounting policies
                used
                are appropriate to
                the needs of the members.</p>
            <p>The financial report has been prepared for distribution to members for the purpose of fulfilling the
                directors' financial
                reporting requirements under the company's constitution. We disclaim any assumption of responsibility
                for
                any reliance
                on this report or on the financial report to which it relates to any person other than the members, or
                for
                any purpose
                other than that for which it was prepared.</p>
            <p>Our audit has been conducted in accordance with Australian Auditing Standards. Our procedures included
                examination,
                on a test basis, of evidence supporting the amounts and other disclosures in the financial report, and
                the
                evaluation of
                significant accounting estimates. These procedures have been undertaken to form an opinion whether, in
                all
                material
                respects, the financial report is presented fairly in accordance with accounting policies described in
                Note
                1, so as to
                present a view which is consistent with our understanding of the company's financial position, and
                performance as
                represented by the results of its operations and its cash flows. These policies do not require the
                application of all
                Accounting Standards and other mandatory professional reporting requirements in Australia.</p>
            <p>The audit opinion expressed in this report has been formed on the above basis.</p> <br>
            <p style="font-weight:bold">Audit Opinion</p>
            <p>In our opinion, the financial report presents fairly in accordance with the
                accounting
                policies described in Note 1
                to the financial statements, the financial position of {{$client->fullname}} as at
                {{$data['date']->format('d-M-Y')}} and the results of its
                operations and its cash flows for the year then ended.</p>
            <p style="font-weight:bold">Name of Firm Services</p>
            <p>Auditor Signature <br>
                Name of Auditor:{{$client->auditor_name}} <br>
                Dated : {{$data['date']->format('d-M-Y')}}</p>
            <p style="text-align: center">The accompanying notes form part of these financial
                statements. These statements should be
                read in conjunction with the attached compilation report.</p>
        </div>
    </div>
</div>
