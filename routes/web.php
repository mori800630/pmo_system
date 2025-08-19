<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ChecklistController;

Route::get('/', function () {
    return redirect()->route('projects.index');
});

// プロジェクト関連のルート
Route::resource('projects', ProjectController::class);

// チェックリスト関連のルート
Route::patch('/checklists/{checklist}/toggle', [ChecklistController::class, 'toggle'])->name('checklists.toggle');
Route::post('/projects/{project}/checklists', [ChecklistController::class, 'store'])->name('checklists.store');
Route::put('/checklists/{checklist}', [ChecklistController::class, 'update'])->name('checklists.update');
Route::delete('/checklists/{checklist}', [ChecklistController::class, 'destroy'])->name('checklists.destroy');
