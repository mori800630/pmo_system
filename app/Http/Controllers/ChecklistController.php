<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Project;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    /**
     * チェックリスト項目の完了状態を切り替え
     */
    public function toggle(Checklist $checklist)
    {
        $checklist->update([
            'is_completed' => !$checklist->is_completed
        ]);

        return response()->json([
            'success' => true,
            'is_completed' => $checklist->is_completed
        ]);
    }

    /**
     * 新しいチェックリスト項目を追加
     */
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'phase' => 'required|in:planning,execution,completion',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $maxOrder = $project->checklists()
            ->where('phase', $request->phase)
            ->max('order') ?? 0;

        $checklist = $project->checklists()->create([
            'phase' => $request->phase,
            'title' => $request->title,
            'description' => $request->description,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'チェックリスト項目が追加されました。');
    }

    /**
     * チェックリスト項目を更新
     */
    public function update(Request $request, Checklist $checklist)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $checklist->update($request->only(['title', 'description']));

        return redirect()->route('projects.show', $checklist->project)
            ->with('success', 'チェックリスト項目が更新されました。');
    }

    /**
     * チェックリスト項目を削除
     */
    public function destroy(Checklist $checklist)
    {
        $project = $checklist->project;
        $checklist->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'チェックリスト項目が削除されました。');
    }
}
