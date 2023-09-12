<section class="bg-light mb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-md navbar-light" id="navbar">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            @php
                                // $client = \App\Models\Client::with([
                                //     'paylist' => function ($q) {
                                //         $q->where('is_expire', 0);
                                //     },
                                //     'invoiceLayout',
                                // ])->findOrFail(client()->id);
                                
                                $client = \App\Models\Client::with([
                                    'paymentList' => function ($q) {
                                        $q->select('id', 'client_id', 'subscription_id', 'is_expire', 'status')->where('is_expire', 0);
                                    },
                                    'invoiceLayout',
                                    'paymentList.subscription' => fn($q) => $q->select('id', 'amount'),
                                ])
                                    ->select('id')
                                    ->findOrFail(client()->id);
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link text-primary" style="font-size: 15px" href="{{ route('index') }}"
                                    title="Home/Dashboard"><i class="fa-solid fa-house-chimney"></i></a>
                            </li>
                            @if ($client->paymentList && $client->paymentList->count() > 0)
                                @if ($client->paymentList->first()->status != 0)
                                    @if ($client->invoiceLayout != '')
                                        @if ($client->invoiceLayout->layout == 2)
                                            <li class="nav-item menu_dropdown">
                                                <a class="nav-link menu_dropdown-toggle {{ $mp == 'sales' ? 'active' : '' }}"
                                                    href="#" id="navbarmenu_dropdown" role="button"
                                                    data-toggle="menu_dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Sales<i
                                                        class="fa-solid fa-angle-down"></i></a>
                                                <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                                    <a class="menu_dropdown-item {{ $p == 'cquote_item' ? 'active' : '' }}"
                                                        href="{{ route('quote_item.index') }}">Create Order</a>
                                                    <a class="menu_dropdown-item {{ $p == 'cquote_itemp' ? 'active' : '' }}"
                                                        href="{{ route('quote_item.manage') }}">Edit/Print/E-mail
                                                        Order</a>
                                                    <a class="menu_dropdown-item {{ $p == 'qci' ? 'active' : '' }}"
                                                        href="{{ route('quote_item.convert') }}">Convert to Invoice</a>
                                                    <a class="menu_dropdown-item {{ $p == 'invoice' ? 'active' : '' }}"
                                                        href="{{ route('invoice_item.index') }}">Create Invoice (Enter
                                                        Sales)</a>
                                                    <a class="menu_dropdown-item {{ $p == 'invoicep' ? 'active' : '' }}"
                                                        href="{{ route('invoice_item.manage') }}">Edit/Print/Email
                                                        Invoice</a>
                                                    <a class="menu_dropdown-item {{ $p == 'rs' ? 'active' : '' }}"
                                                        href="{{ route('recurring_item.index') }}">Recurring Sales</a>
                                                    <a class="menu_dropdown-item {{ $p == 'rl' ? 'active' : '' }}"
                                                        href="{{ route('recurring_item.manage') }}">Recurring List</a>
                                                    <a class="menu_dropdown-item {{ $p == 'payment' ? 'active' : '' }}"
                                                        href="{{ route('payment.index') }}">Receive Payment</a>
                                                    <a class="menu_dropdown-item {{ $p == 'paylist' ? 'active' : '' }}"
                                                        href="{{ route('payment.list') }}">Print/Email Receipt</a>
                                                    <a class="menu_dropdown-item {{ $p == 'salreg' ? 'active' : '' }}"
                                                        href="{{ route('salesRegIndex') }}">Sales Register</a>
                                                    <a class="menu_dropdown-item {{ $p == 'cled' ? 'active' : '' }}"
                                                        href="{{ route('cledger.index') }}">Customer Ledger</a>
                                                    <a class="menu_dropdown-item {{ $p == 'debrep' ? 'active' : '' }}"
                                                        href="{{ route('debtors_report.index') }}">Debtors Report</a>
                                                </div>
                                            </li>

                                            <li class="nav-item menu_dropdown">
                                                <a class="nav-link menu_dropdown-toggle {{ $mp == 'purchase' ? 'active' : '' }}"
                                                    href="#" id="navbarmenu_dropdown" role="button"
                                                    data-toggle="menu_dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Purchase<i
                                                        class="fa-solid fa-angle-down"></i></a>
                                                <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                                    <a class="menu_dropdown-item {{ $p == 'so' ? 'active' : '' }}"
                                                        href="{{ route('service_item.index') }}">Service Order</a>
                                                    <a class="menu_dropdown-item {{ $p == 'sol' ? 'active' : '' }}"
                                                        href="{{ route('service_item.manage') }}">Edit Print Email
                                                        Order</a>
                                                    <a class="menu_dropdown-item {{ $p == 'socb' ? 'active' : '' }}"
                                                        href="{{ route('service_item.convert') }}">Order Convert to
                                                        Bill</a>
                                                    <a class="menu_dropdown-item {{ $p == 'eb' ? 'active' : '' }}"
                                                        href="{{ route('enter_item.index') }}">Enter Bill</a>
                                                    <a class="menu_dropdown-item {{ $p == 'epeb' ? 'active' : '' }}"
                                                        href="{{ route('enter_item.manage') }}">Edit Print Email
                                                        Bill</a>
                                                    <a class="menu_dropdown-item {{ $p == 'bp' ? 'active' : '' }}"
                                                        href="{{ route('spayment.index') }}">Bill
                                                        Payment</a>
                                                    <a class="menu_dropdown-item {{ $p == 'peb' ? 'active' : '' }}"
                                                        href="{{ route('spayment.list') }}">Print/Email Bill
                                                        Payment</a>
                                                    <a class="menu_dropdown-item  {{ $p == 'pr' ? 'active' : '' }}"
                                                        href="{{ route('purchaseRegIndex') }}">Purchase Register</a>
                                                    <a class="menu_dropdown-item {{ $p == 'sl' ? 'active' : '' }}"
                                                        href="{{ route('sledger.index') }}">Supplier Ledger</a>
                                                    <a class="menu_dropdown-item {{ $p == 'suprep' ? 'active' : '' }}"
                                                        href="{{ route('creditor_report.index') }}">Supplier Report</a>
                                                </div>
                                            </li>
                                            <li class="nav-item menu_dropdown">
                                                <a class="nav-link menu_dropdown-toggle {{ $mp == 'inventory' ? 'active' : '' }}"
                                                    href="#" id="navbarmenu_dropdown" role="button"
                                                    data-toggle="menu_dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Inventory<i
                                                        class="fa-solid fa-angle-down"></i></a>
                                                <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                                    <a class="menu_dropdown-item {{ $p == 'invCat' ? 'active' : '' }}"
                                                        href="{{ route('inv_category.index') }}">Add/Edit Category, Sub Category</a>
                                                    <a class="menu_dropdown-item {{ $p == 'invAdd' ? 'active' : '' }}"
                                                        href="{{ route('inv_item.index') }}">Add Item</a>
                                                    <a class="menu_dropdown-item {{ $p == 'invEdit' ? 'active' : '' }}"
                                                        href="{{ route('inv_item.listItem') }}">Item List/Edit
                                                        Item</a>
                                                    <a class="menu_dropdown-item {{ $p == 'sl' ? 'active' : '' }}"
                                                        href="#">Adjust
                                                        Item</a>
                                                    <a class="menu_dropdown-item {{ $p == 'invReg' ? 'active' : '' }}"
                                                        href="{{ route('invRegister') }}">Inventory Register</a>
                                                    <a class="menu_dropdown-item {{ $p == 'indvReg' ? 'active' : '' }}"
                                                        href="{{ route('invRegister') }}">Stock Summery</a>
                                                </div>
                                            </li>
                                        @else
                                            <li class="nav-item menu_dropdown">
                                                <a class="nav-link menu_dropdown-toggle {{ $mp == 'sales' ? 'active' : '' }}"
                                                    href="#" id="navbarmenu_dropdown" role="button"
                                                    data-toggle="menu_dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Sales<i
                                                        class="fa-solid fa-angle-down"></i></a>
                                                <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                                    {{-- <a class="menu_dropdown-item {{$p=='upr'?'active':''}}"
                                href="{{ route('upgrade') }}">Update
                                Plan Request</a> --}}
                                                    <a class="menu_dropdown-item {{ $p == 'cquote' ? 'active' : '' }}"
                                                        href="{{ route('quote.index') }}">Create Quote</a>
                                                    <a class="menu_dropdown-item {{ $p == 'cquotep' ? 'active' : '' }}"
                                                        href="{{ route('quote.manage') }}">Edit/Print/E-mail
                                                        Quote</a>
                                                    <a class="menu_dropdown-item {{ $p == 'qci' ? 'active' : '' }}"
                                                        href="{{ route('quote.convert') }}">Quote
                                                        Convert to Invoice</a>
                                                    <a class="menu_dropdown-item {{ $p == 'invoice' ? 'active' : '' }}"
                                                        href="{{ route('invoice.index') }}">Create Invoice (Enter
                                                        Sales)</a>
                                                    <a class="menu_dropdown-item {{ $p == 'invoicep' ? 'active' : '' }}"
                                                        href="{{ route('invoice.manage') }}">Edit/Print/Email
                                                        Invoice</a>
                                                    <a class="menu_dropdown-item {{ $p == 'rs' ? 'active' : '' }}"
                                                        href="{{ route('recurring.index') }}">Recurring Sales</a>
                                                    <a class="menu_dropdown-item {{ $p == 'rl' ? 'active' : '' }}"
                                                        href="{{ route('recurring.manage') }}">Recurring List</a>
                                                    <a class="menu_dropdown-item {{ $p == 'payment' ? 'active' : '' }}"
                                                        href="{{ route('payment.index') }}">Receive Payment</a>
                                                    <a class="menu_dropdown-item {{ $p == 'paylist' ? 'active' : '' }}"
                                                        href="{{ route('payment.list') }}">Print/Email Receipt</a>
                                                    <a class="menu_dropdown-item {{ $p == 'salreg' ? 'active' : '' }}"
                                                        href="{{ route('salesRegIndex') }}">Sales
                                                        Register</a>
                                                    <a class="menu_dropdown-item {{ $p == 'cled' ? 'active' : '' }}"
                                                        href="{{ route('cledger.index') }}">Customer Ledger</a>
                                                    <a class="menu_dropdown-item {{ $p == 'debrep' ? 'active' : '' }}"
                                                        href="{{ route('debtors_report.index') }}">Debtors Report</a>
                                                </div>
                                            </li>

                                            <li class="nav-item menu_dropdown">
                                                <a class="nav-link menu_dropdown-toggle {{ $mp == 'purchase' ? 'active' : '' }}"
                                                    href="#" id="navbarmenu_dropdown" role="button"
                                                    data-toggle="menu_dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Purchase<i
                                                        class="fa-solid fa-angle-down"></i></a>
                                                <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                                    <a class="menu_dropdown-item {{ $p == 'so' ? 'active' : '' }}"
                                                        href="{{ route('service_order.index') }}">Service Order</a>
                                                    <a class="menu_dropdown-item {{ $p == 'sol' ? 'active' : '' }}"
                                                        href="{{ route('service_order.manage') }}">Edit Print Email
                                                        Order</a>
                                                    <a class="menu_dropdown-item {{ $p == 'socb' ? 'active' : '' }}"
                                                        href="{{ route('service_order.convert') }}">Order Convert to
                                                        Bill</a>
                                                    <a class="menu_dropdown-item {{ $p == 'eb' ? 'active' : '' }}"
                                                        href="{{ route('service_bill.index') }}">Enter Bill</a>
                                                    <a class="menu_dropdown-item {{ $p == 'epeb' ? 'active' : '' }}"
                                                        href="{{ route('service_bill.manage') }}">Edit Print Email
                                                        Bill</a>
                                                    <a class="menu_dropdown-item {{ $p == 'bp' ? 'active' : '' }}"
                                                        href="{{ route('spayment.index') }}">Bill
                                                        Payment</a>
                                                    <a class="menu_dropdown-item {{ $p == 'peb' ? 'active' : '' }}"
                                                        href="{{ route('spayment.list') }}">Print/Email Bill
                                                        Payment</a>
                                                    <a class="menu_dropdown-item {{ $p == 'pr' ? 'active' : '' }}"
                                                        href="{{ route('purchaseRegIndex') }}">Purchase Register</a>
                                                    <a class="menu_dropdown-item {{ $p == 'sl' ? 'active' : '' }}"
                                                        href="{{ route('sledger.index') }}">Supplier Ledger</a>
                                                    <a class="menu_dropdown-item {{ $p == 'suprep' ? 'active' : '' }}"
                                                        href="{{ route('creditor_report.index') }}">Supplier
                                                        Report</a>
                                                </div>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- <li class="nav-item menu_dropdown">
                        <a class="nav-link menu_dropdown-toggle {{$mp=='payroll'?'active':''}}" href="#"
                            id="navbarmenu_dropdown" role="button" data-toggle="menu_dropdown" aria-haspopup="true"
                            aria-expanded="false">Payroll</a>
                        <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                            <a class="menu_dropdown-item {{$p=='pp'?'active':''}}"
                                href="{{ route('prepayroll.index') }}">Prepare Payroll</a>
                            <a class="menu_dropdown-item {{$p=='mw'?'active':''}}"
                                href="{{ route('wages.index') }}">Manage
                                Wages</a>
                            <a class="menu_dropdown-item {{$p=='ms'?'active':''}}"
                                href="{{ route('clientannuation.index') }}">Manage Super</a>
                            <a class="menu_dropdown-item {{$p=='md'?'active':''}}"
                                href="{{ route('clientdeduction.index') }}">Manage Deducation</a>
                            <a class="menu_dropdown-item {{$p=='ec'?'active':''}}"
                                href="{{ route('classification.index') }}">Employment Classification</a>
                            <a class="menu_dropdown-item {{$p=='pep'?'active':''}}"
                                href="{{ route('payslip.index') }}">Print/Email Payslip</a>
                            <a class="menu_dropdown-item {{$p=='sda'?'active':''}}"
                                href="{{ route('SendDataAto.index') }}">Send Data ATO (STP)</a>
                            <a class="menu_dropdown-item {{$p=='upd'?'active':''}}"
                                href="{{ route('SendDataAto.list') }}">Update STP Data to ATO</a>
                            <a class="menu_dropdown-item {{$p=='peps'?'active':''}}"
                                href="{{ route('payrollPeridocSummerySelectActivity') }}">Print/Emai
                                Periodic
                                Summary</a>
                            <a class="menu_dropdown-item" href="#">Print Payment Summaries(PAYG)</a>
                            <a class="menu_dropdown-item" href="#">Annual Summary Report</a>
                            <a class="menu_dropdown-item" href="#">Payroll Year Closing</a>
                            <a class="menu_dropdown-item" href="#">Year Closed Data</a>
                        </div>
                    </li> --}}

                                    <li class="nav-item menu_dropdown">
                                        <a class="nav-link menu_dropdown-toggle {{ $mp == 'acccounts' ? 'active' : '' }}"
                                            href="#" id="navbarmenu_dropdown" role="button"
                                            data-toggle="menu_dropdown" aria-haspopup="true"
                                            aria-expanded="false">Accounts<i class="fa-solid fa-angle-down"></i></a>
                                        <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                            <a class="menu_dropdown-item {{ $p == 'ac' ? 'active' : '' }}"
                                                href="{{ route('account_chart_select_activity') }}">Account Chart</a>
                                            <a class="menu_dropdown-item {{ $p == 'aep' ? 'active' : '' }}"
                                                href="{{ route('adtperiod.index') }}">Add/Edit Entry</a>
                                            <a class="menu_dropdown-item {{ $p == 'cb' ? 'active' : '' }}"
                                                href="{{ route('cashbook.index') }}">Cash
                                                Book</a>
                                            <a class="menu_dropdown-item {{ $p == 'cbr' ? 'active' : '' }}"
                                                href="{{ route('cashbook.reportActivity') }}">Cash Book Report</a>
                                            <a class="menu_dropdown-item {{ $p == 'cje' ? 'active' : '' }}"
                                                href="{{ route('client.je_profession') }}">Journal Entry</a>
                                            <a class="menu_dropdown-item {{ $p == 'cjl' ? 'active' : '' }}"
                                                href="{{ route('client.jl_profession') }}">Journal List</a>
                                            <a class="menu_dropdown-item {{ $p == 'cdep' ? 'active' : '' }}"
                                                href="{{ route('client.dep_index') }}">Deprecation</a>
                                            <a class="menu_dropdown-item {{ $p == 'cbr' ? 'active' : '' }}"
                                                href="{{ route('cashbook.reportActivity') }}">Verify & fixed
                                                trans</a>
                                            <a class="menu_dropdown-item {{ $p == 'prb' ? 'active' : '' }}"
                                                href="{{ route('client-budget.index') }}">Prepare Budget</a>
                                        </div>
                                    </li>

                                    <li class="nav-item menu_dropdown">
                                        <a class="nav-link menu_dropdown-toggle {{ $mp == 'bank' ? 'active' : '' }}"
                                            href="#" id="navbarmenu_dropdown" role="button"
                                            data-toggle="menu_dropdown" aria-haspopup="true"
                                            aria-expanded="false">Banking<i class="fa-solid fa-angle-down"></i></a>
                                        <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                            <a class="menu_dropdown-item {{ $p == 'in' ? 'active' : '' }}"
                                                href="{{ route('cbs_input.index') }}">Input Bank Statement</a>
                                            <a class="menu_dropdown-item {{ $p == 'im' ? 'active' : '' }}"
                                                href="{{ route('cbs_import.index') }}">Import Bank Statement</a>
                                            <a class="menu_dropdown-item {{ $p == 'impt' ? 'active' : '' }}"
                                                href="{{ route('cbs_tranlist.index') }} ">Imp
                                                Trn List</a>
                                            <a class="menu_dropdown-item {{ $p == 'brec' ? 'active' : '' }}"
                                                href="{{ route('bank_reconciliation.index') }}">Bank
                                                Reconciliation</a>
                                            <a class="menu_dropdown-item {{ $p == 'brecs' ? 'active' : '' }}"
                                                href="{{ route('bank_recon_statement.index') }}">Bank Reconciliation
                                                Statement</a>
                                        </div>
                                    </li>

                                    <li class="nav-item menu_dropdown">
                                        <a class="nav-link menu_dropdown-toggle {{ $mp == 'cf' ? 'active' : '' }}"
                                            href="#" id="navbarmenu_dropdown" role="button"
                                            data-toggle="menu_dropdown" aria-haspopup="true"
                                            aria-expanded="false">Card
                                            File<i class="fa-solid fa-angle-down"></i></a>
                                        <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                            <a class="menu_dropdown-item {{ $p == 'acard' ? 'active' : '' }}"
                                                href="{{ route('add_card_select_activity') }}">Add Card</a>
                                            <a class="menu_dropdown-item {{ $p == 'cl' ? 'active' : '' }}"
                                                href="{{ route('card_list_select_activity') }}">Card List/Edit</a>
                                        </div>
                                    </li>

                                    <li class="nav-item menu_dropdown">
                                        <a class="nav-link menu_dropdown-toggle {{ $mp == 'report' ? 'active' : '' }}"
                                            href="#" id="navbarmenu_dropdown" role="button"
                                            data-toggle="menu_dropdown" aria-haspopup="true"
                                            aria-expanded="false">Report<i class="fa-solid fa-angle-down"></i></a>
                                        <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                            <a class="menu_dropdown-item {{ $p == 'cbasis' ? 'active' : '' }}"
                                                href="{{ route('cbasis.index') }}">GST/BAS(Cash Basis)</a>
                                            <a class="menu_dropdown-item {{ $p == 'abasis' ? 'active' : '' }}"
                                                href="{{ route('abasis.index') }}">GST/BAS(Accrued Basis)</a>
                                            <a class="menu_dropdown-item {{ $p == 'gl' ? 'active' : '' }}"
                                                href="{{ route('ledger.select') }}">General
                                                Ledger</a>
                                            <a class="menu_dropdown-item {{ $p == 'tb' ? 'active' : '' }}"
                                                href="{{ route('trial.profession') }}">Trial
                                                Balance</a>

                                            <a class="menu_dropdown-item {{ $p == 'pexcl' ? 'active' : '' }}"
                                                href="{{ route('excl.index') }}">Profit
                                                &amp; Loss(GST <span style="color:red">Incl</span>)</a>
                                            <a class="menu_dropdown-item {{ $p == 'pincl' ? 'active' : '' }}"
                                                href="{{ route('incl.index') }}">Profit
                                                &amp; Loss(GST <span style="color:red">Excl</span>)</a>


                                            <a class="menu_dropdown-item {{ $p == 'bs' ? 'active' : '' }}"
                                                href="{{ route('balance.select') }}">Balance
                                                Sheet</a>


                                        </div>
                                    </li>
                                    {{-- Advanced Report Start --}}
                                    @if ($client->paymentList->subscription->amount > 40)
                                        <li class="nav-item menu_dropdown">
                                            <a class="nav-link menu_dropdown-toggle {{ $mp == 'advr' ? 'active' : '' }}"
                                                href="#" id="navbarmenu_dropdown" role="button"
                                                data-toggle="menu_dropdown" aria-haspopup="true"
                                                aria-expanded="false">Adv Rep.<i
                                                    class="fa-solid fa-angle-down"></i></a>
                                            <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                                {{-- GST Bash --}}
                                                <a class="menu_dropdown-item"
                                                    href="{{ route('client_cash_basis.index') }}">GST/BAS(
                                                    <span class="text-success">Consol</span> Cash)
                                                </a>
                                                <a class="menu_dropdown-item"
                                                    href="{{ route('client_accrued_basis.index') }}">GST/BAS(
                                                    <span class="text-success">Consol</span> Accrued)
                                                </a>

                                                {{-- Periodic Bash --}}
                                                <a class="menu_dropdown-item"
                                                    href="{{ route('c.periodicCash.profession') }}">Periodic
                                                    BAS(<span class="text-success">s/actv</span> Cash)</a>
                                                <a class="menu_dropdown-item"
                                                    href="{{ route('c.periodicAccrued.profession') }}">Periodic
                                                    BAS(<span class="text-success">s/actv</span> Accrued)</a>

                                                <a class="menu_dropdown-item"
                                                    href="{{ route('c.accum_excl_index') }}">Accumulated GST/BAS(Cash
                                                    Basis)
                                                </a>
                                                <a class="menu_dropdown-item"
                                                    href="{{ route('c.accum_excl_index') }}">Accumulated
                                                    GST/BAS(Accrued
                                                    Basis)
                                                </a>
                                                <a class="menu_dropdown-item"
                                                    href="{{ route('c.accum_excl_index') }}">Accumulated P/L (GST
                                                    <span style="color:red">Excl</span>)
                                                </a>
                                                <a class="menu_dropdown-item"
                                                    href="{{ route('c.accum_incl_index') }}">Accumulated P/L (GST
                                                    <span style="color:red">Incl</span>)
                                                </a>
                                                <a class="menu_dropdown-item"
                                                    href="{{ route('cdep_report.index') }}">Deprecation Report</a>
                                                <a class="menu_dropdown-item {{ $p == 'iec' ? 'active' : '' }}"
                                                    href="{{ route('client-avd.income_expense_comparison.index') }}">Income
                                                    & Expense Comparison</a>
                                                <a class="menu_dropdown-item {{ $p == 'cbs' ? 'active' : '' }}"
                                                    href="{{ route('cbalance.select') }}">Comparative Profit &
                                                    Loss</a>
                                                <a class="menu_dropdown-item {{ $p == 'cbs' ? 'active' : '' }}"
                                                    href="{{ route('cbalance.select') }}">Comparative Balance
                                                    Sheet</a>
                                                <a class="menu_dropdown-item {{ $p == 'cfr' ? 'active' : '' }}"
                                                    href="{{ route('cr_complete_financial.index') }}">Complete
                                                    Financial
                                                    Report</a>
                                                <a class="menu_dropdown-item"
                                                    {{ activeNav('front_complete_financial_report_tf.*') }}
                                                    href="{{ route('front_complete_financial_report_tf.index') }}">Complete
                                                    Financial
                                                    Report(T Form)</a>
                                                <a class="menu_dropdown-item {{ $p == 'cconfr' ? 'active' : '' }}"
                                                    href="{{ route('cr_console_financial.index') }}">Console
                                                    Financial
                                                    Report</a>
                                                <a class="menu_dropdown-item {{ $p == 'ccfr' ? 'active' : '' }}"
                                                    href="{{ route('cr_comperative_financial.index') }}">Comparative
                                                    Financial
                                                    Report</a>
                                                <a class="menu_dropdown-item {{ $p == 'avdbudget' ? 'active' : '' }}"
                                                    href="{{ route('client-avd.budget.index') }}">Budget</a>
                                                <a class="menu_dropdown-item" href="#">Retie Analysis</a>
                                                <a class="menu_dropdown-item" href="#">Decision Making Tools</a>
                                            </div>
                                        </li>
                                    @endif
                                    {{-- Advanced Report End --}}

                                    <li class="nav-item menu_dropdown">
                                        <a class="nav-link menu_dropdown-toggle {{ $mp == 'setting' ? 'active' : '' }}"
                                            href="#" id="navbarmenu_dropdown" role="button"
                                            data-toggle="menu_dropdown" aria-haspopup="true"
                                            aria-expanded="false">Setting<i class="fa-solid fa-angle-down"></i></a>
                                        <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
                                            <a class="menu_dropdown-item {{ $p == 'mp' ? 'active' : '' }}"
                                                href="{{ route('profile.index') }}">My
                                                Profile</a>
                                            <a class="menu_dropdown-item {{ $p == 'qr' ? 'active' : '' }}"
                                                href="{{ route('profile.2fa.index') }}">
                                                2FA setting</a>
                                            <a class="menu_dropdown-item {{ $p == 'ul' ? 'active' : '' }}"
                                                href="{{ route('profile.logo') }}">Upload
                                                Logo</a>
                                            <a class="menu_dropdown-item {{ $p == 'il' ? 'active' : '' }}"
                                                href="{{ route('invoice_layout.index') }}">Invoice Layout</a>
                                            <a class="menu_dropdown-item" href="#">Purchase Layout</a>
                                            <a class="menu_dropdown-item {{ $p == 'pl' ? 'active' : '' }}"
                                                href="{{ route('front_period_lock.index') }}">Period Lock</a>
                                            <a class="menu_dropdown-item" href="{{ route('paymentList') }}">Payment
                                                List</a>
                                        </div>
                                    </li>
                                    @include('frontend.layout.menu.help')
                                @else
                                    @include('frontend.layout.plan_menu')
                                @endif
                            @else
                                @include('frontend.layout.plan_menu')
                            @endif
                            <!-- <li class="nav-item"><a class="nav-link" href="#">Blog</a></li> -->
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>
