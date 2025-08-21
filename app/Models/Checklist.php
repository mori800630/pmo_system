<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'phase',
        'title',
        'description',
        'is_completed',
        'order',
        'status',
        'submitted_by',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_comment',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * チェックリストが属するプロジェクトを取得
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * フェーズの日本語名を取得
     */
    public function getPhaseNameAttribute()
    {
        return match($this->phase) {
            'planning' => '計画',
            'execution' => '実行',
            'completion' => '終結',
            default => $this->phase,
        };
    }

    /**
     * ステータスの日本語名を取得
     */
    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'draft' => '下書き',
            'submitted' => '提出済み',
            'under_review' => 'レビュー中',
            'approved' => '承認',
            'rejected' => '差戻し',
            default => $this->status,
        };
    }

    /**
     * ステータスの色クラスを取得
     */
    public function getStatusColorClassAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'under_review' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
