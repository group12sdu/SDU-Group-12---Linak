<?php

use Illuminate\Support\Facades\Schedule;

// Checks every minute whether the cleaning time has been reached.
// Run `php artisan schedule:work` locally, or add the usual cron entry in production.
Schedule::command('cleaning:run-scheduled')->everyMinute();
