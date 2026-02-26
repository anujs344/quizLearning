<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'min_value',
        'max_value',
        'points',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}


