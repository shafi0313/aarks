<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AllPageController;
use App\Http\Controllers\Frontend\Sales\DedotorReportController;
use App\Http\Controllers\Frontend\Sales\InvoiceReportController;
use App\Http\Controllers\Frontend\Sales\SalesRegisterController;
use App\Http\Controllers\Frontend\Sales\CustomerLedgerController;
use App\Http\Controllers\Frontend\Sales\ReceivePaymentController;
use App\Http\Controllers\Frontend\Sales\Services\DedotrController;
use App\Http\Controllers\Frontend\Sales\Items\ItemRecurringController;
use App\Http\Controllers\Frontend\Sales\Items\DedotrQuoteItemController;
use App\Http\Controllers\Frontend\Sales\Services\DedotrInvoiceController;
use App\Http\Controllers\Frontend\Sales\Items\DedotrInvoiceItemController;
use App\Http\Controllers\Frontend\Sales\Services\DedotrQuoteOrderController;
use App\Http\Controllers\Frontend\Sales\Services\ServiceRecurringController;


Route::prefix('item-layout')->group(function () {
    //Quote Item routes
    Route::prefix('quote-item')->name('quote_item.')->group(function () {
        Route::get('create/{profession}', [DedotrQuoteItemController::class, 'quoteItem'])->name('service');
        Route::get('manage/list/', [DedotrQuoteItemController::class, 'manage'])->name('manage');
        Route::get('manage/convert/', [DedotrQuoteItemController::class, 'convertInvoice'])->name('convert');
        Route::get('manage/detele/', [DedotrQuoteItemController::class, 'delete'])->name('delete');
        Route::get('manage/convert/manage/view/{inv}', [DedotrQuoteItemController::class, 'convertView'])->name('convertView');
        Route::get('manage/convert/store/{quote_item}', [DedotrQuoteItemController::class, 'convertStore'])->name('convertStore');
    });
    Route::resource('quote_item', DedotrQuoteItemController::class);
    // invoice item
    Route::prefix('invoice-item')->name('invoice_item.')->group(function () {
        Route::get('create/{profession}', [DedotrInvoiceItemController::class, 'quote'])->name('quote');
        Route::get('manage/list/', [DedotrInvoiceItemController::class, 'manage'])->name('manage');
        Route::get('manage/customerList', [DedotrInvoiceItemController::class, 'customerList'])->name('clist');
        Route::get('manage/inv-edit/{inv}/{client}/{customer}', [DedotrInvoiceItemController::class, 'edit'])->name('invedit');
        Route::put('manage/inv-update/{inv}/{client}/{profession}', [DedotrInvoiceItemController::class, 'update'])->name('invupdate');
        Route::delete('manage/inv-delete/', [DedotrInvoiceItemController::class, 'delete'])->name('delete');
    });
    Route::resource('invoice_item', DedotrInvoiceItemController::class);
    // recurrng services
    Route::resource('recurring_item', ItemRecurringController::class);
    Route::get('recurring_item/manage/list', [ItemRecurringController::class, 'manage'])->name('recurring_item.manage');
    Route::get('recurring-item-auto-generate', [ItemRecurringController::class, 'recurring'])->name('recurring_item.recurring_item');
    Route::delete('recurring-item-delete/', [ItemRecurringController::class, 'delete'])->name('recurring_item.delete');
});

// Dedotr and Creditor  Services
Route::prefix('services-layout')->group(function () {
    Route::prefix('quote')->group(function () {
        Route::get('create/{profession}', [DedotrQuoteOrderController::class, 'quote'])->name('quote.quote');
        // Template
        Route::post('template/store', [DedotrQuoteOrderController::class, 'templateStore'])->name('quote.tmstore');
        Route::get('template/show', [DedotrQuoteOrderController::class, 'templateShow'])->name('quote.tmShow');
        Route::post('template/update', [DedotrQuoteOrderController::class, 'templateUpdate'])->name('quote.tm.update');
        Route::get('template/delete/', [DedotrQuoteOrderController::class, 'templateDelete'])->name('quote.tmDelete');
        // Job
        Route::post('job/store', [DedotrQuoteOrderController::class, 'jobStore'])->name('quote.jobstore');
        Route::get('job/show', [DedotrQuoteOrderController::class, 'jobShow'])->name('quote.jobshow');
        Route::post('job/update', [DedotrQuoteOrderController::class, 'jobUpdate'])->name('quote.jobUpdate');
        Route::get('job/delete/', [DedotrQuoteOrderController::class, 'jobDelete'])->name('quote.jobdelete');
        // Quote/Invoice
        Route::get('manage/list/', [DedotrQuoteOrderController::class, 'manage'])->name('quote.manage');
        Route::get('manage/convert/', [DedotrQuoteOrderController::class, 'convertInvoice'])->name('quote.convert');
        Route::get('manage/detele/', [DedotrQuoteOrderController::class, 'delete'])->name('quote.delete');
        Route::get('manage/convert/manage/view/{inv}', [DedotrQuoteOrderController::class, 'convertView'])->name('quote.convertView');
        Route::get('manage/convert/store/{quote}', [DedotrQuoteOrderController::class, 'convertStore'])->name('quote.convertStore');
    });
    Route::resource('quote', DedotrQuoteOrderController::class);
    Route::prefix('dedotr')->group(function () {
        Route::post('template/store', [DedotrController::class, 'templateStore'])->name('dedotrs.tmstore');
        Route::get('template/show', [DedotrController::class, 'templateShow'])->name('dedotrs.tmShow');
        Route::get('template/delete/', [DedotrController::class, 'templateDelete'])->name('dedotrs.tmDelete');
        Route::post('job/store', [DedotrController::class, 'jobStore'])->name('dedotrs.jobstore');
        Route::get('job/show', [DedotrController::class, 'jobShow'])->name('dedotrs.jobshow');
        Route::get('job/delete/', [DedotrController::class, 'jobDelete'])->name('dedotrs.jobdelete');
        Route::get('/item-quote', [AllPageController::class, 'itemQuoteCreate'])->name('itemQuoteCreate');
        Route::get('/quote_convert_invoice', [AllPageController::class, 'quote_convert_invoice'])->name('quote_convert_invoice');
    });
    Route::resource('dedotr', DedotrController::class);
    // invoice
    Route::resource('invoice', DedotrInvoiceController::class);
    Route::prefix('invoice')->as('invoice.')->group(function () {
        Route::get('create/{profession}', [DedotrInvoiceController::class, 'quote'])->name('quote');
        Route::get('manage/list/', [DedotrInvoiceController::class, 'manage'])->name('manage');
        Route::get('manage/customerList', [DedotrInvoiceController::class, 'customerList'])->name('clist');
        Route::get('manage/inv-edit/{inv}/{client}/{customer}', [DedotrInvoiceController::class, 'edit'])->name('invedit');
        Route::put('manage/inv-update/{inv}/{client}/{profession}', [DedotrInvoiceController::class, 'update'])->name('invupdate');
        Route::delete('manage/inv-delete/', [DedotrInvoiceController::class, 'delete'])->name('delete');
    });

    // recurrng services
    Route::resource('recurring', ServiceRecurringController::class);
    Route::get('recurring/manage/list', [ServiceRecurringController::class, 'manage'])->name('recurring.manage');
    Route::get('email-view-recurring/{source?}/{inv_no}/{client}', [ServiceRecurringController::class, 'emailViewRecurring'])->name('recurring.email_view_report');
    Route::get('recurring-print/{source?}/{inv_no}/{client}/{type?}', [ServiceRecurringController::class, 'print'])->name('recurring.print');
    Route::get('recurring-auto-generate', [ServiceRecurringController::class, 'recurring'])->name('recurring.recurring');
    Route::get('recurring-get-customer-info', [ServiceRecurringController::class, 'getCustomerInfo'])->name('recurring.getCustomerInfo');
});

// payment
Route::prefix('payment')->group(function () {
    Route::get('/', [ReceivePaymentController::class, 'index'])->name('payment.index');
    Route::get('profession/{customer}', [ReceivePaymentController::class, 'profession'])->name('payment.profession');
    Route::get('payment_form/{customer}/{profession}', [ReceivePaymentController::class, 'paymentForm'])->name('payment.form');
    Route::post('store', [ReceivePaymentController::class, 'paymentStore'])->name('payment.store');
    Route::get('list', [ReceivePaymentController::class, 'paymentList'])->name('payment.list');
    Route::get('list/report/{payment}', [ReceivePaymentController::class, 'report'])->name('payment.report');
    Route::get('list/report/{payment}/print', [ReceivePaymentController::class, 'reportPrint'])->name('payment.reportPrint');
    Route::get('list/report/{payment}/mail', [ReceivePaymentController::class, 'reportMail'])->name('payment.reportMail');
    Route::delete('list/destroy/{payment}', [ReceivePaymentController::class, 'destroy'])->name('payment.destroy');
});



//! Invoice Reports
Route::get('/invoice/report/{source}/{inv_no}/{client}/{customer}', [InvoiceReportController::class, 'report'])->name('inv.report');
Route::get('/invoice/report/print/{source}/{inv_no}/{client}/{customer}', [InvoiceReportController::class, 'print'])->name('inv.report.print');
Route::get('/invoice/report/mail/{source}/{inv_no}/{client}', [InvoiceReportController::class, 'mail'])->name('inv.report.mail');

// ! Invoice Viewable Routes
Route::get('inv-report-mail/{source}/{inv_no}/{client}/{customer}', [InvoiceReportController::class, 'viewableMail'])->name('inv.viewable_mail');
Route::get('email-view-invoice-report/{source?}/{inv_no}/{client}', [InvoiceReportController::class, 'emailViewReport'])->name('inv.email_view_report');


// sales_Register
Route::get('/register/select-date', [SalesRegisterController::class, 'index'])->name('salesRegIndex');
Route::get('/register/list', [SalesRegisterController::class, 'report'])->name('salesRegReport');

//Customer ledger
Route::get('customer/ledger', [CustomerLedgerController::class, 'index'])->name('cledger.index');
Route::get('customer/ledger/show', [CustomerLedgerController::class, 'show'])->name('cledger.show');
Route::get('customer/ledger/inv-view/{dedotr}', [CustomerLedgerController::class, 'invView'])->name('cledger.inv_view');
Route::get('customer/ledger/payment/{dedotr}', [CustomerLedgerController::class, 'payment'])->name('cledger.payment');

//Dedotr/Customer report
Route::get('dedotrs-report/select-date', [DedotorReportController::class, 'index'])->name('debtors_report.index');
Route::get('dedotrs-report/show', [DedotorReportController::class, 'report'])->name('debtors_report.report');


