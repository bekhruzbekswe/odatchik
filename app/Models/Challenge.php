<?php

namespace App\Models;

use App\Enums\ChallengeFrequency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 */
class Challenge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'frequency',
        'is_public',
        'start_date',
        'end_date',
        'checkin_deadline',
        'price_per_miss',
        'price_early_leave',
        'coins_per_checkin',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'frequency' => ChallengeFrequency::class,
            'is_public' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
            'price_per_miss' => 'integer',
            'price_early_leave' => 'integer',
            'coins_per_checkin' => 'integer',
        ];
    }
}
