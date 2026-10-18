<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Child extends Model
{
    protected $table = 'children';

    /** parent_id n'est pas assignable en masse : on passe toujours par $user->children()->create(). */
    protected $fillable = ['first_name', 'last_name', 'birth_date', 'photo_consent', 'medical_notes'];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'photo_consent' => 'boolean',
            'medical_notes' => 'encrypted', // donnée sensible chiffrée en base (clé APP_KEY)
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }
}
