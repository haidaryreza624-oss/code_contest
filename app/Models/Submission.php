<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = [
        'user_id',
        'problem_id',
        'language',
        'source_code',
        'judge_job_id',
        'execution_status',
        'verdict',
        'execution_time_ms',
        'memory_used_kb',
        'passed_test_cases',
        'total_test_cases',
        'error_message',
    ];
    protected function casts(): array
    {
        return [
            'execution_time_ms' => 'integer',
            'memory_used_kb' => 'integer',
            'passed_test_cases' => 'integer',
            'total_test_cases' => 'integer',
        ];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }
}
