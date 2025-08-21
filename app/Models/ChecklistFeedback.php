<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_id',
        'reviewer_id',
        'action',
        'comment',
    ];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}


