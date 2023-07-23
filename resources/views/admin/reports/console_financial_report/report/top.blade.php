<ul style="list-style: none;">
    <li>{{$data['is_balance_sheet']==true?'Balance Sheet':''}}</li>
    <li>{{$data['is_incomestatment_note']==true?$data['incomestatment_note']:''}}</li>
    <li>{{$data['is_details_balance_sheet']==true?$data['details_balance_sheet']:''}}</li>
    <li>{{$data['is_trading_profit_loss']==true?$data['trading_profit_loss']:''}}</li>
    <li>{{$data['is_trial_balance']==true?$data['trial_balance']:''}}</li>
    <li>{{$data['is_cash_flow_statement']==true?$data['cash_flow_statement']:''}}</li>
    <li>{{$data['is_statement_of_receipts_and_payments']==true?$data['statement_of_receipts_and_payments']:''}}
    </li>
    <li>{{$data['is_statement_of_chanes_in_equity']==true?$data['statement_of_chanes_in_equity']:''}}
    </li>
    <li>{{$data['is_depreciation']==true?$data['depreciation']:''}}</li>
    <li>{{$data['is_notes_to_financial_statements']==true?$data['notes_to_financial_statements']:''}}
    </li>
    <li>{{$data['is_directors_report']==true?$data['directors_report']:''}}</li>
    <li>{{$data['is_directors_declaration']==true?$data['directors_declaration']:''}}</li>
    <li>{{$data['is_audit_report']==true?$data['audit_report']:''}}</li>
    <li>{{$data['is_compilation_report']==true?$data['compilation_report']:''}}</li>
    <li>{{$data['is_contents']==true?$data['contents']:''}}</li>
</ul>
