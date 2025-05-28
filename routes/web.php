<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\inferenceController;
use App\Http\Controllers\uploadNiftyController;
use App\Http\Controllers\LockController;
use App\Http\Controllers\processDone;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/projects', function () {
    return view('projects');
})->middleware(['auth', 'verified'])->name('projects');

Route::get('/newProject', function () {
    return view('newProject');
})->middleware(['auth', 'verified'])->name('projects.create');

Route::post('/upload-image', [uploadNiftyController::class, 'uploadNifty'])->middleware(['auth', 'verified'])->name('upload.files');

Route::post('/process-done', [processDone::class, 'processDone'])->middleware(['auth', 'verified'])->name('process.done');

Route::get('/check-lock', [LockController::class, 'check']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





Route::get('/show-inference', function () {
    return view('showInference');
})->name('show-inference');

Route::fallback(function () {
    return redirect('/home');
})->name('fallback');

require __DIR__.'/auth.php';
