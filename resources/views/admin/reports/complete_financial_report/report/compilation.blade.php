<div class="reportH mt-5">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h3 class="company_name">{{$client->fullname}}</h3>
            <h4 class="company_name">ABN {{$client->abn_number}}</h4>
            <h5 class="report_name">COMPILATION REPORT TO {{$client->fullname}}</h5>
            {{-- <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">For the
                year ended
                {{$data['date']->format('d-M-Y')}}</h5> --}}
        </div> <br>
        <div class="col-12">
            <p>
                We have compiled the accompanying accounting purpose financial statements of {{$client->fullname}} ,
                which comprise
                the
                balance sheet as at {{$data['date']->format('d/m/Y')}}, the statement of profit or loss for the year
                ended, a summary of
                significant
                accounting policies and other explanatory notes. The specific purpose for which the accounting purpose
                financial
                statements have been prepared is set out in Note 1 to the financial statements.
            </p><br>
            <p><b>The Responsibility of the Directors</b></p>
            <p>The directors of {{$client->fullname}} are solely responsible for the information contained in the
                accounting purpose financial
                statements, the reliability, accuracy and completeness of the information and for the determination that
                the significant
                accounting policies used are appropriate to meet their needs and for the purpose that the financial
                statements were
                prepared.</p> <br>
            <p><b>Our Responsibility</b></p>
            <p>On the basis of information provided by the directors, we have compiled the accompanying accounting
                purpose financial
                statements in accordance with the significant accounting policies as described in Note 1 to the
                financial statements and
                APES 315:Compilation of Financial Information. We have applied our expertise in accounting and financial
                reporting to
                compile these financial statements in accordance with the significant accounting policies described in
                Note 1 to the
                financial statements. We have complied with the relevant ethical requirements of APES 110:Code of Ethics
                for
                Professional Accountants.</p><br>
            <p><b>Assurance Disclaimer</b></p>
            <p>Since a compilation engagement is not an assurance engagement, we are not required to verify the
                reliability, accuracy
                or completeness of the information provided to us by management to compile these financial statements.
                Accordingly,
                we do not express an audit opinion or a review conclusion on these financial statements.</p><br>
            <p>The accounting purpose financial statements were compiled exclusively for the benefit of the directors
                who are responsible
                for the reliability, accuracy and completeness of the information used to compile them. We do not accept
                responsibility
                for the contents of the accounting purpose financial statements.</p>
            <br><br>

            <p>Name of Accountant &nbsp; &nbsp; &nbsp; <b>{{$client->agent_name}}</b></p>
            <p style="width: 400px;border-top: 2px solid;">
                On behalf of &nbsp; &nbsp; &nbsp; &nbsp;
                <b>{{ $client->agent_name }}</b> <br>
                {{ $client->agent_address }}
            </p>
            <p>Dated: {{now()->format('d/m/Y')}}</p>
        </div>
    </div>
</div>
