<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Calendar\HomeController;
use App\Http\Controllers\Calendar\InvoiceController;
use App\Http\Controllers\Calendar\RoomController;


Route::name('calendar.')->group(function(){
    Route::get('/', [HomeController::class,'index'])->name('home');
    Route::controller(RoomController::class)->prefix('rooms')->name('rooms.')->group(function(){
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::post('/store', [RoomController::class, 'store'])->name('store');
        Route::delete('/destroy', [RoomController::class, 'destroy'])->name('destroy');
    });

    Route::controller(InvoiceController::class)->prefix('invoices')->name('invoices.')->group(function(){
        Route::get('/{calendar_id?}', 'index')->name('index');
        Route::get('/create/{profession}/{calendar_id}', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{inv}/{client}/{customer}', 'edit')->name('edit');
        Route::put('/update/{inv}/{client}/{profession}', 'update')->name('update');

        Route::get('/report/{source}/{inv_no}/{client}/{customer}/{calendar_id}', 'report')->name('report');
        Route::get('/report/print/{source}/{inv_no}/{client}/{customer}/{calendar_id}', 'print')->name('report.print');
        // Route::get('/invoice/report/mail/{source}/{inv_no}/{client}', 'mail')->name('inv.report.mail');

    });
    // Not working in group
    Route::get('/get_tax', [InvoiceController::class, 'getTax'])->name('get_tax');

});


Route::get('/events', [HomeController::class,'getEvents'])->name('events');
Route::post('/store-schedule', [HomeController::class,'store'])->name('store-schedule');
Route::get('/sync-calender', [HomeController::class,'sync_calender'])->name('sync-calender');
Route::get('/edit/{id}', [HomeController::class,'find'])->name('edit');
Route::get('/delete/{id}', [HomeController::class,'delete'])->name('delete');

Route::delete('/destroy/{id}', [HomeController::class,'destroy'])->name('destroy');
Route::post('/update', [HomeController::class,'update'])->name('update');

Route::post('/events-store', [HomeController::class,'event_store'])->name('events.store'); //*** */
Route::post('/events-delete', [HomeController::class,'destroy'])->name('events-delete');
Route::get('/events-edit/{id}', [HomeController::class,'event_edit'])->name('events-edit');
Route::post('/events-update', [HomeController::class,'event_update'])->name('events-update');
// Route::post('/setting-update', [SettingController::class,'setting_update'])->name('setting-update');


// Route::get('/users', [HomeController::class,'users'])->name('users');
// Route::post('/user-store', [HomeController::class,'user_store'])->name('user-store');
// Route::post('/user-delete', [HomeController::class,'destroy'])->name('user-delete');
// Route::get('/user-edit/{id}', [HomeController::class,'user-edit'])->name('user-edit');
Route::post('/user-update', [HomeController::class,'user_update'])->name('user-update');


