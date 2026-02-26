<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'text',
        'time_limit_seconds',
        'allow_skip',
        'show_skip_button',
        'allow_back',
        'base_points',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function scoringRules()
    {
        return $this->hasMany(ScoringRule::class);
    }
}


