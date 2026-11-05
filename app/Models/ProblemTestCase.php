<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProblemTestCase extends Model
{
    protected $fillable = [
        'problem_id',
        'input',
        'expected_output',
        'is_sample',
        'position',
    ];
    protected function casts(): array
    {
        return [
            'is_sample' => 'boolean',
            'position' => 'integer',
        ];
    }
    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }
}
