<?php

use App\Http\Controllers\Admin\CleaningController;
use App\Http\Controllers\Admin\DeskController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MyDeskController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/my-desk');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Employee area
    Route::get('/my-desk', [MyDeskController::class, 'index'])->name('my-desk');
    Route::post('/my-desk/use', [MyDeskController::class, 'useOwnDesk'])->name('my-desk.use');
    Route::put('/presets/{kind}', [MyDeskController::class, 'updatePreset'])->name('presets.update');
    Route::delete('/presets/{kind}', [MyDeskController::class, 'deletePreset'])->name('presets.destroy');
    Route::post('/desks/{desk}/move', [MyDeskController::class, 'moveDesk'])->name('desks.move');

    // Admin area
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/desks', [DeskController::class, 'index'])->name('desks.index');
        Route::post('/desks', [DeskController::class, 'store'])->name('desks.store');
        Route::patch('/desks/{desk}', [DeskController::class, 'updateHeight'])->name('desks.update');
        Route::delete('/desks/{desk}', [DeskController::class, 'destroy'])->name('desks.destroy');

        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

        Route::get('/cleaning', [CleaningController::class, 'index'])->name('cleaning.index');
        Route::put('/cleaning', [CleaningController::class, 'update'])->name('cleaning.update');
        Route::post('/cleaning/start', [CleaningController::class, 'start'])->name('cleaning.start');
        Route::post('/cleaning/end', [CleaningController::class, 'end'])->name('cleaning.end');
    });
});
