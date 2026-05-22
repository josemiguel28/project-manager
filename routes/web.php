<?php

use App\Livewire\WorkOrderManager;
use Illuminate\Support\Facades\Route;

Route::get('/', WorkOrderManager::class)->name('home');

require __DIR__.'/settings.php';
