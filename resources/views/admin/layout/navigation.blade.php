<div id="sidebar" class="sidebar responsive ace-save-state">
    <script type="text/javascript">
        try{ace.settings.loadState('sidebar');
					}catch(e){}
    </script>

    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <button class="btn btn-success">
                <i class="ace-icon fa fa-signal"></i>
            </button>
            <button class="btn btn-info">
                <i class="ace-icon fa fa-pencil"></i>
            </button>
            <button class="btn btn-warning">
                <i class="ace-icon fa fa-users"></i>
            </button>
            <button class="btn btn-danger">
                <i class="ace-icon fa fa-cogs"></i>
            </button>
        </div>

        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>
            <span class="btn btn-info"></span>
            <span class="btn btn-warning"></span>
            <span class="btn btn-danger"></span>
        </div>
    </div><!-- /.sidebar-shortcuts -->

    <ul class="nav nav-list">
        <li class="active">
            <a href="{{ route('admin.dashboard') }}">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span>
            </a>
            <b class="arrow"></b>
        </li>

        <li class="">
            <a href="https://calendar.google.com/calendar/" target="_blank">
                <i class="menu-icon fa fa-calendar" aria-hidden="true"></i>
                <span class="menu-text">
                    Calendar
                </span>
            </a>
            <b class="arrow"></b>
        </li>

        <!-- Admin -->
        <li class="{{ activeOpenNav(['client_payment_index','plan.*','admin.user.*','admin.role.*','admin.profession.*','profession.*']) }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-desktop"></i>
                <span class="menu-text">
                    Admin
                </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>

            <ul class="submenu">
                @canany(['admin.client_payment.create','admin.client_payment.edit','admin.client_payment.index','admin.client_payment.delete'])
                <li class="{{ activeNav('client_payment_index') }}">
                    <a href="{{ route('client_payment_index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Client Payment List
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="{{ activeNav('plan.*') }}">
                    <a href="{{ route('plan.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Subscription Plans
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcanany
                @canany(['admin.user.create','admin.role.index'])
                <li class="{{ activeOpenNav(['admin.user.*','admin.role.*']) }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        User Management
                        <b class="arrow fa fa-angle-down"></b>
                    </a>

                    <b class="arrow"></b>

                    <ul class="submenu">
                        @can('admin.user.create')
                        <li class="{{ activeNav('user.*') }}">
                            <a href="{{ route('user.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Users
                            </a>
                            <b class="arrow"></b>
                        </li>
                        @endcan
                        @can('admin.role.index')
                        <li class="{{ activeNav('role.*') }}">
                            <a href="{{route('role.index')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Roles
                            </a>
                            <b class="arrow"></b>
                        </li>
                        @endcan
                        {{-- @can('permission')
                        <li class="">
                            <a href="{{route('permission')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Permissions
                            </a>

                            <b class="arrow"></b>
                        </li>
                        @endcan --}}
                    </ul>
                </li>
                @endcanany
                @canany(['admin.profession.index'])
                @can('admin.profession.index')
                <li class="{{ activeNav('profession.*') }}">
                    <a href="{{ route('profession.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Profession
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @endcanany

                @canany(['admin.account-code.index', 'admin.account-code.create', 'admin.account-code.edit',
                'admin.account-code.delete', 'admin.master-chart.index', 'admin.master-chart.create',
                'admin.master-chart.edit', 'admin.master-chart.delete', 'admin.account-code.sub-category.create',
                'admin.account-code.additional-category.create', 'admin.master-chart.sub-category.create',
                'admin.master-chart.additional-category.create','admin.cash_basis.index'])
                <li class="">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Code
                        <b class="arrow fa fa-angle-down"></b>
                    </a>

                    <b class="arrow"></b>
                    <ul class="submenu">
                        @canany(['admin.account-code.index', 'admin.account-code.create', 'admin.account-code.edit',
                        'admin.account-code.delete', 'admin.account-code.sub-category.create',
                        'admin.account-code.additional-category.create'])
                        <li class="">
                            <a href="{{route('code')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Account Chart
                            </a>

                            <b class="arrow"></b>
                        </li>
                        @endcanany
                        @canany(['admin.master-chart.index', 'admin.master-chart.create', 'admin.master-chart.edit',
                        'admin.master-chart.delete', 'admin.master-chart.sub-category.create',
                        'admin.master-chart.additional-category.create'])
                        <li class="">
                            <a href="{{route('master.chart')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Master Chart
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="{{route('client_fixed_code.index')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Fixed Client Acc.Code
                            </a>
                            <b class="arrow"></b>
                        </li>
                        @endcanany
                    </ul>
                </li>
                @endcanany
                @canany(['admin.fuel_tax_cr.index','admin.fuel_tax_cr.edit','admin.fuel_tax_cr.create','admin.fuel_tax_cr.delete'])
                <li class="">
                    <a href="{{ route('FuelTaxCredit.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Fuel Tax Credit Rate
                    </a>
                </li>
                @endcanany

                @canany(['admin.coefficients.index','admin.coefficients.edit','admin.coefficients.create','admin.coefficients.delete','admin.i_tax_table.index','admin.i_tax_table.edit','admin.i_tax_table.create','admin.i_tax_table.delete','admin.wages.index','admin.wages.edit','admin.wages.create','admin.wages.delete','admin.superannuation.index','admin.superannuation.edit','admin.superannuation.create','admin.superannuation.delete','admin.leave.index','admin.leave.edit','admin.leave.create','admin.leave.delete','admin.deducation.index','admin.deducation.edit','admin.deducation.create','admin.deducation.delete'])
                {{-- <li class="">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Manage Payroll
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    @canany(['admin.coefficients.index','admin.coefficients.edit','admin.coefficients.create','admin.coefficients.delete'])
                    <ul class="submenu">
                        <li class="">
                            <a href="{{ route('coefficient.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Coefficients
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                    @endcanany
                    @canany(['admin.i_tax_table.index','admin.i_tax_table.edit','admin.i_tax_table.create','admin.i_tax_table.delete'])
                    <ul class="submenu">
                        <li class="">
                            <a href="{{ route('stanwages.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                I. Tax Table
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                    @endcanany
                    @canany(['admin.wages.index','admin.wages.edit','admin.wages.create','admin.wages.delete'])
                    <ul class="submenu">
                        <li class="">
                            <a href="{{ route('stanwages.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Wages
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                    @endcanany
                    @canany(['admin.superannuation.index','admin.superannuation.edit','admin.superannuation.create','admin.superannuation.delete'])
                    <ul class="submenu">
                        <li class="">
                            <a href="{{ route('superannuation.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Superannuation
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                    @endcanany
                    @canany(['admin.leave.index','admin.leave.edit','admin.leave.create','admin.leave.delete'])
                    <ul class="submenu">
                        <li class="">
                            <a href="{{ route('standardleave.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Leave(Entatilment)
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                    @endcanany
                    @canany(['admin.deducation.index','admin.deducation.edit','admin.deducation.create','admin.deducation.delete'])
                    <ul class="submenu">
                        <li class="">
                            <a href="{{ route('deducation.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Deducation
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                    @endcanany --}}
                </li>
                @endcanany
                @canany(['admin.service.index', 'admin.service.create', 'admin.service.edit', 'admin.service.delete'])
                <li class="">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Services
                        <b class="arrow fa fa-angle-down"></b>
                    </a>

                    <b class="arrow"></b>

                    <ul class="submenu">
                        @can('admin.service.index')
                        <li class="">
                            <a href="{{route('service.index')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Services List
                            </a>

                            <b class="arrow"></b>
                        </li>
                        @endcan
                        @can('admin.service.create')
                        <li class="">
                            <a href="{{route('service.create')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Add Services
                            </a>

                            <b class="arrow"></b>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany
                <li class="">
                    <a href="{{ route('helpdesk.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Help Menu Manage
                    </a>
                    <b class="arrow"></b>
                </li>
                @role('Admin')
                <li class="">
                    <a href="{{ route('trash.index') }}">
                        <i class="menu-icon fa fa-trash"></i>
                        Trash
                    </a>
                    <b class="arrow"></b>
                </li>
                @endrole
            </ul>
        </li>


        @canany(['admin.verify_account.index','admin.period_lock.index'])
        <!-- Tools -->
        <li class="{{ activeOpenNav(['verify_account.*']) }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-cogs" aria-hidden="true"></i>
                <span class="menu-text">
                    Tools
                </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('admin.verify_account.index')
                <li class="{{ activeNav('verify_account.*') }}">
                    <a href="{{ route('verify_account.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Verify & fixed trans
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan

                {{-- <li class="">
                    <a href="{{ route('fixed_accounts_index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Fixed Accounts
                    </a>
                    <b class="arrow"></b>
                </li> --}}
                @can('admin.period_lock.index')
                <li class="">
                    <a href="{{ route('period_lock.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Period Lock
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        @canany(['admin.client.create','admin.client.index'])
        <!-- Client List -->
        <li class="{{ activeOpenNav(['client.*']) }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-users" aria-hidden="true"></i>
                <span class="menu-text">Client</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('admin.client.index')
                <li class="{{ activeNav('client.index') }}">
                    <a href="{{ route("client.index") }}">
                        <i class="menu-icon fa fa-caret-right"></i>Client List</a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.client.create')
                <li class="{{ activeNav('client.create') }}">
                    <a href="{{ route('client.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Add Client</a>
                    <b class="arrow"></b>
                </li>
                <li class="{{ activeNav('client.data.*') }}" >
                    <a href="{{ route('client.data.password') }}" style="color: red; font-weight:bold">
                        <i class="menu-icon fa fa-caret-right"></i>Delete Client Data Permanently</a>
                    <b class="arrow"></b>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany
        <li class="">
            <a href="https://smsf.focustaxation.com.au/admin/login" target="_blank">
                <i class="menu-icon fa fa-money"></i>Superfund
            </a>
            <b class="arrow"></b>
        </li>

        <li
            class="{{ activeNav(['admin.period.*','dmin.bs_import.*','admin.bs_input.*','admin.depreciation.*','admin.manage_invest.*','journal_entry*']) }}">
            @canany([
            'admin.period.index','admin.bs_import.create','admin.bs_input.create',
            'admin.journal_entry.create','admin.depreciation.create','admin.manage_invest.create'
            ])
            <a href="{{route('select_method')}}">
                <i class="menu-icon fa fa-database" aria-hidden="true"></i>
                <span class="menu-text">Add/Edit Entry</span>
            </a>
            <b class="arrow"></b>
            @endcanany
        </li>
        <!-- Add/Edit Data -->
        @canany(['admin.bs_import.create','admin.bs_import.index','admin.bs_import.edit','admin.bs_import.import'])
        <li class="{{ activeOpenNav(['journal_list.*','bs_tran_list.*','payment_sync.*','bank_recon.*']) }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-file-text-o"></i>
                <span class="menu-text">Transaction List</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                {{-- <li class="{{activeNav('journal_entry*')}}">
                    <a href="{{route('journal_entry_client')}}">
                        <i class="menu-icon fa fa-download" aria-hidden="true"></i>
                        <span class="menu-text">Journal Entry(JNP)</span>
                    </a>
                    <b class="arrow"></b>
                </li> --}}
                @can('admin.journal_list.index')
                <li class="{{activeNav('journal_list.*')}}">
                    <a href="{{route('journal_list.index')}}">
                        <i class="menu-icon fa fa-download" aria-hidden="true"></i>
                        <span class="menu-text">Journal List</span>
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                <li class="{{activeNav('bs_tran_list.*')}}">
                    <a href="{{route('bs_tran_list.index')}}">
                        <i class="menu-icon fa fa-download" aria-hidden="true"></i>
                        <span class="menu-text">Bank Trn List</span>
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="{{activeNav('bank_recon.*')}}">
                    <a href="{{route('bank_recon.index')}}">
                        <i class="menu-icon fa fa-download" aria-hidden="true"></i>
                        <span class="menu-text">Bank Reconciliation</span>
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="{{activeNav('payment_sync.*')}}">
                    <a href="{{route('payment_sync.index')}}">
                        <i class="menu-icon fa fa-download" aria-hidden="true"></i>
                        <span class="menu-text">Payment Trn Adv.Delete</span>
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>
        @endcanany

        <!-- Reports -->
        @canany([
        'admin.gst_recon.index',
        'admin.cash_basis.index',
        'admin.accrued_basis.index',
        'admin.periodic_cash.index',
        'admin.periodic_accrued.index',
        'admin.trial_balance.index',
        'admin.general_ledger.index',
        'admin.profit_loss_excl.index',
        'admin.profit_loss_incl.index',
        'admin.balance_sheet.index',
        'admin.comperative_bs.index',
        'admin.advanced_report.index',
        'admin.depreciation_report.index',
        'admin.consolidated_report.index',
        'admin.investment_report.index',
        'admin.ratio_report.index',
        'admin.investment_report.index',
        ])
        <li class="{{ activeOpenNav(
                        ['gst_recon.*',
                        'cash_basis.*',
                        'accrued_basis.*',
                        'cash_periodic.*',
                        'accrued_periodic.*',
                        'general_ledger.*',
                        'trial-balance.*',
                        'console_trial_balance.*',
                        'profit_loss_gst_excl.*',
                        'profit_loss_gst_incl.*',
                        'console_accum.*',
                        'balance_sheet.*',
                        'console_balance_sheet.*',
                        'comperative_balance_sheet.*',
                        'complete_financial_report.*',
                        'console_financial_report*',
                        'comperative_financial_report*',
                        'depreciation_report.*',
                        'complete_financial_report_tf*'
                        ]) }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-clipboard" aria-hidden="true"></i>
                <span class="menu-text">
                    Reports
                </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('admin.gst_recon.index')
                <li class="{{ activeNav('gst_recon.*') }}">
                    <a href="{{ route('gst_recon.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        GST Recon for TR
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.cash_basis.index')
                <li class="{{ activeNav('cash_basis.*') }}">
                    <a href="{{ route('cash_basis.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        GST/BAS(<span style="color: green">Cnosol.</span>Cash)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.accrued_basis.index')
                <li class="{{ activeNav('accrued_basis.*') }}">
                    <a href="{{ route('accrued_basis.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        GST/BAS (<span style="color: green">Cnosol.</span>Acured)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.periodic_cash.index')
                <li class="{{ activeNav('cash_periodic.*') }}">
                    <a href="{{ route('cash_periodic.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Periodic BAS(<span style="color: green">s/actv</span>Cash)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.periodic_accrued.index')
                <li class="{{ activeNav('accrued_periodic.*') }}">
                    <a href="{{ route('accrued_periodic.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Periodic BAS(<span style="color: green">s/actv</span>Acur)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.general_ledger.index')
                <li class="{{ activeNav('general_ledger.*') }}">
                    <a href="{{route('general_ledger.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        General Ledger
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.trial_balance.index')
                <li class="{{ activeNav('trial-balance.*') }}">
                    <a href="{{route('trial-balance.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Trial Balance(S/Activity)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.cons_trial_balance.index')
                <li class="{{ activeNav('console_trial_balance.*') }}">
                    <a href="{{route('console_trial_balance.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span style="color: green">Cnosol.</span> Trial Balance
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.profit_loss_excl.index')
                <li class="{{ activeNav('profit_loss_gst_excl.*') }}">
                    <a href="{{ route('profit_loss_gst_excl.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        P/L(GST <span style="color: red;">Excl</span>,S/Activity)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan

                @can('admin.profit_loss_incl.index')
                <li class="{{ activeNav('profit_loss_gst_incl.*') }}">
                    <a href="{{ route('profit_loss_gst_incl.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        P/L(GST <span style="color: red;">Incl</span>,S/Activity)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.console_accum_excl.index')
                <li class="{{ activeNav('console_accum.excl_index') }}">
                    <a href="{{ route('console_accum.excl_index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span style="color: green">Cnosol.</span>
                        P/L(GST <span style="color: red;">Excl</span>)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.console_accum_incl.index')
                <li class="{{ activeNav('console_accum.incl_index') }}">
                    <a href="{{ route('console_accum.incl_index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span style="color: green">Cnosol.</span> P/L(GST <span style="color: red;">Incl</span>)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                {{-- @can('admin.balance_sheet.index')
                <li class="">
                    <a href="{{ route('balance_sheet_index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Balance Sheet
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan --}}
                @can('admin.balance_sheet.index')
                <li class="{{ activeNav('balance_sheet.*') }}">
                    <a href="{{ route('balance_sheet.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Balance Sheet(S/Activity)
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.console_balance_sheet.index')
                <li class="{{ activeNav('console_balance_sheet.*') }}">
                    <a href="{{ route('console_balance_sheet.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span style="color: green">Cnosol.</span> Balance Sheet
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.comperative_bs.index')
                <li class="{{ activeNav('comperative_balance_sheet.*') }}">
                    <a href="{{ route('comperative_balance_sheet.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Comparative B/S
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.complete_financial_report.index')
                <li class="{{ activeNav('complete_financial_report.*') }}">
                    <a href="{{ route('complete_financial_report.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Complete Financial R.
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                <li class="{{ activeNav('complete_financial_report_tf*') }}">
                    <a href="{{ route('complete_financial_report_tf.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Complete Fin Report(T Form)
                    </a>
                    <b class="arrow"></b>
                </li>
                @can('admin.console_financial_report.index')
                <li class="{{ activeNav('console_financial_report*') }}">
                    <a href="{{route('console_financial_report_index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span style="color: green">Cnosol.</span>Financial Report.
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.comperative_financial_report.index')
                <li class="{{ activeNav('comperative_financial_report*') }}">
                    <a href="{{ route('comperative_financial_report_index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Comparative Financial R.
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                
                @can('admin.depreciation_report.index')
                <li class="{{ activeNav('depreciation_report.*') }}">
                    <a href="{{ route('depreciation_report.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Depreciation Report
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('admin.advanced_report.index')
                <li class="">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span style="color: green;">Advanced Report</span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>

                    <b class="arrow"></b>
                    <ul class="submenu">
                        <li class="">
                            <a href="{{ route('advance_report.business_plan.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Business Plan Report
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="{{ route('advance_report.business_analysis.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Monthly Business Analysis Details P & L
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="{{route('advance_report.budget.index')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Budget
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
                @endcan
            </ul>
        </li>

        @endcanany
        <!-- Database Download -->
        @can('admin.database_dump.index')
        <li class="">
            <a href="{{ route("backup.load") }}">
                <i class="menu-icon fa fa-download" aria-hidden="true"></i>
                <span class="menu-text">
                    Database Backup
                </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan

        @can('admin.database_dump.index')
        <li class="">
            <a href="{{ route("backup.restore") }}">
                <i class="menu-icon fa fa-desktop" aria-hidden="true"></i>
                <span class="menu-text">
                    Restore Backup
                </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan

        <!-- Agent Audit -->
        @can('admin.agent_audit.index')

        <li class="">
            <a href="{{ route('audit.agent_index') }}">
                <i class="menu-icon fa fa-sign-out" aria-hidden="true"></i>
                <span class="menu-text">
                    Agent Audit
                </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan

        <!-- Logging Audit -->
        @can('admin.logging_audit.index')
        <li class="">
            <a href="{{ route('logging_audit_index') }}">
                <i class="menu-icon fa fa-sign-out" aria-hidden="true"></i>
                <span class="menu-text">
                    Logging Audit
                </span>
            </a>
            <b class="arrow"></b>
        </li>

        @endcan
        <!-- visitor Audit -->
        @can('admin.visitor_info.index')
        <li class="">
            <a href="{{ route('visitor.index') }}">
                <i class="menu-icon fa fa-sign-out" aria-hidden="true"></i>
                <span class="menu-text">
                    visitor Information
                </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan

        <!-- Logout -->
        <li class="">
            <a href="{{route('admin.logout')}}">
                <i class="menu-icon fa fa-sign-out" aria-hidden="true"></i>
                <span class="menu-text">
                    Logout
                </span>
            </a>
            <b class="arrow"></b>
        </li>
    </ul><!-- /.nav-list -->

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
            data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>
