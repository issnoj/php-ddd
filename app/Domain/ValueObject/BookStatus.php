<?php

namespace App\Domain\ValueObject;

enum BookStatus
{
    case AvailableForBorrow;
    case Borrowing;
}
