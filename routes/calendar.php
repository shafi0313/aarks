<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Calendar\HomeController;
use App\Http\Controllers\Calendar\RoomController;


Route::name('calender.')->group(function(){
    Route::get('/', [HomeController::class,'index'])->name('home');
    Route::post('/rooms/store', [RoomController::class, 'store'])->name('rooms.store');
});


Route::get('/events', [HomeController::class,'getEvents'])->name('events');
Route::post('/store-schedule', [HomeController::class,'store'])->name('store-schedule');
Route::get('/sync-calender', [HomeController::class,'sync_calender'])->name('sync-calender');
Route::get('/edit/{id}', [HomeController::class,'find'])->name('edit');
Route::get('/delete/{id}', [HomeController::class,'delete'])->name('delete');

Route::delete('/destroy/{id}', [HomeController::class,'destroy'])->name('destroy');
Route::post('/update', [HomeController::class,'update'])->name('update');

Route::post('/events-store', [HomeController::class,'event_store'])->name('events-store');
Route::post('/events-delete', [HomeController::class,'destroy'])->name('events-delete');
Route::get('/events-edit/{id}', [HomeController::class,'event_edit'])->name('events-edit');
Route::post('/events-update', [HomeController::class,'event_update'])->name('events-update');
// Route::post('/setting-update', [SettingController::class,'setting_update'])->name('setting-update');


// Route::get('/users', [HomeController::class,'users'])->name('users');
// Route::post('/user-store', [HomeController::class,'user_store'])->name('user-store');
// Route::post('/user-delete', [HomeController::class,'destroy'])->name('user-delete');
// Route::get('/user-edit/{id}', [HomeController::class,'user-edit'])->name('user-edit');
Route::post('/user-update', [HomeController::class,'user_update'])->name('user-update');


