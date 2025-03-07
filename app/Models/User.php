<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'type' => UserType::class,
        ];
    }

    /**
     * Returns the bank account that belongs to this user.
     *
     * @return HasOne
     */
    public function bankAccount(): HasOne
    {
        return $this->hasOne(BankAccount::class);
    }

    /**
     * Returns the transactions this user has through their bank account,
     *
     * @return HasManyThrough
     */
    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, BankAccount::class);
    }

    /**
     * Scope a query by transaction number
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeByTransactionsNumberInADateRange(Builder $query, ?string $from, ?string $to): Builder
    {
        return $query->withCount(['transactions' => function(Builder $subQuery) use($from, $to){
            if ($from && $to) {
                $subQuery->where('transactions.created_at', '>', $from)
                    ->where('transactions.created_at', '<', $to);
            }
        }])
            ->orderBy('transactions_count', 'desc');

    }
}
