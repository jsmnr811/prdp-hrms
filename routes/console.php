<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\PruneTelescopeEntries;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Run the job daily to prune Telescope entries older than 7 days
Schedule::job(new PruneTelescopeEntries)->daily();