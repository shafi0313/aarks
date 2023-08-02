<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaygController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClientPaymentSync;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HelpDeskController;
use App\Http\Controllers\Google2faController;
use App\Http\Controllers\WebBackupController;
use App\Http\Controllers\AgentAuditController;
use App\Http\Controllers\FuelTaxLtrController;
use App\Http\Controllers\PeriodLockController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\BankStatementTranList;
use App\Http\Controllers\BudgetEntryController;
use App\Http\Controllers\CoefficientController;
use App\Http\Controllers\DataStorageController;
use App\Http\Controllers\JournalListController;
use App\Http\Controllers\MasterChartController;
use App\Http\Controllers\TrashActionController;
use App\Http\Controllers\VisitorInfoController;
use App\Http\Controllers\BusinessPlanController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AssetDesposalController;
use App\Http\Controllers\AssetRolloverController;
use App\Http\Controllers\FuelTaxCreditController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\StandardLeaveController;
use App\Http\Controllers\StandardWagesController;
use App\Http\Controllers\VerifyAccountController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AddDepreciationController;
use App\Http\Controllers\ClientDataDeleteController;
use App\Http\Controllers\ClientPaymentListController;
use App\Http\Controllers\Reports\ConsolePLController;
use App\Http\Controllers\StandardDeducationController;
use App\Http\Controllers\AccountCodeCategoryController;
use App\Http\Controllers\Reports\BudgetReportController;
use App\Http\Controllers\Reports\CashPeriodicController;
use App\Http\Controllers\Reports\GstCashBasisController;
use App\Http\Controllers\Reports\TrialBalanceController;
use App\Http\Controllers\AccountCodeValidationController;
use App\Http\Controllers\ProfessionAccountCodeController;
use App\Http\Controllers\Reports\GeneralLedgerController;
use App\Http\Controllers\ClientFixedAccountCodeController;
use App\Http\Controllers\StandardSuperannuationController;
use App\Http\Controllers\Reports\AccruedPeriodicController;
use App\Http\Controllers\Reports\GstAccruedBasisController;
use App\Http\Controllers\Reports\GstReconcilationController;
use App\Http\Controllers\Reports\BankReconcilationController;
use App\Http\Controllers\Reports\GstProfitLossExclController;
use App\Http\Controllers\Reports\GstProfitLossInclController;
use App\Http\Controllers\Reports\BalanceSheetReportController;
use App\Http\Controllers\Reports\BusinessPlanReportController;
use App\Http\Controllers\Reports\DepreciationReportController;
use App\Http\Controllers\Reports\ConsoleTrialBalanceController;
use App\Http\Controllers\Reports\ConsoleFinancialReportController;
use App\Http\Controllers\Reports\CompleteFinancialReportController;
use App\Http\Controllers\Reports\MonthlyBusinessAnalysisController;
use App\Http\Controllers\Reports\ComperativeBalanceReportController;
use App\Http\Controllers\Reports\CompleteFinancialReportTFController;
use App\Http\Controllers\Reports\ComperativeFinancialReportController;

Route::get('/', function () {
    return redirect()->route('index');
});
Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::get('admin/impersonate/destroy', [ClientController::class, 'destroyImpersonate'])->name('destroy.impersonate');

//? Google 2FA
Route::get('admin/google-2fa/active/{client}', [Google2faController::class, 'index'])->name('2fa.index');
Route::post('admin/google-2fa/store/{client}', [Google2faController::class, 'enable'])->name('2fa.store');
Route::post('admin/google-2fa/destroy/{client}', [Google2faController::class, 'destroy'])->name('2fa.destroy');
Route::post('admin/google-2fa-Verify', function () {
    return redirect(URL()->previous());
})->name('2faVerify')->middleware('2fa');

Route::get('select-two', [AdminDashboardController::class, 'selectTwo'])->name('select-two');
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('admin.profile');
    Route::resource('profession', ProfessionController::class);
    Route::resource('client', ClientController::class);
    Route::get('client/impersonate/{client}', [ClientController::class, 'impersonate'])->name('impersonate');

    Route::controller(ClientDataDeleteController::class)->prefix('client-data-delete')->name('client.data.')->group(function () {
        Route::get('/password', 'password')->name('password');
        Route::post('/checkPassword', 'checkPassword')->name('checkPassword');
        Route::get('/clients', 'client')->name('client');
        Route::delete('/delete/{client}', 'destroy')->name('destroy');
    });



    Route::get('master-chart', [MasterChartController::class, 'showMasterChart'])->name('master.chart');
    Route::delete('master-chart/delete-account-code', [MasterChartController::class, 'delete'])->name('delete.master.chart.account.code')->middleware('checkPassword');
    Route::get('master-chart/delete-sub-group', [MasterChartController::class, 'deleteSubCategory'])->name('delete.master.chart.sub.category');
    Route::delete('master-chart/additional_category/delete-sub-sub-group', [MasterChartController::class, 'deleteAdditionalCategory'])->name('delete.master.chart.additional.category')->middleware('checkPassword');
    Route::post('master-chart/sub-category', [MasterChartController::class, 'addSubCategory'])->name('create.master.sub.category');
    Route::post('master-chart/account-code', [MasterChartController::class, 'addAccountCode'])->name('create.master.account.code');
    Route::post('master-chart/account-code/edit', [MasterChartController::class, 'editAccountCode'])->name('edit.master.account.code');
    Route::post('master-chart/sub-sub-category', [MasterChartController::class, 'addAdditionalCategory'])->name('create.master.additional.category');

    Route::get('account-code/search-by-profession', [ProfessionAccountCodeController::class, 'showForm'])->name('code');
    Route::get('profession/{profession}/account-code', [ProfessionAccountCodeController::class, 'index'])->name('account-code.index');
    Route::post('account-code/edit', [ProfessionAccountCodeController::class, 'editAccountCode'])->name('edit.account.code');
    Route::post('account-code/edit', [ProfessionAccountCodeController::class, 'editAccountCode'])->name('edit.account.code');

    Route::post('generate/additional-category', [AccountCodeCategoryController::class, 'generateAdditionalCategory'])->name('create.additional.category');
    Route::post('account-code/sub-category', [AccountCodeCategoryController::class, 'store'])->name('create.sub.category');
    Route::post('generate/account-code', [ProfessionAccountCodeController::class, 'store'])->name('create.account.code');
    Route::delete('account-code/{profession_id}/{account_code}/delete', [ProfessionAccountCodeController::class, 'delete'])->name('delete.account.code')->middleware('checkPassword');
    Route::get('master-account-code-sync/{profession}', [MasterChartController::class, 'sync'])->name('master-account-code.sync');
    Route::resource('service', ServiceController::class);

    //help desk
    Route::post('help-category', [HelpDeskController::class, 'category'])->name('helpdesk.category');
    Route::get('sub-category', [HelpDeskController::class, 'subcategory'])->name('helpdesk.subcategory');
    Route::resource('helpdesk', HelpDeskController::class);

    //client-fixed-code
    Route::as('client_fixed_code.')->group(function () {
        Route::get('client-fixed-code', [ClientFixedAccountCodeController::class, 'index'])->name('index');
        Route::get('client-fixed-code/client/{client}', [ClientFixedAccountCodeController::class, 'selectProfession'])->name('profession');
        Route::get('client-fixed-code/codes/{client}/{profession}', [ClientFixedAccountCodeController::class, 'show'])->name('show');
        Route::put('client-fixed-code/codes/update', [ClientFixedAccountCodeController::class, 'update'])->name('update');
    });

    Route::get('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::resource('user', AdminController::class);
    Route::get('user/{admin}/deactivate', [AdminController::class, 'deactivate'])->name('user.deactivate');
    Route::get('user/{admin}/reactivate', [AdminController::class, 'reactivate'])->name('user.reactivate');
    Route::resource('role', RoleController::class);

    // !Permission
    Route::get('permission', [RoleController::class, 'permission'])->name('permission');
    Route::post('permission/store', [RoleController::class, 'permissionStore'])->name('permission.store');
    // !End Permission

    Route::resource('dataStore', DataStorageController::class);

    // !Bank Statement Import
    Route::resource('bs_import', ImportController::class);
    Route::group(['prefix' => 'bank-statement-import', 'as' => 'bs_import.'], function () {
        Route::get('{client}', [ImportController::class, 'showProfessions'])->name('professions');
        Route::get('show/{client}/{profession}', [ImportController::class, 'showBS'])->name('BS');
        Route::post('statement/post', [ImportController::class, 'post'])->name('post');
        Route::delete('deleteAll', [ImportController::class, 'deleteAll'])->name('delete');
        Route::get('update-account-name/{id}', [ImportController::class, 'updateAccountCode'])->name('updateCode');
    });

    // !Bank Statement Input Controller
    Route::resource('bs_input', InputController::class);
    Route::group(['prefix' => 'bank-statement-input', 'as' => 'bs_input.'], function () {
        Route::get('client-account-code', [InputController::class, 'getCodes'])->name('getcodes');
        Route::get('/client-account-balance', [InputController::class, 'getBalance'])->name('getBalance');
        Route::get('{client}', [InputController::class, 'showProfessions'])->name('professions');
        Route::get('{client}/{profession}', [InputController::class, 'inputBS'])->name('BS');
    });



    //!BankStatement Tran List report

    // Import tran List
    Route::prefix('bank-statement/transaction-list/')->name('bs_tran_list.')->group(function () {
        Route::get('/client', [BankStatementTranList::class, 'index'])->name('index');
        Route::get('/profession/{client}', [BankStatementTranList::class, 'profession'])->name('profession');
        Route::get('/client-account-balance', [BankStatementTranList::class, 'getBalance'])->name('getBalance');
        Route::get('/all/{client}/{profession}', [BankStatementTranList::class, 'report'])->name('report');
        Route::get('/details/{client}/{profession}/{tran_id}/{src}', [BankStatementTranList::class, 'detailsReport'])->name('details');

        // Import
        Route::group(['prefix' => 'details-import', 'as' => 'import.'], function () {
            Route::put('update/{client}/{profession}/{tran_id}', [BankStatementTranList::class, 'importUpdate'])->name('update');
            Route::get('destroy/{client}/{profession}/{bank_statement}', [BankStatementTranList::class, 'importDestroy'])->name('destroy');
            Route::get('delete/{client}/{profession}/{tran_id}/{src}', [BankStatementTranList::class, 'importDeleteAll'])->name('delete');
        });


        // Input
        Route::group(['prefix' => 'details-input', 'as' => 'input.'], function () {
            Route::put('update/{client}/{profession}/{tran_id}', [BankStatementTranList::class, 'inputUpdate'])->name('update');
            Route::get('destroy/{client}/{profession}/{bank_statement}', [BankStatementTranList::class, 'inputDestroy'])->name('destroy');
            Route::get('delete/{client}/{profession}/{tran_id}/{src}', [BankStatementTranList::class, 'inputDeleteAll'])->name('delete');
        });
    });

    // bank reconcilation for admin
    Route::group([
        'prefix' => 'bank-reconcilation',
        'as'     => 'bank_recon.',
    ], function () {
        Route::get('/', [BankReconcilationController::class, 'index'])->name('index');
        Route::get('get/client', [BankReconcilationController::class, 'getClient'])->name('client');
        Route::get('get/account-codes/{client}', [BankReconcilationController::class, 'getCodes'])->name('codes');
        Route::get('report', [BankReconcilationController::class, 'report'])->name('report');
        Route::post('store/{client}', [BankReconcilationController::class, 'store'])->name('store');
        Route::post('edit-permission/{client}', [BankReconcilationController::class, 'permission'])->name('permission');
    });

    Route::group(['prefix' => 'client-payment-sync', 'as' => 'payment_sync.'], function () {
        Route::get('client', [ClientPaymentSync::class, 'index'])->name('index');
        Route::get('search', [ClientPaymentSync::class, 'search'])->name('search');
        Route::delete('search/destroy', [ClientPaymentSync::class, 'destroy'])->name('destroy');

        // Route::get('profession/{client}', [ClientPaymentSync::class, 'profession'])->name('profession');
        // Route::get('list/{client}', [ClientPaymentSync::class, 'list'])->name('list');
        // Route::post('post', [ClientPaymentSync::class, 'post'])->name('post');
    });
    // Journal Entry
    Route::prefix('journal-entry')->group(function () {
        Route::get('client', [JournalEntryController::class, 'client'])->name('journal_entry_client');
        Route::get('profession/{client}', [JournalEntryController::class, 'profession'])->name('journal_entry_profession');
        Route::get('input/{client}/{profession}', [JournalEntryController::class, 'input'])->name('journal_entry_input');
        Route::get('input/store', [JournalEntryController::class, 'store'])->name('journal_entry.store');
        Route::get('input/read', [JournalEntryController::class, 'read'])->name('journal_entry.read');
        Route::get('delete/{journal}', [JournalEntryController::class, 'delete'])->name('journal_entry.delete');
        Route::post('input/post', [JournalEntryController::class, 'post'])->name('journal_entry.post');
        Route::get('journal_number', [JournalEntryController::class, 'number'])->name('journal_number');
    });


    // Journal Entry list
    Route::prefix('journal-list')->group(function () {
        Route::get('/', [JournalListController::class, 'index'])->name('journal_list.index');
        Route::get('profession/{id}', [JournalListController::class, 'listProfession'])->name('journal_list_profession');
        Route::get('transaction/{client}/{profession}', [JournalListController::class, 'trans'])->name('journal_list_trans');
        Route::get('transaction/details/{client}/{profession}/{tran}', [JournalListController::class, 'transShow'])->name('journal_list_trans_show');
        Route::get('edit/{journal}', [JournalListController::class, 'listEdit'])->name('journal_list_edit');
        Route::delete('delete/{journal}', [JournalListController::class, 'listDelete'])->name('journal_list_delete');
        Route::get('singleDelete/{journal}', [JournalListController::class, 'singleDelete'])->name('journal_list_singleDelete');
        Route::put('update/{journal}', [JournalListController::class, 'listUpdate'])->name('journal_list_update');
    });




    /*===================
    =! Add Depreciations
    ====================*/
    Route::prefix('depreciation')->name('depreciation.')->group(function () {
        Route::get('client', [AddDepreciationController::class, 'index'])->name('index');
        Route::get('profession/{client}', [AddDepreciationController::class, 'profession'])->name('profession');
        Route::prefix('asset-group')->name('group.')->group(function () {
            Route::get('{client}/{profession}', [AddDepreciationController::class, 'assetGroupIndex'])->name('index');
            Route::post('store', [AddDepreciationController::class, 'assetGroupStore'])->name('store');
            Route::get('read', [AddDepreciationController::class, 'assetGroupRead'])->name('read');

            Route::get('edit', [AddDepreciationController::class, 'assetGroupEdit'])->name('edit');
            Route::put('update', [AddDepreciationController::class, 'assetGroupUpdate'])->name('update');
        });
        Route::prefix('asset-name')->name('name.')->group(function () {
            Route::get('create/{dep}', [AddDepreciationController::class, 'assetNameIndex'])->name('index');
            Route::post('store', [AddDepreciationController::class, 'assetNameStore'])->name('store');
            Route::get('read', [AddDepreciationController::class, 'assetNameRead'])->name('read');
            Route::get('edit', [AddDepreciationController::class, 'assetNameEdit'])->name('edit');
            Route::post('update', [AddDepreciationController::class, 'assetNameUpdate'])->name('update');
            Route::delete('destroy/{asset}/', [AddDepreciationController::class, 'assetNameDestroy'])->name('destroy');

            Route::put('active-asset/update', [AddDepreciationController::class, 'activeAssetUpdate'])->name('active_update');
            Route::get('financial-asset/update', [AddDepreciationController::class, 'financialAssetUpdate'])->name('assetName.update');
        });

        /*================
        ===Asset Disposal
        =================*/
        Route::prefix('disposal')->name('disposal.')->group(function () {
            Route::get('get/asset', [AssetDesposalController::class, 'getDesposal'])->name('getAsset');
            Route::get('get/modal', [AssetDesposalController::class, 'getDesposalModal'])->name('getModal');
            Route::get('get/amount/{asset_name?}', [AssetDesposalController::class, 'getDesposalAmount'])->name('getAmount');
            Route::post('post/asset/{asset}', [AssetDesposalController::class, 'postDesposalAasset'])->name('postAsset');
        });
    });
    /*================
    ===Asset Rollover/Reinstated
    =================*/
    Route::prefix('rollover')->name('rollover.')->group(function () {
        Route::get('/get/asset/name', [AssetRolloverController::class, 'getAsset'])->name('get_asset');
        Route::post('/post/asset/name', [AssetRolloverController::class, 'post'])->name('post_asset');
    });
    Route::prefix('reinstated')->name('reinstated.')->group(function () {
        Route::get('/get/asset/name', [AssetRolloverController::class, 'reinstatedAsset'])->name('get_asset');
        Route::delete('/post/asset/name/', [AssetRolloverController::class, 'reinstatedPost'])->name('post_asset');
    });


    /*===================
    =! Budget Entry
    ====================*/
    Route::prefix('budget')->name('budget.')->group(function () {
        Route::get('/', [BudgetEntryController::class, 'index'])->name('index');
        Route::get('create', [BudgetEntryController::class, 'create'])->name('create');
        Route::post('store', [BudgetEntryController::class, 'store'])->name('store');
        Route::get('edit/{budget}', [BudgetEntryController::class, 'edit'])->name('edit');
        Route::put('update/{budget}', [BudgetEntryController::class, 'update'])->name('update');
        Route::delete('destroy/{budget}', [BudgetEntryController::class, 'destroy'])->name('destroy');
    });
    Route::resource('budget', BudgetEntryController::class);

    /*===================
    =! Business Plan
    ====================*/
    Route::resource('business-plan', BusinessPlanController::class);




    Route::post('payable', [ReportController::class, 'payable'])->name('payable');

    Route::resource('period', PeriodController::class);

    Route::get('select-method', [PeriodController::class, 'selectMethod'])->name('select_method');

    // !APP BACKUP
    Route::get('web-backup/confirm', [WebBackupController::class, 'load'])->name('backup.load');
    Route::post('web-backup/setpass', [WebBackupController::class, 'setPass'])->name('backup.setPass');
    Route::get('web-backup/', [WebBackupController::class, 'index'])->name('backup.index');

    Route::post('backup-file', [WebBackupController::class, 'backupFiles'])->name('backup.files');
    Route::post('backup-db', [WebBackupController::class, 'backupDb'])->name('backup.db');

    Route::get('web-backup/restore', [WebBackupController::class, 'restoreLoad'])->name('backup.restore');
    Route::post('web-backup/restore/post', [WebBackupController::class, 'restore'])->name('backup.restore.post');

    Route::post('/backup-download/{name}/{ext}', [WebBackupController::class, 'downloadBackup'])->name('backup.download');
    Route::post('/backup-delete/{name}/{ext}', [WebBackupController::class, 'deleteBackup'])->name('backup.delete');

    Route::get('client-profession/{client_id}', [PeriodController::class, 'client_pro'])->name('client-pro');
    Route::get('period/{profession_id}/{client_id}', [PeriodController::class, 'sh'])->name('period_shows');
    Route::get('edit-period/{profession_id}/{period_id}/{client_id}', [PeriodController::class, 'editperiod'])->name('edit_period');

    Route::resource('fuel', FuelTaxLtrController::class);
    Route::resource('payg', PaygController::class);
    Route::get('fuel/getRecord', [FuelTaxLtrController::class, 'getRecord'])->name('fuel.getRecord');
    Route::get('fuel/delete/{id}', [FuelTaxLtrController::class, 'delete'])->name('fuel.delete');



    Route::get('period/sub-pro/{sub_id}/{sub_code}/{Period_id}/{pro_id}/{client_id}', [PeriodController::class, 'sub_pro_show'])->name('sub_pro_show');




    // Add new IMP data Route

    Route::post('/bank-statement/imp/store', [InputController::class, 'bstImpStore'])->name('bst.imp.store');
    Route::get('/bank-statement/imp/update/{bst}', [InputController::class, 'bstImpEdit'])->name('bst.imp.edit');
    Route::put('/bank-statement/imp/update/{bst}', [InputController::class, 'bstImpUpdate'])->name('bst.imp.update');
    Route::get('/bank-statement/imp/delete/{bst}', [InputController::class, 'bstImpDelete'])->name('bst.imp.delete');




    Route::get('/bank-statement/store', [InputController::class, 'bankStatementStore'])->name('bank-statement.store');
    Route::get('/bank-statement/delete', [InputController::class, 'bankStatementDelete'])->name('bank-statement.delete');
    Route::post('/bank-statement/post', [InputController::class, 'post'])->name('bank-statement.post');

    Route::resource('FuelTaxCredit', FuelTaxCreditController::class);
    // Route::get('/ltr', [PeriodController::class, 'fuel_get']);


    Route::get('/blank', [ReportController::class, 'blank'])->name('blank');

    // gst-reconcilation
    Route::group([
        'prefix' => 'gst-reconcilation',
        'as'     => 'gst_recon.',
    ], function () {
        Route::get('/', [GstReconcilationController::class, 'index'])->name('index');
        Route::get('get/client', [GstReconcilationController::class, 'getClient'])->name('client');
        Route::get('profession/{client}', [GstReconcilationController::class, 'profession'])->name('profession');
        Route::get('period/{client}/{profession}', [GstReconcilationController::class, 'period'])->name('period');
        Route::get('report/{client}/{profession}/{period}', [GstReconcilationController::class, 'report'])->name('report');
        Route::post('store/{client}/{profession}/{period}', [GstReconcilationController::class, 'store'])->name('store');
        Route::post('edit-permission/{client}/{profession}/{period}', [GstReconcilationController::class, 'permission'])->name('permission');
        Route::post('save-note/{client}/{profession}', [GstReconcilationController::class, 'saveNote'])->name('save_note');
    });

    // cash-basis
    Route::group([
        'prefix' => 'cash-basis',
        'as'     => 'cash_basis.',
    ], function () {
        Route::get('/', [GstCashBasisController::class, 'index'])->name('index');
        Route::get('date/{client}', [GstCashBasisController::class, 'date'])->name('date');
        Route::get('report', [GstCashBasisController::class, 'report'])->name('report');
    });

    // accrued-basis
    Route::group([
        'prefix' => 'accrued-basis',
        'as'     => 'accrued_basis.',
    ], function () {
        Route::get('/', [GstAccruedBasisController::class, 'index'])->name('index');
        Route::get('date/{client}', [GstAccruedBasisController::class, 'date'])->name('date');
        Route::get('report', [GstAccruedBasisController::class, 'report'])->name('report');
    });
    // cash-periodic
    Route::group([
        'prefix' => 'cash-periodic',
        'as'     => 'cash_periodic.',
    ], function () {
        Route::get('/', [CashPeriodicController::class, 'index'])->name('index');
        Route::get('profession/{client}', [CashPeriodicController::class, 'profession'])->name('profession');
        Route::get('date/{client}/{profession}', [CashPeriodicController::class, 'date'])->name('date');
        Route::get('report/{client}/{profession}', [CashPeriodicController::class, 'report'])->name('report');
    });

    // accrued-periodic
    Route::group([
        'prefix' => 'accrued-periodic',
        'as'     => 'accrued_periodic.',
    ], function () {
        Route::get('/', [AccruedPeriodicController::class, 'index'])->name('index');
        Route::get('profession/{client}', [AccruedPeriodicController::class, 'profession'])->name('profession');
        Route::get('date/{client}/{profession}', [AccruedPeriodicController::class, 'date'])->name('date');
        Route::get('report', [AccruedPeriodicController::class, 'report'])->name('report');
    });


    // general-ledgers
    Route::group([
        'prefix' => 'general-ledgers',
        'as'     => 'general_ledger.',
    ], function () {
        Route::get('/', [GeneralLedgerController::class, 'index'])->name('index');
        Route::get('profession/{client}', [GeneralLedgerController::class, 'profession'])->name('profession');
        Route::get('date/{client}/{profession?}', [GeneralLedgerController::class, 'date'])->name('date');
        Route::get('report', [GeneralLedgerController::class, 'report'])->name('report');
        Route::get('transaction/{transaction_id}/{sourese}', [GeneralLedgerController::class, 'showTransaction'])->name('transaction');
        Route::get('details/transaction/{transaction_id}/{code}/{src?}', [GeneralLedgerController::class, 'showDataStoreTransaction'])->name('data_store.transaction');
        Route::get('ledgers-print-mail', [GeneralLedgerController::class, 'print'])->name('print');
    });

    // Trial Balance report
    Route::resource('trial-balance', TrialBalanceController::class);
    Route::group([
        'prefix' => 'trial-balance',
        'as' => 'trial-balance.'
    ], function () {
        Route::get('professions/{client}', [TrialBalanceController::class, 'showProfessions'])->name('professions');
        Route::get('{client}/{profession}/select-date', [TrialBalanceController::class, 'selectDate'])->name('selectDate');
        Route::get('{client}/{profession}/report/show', [TrialBalanceController::class, 'report'])->name('report');
    });
    // Console Trial Balance report
    Route::group([
        'prefix' => 'console-trial-balance',
        'as' => 'console_trial_balance.'
    ], function () {
        Route::get('/', [ConsoleTrialBalanceController::class, 'index'])->name('index');
        Route::get('{client}/select-date', [ConsoleTrialBalanceController::class, 'date'])->name('date');
        Route::get('{client}/report/show', [ConsoleTrialBalanceController::class, 'report'])->name('report');
    });

    // Profit and loss GST Exclusic
    Route::group([
        'prefix' => 'profit-loss-gst-excl',
        'as' => 'profit_loss_gst_excl.'
    ], function () {
        Route::get('', [GstProfitLossExclController::class, 'index'])->name('index');
        Route::get('profession/{id}', [GstProfitLossExclController::class, 'profession'])->name('profession');
        Route::get('date/{client}/{profession}', [GstProfitLossExclController::class, 'date'])->name('date');
        Route::get('report', [GstProfitLossExclController::class, 'report'])->name('report');
    });
    // Profit and loss GST Inclusic
    Route::group([
        'prefix' => 'profit-loss-gst-incl',
        'as' => 'profit_loss_gst_incl.'
    ], function () {
        Route::get('', [GstProfitLossInclController::class, 'index'])->name('index');
        Route::get('profession/{id}', [GstProfitLossInclController::class, 'profession'])->name('profession');
        Route::get('date/{client}/{profession}', [GstProfitLossInclController::class, 'date'])->name('date');
        Route::get('report', [GstProfitLossInclController::class, 'report'])->name('report');
    });
    //! Accumulated P/L Gst
    Route::prefix('console-accumulated')->as('console_accum.')->group(function () {
        // Accum GST Excl
        Route::get('excl/index', [ConsolePLController::class, 'exclindex'])->name('excl_index');
        Route::get('excl/select_date/{client}', [ConsolePLController::class, 'exclPeriod'])->name('excl_period');
        Route::get('excl/final-report', [ConsolePLController::class, 'excleRport'])->name('excl_report');

        // Accum GST Incl
        Route::get('incl/index', [ConsolePLController::class, 'inclindex'])->name('incl_index');
        Route::get('incl/select_date/{client}', [ConsolePLController::class, 'inclPeriod'])->name('incl_period');
        Route::get('incl/final-report', [ConsolePLController::class, 'incleRport'])->name('incl_report');
    });


    //!Balance Sheet
    Route::prefix('balance-sheet')->as('balance_sheet.')->group(function () {
        Route::get('index', [BalanceSheetReportController::class, 'index'])->name('index');
        Route::get('profession/{client}', [BalanceSheetReportController::class, 'profession'])->name('profession');
        Route::get('date/{client}/{profession}', [BalanceSheetReportController::class, 'date'])->name('date');
        Route::get('final-report/{client}/{profession}', [BalanceSheetReportController::class, 'report'])->name('report');
        Route::get('print/final-report/{client}/{profession}', [BalanceSheetReportController::class, 'reportPDF'])->name('print');
    });
    //!Console Balance Sheet
    Route::prefix('console-balance-sheet')->as('console_balance_sheet.')->group(function () {
        Route::get('index', [BalanceSheetReportController::class, 'consoleIndex'])->name('index');
        Route::get('select_date/{client}', [BalanceSheetReportController::class, 'consoleDate'])->name('date');
        Route::get('report/{client}', [BalanceSheetReportController::class, 'consoleReport'])->name('report');
    });

    //comperative balance sheet
    Route::group(['prefix' => 'comperative-balance-sheet', 'as' => 'comperative_balance_sheet.'], function () {
        Route::get('/', [ComperativeBalanceReportController::class, 'index'])->name('index');
        Route::get('profession/{client}', [ComperativeBalanceReportController::class, 'profession'])->name('profession');
        Route::get('date/{client}/{profession}', [ComperativeBalanceReportController::class, 'selectDate'])->name('date');
        Route::get('report/{client}/{profession}', [ComperativeBalanceReportController::class, 'report'])->name('report');
        Route::get('print/{client}/{profession}', [ComperativeBalanceReportController::class, 'print'])->name('print');
    });

    // Complete Financial Report
    Route::prefix('complete-financial-report')->as('complete_financial_report.')->group(function () {
        Route::get('index', [CompleteFinancialReportController::class, 'index'])->name('index');
        Route::get('select_profession/{id}', [CompleteFinancialReportController::class, 'profession'])->name('select_profession');
        Route::get('select-report/{client}/{profession}', [CompleteFinancialReportController::class, 'selectReport'])->name('financial_report');
        Route::get('final-report/{client}/{profession}', [CompleteFinancialReportController::class, 'report'])->name('report');
        Route::get('final-report-print/{client}/{profession}', [CompleteFinancialReportController::class, 'print'])->name('print');
    });

    // Complete Financial Report T Form
    Route::controller(CompleteFinancialReportTFController::class)->prefix('complete-financial-report-t-form')->as('complete_financial_report_tf.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('select_profession/{id}', 'profession')->name('select_profession');
        Route::get('select-report/{client}/{profession}', 'selectReport')->name('financial_report');
        Route::get('final-report/{client}/{profession}', 'report')->name('report');
        Route::get('final-report-print/{client}/{profession}', 'print')->name('print');
    });


    // console financial report
    Route::group([
        'prefix' => 'console-financial-report',
        'as'     => 'console_financial_report_'
    ], function () {
        Route::get('index', [ConsoleFinancialReportController::class, 'index'])->name('index');
        Route::get('select-report/{client}', [ConsoleFinancialReportController::class, 'selectReport'])->name('select');
        Route::get('final-report/{client}', [ConsoleFinancialReportController::class, 'report'])->name('report');
        Route::get('final-report-print/{client}', [ConsoleFinancialReportController::class, 'print'])->name('print');
    });
    // comparative financial report
    Route::group([
        'prefix' => 'comperative-financial-report',
        'as'     => 'comperative_financial_report_'
    ], function () {
        Route::get('index', [ComperativeFinancialReportController::class, 'index'])->name('index');
        Route::get('select-report/{client}', [ComperativeFinancialReportController::class, 'selectReport'])->name('select');
        Route::get('final-report/{client}', [ComperativeFinancialReportController::class, 'report'])->name('report');
        Route::get('final-report-print/{client}', [ComperativeFinancialReportController::class, 'print'])->name('print');
    });


    // depreciation_report
    Route::group([
        'prefix' => 'depreciation-report',
        'as' => 'depreciation_report.'
    ], function () {
        Route::get('', [DepreciationReportController::class, 'index'])->name('index');
        Route::get('profession/{id}', [DepreciationReportController::class, 'profession'])->name('profession');
        Route::get('date/{client}/{profession}', [DepreciationReportController::class, 'date'])->name('date');
        Route::get('report/{client}/{profession}/{year}', [DepreciationReportController::class, 'report'])->name('report');
        Route::get('print/{client}/{profession}/{year}', [DepreciationReportController::class, 'print'])->name('print');
    });

    //! Advance Report
    Route::group([
        'prefix' => 'advance-report',
        'as' => 'advance_report.'
    ], function () {
        Route::group([
            'prefix' => 'business-plan',
            'as' => 'business_plan.'
        ], function () {
            Route::get('', [BusinessPlanReportController::class, 'index'])->name('index');
            Route::get('report', [BusinessPlanReportController::class, 'report'])->name('report');
            Route::get('print/{client}/{profession}/{year}', [BusinessPlanReportController::class, 'print'])->name('print');
        });
        Route::group([
            'prefix' => 'budget',
            'as' => 'budget.'
        ], function () {
            Route::get('', [BudgetReportController::class, 'index'])->name('index');
            Route::get('report', [BudgetReportController::class, 'report'])->name('report');
            Route::get('print/{client}/{profession}/{year}', [BudgetReportController::class, 'print'])->name('print');
        });
        Route::group([
            'prefix' => 'business-analysis',
            'as' => 'business_analysis.'
        ], function () {
            Route::get('', [MonthlyBusinessAnalysisController::class, 'index'])->name('index');
            Route::get('report', [MonthlyBusinessAnalysisController::class, 'report'])->name('report');
            Route::get('print/{client}/{profession}/{year}', [MonthlyBusinessAnalysisController::class, 'print'])->name('print');
        });
    });

    // ratio_analysis
    Route::get('ratio_analysis_index', [ReportController::class, 'ratio_analysis_index'])->name('ratio_analysis_index');
    Route::get('ratio_analysis_select_profession', [ReportController::class, 'ratio_analysis_select_profession'])->name('ratio_analysis_select_profession');
    Route::get('ratio_analysis_financial_report', [ReportController::class, 'ratio_analysis_financial_report'])->name('ratio_analysis_financial_report');
    Route::get('ratio_analysis_final_report', [ReportController::class, 'ratio_analysis_final_report'])->name('ratio_analysis_final_report');


    // consolidate_cash_basis
    Route::get('consolidate_cash_basis_index', [ReportController::class, 'consolidate_cash_basis_index'])->name('consolidate_cash_basis_index');
    Route::get('consolidate_cash_basis_gst_bas', [ReportController::class, 'consolidate_cash_basis_gst_bas'])->name('consolidate_cash_basis_gst_bas');
    Route::get('consolidate_cash_basis_report', [ReportController::class, 'consolidate_cash_basis_report'])->name('consolidate_cash_basis_report');




    // Subscription Plans
    Route::resource('subscription/plan', SubscriptionController::class);
    // Client  payment
    Route::group(['prefix' => 'client-payment', 'as' => 'client_payment_'], function () {
        Route::get('/', [ClientPaymentListController::class, 'index'])->name('index');
        Route::get('list', [ClientPaymentListController::class, 'list'])->name('list');
        Route::get('details/{payment}', [ClientPaymentListController::class, 'pendingDetails'])->name('pending_details');
        Route::get('edit/{payment}', [ClientPaymentListController::class, 'edit'])->name('edit');
        Route::put('update/{paylist}', [ClientPaymentListController::class, 'update'])->name('update');
        Route::get('status/{paylist}', [ClientPaymentListController::class, 'status'])->name('status');
        Route::delete('delete/{paylist}', [ClientPaymentListController::class, 'delete'])->name('delete');
    });


    // Fuel Tax Credit
    Route::get('fuel_tax_credit', [TestController::class, 'fuel_tax_credit_index'])->name('fuel_tax_credit_index');


    // Tax Calculator
    Route::get('tax_calculator', [TestController::class, 'tax_calculator_index'])->name('tax_calculator_index');

    // Coefficient
    Route::resource('coefficient', CoefficientController::class)->except(['create', 'show']);

    // Wages
    // Route::resource('stanwages', StandardWagesController::class);
    Route::get('wages/', [StandardWagesController::class, 'index'])->name('stanwages.index');
    Route::post('wages/store', [StandardWagesController::class, 'store'])->name('stanwages.store');
    Route::get('wages/edit/{wages}', [StandardWagesController::class, 'edit'])->name('stanwages.edit');
    Route::put('wages/update/{wages}', [StandardWagesController::class, 'update'])->name('stanwages.update');
    Route::get('stanwages/delete/{wages}', [StandardWagesController::class, 'delete'])->name('stanwages.delete');


    // superannuation
    Route::resource('superannuation', StandardSuperannuationController::class);
    Route::get('superannuation/delete/{superannuation}', [StandardSuperannuationController::class, 'delete'])->name('superannuation.delete');
    // Leaves
    Route::resource('standardleave', StandardLeaveController::class);
    Route::get('standardleave/delete/{standardleave}', [StandardLeaveController::class, 'delete'])->name('standardleave.delete');
    // Leaves
    Route::resource('deducation', StandardDeducationController::class);
    Route::get('deducation/delete/{deducation}', [StandardDeducationController::class, 'delete'])->name('deducation.delete');



    // verify_accounts
    Route::name('verify_account.')->prefix('verify-accounts')->group(function () {
        Route::get('/', [VerifyAccountController::class, 'index'])->name('index');
        Route::get('profession/{client}', [VerifyAccountController::class, 'selectProfession'])->name('profession');
        Route::get('select-period/{client}/{profession}', [VerifyAccountController::class, 'selectDate'])->name('period');
        // Route::get('general_ledger', [VerifyAccountController::class, 'verify_accounts_general_ledger'])->name('general_ledger');
        Route::get('final-report/{client}/{profession}/', [VerifyAccountController::class, 'finalReport'])->name('report');
        Route::get('transastion/view/{tran_id}/{source}', [VerifyAccountController::class, 'tranView'])->name('tranView');
    });
    // fixed_accounts
    Route::get('fixed_accounts', [TestController::class, 'fixed_accounts_index'])->name('fixed_accounts_index');

    // period_lock
    Route::get('period_lock/client/{client}', [PeriodLockController::class, 'client'])->name('period_lock.client');
    Route::resource('period_lock', PeriodLockController::class);


    // financial_year_close & data backup
    Route::get('financial_year_close_lock', [PeriodLockController::class, 'financial_year_close_index'])->name('financial_year_close_index');
    Route::get('financial_year_close_lock/select_profession', [TestController::class, 'financial_year_close_select_profession'])->name('financial_year_close_select_profession');
    Route::get('financial_year_close_lock/select_year', [TestController::class, 'financial_year_close_select_year'])->name('financial_year_close_select_year');

    // payroll_year_close
    Route::get('payroll_year_close', [TestController::class, 'payroll_year_close_index'])->name('payroll_year_close_index');
    Route::get('financial_year_close_lock/payroll_year_close', [TestController::class, 'payroll_year_close_select_profession'])->name('payroll_year_close_select_profession');


    // data_restore
    Route::get('data_restore', [TestController::class, 'data_restore_index'])->name('data_restore_index');
    Route::get('data_restore/select_profession', [TestController::class, 'data_restore_select_profession'])->name('data_restore_select_profession');
    Route::get('data_restore/select_year', [TestController::class, 'data_restore_select_year'])->name('data_restore_select_year');


    // closed_year_report_financial
    Route::get('closed_year_report_financial', [TestController::class, 'closed_year_report_financial_index'])->name('closed_year_report_financial_index');
    Route::get('closed_year_report_financial/select_profession', [TestController::class, 'closed_year_report_financial_select_profession'])->name('closed_year_report_financial_select_profession');
    Route::get('closed_year_report_financial/select_year', [TestController::class, 'closed_year_report_financial_select_year'])->name('closed_year_report_financial_select_year');


    // agent-audit
    Route::prefix('agent-audit')->group(function () {
        Route::get('/', [AgentAuditController::class, 'index'])->name('audit.agent_index');
        Route::get('activity/{agent}', [AgentAuditController::class, 'activity'])->name('audit.agent_activity');
        Route::delete('activity-bulk-delete', [AgentAuditController::class, 'bulkDelete'])->name('audit.agent_delete_checked');
        Route::get('activity-bulk-destroy', [AgentAuditController::class, 'destroy'])->name('audit.agent_destroy');
        Route::get('activity/delete/{activity}', [AgentAuditController::class, 'delete'])->name('audit.agent_delete');
    });

    // logging_audit
    Route::get('logging_audit', [TestController::class, 'logging_audit_index'])->name('logging_audit_index');
    Route::get('visitor', [VisitorInfoController::class, 'index'])->name('visitor.index');
    Route::post('visitor/delete/selected', [VisitorInfoController::class, 'delSelected'])->name('visitor.delSelected');
    Route::get('visitor/delete/all', [VisitorInfoController::class, 'destroy'])->name('visitor.destroy');
});


Route::middleware('auth:admin', '2fa')->prefix('api')->group(function () {
    Route::get('check/account-code', [AccountCodeValidationController::class, 'checkAccountCode'])->name('check.account.code');
    Route::get('check/sub-category/account-code', [AccountCodeValidationController::class, 'checkSubCategoryCode'])->name('check.sub.category.account.code');
    Route::get('check/additional-category/account-code', [AccountCodeValidationController::class, 'checkAdditionalCategoryCode'])->name('check.additional.category.account.code');
    Route::get('/clients', [PeriodController::class, 'getClient']);
});

Route::middleware('auth:admin', '2fa')->prefix('trashed')->as('trash.')->group(function () {
    Route::get('/', [TrashController::class, 'index'])->name('index');
    Route::get('source/{client}', [TrashController::class, 'source'])->name('source');
    Route::get('details/{client}/{source}', [TrashController::class, 'details'])->name('details');
    Route::match(['put', 'delete'], 'restore/{client}/{item}/{source}/', [TrashActionController::class, 'index'])->name('restore');
    // Route::delete('destroy/{client}/{source}/{tran_id}', [TrashActionController::class, 'index'])->name('restore');
});

Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index']);
