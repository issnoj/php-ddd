<?php

namespace App\Domain\ValueObject;

enum Plan
{
    case Free;
    case Standard;

    /**
     * @return string
     */
    public function name(): string
    {
        return match ($this) {
            Plan::Free => 'フリープラン',
            Plan::Standard => 'スタンダードプラン',
        };
    }

    /**
     * @return int
     */
    public function maxBorrowingCount(): int
    {
        return match ($this) {
            Plan::Free => 2,
            Plan::Standard => 10,
        };
    }
}