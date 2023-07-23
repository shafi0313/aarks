<div class="reportH mt-5">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h3 class="company_name">{{$client->fullname}}</h3>
            <h5 class="report_name">DIRECTORS' DECLARATION</h5>
            <h5 style="border-bottom:1px solid black;display:inline-block; padding-bottom:2px;margin:0">
                For the year ended {{$data['date']->format('d-M-Y')}}</h5>
        </div>
        <div class="col-12 card" style="padding-top:40px; ">
            <p>The directors have determined that the company is not a reporting entity. The
                directors
                have determined that this
                accounting purpose financial report should be prepared in accordance with the
                accounting
                policies outlined in Note 1 to
                the financial statements.</p>
            <p>The directors of the company declare that:</p>
            <p>1. the financial statements and notes attached present fairly the company's financial
                position as at {{$data['date']->format('d-M-Y')}} and
                its performance for the year ended on that date in accordance with the accounting
                policies described in Note 1 to
                the financial statements;</p>
            <p>2. in the directors' opinion there are reasonable grounds to believe that the company
                will be able to pay its debts
                as and when they become due and payable.
                This declaration is made in accordance with a resolution of the Board of Directors:
            </p>
            <p>Director : {{$client->director_name}}</p>
            <p>Dated : {{$data['date']->format('d-M-Y')}}</p><br>
            @if ($data['is_notes_to_financial_statements']==true)
            @include('admin.reports.complete_financial_report.report.report_footer_note')
            @endif
        </div>
    </div>
</div>
