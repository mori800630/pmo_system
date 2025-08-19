<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ChecklistController;
use Illuminate\Support\Facades\Route;

// Faviconルート（ブラウザの自動リクエスト用）
Route::get('/favicon.ico', function () {
    return response('', 204);
});

// 認証が必要なルート
Route::middleware('auth')->group(function () {
    // ダッシュボード（プロジェクト一覧にリダイレクト）
    Route::get('/dashboard', function () {
        return redirect()->route('projects.index');
    })->name('dashboard');

    // プロジェクト関連のルート
    Route::resource('projects', ProjectController::class);
    
    // チェックリスト関連のルート
    Route::patch('/checklists/{checklist}/toggle', [ChecklistController::class, 'toggle'])->name('checklists.toggle');
    Route::post('/projects/{project}/checklists', [ChecklistController::class, 'store'])->name('checklists.store');
    Route::put('/checklists/{checklist}', [ChecklistController::class, 'update'])->name('checklists.update');
    Route::delete('/checklists/{checklist}', [ChecklistController::class, 'destroy'])->name('checklists.destroy');

    // プロフィール関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ルートページをログインページにリダイレクト
Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';
