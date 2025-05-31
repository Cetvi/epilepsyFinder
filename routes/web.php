<?php

use App\Http\Controllers\DeleteProject;
use App\Http\Controllers\MoreInfo;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\inferenceController;
use App\Http\Controllers\UploadNiftyController;
use App\Http\Controllers\LockController;
use App\Http\Controllers\Projects;
use App\Http\Controllers\VolumenController;
use Illuminate\Support\Facades\Route;
use App\Mail\ProcessFinishedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::post('/send-mail-notification', function () {
    $email = Auth::user()->email;
    $name = Auth::user()->name;

    Mail::to($email)->send(new ProcessFinishedMail($name));

    return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
});

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


Route::get('/more-info', [MoreInfo::class, 'showMoreInfo'])->middleware(['auth', 'verified'])->name('show-more-info');

Route::get('/show-inference', [VolumenController::class, 'show'])->middleware(['auth', 'verified'])->name('show-inference.get');

Route::get('/show-projects', [Projects::class, 'showProjects'])->middleware(['auth', 'verified'])->name('show.projects');

Route::get('/delete-project', [Projects::class,'deleteProject'])->middleware(['auth', 'verified'])->name('delete.project');

Route::post('/upload-image', [uploadNiftyController::class, 'uploadNifty'])->middleware(['auth', 'verified'])->name('upload.files');

Route::get('/check-lock', [LockController::class, 'check']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});







Route::fallback(function () {
    return redirect('/');
})->name('fallback');

require __DIR__.'/auth.php';
