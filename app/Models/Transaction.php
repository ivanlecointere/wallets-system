<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bank_account_id',
        'type',
        'amount',
    ];

    /**
     * The attributes that must be appended
     *
     * @var string[]
     */
    protected $appends = ['formatted_amount'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
        ];
    }

    /**
     * Returns the address this account was created at
     *
     * @return MorphOne
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Defining mutator for amount attribute
     *
     * @return Attribute
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

    /**
     * Returns formatted amount string
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2);
    }

    /**
     * Scope a query by date range
     *
     * @param Builder $query
     * @param string|null $from
     * @param string|null $to
     * @return Builder
     */
    public function scopeByDateRange(Builder $query, ?string $from, ?string $to): Builder
    {
        return $from && $to ? $query
            ->where('created_at', '>', $from)
            ->where('created_at', '<', $to) : $query;
    }
}
