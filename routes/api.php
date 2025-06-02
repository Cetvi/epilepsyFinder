<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Process;

Route::post('/process-finished', [Process::class, 'processFinished']);



