<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', \App\Livewire\StartScreen::class)->name('start');
Route::get('/emails/{task}', \App\Livewire\TaskView::class)->name('task');
Route::get('/smtp-settings', \App\Livewire\SmtpSettings::class)->name('smtpSettings');
