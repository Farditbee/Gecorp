<?php

use App\Console\Commands\UpdatePromoStatus;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(UpdatePromoStatus::class)->everyTenMinutes();
Schedule::command(UpdatePromoStatus::class)->dailyAt('00:00');