<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingGroup extends Model
{
    protected $fillable = [
        'name', 'category', 'age_min', 'age_max', 'schedule', 'location',
        'coach_id', 'capacity', 'price', 'active',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:3', 'active' => 'boolean'];
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class);
    }

    public function acceptsAge(int $age): bool
    {
        return $age >= $this->age_min && $age <= $this->age_max;
    }

    /** Places déjà réservées (en attente + validées) pour la saison donnée. */
    public function reservedSeats(string $season): int
    {
        return $this->registrations()
            ->where('season', $season)
            ->whereIn('status', [Registration::PENDING, Registration::APPROVED])
            ->count();
    }
}
