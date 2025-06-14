<?php

use App\Http\Controllers\DeleteProject;
use App\Http\Controllers\MoreInfo;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\inferenceController;
use App\Http\Controllers\UploadNiftyController;
use App\Http\Controllers\LockController;
use App\Http\Controllers\Process;
use App\Http\Controllers\Projects;
use App\Http\Controllers\VolumenController;
use Illuminate\Support\Facades\Route;
use App\Mail\ProcessFinishedMail;
use App\Mail\ProcessStartedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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


Route::get('/newProjectTutorial', function () {
    return view('tutorial/newProjectTutorial');
});

Route::get('/projectsTutorial', function () {
    return view('tutorial/projectsTutorial');
});

Route::get('/showProjectTutorial', function () {
    return view('tutorial/showProjectTutorial');
});

Route::get('/moreInfoTutorial', function () {
    return view('tutorial/moreInfoTutorial');
})->name('moreInfo-tutorial');

Route::get('/more-info', [MoreInfo::class, 'showMoreInfo'])->middleware(['auth', 'verified'])->name('show-more-info');

Route::get('/show-inference', [VolumenController::class, 'show'])->middleware(['auth', 'verified'])->name('show-inference.get');

Route::get('/show-projects', [Projects::class, 'showProjects'])->middleware(['auth', 'verified'])->name('show.projects');

Route::get('/delete-project', [Projects::class,'deleteProject'])->middleware(['auth', 'verified'])->name('delete.project');

Route::get('/last-project', [Projects::class,'lastProject'])->middleware(['auth', 'verified'])->name('last.project');

Route::post('/upload-image', [UploadNiftyController::class, 'uploadNifty'])->middleware(['auth', 'verified'])->name('upload.files');

Route::get('/continue-process', [Process::class, 'continueQueue'])->middleware(['auth', 'verified'])->name('qeue.files');

Route::get('/fastsurfer-colours', [VolumenController::class, 'freeSurferColours'])->middleware(['auth', 'verified'])->name('freesurfer.colours');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});







Route::fallback(function () {
    return redirect('/');
})->name('fallback');

require __DIR__.'/auth.php';
