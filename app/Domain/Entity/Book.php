<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\BookStatus;

class Book
{
    private function __construct(
        readonly ?int      $id,
        readonly string    $title,
        private BookStatus $status,
    )
    {
    }

    public static function create(int $id, string $title): static
    {
        return new static($id, $title, BookStatus::AvailableForBorrow);
    }

    /**
     * @return bool
     */
    public function canBorrow(): bool
    {
        return $this->status === BookStatus::AvailableForBorrow;
    }

    /**
     * @return void
     */
    public function borrow(): void
    {
        $this->status = BookStatus::Borrowing;
    }

    /**
     * @return void
     */
    public function return(): void
    {
        $this->status = BookStatus::AvailableForBorrow;
    }
}