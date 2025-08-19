<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'pm_name',
        'description',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
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
}
