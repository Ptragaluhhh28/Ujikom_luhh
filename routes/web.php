<?php

use App\Http\Controllers\LoginController;

Route::get('/', function () { return view('landing'); });
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', function () { return view('auth.register'); });


Route::get('/admin/login', function () { return view('admin.login'); })->name('admin.login');
Route::get('/admin', function () { return view('admin.dashboard'); })->middleware(\App\Http\Middleware\CheckAdmin::class);
Route::get('/owner', function () { return view('owner.dashboard'); });
Route::get('/owner/motors', function () { return view('owner.motors.index'); });
Route::get('/owner/motors/create', function () { return view('owner.motors.create'); });
Route::get('/owner/motors/{id}', function () { return view('owner.motors.show'); });
Route::get('/owner/motors/{id}/edit', function () { return view('owner.motors.edit'); });

Route::get('/renter', function () { return view('renter.index'); });
Route::get('/renter/motors/{id}/book', function () { return view('renter.booking'); });
