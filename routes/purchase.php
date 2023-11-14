<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Purchase\BillReportController;
use App\Http\Controllers\Frontend\Purchase\PurchaseReportController;
use App\Http\Controllers\Frontend\Purchase\SupplierLedgerController;
use App\Http\Controllers\Frontend\Purchase\CreditorPaymentController;
use App\Http\Controllers\Frontend\Purchase\PurchaseRegisterController;
use App\Http\Controllers\Frontend\Purchase\Services\CreditorController;
use App\Http\Controllers\Frontend\Purchase\Items\CreditorItemController;
use App\Http\Controllers\Frontend\Purchase\Items\CreditorServiceItemController;
use App\Http\Controllers\Frontend\Purchase\Services\CreditorServiceOrderController;

Route::prefix('item-layout')->group(function () {
    // service_item Sevice Item
    Route::prefix('service-item')->name('service_item.')->group(function () {
        Route::get('create/{profession}', [CreditorServiceItemController::class, 'service'])->name('quote');
        Route::get('template/show', [CreditorServiceItemController::class, 'templateShow'])->name('tmShow');
        Route::get('job/show', [CreditorServiceItemController::class, 'jobShow'])->name('jobshow');
        Route::get('manage/list', [CreditorServiceItemController::class, 'manage'])->name('manage');
        Route::get('manage/list/delete/', [CreditorServiceItemController::class, 'delete'])->name('delete');

        Route::get('manage/delete/', [CreditorServiceItemController::class, 'de'])->name('convert');
        Route::get('manage/convert/', [CreditorServiceItemController::class, 'convertInvoice'])->name('convert');
        Route::get('manage/convert/view/{client}/{profession}/{inv}', [CreditorServiceItemController::class, 'convertView'])->name('convertView');
        Route::post('manage/convert/store/{client}/{profession}/{inv}', [CreditorServiceItemController::class, 'convertStore'])->name('convertStore');
    });
    Route::resource('service_item', CreditorServiceItemController::class);
    // Service Enter Item
    Route::prefix('enter-item')->name('enter_item.')->group(function () {
        Route::get('create/{profession}', [CreditorItemController::class, 'quote'])->name('enter');
        Route::get('manage/list/', [CreditorItemController::class, 'manage'])->name('manage');
        Route::get('manage/customerList', [CreditorItemController::class, 'customerList'])->name('clist');
        Route::get('manage/bill-edit/{bill}/{client}', [CreditorItemController::class, 'edit'])->name('billedit');
        Route::put('manage/bill-update/{bill}/{client}', [CreditorItemController::class, 'update'])->name('billupdate');
    });
    Route::resource('enter_item', CreditorItemController::class);
});

// Dedotr and Creditor  Services
Route::prefix('services-layout')->group(function () {
    // service_order
    Route::prefix('service-order')->group(function () {
        Route::get('create/{profession}', [CreditorServiceOrderController::class, 'service'])->name('service_order.quote');
        Route::get('template/show', [CreditorServiceOrderController::class, 'templateShow'])->name('service_order.tmShow');
        Route::get('job/show', [CreditorServiceOrderController::class, 'jobShow'])->name('service_order.jobshow');
        Route::get('manage/list', [CreditorServiceOrderController::class, 'manage'])->name('service_order.manage');
        Route::get('manage/list/delete/', [CreditorServiceOrderController::class, 'delete'])->name('service_order.delete');

        Route::get('manage/delete/', [CreditorServiceOrderController::class, 'de'])->name('service_order.convert');
        Route::get('manage/convert/', [CreditorServiceOrderController::class, 'convertInvoice'])->name('service_order.convert');
        Route::get('manage/convert/view/{inv}', [CreditorServiceOrderController::class, 'convertView'])->name('service_order.convertView');
        Route::post('manage/convert/store/{quote}', [CreditorServiceOrderController::class, 'convertStore'])->name('service_order.convertStore');
    });
    Route::resource('service_order', CreditorServiceOrderController::class)->except(['edit']);
    Route::get('/service_order/edit/{proId}/{cusCardId}/{invNo}', [CreditorServiceOrderController::class, 'edit'])->name('service_order.edit');
    Route::delete('/service_order/destroy/{proId}/{cusCardId}/{invNo}', [CreditorServiceOrderController::class, 'destroy'])->name('service_order.destroy');

    // Service Enter BILL
    Route::prefix('service-bill')->as('service_bill.')->group(function () {
        Route::get('create/{profession}', [CreditorController::class, 'create'])->name('enter');
        Route::get('manage/list/', [CreditorController::class, 'manage'])->name('manage');
        Route::get('manage/customerList', [CreditorController::class, 'customerList'])->name('clist');
        Route::get('manage/bill-edit/{bill}/{client}', [CreditorController::class, 'edit'])->name('billedit');
        Route::put('manage/bill-update/{bill}/{client}', [CreditorController::class, 'update'])->name('billupdate');
        Route::delete('manage/bill-delete/', [CreditorController::class, 'delete'])->name('delete');
    });
    Route::resource('service_bill', CreditorController::class);
});


//!Creditor Payment
Route::prefix('bill/payment')->name('spayment.')->group(function () {
    Route::get('/', [CreditorPaymentController::class, 'index'])->name('index');
    Route::get('profession/{customer}', [CreditorPaymentController::class, 'profession'])->name('profession');
    Route::get('form/{customer}/{profession}', [CreditorPaymentController::class, 'paymentForm'])->name('form');
    Route::post('/store', [CreditorPaymentController::class, 'paymentStore'])->name('store');
    Route::get('/list', [CreditorPaymentController::class, 'paymentList'])->name('list');
    Route::get('/list/report/{payment}', [CreditorPaymentController::class, 'report'])->name('report');
    Route::get('/list/report/{payment}/print', [CreditorPaymentController::class, 'printReport'])->name('printReport');
    Route::get('/list/report/{payment}/mail', [CreditorPaymentController::class, 'mailReport'])->name('mailReport');
    Route::delete('/list/destroy/{payment}', [CreditorPaymentController::class, 'destroy'])->name('destroy');
});

Route::controller(CreditorServiceOrderController::class)->prefix('/manage/order')->name('order.')->group(function(){
    Route::get('/show/{source}/{client}/{proId}/{customer}/{inv_no}','show')->name('show');
    // Route::get('/mail/{source}/{client}/{proId}/{customer}/{inv_no}', 'mail')->name('mail');
    Route::get('/mail-view/{source}/{client}/{proId}/{customer}/{inv_no}', 'viewableMail')->name('viewable_mail');
    Route::get('/email-view-report/{source?}/{inv_no}/{client}',  'emailViewReport')->name('email_view_report');
    Route::get('/print/{source}/{client}/{proId}/{customer}/{inv_no}','print')->name('print');
});

//! Invoice Reports
Route::get('/bill/report/{source}/{inv_no}/{client}', [BillReportController::class, 'report'])->name('bill.report');
Route::get('/bill/report/print/{source}/{inv_no}/{client}', [BillReportController::class, 'print'])->name('bill.report.print');
Route::get('/bill/report/mail/{source}/{inv_no}/{client}', [BillReportController::class, 'mail'])->name('bill.report.mail');



// ! Bill  Viewable Routes
Route::get('bill-report-mail/{source}/{inv_no}/{client}', [BillReportController::class, 'viewableMail'])->name('bill.viewable_mail');
Route::get('email-view-Bill-report/{source?}/{inv_no}/{client}', [BillReportController::class, 'emailViewReport'])->name('bill.email_view_report');

// purchase_Register
Route::get('/purchase-register/select-date', [PurchaseRegisterController::class, 'index'])->name('purchaseRegIndex');
Route::get('/purchase-register/list', [PurchaseRegisterController::class, 'report'])->name('purchaseRegReport');

//Supplier ledger
Route::get('supplier/ledger', [SupplierLedgerController::class, 'index'])->name('sledger.index');
Route::get('supplier/ledger/show', [SupplierLedgerController::class, 'show'])->name('sledger.show');
Route::get('supplier/ledger/inv-view/{dedotr}', [SupplierLedgerController::class, 'invView'])->name('sledger.inv_view');
Route::get('supplier/ledger/payment/{dedotr}', [SupplierLedgerController::class, 'payment'])->name('sledger.payment');

//Creditor / Supplier report
Route::get('creditor-report/select-date', [PurchaseReportController::class, 'index'])->name('creditor_report.index');
Route::get('creditor-report/show', [PurchaseReportController::class, 'report'])->name('creditor_report.report');
