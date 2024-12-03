<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\VocabularyController;
use App\Http\Controllers\LearningController;
use Inertia\Inertia;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/dashboard', [FrontendController::class, 'index'])->name('dashboard');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::inertia('/contact', 'Frontend/Contact')->name('contact');
// Route::resource('/vocabulary', VocabularyController::class)->names('vocabulary');

Route::group(['prefix' => 'learning', 'as' => 'learning.'], function () {
    Route::get('/', [LearningController::class, 'index'])->name('index');
    Route::post('/check', [LearningController::class, 'check'])->name('check');
    Route::get('/vocabulary', [LearningController::class, 'vocabulary'])->name('vocabulary');
    Route::get('/vocabulary/fill-out', [LearningController::class, 'fillOut'])->name('vocabulary.fill_out');
    Route::get('/vocabulary/fill-out/incorrect/{id}', [LearningController::class, 'incorrectDetail'])->name('vocabulary.fill_out.incorrect');
    Route::post('/vocabulary/{type}/save-result', [LearningController::class, 'saveResult'])->name('vocabulary.save_result');

    Route::post('/vocabulary/results', [LearningController::class, 'results']);

    Route::post('/vocabulary/questions', [LearningController::class, 'vocabularyData']);
    Route::post('/vocabulary/save-result', [LearningController::class, 'saveResult']);
});     

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/vocabulary', VocabularyController::class)->names('vocabulary');
});

require __DIR__.'/auth.php';
