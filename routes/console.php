<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\AutoApproveAppointments;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('appointments:auto-approve', function () {
    $this->call(AutoApproveAppointments::class);
})->everyFiveMinutes();