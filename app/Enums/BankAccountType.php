<?php

namespace App\Enums;

enum BankAccountType: string
{
    case SAVING = 'saving';
    case CHECKING = 'checking';

    public function description(): string
    {
        return match ($this) {
            self::SAVING => 'For natural clients only',
            self::CHECKING => 'For business clients only',
        };
    }
}
