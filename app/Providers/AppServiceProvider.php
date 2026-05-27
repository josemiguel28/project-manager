<?php

namespace App\Providers;

use App\Modules\Payroll\Livewire\WeekDetail;
use App\Modules\Payroll\Livewire\WeekForm;
use App\Modules\Payroll\Livewire\WeekList;
use App\Modules\Payroll\Livewire\WeekManager;
use App\Modules\Payroll\Livewire\WorkerForm;
use App\Modules\Payroll\Livewire\WorkerManager;
use App\Modules\Payroll\Livewire\WorkerTable;
use App\Modules\WorkOrders\Livewire\WorkOrderForm;
use App\Modules\WorkOrders\Livewire\WorkOrderManager;
use App\Modules\WorkOrders\Livewire\WorkOrderStats;
use App\Modules\WorkOrders\Livewire\WorkOrderTable;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('work-orders.manager', WorkOrderManager::class);
        Livewire::component('work-orders.table', WorkOrderTable::class);
        Livewire::component('work-orders.form', WorkOrderForm::class);
        Livewire::component('work-orders.stats', WorkOrderStats::class);

        Livewire::component('payroll.workers.manager', WorkerManager::class);
        Livewire::component('payroll.workers.table', WorkerTable::class);
        Livewire::component('payroll.workers.form', WorkerForm::class);
        Livewire::component('payroll.weeks.manager', WeekManager::class);
        Livewire::component('payroll.weeks.list', WeekList::class);
        Livewire::component('payroll.weeks.form', WeekForm::class);
        Livewire::component('payroll.weeks.detail', WeekDetail::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
