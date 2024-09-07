<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// only delete api tokens and dashboard tokens that have been expired for longer than 24 hours
Schedule::command('sanctum:prune-expired --hours=24')->daily();
Schedule::command('session:prune-expired --hours=24')->daily();

// to run this Schedule we can run       php artisan schedule:work