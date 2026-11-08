<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Problem extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'input_format',
        'output_format',
        'constraints',
        'difficulty',
        'time_limit_ms',
        'memory_limit_mb',
        'status',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function testCases():HasMany
    {
        return $this->hasMany(ProblemTestCase::class);
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
