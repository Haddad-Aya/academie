<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = ['training_session_id', 'child_id', 'present', 'recorded_by'];

    protected function casts(): array
    {
        return ['present' => 'boolean'];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}
