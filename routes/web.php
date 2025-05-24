<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TagController;

Route::view('/', 'welcome');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



Route::middleware(['auth'])->group(function () {
    Route::get('/upload', fn() => view('upload'))->name('upload.form');
    Route::post('/upload', [TenderController::class, 'upload'])->name('upload.submit');
});

Route::get('/tenders', [TenderController::class, 'index'])->name('tenders.index');
Route::get('/tenders/{tender}/download', [TenderController::class, 'download'])->name('tenders.download');
Route::get('/tenders/{tender}/edit-tags', [TenderController::class, 'editTags'])->name('tenders.edit-tags');
Route::post('/tenders/{tender}/update-tags', [TenderController::class, 'updateTags'])->name('tenders.update-tags');
Route::get('/tenders/export', [TenderController::class, 'exportCsv'])->name('tenders.export');


Route::get('/analytics', [AnalyticsController::class, 'index'])->middleware(['auth'])->name('analytics');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('tags', TagController::class); // or wherever you allow tag management
});


require __DIR__.'/auth.php';
