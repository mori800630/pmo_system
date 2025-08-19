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
    ];

    protected $casts = [
        'is_completed' => 'boolean',
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
}
