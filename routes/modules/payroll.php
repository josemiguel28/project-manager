<?php

use App\Modules\Payroll\Livewire\WeekDetail;
use App\Modules\Payroll\Livewire\WeekManager;
use App\Modules\Payroll\Livewire\WorkerManager;
use Illuminate\Support\Facades\Route;

Route::get('/workers', WorkerManager::class)->name('payroll.workers');
Route::get('/weeks', WeekManager::class)->name('payroll.weeks');
Route::get('/weeks/{week}', WeekDetail::class)->name('payroll.weeks.show');
