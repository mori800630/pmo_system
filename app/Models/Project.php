<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pm_name',
        'health',
        'customer_name',
        'priority',
        'phase',
        'budget',
        'baseline_start_date',
        'baseline_end_date',
        'actual_start_date',
        'actual_end_date',
        'deliverables_summary',
        'main_links',
    ];

    protected $casts = [
        'baseline_start_date' => 'date',
        'baseline_end_date' => 'date',
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
        'budget' => 'decimal:2',
        'main_links' => 'array',
    ];

    /**
     * プロジェクトに関連するチェックリストを取得
     */
    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

    /**
     * 特定のフェーズのチェックリストを取得
     */
    public function checklistsByPhase($phase)
    {
        return $this->checklists()->where('phase', $phase)->orderBy('order');
    }

    /**
     * 計画フェーズのチェックリストを取得
     */
    public function planningChecklists()
    {
        return $this->checklistsByPhase('planning');
    }

    /**
     * 実行フェーズのチェックリストを取得
     */
    public function executionChecklists()
    {
        return $this->checklistsByPhase('execution');
    }

    /**
     * 終結フェーズのチェックリストを取得
     */
    public function completionChecklists()
    {
        return $this->checklistsByPhase('completion');
    }

    /**
     * ヘルス状況の日本語名を取得
     */
    public function getHealthNameAttribute()
    {
        return [
            'Green' => '良好',
            'Amber' => '注意',
            'Red' => '危険',
        ][$this->health] ?? $this->health;
    }

    /**
     * 優先度の日本語名を取得
     */
    public function getPriorityNameAttribute()
    {
        return [
            'High' => '高',
            'Medium' => '中',
            'Low' => '低',
        ][$this->priority] ?? $this->priority;
    }

    /**
     * フェーズの日本語名を取得
     */
    public function getPhaseNameAttribute()
    {
        return [
            'planning' => '企画',
            'requirements' => '要件',
            'design' => '設計',
            'implementation' => '実装',
            'testing' => 'テスト',
            'release' => 'リリース',
            'operation' => '運用',
        ][$this->phase] ?? $this->phase;
    }

    /**
     * ヘルス状況の色クラスを取得
     */
    public function getHealthColorClassAttribute()
    {
        return [
            'Green' => 'bg-green-100 text-green-800',
            'Amber' => 'bg-yellow-100 text-yellow-800',
            'Red' => 'bg-red-100 text-red-800',
        ][$this->health] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * 優先度の色クラスを取得
     */
    public function getPriorityColorClassAttribute()
    {
        return [
            'High' => 'bg-red-100 text-red-800',
            'Medium' => 'bg-yellow-100 text-yellow-800',
            'Low' => 'bg-green-100 text-green-800',
        ][$this->priority] ?? 'bg-gray-100 text-gray-800';
    }
}
