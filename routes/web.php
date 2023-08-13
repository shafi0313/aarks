<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HelpDeskController;
use App\Http\Controllers\Google2faController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\AllPageController;
use App\Http\Controllers\Frontend\AtoDataController;
use App\Http\Controllers\Frontend\BsbTableController;
use App\Http\Controllers\Frontend\CashBookController;
use App\Http\Controllers\Frontend\AdtPeriodController;
use App\Http\Controllers\Frontend\PeriodLockController;
use App\Http\Controllers\Frontend\ClientWagesController;
use App\Http\Controllers\Frontend\PayAccumAmtController;
use App\Http\Controllers\Frontend\Report\ExclController;
use App\Http\Controllers\Frontend\Report\InclController;
use App\Http\Controllers\Frontend\Banking\ImportTranList;
use App\Http\Controllers\Frontend\CustomerCardController;
use App\Http\Controllers\Frontend\EmployeeCardController;
use App\Http\Controllers\Frontend\PersonalCardController;
use App\Http\Controllers\Frontend\SupplierCardController;
use App\Http\Controllers\Frontend\InventoryItemController;
use App\Http\Controllers\Frontend\PreparePayrollController;
use App\Http\Controllers\Frontend\ClientDeductionController;
use App\Http\Controllers\Frontend\EClassificationController;
use App\Http\Controllers\Frontend\Setting\ProfileController;
use App\Http\Controllers\Frontend\Report\CashBasisController;
use App\Actions\RecurringGenerate\RecurringAutoGenerateAction;
use App\Http\Controllers\Frontend\InventoryCategoryController;
use App\Http\Controllers\Frontend\ClientJournalEntryController;
use App\Http\Controllers\Frontend\Report\AccumulatedPlGstReport;
use App\Http\Controllers\Frontend\Report\AccuredBasisController;
use App\Http\Controllers\Frontend\Report\BalanceSheetController;
use App\Http\Controllers\Frontend\Report\BudgetReportController;
use App\Http\Controllers\Frontend\Report\GstCashBasisController;
use App\Http\Controllers\Frontend\Report\TrialBalanceController;
use App\Http\Controllers\Frontend\Accounts\BudgetEntryController;
use App\Http\Controllers\Frontend\ClientSuperannuationController;
use App\Http\Controllers\Frontend\Report\GeneralLedgerController;
use App\Http\Controllers\Frontend\Banking\ClientBsInputController;
use App\Http\Controllers\Frontend\Report\CompleteReportController;
use App\Http\Controllers\Frontend\Banking\ClientBsImportController;
use App\Http\Controllers\Frontend\Report\GstAccruedBasisController;
use App\Http\Controllers\Frontend\Report\DepreciationReportController;
use App\Http\Controllers\Frontend\Banking\BankReconciliationController;
use App\Http\Controllers\Frontend\Report\ConsoleFinancialReportController;
use App\Http\Controllers\Frontend\Accounts\Depreciation\DesposalController;
use App\Http\Controllers\Frontend\Accounts\Depreciation\RolloverController;
use App\Http\Controllers\Frontend\Report\ComparativeBalanceSheetController;
use App\Http\Controllers\Frontend\Report\IncomeExpenseComparisonController;
use App\Http\Controllers\Frontend\Report\CompleteFinancialReportTFController;
use App\Http\Controllers\Frontend\Report\ComperativeFinancialReportController;
use App\Http\Controllers\Frontend\Accounts\Depreciation\DepreciationController;
use App\Http\Controllers\Frontend\Banking\BankReconciliationStatementController;

Route::redirect('admin', 'admin/login')->name('admin');
Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/login', [ClientController::class, 'showLoginForm'])->middleware("throttle:5,1")->name('login');
Route::post('login', [ClientController::class, 'login']);
Route::post('register', [ClientController::class, 'clientRegister'])->name('clientRegister');
Route::post('log-out', [ClientController::class, 'logout'])->name('client.logout');
Route::post('/payment_post', [AllPageController::class, 'payment_post'])->name('payment_post');
// Frontend=============================================================

Route::get('fresh', function () {
    if (env('APP_ENV') == 'local') {
        // return 'Oke Database Clear!';
        DB::table('cash_books')->delete();
        DB::table('general_ledgers')->delete();
        DB::table('gsttbls')->delete();
        DB::table('data_storages')->delete();
        DB::table('bank_statement_inputs')->delete();
        DB::table('bank_statement_imports')->delete();
        DB::table('dedotr_quote_orders')->delete();
        DB::table('dedotrs')->delete();
        DB::table('dedotr_payment_receives')->delete();
        DB::table('dedotr_temp_payment_receives')->delete();
        DB::table('inventory_registers')->delete();
        DB::table('creditors')->delete();
        DB::table('creditor_service_orders')->delete();
        DB::table('creditor_payment_receives')->delete();
        DB::table('journal_entries')->delete();
        DB::table('dep_asset_names')->delete();
        DB::table('disposeable_ledgers')->delete();
        DB::table('fuel_tax_credits')->delete();
        DB::table('fuel_tax_ltrs')->delete();
        DB::table('paygs')->delete();
        DB::table('period_locks')->delete();
        DB::table('recurrings')->delete();
        DB::table('standard_wages')->delete();
        DB::table('inventory_items')->delete();
        return 'Oke Database Clear!';
    }
    return redirect('/');
});
Route::middleware('clientAuth')->group(function () {
    // update_account
    Route::get('upgrage-plan', [SubscriptionController::class, 'upgrade'])->name('upgrade');
    Route::get('account/payment/list', [SubscriptionController::class, 'paymentList'])->name('paymentList');
    // Request for package
    Route::post('account-upgrade-request', [SubscriptionController::class, 'upgradeRequest'])->name('upgradeRequest');
});

Route::middleware(['subsCheck', 'clientAuth'])->group(function () {
    // Period Lock
    Route::resource('front_period_lock', PeriodLockController::class)->only(['index', 'store']);

    // Category
    Route::get('inv_category/report/{client}/{profession}', [InventoryCategoryController::class, 'report'])->name('inv_category.report');
    Route::post('inv_category/addSub/', [InventoryCategoryController::class, 'addSub'])->name('inv_category.addSub');
    Route::resource('inv_category', InventoryCategoryController::class);

    // item
    Route::get('inv_item/report/{client}/{profession}', [InventoryItemController::class, 'report'])->name('inv_item.report');
    Route::post('inv_item/measure/store', [InventoryItemController::class, 'measure'])->name('inv_item.measure');
    Route::get('inv_item/list/all', [InventoryItemController::class, 'listItem'])->name('inv_item.listItem');
    Route::resource('inv_item', InventoryItemController::class);

    // item_list
    Route::get('/item_list', [AllPageController::class, 'item_list_index'])->name('item_list_index');

    // inv_register
    Route::get('/inv_register', [AllPageController::class, 'invRegister'])->name('invRegister');
    Route::get('/inv_report', [AllPageController::class, 'invReport'])->name('invReport');

    // period
    Route::resource('/adtperiod', AdtPeriodController::class);
    Route::get('period/{client}/{profession}', [AdtPeriodController::class, 'periodIndex'])->name('client.periodIndex');
    Route::post('period/store', [AdtPeriodController::class, 'periodStore'])->name('client.periodStore');
    Route::delete('period/delete/{period}', [AdtPeriodController::class, 'periodDelete'])->name('client.periodDelete');
    Route::get('period/add-edit/{client}/{profession}/{period}', [AdtPeriodController::class, 'periodAddEdit'])->name('client.periodAddEdit');
    Route::get('period/chart-code-add-edit/{client_account}/{code}/{client}/{profession}/{period}', [AdtPeriodController::class, 'periodCodeAddEdit'])->name('client.periodCodeAddEdit');

    //! BS Input
    // Route::resource('cbs_input', ClientBsInputController::class);
    Route::prefix('bank-statement/input/')->name('cbs_input.')->group(function () {
        Route::get('/', [ClientBsInputController::class, 'index'])->name('index');
        Route::get('{client}/{profession}', [ClientBsInputController::class, 'inputBS'])->name('inputbs');
        Route::get('getCodes', [ClientBsInputController::class, 'getCodes'])->name('getcodes');
        Route::get('store', [ClientBsInputController::class, 'bankStatementStore'])->name('store');

        Route::post('post', [ClientBsInputController::class, 'post'])->name('post');
        Route::get('delete', [ClientBsInputController::class, 'bankStatementDelete'])->name('delete');
    });

    //! BS Import
    Route::prefix('bank-statement/import/')->name('cbs_import.')->group(function () {
        Route::get('profession', [ClientBsImportController::class, 'index'])->name('index');
        Route::get('show/{client}/{profession}', [ClientBsImportController::class, 'show'])->name('show');

        Route::post('store', [ClientBsImportController::class, 'store'])->name('store');
        Route::get('update-account-name/{id}', [ClientBsImportController::class, 'updateAccountCode'])->name('updateCode');
        Route::post('post', [ClientBsImportController::class, 'post'])->name('post');
        Route::delete('destroy-all', [ClientBsImportController::class, 'destroy'])->name('delete');
    });

    // !Import tran List
    Route::prefix('bank-statement/transaction/')->name('cbs_tranlist.')->group(function () {
        Route::get('/', [ImportTranList::class, 'index'])->name('index');
        Route::get('report/{client}/{profession}', [ImportTranList::class, 'report'])->name('report');
        Route::get('/details/{client}/{profession}/{tran_id}/{src}', [ImportTranList::class, 'detailsReport'])->name('details');

        // Import
        Route::put('/details/import/update/{client}/{profession}/{tran_id}', [ImportTranList::class, 'importUpdate'])->name('import.update');
        Route::get('/details/import/destroy/{client}/{profession}/{bank_statement}', [ImportTranList::class, 'importDestroy'])->name('import.destroy');
        Route::get('/details/import/delete/{client}/{profession}/{tran_id}/{src}', [ImportTranList::class, 'importDeleteAll'])->name('import.delete');


        // Input
        Route::put('/details/input/update/{client}/{profession}/{tran_id}', [ImportTranList::class, 'inputUpdate'])->name('input.update');
        Route::get('/details/input/destroy/{client}/{profession}/{bank_statement}', [ImportTranList::class, 'inputDestroy'])->name('input.destroy');
        Route::get('/details/input/delete/{client}/{profession}/{tran_id}/{src}', [ImportTranList::class, 'inputDeleteAll'])->name('input.delete');
    });

    // bank-reconciliation
    Route::group([
        'prefix' => 'bank-reconciliation',
        'as'     => 'bank_reconciliation.',
    ], function () {
        Route::get('profession', [BankReconciliationController::class, 'index'])->name('index');
        Route::get('date/{client}/{profession}', [BankReconciliationController::class, 'date'])->name('date');
        Route::post('post', [BankReconciliationController::class, 'post'])->name('post');
        Route::get('show', [BankReconciliationController::class, 'show'])->name('show');
        Route::get('transaction/{transaction}/{source}', [BankReconciliationController::class, 'transaction'])->name('transaction');
        Route::get('transaction-detalis/{transaction}/{chart_id}', [BankReconciliationController::class, 'transactionByDS'])->name('ds_transaction');
    });
    // Bank Reconciliation Statement
    Route::group([
        'prefix' => 'bank-reconciliation-statement',
        'as'     => 'bank_recon_statement.',
    ], function () {
        Route::get('', [BankReconciliationStatementController::class, 'index'])->name('index');
        Route::get('activity/{client}/{profession}', [BankReconciliationStatementController::class, 'date'])->name('date');
        Route::get('report/{client}/{profession}', [BankReconciliationStatementController::class, 'report'])->name('report');
    });

    // profile
    Route::group([
        'prefix' => 'profile',
        'as' => 'profile.',
    ], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index')->middleware('2fa');
        Route::put('update/{client}', [ProfileController::class, 'clientProfileUpdate'])->name('update');
        Route::get('upload-logo', [ProfileController::class, 'uploadLogo'])->name('logo');
        Route::post('store-logo', [ProfileController::class, 'storeLogo'])->name('logoStore');

        //? Google 2FA
        Route::get('google-2fa/active', [Google2faController::class, 'index'])->name('2fa.index');
        Route::post('google-2fa/store/{client}', [Google2faController::class, 'enable'])->name('2fa.store');
        Route::post('google-2fa/destroy/{client}', [Google2faController::class, 'destroy'])->name('2fa.destroy');
        Route::post('google-2fa-Verify', function () {
            return redirect(URL()->previous());
        })->name('2faVerify')->middleware('2fa');
    });

    // BSB Table
    Route::resource('bsbtable', BsbTableController::class);

    // invoice_layout
    Route::get('invoice-layout', [AllPageController::class, 'invoiceIndex'])->name('invoice_layout.index');
    Route::post('invoice-layout/store', [AllPageController::class, 'invoiceStore'])->name('invoice_layout.store');


    // period_lock
    // Route::get('period-lock', [AllPageController::class, 'period_lock_index'])->name('period_lock_index');

    // employee card
    Route::group(['prefix' => 'add-card'], function () {
        Route::get('/select-activity', [EmployeeCardController::class, 'add_card_select_activity'])->name('add_card_select_activity');
        Route::get('/select-type/{profession}', [EmployeeCardController::class, 'add_card_select_type'])->name('add_card_select_type');
        Route::get('/employee/{profession}', [EmployeeCardController::class, 'add_card_employee'])->name('add_card_employee');

        Route::resource('employee', EmployeeCardController::class);
        Route::group(['prefix' => 'card-list'], function () {
            Route::get('employee/view/{profession}', [EmployeeCardController::class, 'view'])->name('employee.view');
            Route::get('employee/delete/{employee}', [EmployeeCardController::class, 'delete'])->name('employee.delete');
            // Customer Card
            Route::resource('customer', CustomerCardController::class);
            Route::get('customer/view/{profession}', [CustomerCardController::class, 'view'])->name('customer.view');
            Route::get('customer/delete/{customer}', [CustomerCardController::class, 'delete'])->name('customer.delete');
            // supplier
            Route::resource('supplier', SupplierCardController::class);
            Route::get('supplier/view/{profession}', [SupplierCardController::class, 'view'])->name('supplier.view');
            Route::get('supplier/delete/{supplier}', [SupplierCardController::class, 'delete'])->name('supplier.delete');
            // personal
            Route::resource('personal', PersonalCardController::class);
            Route::get('personal/view/{profeession}', [PersonalCardController::class, 'view'])->name('personal.view');
            Route::get('personal/delete/{personal}', [PersonalCardController::class, 'delete'])->name('personal.delete');
        });
    });


    // add_list
    Route::get('/card_list/select_activity', [AllPageController::class, 'card_list_select_activity'])->name('card_list_select_activity');
    Route::get('/card_list/select_type/{profession}', [AllPageController::class, 'card_list_select_type'])->name('card_list_select_type');
    Route::get('/card_list/show', [AllPageController::class, 'card_list_show'])->name('card_list_show');
    Route::get('/card_list/employee/show', [AllPageController::class, 'card_list_employee_show'])->name('card_list_employee_show');
    Route::get('/card_list/employee', [AllPageController::class, 'card_list_employee'])->name('card_list_employee');




    // bill
    Route::get('/bill', [AllPageController::class, 'bill_enter_bill'])->name('bill_enter_bill');
    Route::get('/bill/manage', [AllPageController::class, 'bill_manage'])->name('bill_manage');
    Route::get('/bill/payment', [AllPageController::class, 'bill_payment'])->name('bill_payment');



    //Quote
    Route::get('edit/print/filter', [AllPageController::class, 'editPrintFilter'])->name('edit.print.filter');



    // transation_journal
    Route::get('/payroll/transation_journal', [AllPageController::class, 'transation_journal_index'])->name('transation_journal_index');


    Route::get('/payroll/view_payroll_journal', [AllPageController::class, 'view_payroll_journal_index'])->name('view_payroll_journal_index');
    Route::get('/payroll/view_payroll_journal/report', [AllPageController::class, 'view_payroll_journal_report'])->name('view_payroll_journal_report');

    // accounts
    Route::get('/account-chart/select-activity', [AllPageController::class, 'account_chart_select_activity'])->name('account_chart_select_activity');
    Route::get('/account-chart/show-activity/{client}/{profession}', [AllPageController::class, 'clientCodeProfession'])->name('clientCodeProfession');
    Route::post('/account-chart/store-activity', [AllPageController::class, 'account_chart_store'])->name('account_chart_store');
    Route::put('/account-chart/update-activity', [AllPageController::class, 'account_chart_update'])->name('account_chart_update');
    Route::delete('/account-chart/delete-activity', [AllPageController::class, 'account_chart_delete'])->name('account_chart_delete');

    // cash_book
    Route::group([
        'prefix' => 'cashbook',
        'as'     => 'cashbook.'
    ], function () {
        Route::get('/', [CashBookController::class, 'index'])->name('index');
        Route::post('store', [CashBookController::class, 'store'])->name('store');
        Route::get('destroy/{cashbook}', [CashBookController::class, 'destroy'])->name('destroy');
        Route::get('office/{client}/{profession}', [CashBookController::class, 'office'])->name('office');
        Route::post('newoffice', [CashBookController::class, 'newoffice'])->name('newoffice');
        Route::get('dataentry/{client}/{profession}/{office}', [CashBookController::class, 'dataentry'])->name('dataentry');
        Route::post('massUpdate/', [CashBookController::class, 'massUpdate'])->name('massUpdate');

        //Cash Book Report
        Route::get('report/select_activity', [CashBookController::class, 'reportActivity'])->name('reportActivity');
        Route::get('report/select_office/{client}/{profession}', [CashBookController::class, 'reportOffice'])->name('reportOffice');
        Route::get('report/{client}/{profession}', [CashBookController::class, 'report'])->name('report');
    });
    // Route::resource('cashbook', [CashBookController::class, ']);








    // Journal Entry
    Route::prefix('journal-entry')->name('client.je_')->group(function () {
        Route::get('profession', [ClientJournalEntryController::class, 'profession'])->name('profession');
        Route::get('input/{client}/{profession}', [ClientJournalEntryController::class, 'input'])->name('input');
        Route::get('input/store', [ClientJournalEntryController::class, 'store'])->name('store');
        Route::get('input/read', [ClientJournalEntryController::class, 'read'])->name('read');
        Route::get('delete/{journal}', [ClientJournalEntryController::class, 'delete'])->name('delete');
        Route::post('input/post', [ClientJournalEntryController::class, 'post'])->name('post');
        Route::get('journal_number', [ClientJournalEntryController::class, 'number'])->name('number');
    });


    // Journal Entry list
    Route::prefix('journal-list')->name('client.jl_')->group(function () {
        Route::get('profession/', [ClientJournalEntryController::class, 'listProfession'])->name('profession');
        Route::get('transaction/{client}/{profession}', [ClientJournalEntryController::class, 'trans'])->name('trans');
        Route::get('transaction/details/{client}/{profession}/{tran}', [ClientJournalEntryController::class, 'transShow'])->name('trans_show');
        Route::get('edit/{journal}', [ClientJournalEntryController::class, 'listEdit'])->name('edit');
        Route::get('delete/{journal}', [ClientJournalEntryController::class, 'listDelete'])->name('delete');
        Route::put('update/{journal}', [ClientJournalEntryController::class, 'listUpdate'])->name('update');
    });




    /*===================
    =! Add Depreciations
    ====================*/
    Route::prefix('depreciation')->name('client.dep_')->group(function () {
        Route::get('profession', [DepreciationController::class, 'index'])->name('index');
        Route::prefix('asset-group')->name('group.')->group(function () {
            Route::get('{client}/{profession}', [DepreciationController::class, 'assetGroupIndex'])->name('index');
            Route::post('store', [DepreciationController::class, 'assetGroupStore'])->name('store');
            Route::get('read', [DepreciationController::class, 'assetGroupRead'])->name('read');

            Route::get('edit', [DepreciationController::class, 'assetGroupEdit'])->name('edit');
            Route::post('update', [DepreciationController::class, 'assetGroupUpdate'])->name('update');
        });
        Route::prefix('asset-name')->name('name.')->group(function () {
            Route::get('create/{dep}', [DepreciationController::class, 'assetNameIndex'])->name('index');
            Route::post('store', [DepreciationController::class, 'assetNameStore'])->name('store');
            Route::get('read', [DepreciationController::class, 'assetNameRead'])->name('read');
            Route::get('edit', [DepreciationController::class, 'assetNameEdit'])->name('edit');
            Route::put('update', [DepreciationController::class, 'assetNameUpdate'])->name('update');
            Route::delete('destroy/{asset}/', [DepreciationController::class, 'assetNameDestroy'])->name('destroy');

            Route::put('active-asset/update', [DepreciationController::class, 'activeAssetUpdate'])->name('active_update');
            Route::get('financial-asset/update', [DepreciationController::class, 'financialAssetUpdate'])->name('assetName.update');
        });

        // Asse Desposal
        Route::prefix('disposal')->name('disposal.')->group(function () {
            Route::get('get/asset', [DesposalController::class, 'getAsset'])->name('getAsset');
            Route::get('get/modal', [DesposalController::class, 'getModal'])->name('getModal');
            Route::get('get/amount/{asset_name?}', [DesposalController::class, 'getAmount'])->name('getAmount');
            Route::post('post/asset/{asset}', [DesposalController::class, 'postAsset'])->name('postAsset');
        });

        /*================
        ===Asset Rollover/Reinstated
        =================*/
        Route::prefix('rollover')->name('rollover.')->group(function () {
            Route::get('/get/asset/name', [RolloverController::class, 'getAsset'])->name('get');
            Route::post('/post/asset/name', [RolloverController::class, 'post'])->name('post');
        });
        Route::prefix('reinstated')->name('reinstated.')->group(function () {
            Route::get('/get/asset/name', [RolloverController::class, 'reinstatedAsset'])->name('get');
            Route::delete('/post/asset/name/', [RolloverController::class, 'reinstatedPost'])->name('post');
        });
    });


    /*===================
    =! Budget Entry
    ====================*/
    // Route::prefix('budget')->name('client-budget.')->group(function () {
    // Route::get('/', [BudgetEntryController::class, 'index'])->name('index');
    // Route::get('create', [BudgetEntryController::class, 'create'])->name('create');
    // Route::post('store', [BudgetEntryController::class, 'store'])->name('store');
    // Route::get('edit/{budget}', [BudgetEntryController::class, 'edit'])->name('edit');
    // Route::put('update/{budget}', [BudgetEntryController::class, 'update'])->name('update');
    // Route::delete('destroy/{budget}', [BudgetEntryController::class, 'destroy'])->name('destroy');
    // });
    Route::resource('client-budget', BudgetEntryController::class);




    // prepare_payroll
    Route::prefix('prepayroll')->name('prepayroll')->group(function () {
        Route::get('empselect/{client}/{profession}', [PreparePayrollController::class, 'empselect'])->name('empselect')->middleware('packChecker');
        Route::get('payemp/getemp', [PreparePayrollController::class, 'payemp'])->name('payemp')->middleware('packChecker');
        Route::post('emplist/', [PreparePayrollController::class, 'emplist'])->name('emplist')->middleware('packChecker');
        Route::get('emplist/', [PreparePayrollController::class, 'emplist_redirect'])->name('emplist_redirect')->middleware('packChecker');
        Route::get('edit/{empCard}/{prepay}', [PreparePayrollController::class, 'preedit'])->name('preedit')->middleware('packChecker');
        Route::get('payg/', [PreparePayrollController::class, 'payg'])->name('payg')->middleware('packChecker');
        Route::post('payg/payslip/store', [PreparePayrollController::class, 'payslipStore'])->name('payslip.store')->middleware('packChecker');
    });
    Route::resource('prepayroll', PreparePayrollController::class)->middleware('packChecker');


    Route::resource('payaccumamt', PayAccumAmtController::class);

    // Payslip
    Route::get('/payroll/payslip', [PreparePayrollController::class, 'payslipIndex'])->name('payslip.index');
    Route::get('/payroll/payslip/report/{tran_id}/{pay_accum}', [PreparePayrollController::class, 'payslipReport'])->name('payslip.report');
    Route::get('/payslip/edit/{payslip}/{tran_id}', [PreparePayrollController::class, 'payslipEdit'])->name('payslip.edit');
    Route::put('/payslip/update/{payslip}/{tran_id}', [PreparePayrollController::class, 'payslipUpdate'])->name('payslip.update');
    Route::delete('/payslip/delete/{payslip}/{tran_id}', [PreparePayrollController::class, 'payslipDelete'])->name('payslip.delete');
    Route::post('/payroll/payslip/filter', [PreparePayrollController::class, 'payslipFilter'])->name('payslip.filter');
    Route::post('/payroll/payslip/print', [PreparePayrollController::class, 'payslipPrint'])->name('payslip.print');

    // data
    // Route::resource('SendDataAto', AtoDataController::class);
    Route::get('/payroll/SendDataAto/', [AtoDataController::class, 'index'])->name('SendDataAto.index');
    Route::get('/payroll/SendDataAto/all/', [AtoDataController::class, 'filter'])->name('SendDataAto.filter');
    Route::post('/payroll/SendDataAto/generate/', [AtoDataController::class, 'generate'])->name('generate_stp');
    Route::get('/payroll/SendDataAto/updateAtoReport', [AtoDataController::class, 'list'])->name('SendDataAto.list');
    Route::get('/payroll/SendDataAto/show/{ato}', [AtoDataController::class, 'show'])->name('SendDataAto.show');
    Route::put('/payroll/SendDataAto/update/{ato}', [AtoDataController::class, 'update'])->name('SendDataAto.update');
    Route::delete('/payroll/SendDataAto/delete/{ato}', [AtoDataController::class, 'delete'])->name('SendDataAto.delete');

    // manage_wages
    Route::get('manage/wages/', [ClientWagesController::class, 'index'])->name('wages.index');
    Route::post('manage/wages/store', [ClientWagesController::class, 'store'])->name('wages.store');
    Route::get('manage/wages/edit/{clientWages}', [ClientWagesController::class, 'edit'])->name('wages.edit');
    Route::put('manage/wages/update/{clientWages}', [ClientWagesController::class, 'update'])->name('wages.update');
    Route::get('manage/wages/delete/{clientWages}', [ClientWagesController::class, 'delete'])->name('wages.delete');

    // Manage SuperAnnuation
    Route::resource('/manage/clientannuation', ClientSuperannuationController::class);
    Route::get('/manage/clientannuation/delete/{clientannuation}', [ClientSuperannuationController::class, 'delete'])->name('clientannuation.delete');

    // Client Leave
    // Route::resource('/manage/clientleave', [ClientLeaveController::class, ');
    // Route::get('/manage/clientleave/delete/{clientleave}', [ClientLeaveController::class, 'delete'])->name('clientleave.delete');

    // Client Deductions
    Route::resource('/manage/clientdeduction', ClientDeductionController::class);
    Route::get('/manage/clientdeduction/delete/{clientdeduction}', [ClientDeductionController::class, 'delete'])->name('clientdeduction.delete');


    // employment
    Route::resource('employment/classification', EClassificationController::class);
    Route::get('employment/classification/delete/{classification}', [EClassificationController::class, 'delete'])->name('classification.delete');





    // peridoc_summery
    Route::get('/payroll/peridoc_summery/select_activity', [AllPageController::class, 'payroll_ps_select_activity'])->name('payrollPeridocSummerySelectActivity');
    Route::post('/payroll/peridoc_summery/report', [AllPageController::class, 'payroll_ps_report'])->name('payrollPeridocSummeryReport');

    // Reports
    Route::group(['prefix' => 'report'], function () {

        Route::group([
            'prefix' => 'trial-balance',
            'as'     => 'trial.'
        ], function () {
            Route::get('profession', [TrialBalanceController::class, 'profession'])->name('profession');
            Route::get('report', [TrialBalanceController::class, 'report'])->name('report');
        });

        Route::group([
            'prefix' => 'general-ledger',
            'as'     => 'ledger.'
        ], function () {
            Route::get('profession', [GeneralLedgerController::class, 'profession'])->name('profession');
            Route::get('select-accounts', [GeneralLedgerController::class, 'date'])->name('select');
            Route::get('report', [GeneralLedgerController::class, 'report'])->name('show');
            Route::get('transaction/{transaction_id}/{sourese}', [GeneralLedgerController::class, 'showTransaction'])->name('transaction');
            Route::get('details/transaction/{transaction_id}/{code}/{src?}', [GeneralLedgerController::class, 'showDataStoreTransaction'])->name('data_store.transaction');
            Route::get('ledgers-print-mail', [GeneralLedgerController::class, 'print'])->name('print');
        });

        // Balance Sheet Report
        Route::group([
            'prefix' => 'balance-sheet',
            'as'     => 'balance.'
        ], function () {
            Route::get('profession', [BalanceSheetController::class, 'index'])->name('select');
            Route::get('report', [BalanceSheetController::class, 'report'])->name('report');
        });

        // Comparative Balance Sheet Report
        Route::group([
            'prefix' => 'comparative-balance-sheet',
            'as'     => 'cbalance.'
        ], function () {
            Route::get('profession', [ComparativeBalanceSheetController::class, 'index'])->name('select');
            Route::get('report', [ComparativeBalanceSheetController::class, 'report'])->name('report');
        });
        // CashBasis Report
        Route::get('/cash-basis', [CashBasisController::class, 'index'])->name('cbasis.index');
        Route::get('/cash-basis/report', [CashBasisController::class, 'report'])->name('cbasis.report');
        // CashBasis Report
        Route::get('/accrued-basis', [AccuredBasisController::class, 'index'])->name('abasis.index');
        Route::get('/accrued-basis/report', [AccuredBasisController::class, 'report'])->name('abasis.report');
        // Excl Report
        Route::get('/excl', [ExclController::class, 'index'])->name('excl.index');
        Route::get('/excl/report', [ExclController::class, 'report'])->name('excl.report');
        // Incl Report
        Route::get('/incl', [InclController::class, 'index'])->name('incl.index');
        Route::get('/incl/report', [InclController::class, 'report'])->name('incl.report');

        /*==========
        === Advance Reports
        ===========*/

        // cash-basis
        Route::group([
            'prefix' => 'consolidation-cash-basis',
            'as'     => 'client_cash_basis.',
        ], function () {
            Route::get('/', [GstCashBasisController::class, 'index'])->name('index');
            Route::get('report', [GstCashBasisController::class, 'report'])->name('report');
        });

        // accrued-basis
        Route::group([
            'prefix' => 'consolidation-accrued-basis',
            'as'     => 'client_accrued_basis.',
        ], function () {
            Route::get('/', [GstAccruedBasisController::class, 'index'])->name('index');
            Route::get('report', [GstAccruedBasisController::class, 'report'])->name('report');
        });
        //! Accum P/L Gst
        Route::prefix('accum')->name('c.')->group(function () {
            // Accum GST Excl
            Route::get('excl/index', [AccumulatedPlGstReport::class, 'accumExclindex'])->name('accum_excl_index');
            Route::get('excl/final-report', [AccumulatedPlGstReport::class, 'accumExcleRport'])->name('accum_excl_report');

            // Accum GST Incl
            Route::get('incl/index', [AccumulatedPlGstReport::class, 'accumInclindex'])->name('accum_incl_index');
            Route::get('incl/final-report', [AccumulatedPlGstReport::class, 'accumIncleRport'])->name('accum_incl_report');
        });


        // depreciation_report
        Route::group([
            'prefix' => 'depreciation-report',
            'as' => 'cdep_report.'
        ], function () {
            Route::get('', [DepreciationReportController::class, 'index'])->name('index');
            Route::get('year/{client}/{profession}/', [DepreciationReportController::class, 'date'])->name('date');
            Route::get('report/{client}/{profession}/{year}', [DepreciationReportController::class, 'report'])->name('report');
            // Route::get('print/{client}/{profession}/{year}', [DepreciationReportController::class, 'print'])->name('print');
        });
        // Complete Financial Report
        Route::prefix('complete-financial')->as('cr_complete_financial.')->group(function () {
            Route::get('index', [CompleteReportController::class, 'index'])->name('index');
            Route::get('select-report', [CompleteReportController::class, 'selectReport'])->name('select_report');
            Route::get('final-report/{profession}', [CompleteReportController::class, 'report'])->name('report');
        });

        // Complete Financial Report T Form
        Route::controller(CompleteFinancialReportTFController::class)->prefix('complete-financial-report-t-form')->as('front_complete_financial_report_tf.')->group(function () {
            Route::get('index',  'index')->name('index');
            Route::get('select-report',  'selectReport')->name('select_report');
            Route::get('final-report/{profession}',  'report')->name('report');
            // Route::get('final-report-print/{client}/{profession}', 'print')->name('print');
        });

        // console financial report
        Route::group([
            'prefix' => 'console-financial-report',
            'as'     => 'cr_console_financial.'
        ], function () {
            Route::get('select-report', [ConsoleFinancialReportController::class, 'index'])->name('index');
            Route::get('final-report', [ConsoleFinancialReportController::class, 'report'])->name('report');
        });
        // Comperative Financial Report
        Route::prefix('comperative-financial-report')->as('cr_comperative_financial.')->group(function () {
            Route::get('select-report', [ComperativeFinancialReportController::class, 'index'])->name('index');
            Route::get('final-report', [ComperativeFinancialReportController::class, 'report'])->name('report');
        });
    });

    //! Advance Report
    Route::group([
        'prefix' => 'advance-report',
        'as' => 'client-avd.'
    ], function () {
        // Budget Report
        Route::group([
            'prefix' => 'budget',
            'as' => 'budget.'
        ], function () {
            Route::get('', [BudgetReportController::class, 'index'])->name('index');
            Route::get('report', [BudgetReportController::class, 'report'])->name('report');
            Route::get('print/{client}/{profession}/{year}', [BudgetReportController::class, 'print'])->name('print');
        });
        //Income & Expense Comparison
        Route::group([
            'prefix' => 'income-expense-comparison',
            'as' => 'income_expense_comparison.'
        ], function () {
            Route::get('', [IncomeExpenseComparisonController::class, 'index'])->name('index');
            Route::get('report', [IncomeExpenseComparisonController::class, 'report'])->name('report');
            Route::get('print/{client}/{profession}/{year}', [IncomeExpenseComparisonController::class, 'print'])->name('print');
        });
    });
    Route::get('support/{helpdesk:slug}', [HelpDeskController::class, 'byItem'])->name('helpdesk.byCat');
});

Route::get('action', [RecurringAutoGenerateAction::class, 'recurring'])->name('action');
