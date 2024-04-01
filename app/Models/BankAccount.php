<?php

namespace App\Models;

use App\Enums\BankAccountType;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class BankAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'account_number',
        'type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => BankAccountType::class,
        ];
    }

    /**
     * Returns the client that the account belongs to
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
     * Returns the transactions tha belongs to this account
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Returns the accounts' balance based on transactions difference
     *
     * @return string
     */
    public function getBalanceAttribute(): string
    {
        return number_format(($this->transactions()->where('type', TransactionType::DEPOSIT->value)->sum('amount') -
            $this->transactions()->where('type', TransactionType::WITHDRAW->value)->sum('amount')) / 100, 2);
    }

    /**
     * Returns incomes total amount by date range
     *
     * @param string|null $from
     * @param string|null $to
     * @return string
     */
    public function calculateIncomeByRange(?string $from, ?string $to): string
    {
        return number_format($from && $to ?
            $this->transactions()
                ->where('type', TransactionType::DEPOSIT->value)
                ->byDateRange($from, $to)
                ->sum('amount') / 100:
            $this->transactions()
                ->where('type', TransactionType::DEPOSIT->value)
                ->sum('amount') / 100,
    2);
    }

    /**
     * Returns expenses total amount by date range
     *
     * @param string|null $from
     * @param string|null $to
     * @return string
     */
    public function calculateExpensesByRange(?string $from, ?string $to): string
    {
        return number_format($from && $to ?
            $this->transactions()
                ->where('type', TransactionType::WITHDRAW->value)
                ->byDateRange($from, $to)
                ->sum('amount') / 100:
            $this->transactions()
                ->where('type', TransactionType::WITHDRAW->value)
                ->sum('amount') / 100,
    2);
    }
}
