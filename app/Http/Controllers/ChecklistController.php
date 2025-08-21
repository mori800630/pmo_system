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
     * チェックリスト項目の説明をインライン更新（AJAX）
     */
    public function updateDescription(Request $request, Checklist $checklist)
    {
        $request->validate([
            'description' => 'nullable|string',
        ]);

        $checklist->update([
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => '説明が更新されました'
        ]);
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

    /**
     * PMが下書きを提出（Submitted）
     */
    public function submit(Checklist $checklist)
    {
        // PMのみ（admin/pmo_manager も可とするなら条件調整）
        if (!auth()->user()->isUser() && !auth()->user()->isAdmin() && !auth()->user()->isPmoManager()) {
            abort(403);
        }

        $checklist->update([
            'status' => 'submitted',
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
        ]);

        return redirect()->route('projects.show', $checklist->project)
            ->with('success', 'チェックリストを提出しました。');
    }

    /**
     * PMOがレビュー開始（Under Review）
     */
    public function startReview(Checklist $checklist)
    {
        if (!auth()->user()->isPmoManager() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $checklist->update([
            'status' => 'under_review',
        ]);

        return redirect()->route('projects.show', $checklist->project)
            ->with('success', 'レビューを開始しました。');
    }

    /**
     * PMOが承認（Approved）
     */
    public function approve(Request $request, Checklist $checklist)
    {
        if (!auth()->user()->isPmoManager() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $checklist->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_comment' => $request->input('review_comment'),
        ]);

        return redirect()->route('projects.show', $checklist->project)
            ->with('success', '承認しました。');
    }

    /**
     * PMOが差戻し（Rejected）
     */
    public function reject(Request $request, Checklist $checklist)
    {
        if (!auth()->user()->isPmoManager() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'review_comment' => 'required|string',
        ]);

        $checklist->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_comment' => $request->input('review_comment'),
        ]);

        return redirect()->route('projects.show', $checklist->project)
            ->with('success', '差戻しました。');
    }
}
