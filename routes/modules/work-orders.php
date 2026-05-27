<?php

use App\Modules\WorkOrders\Livewire\WorkOrderManager;
use Illuminate\Support\Facades\Route;

Route::get('/', WorkOrderManager::class)->name('parkers.store');
