<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\BookStatus;

class Book
{
    private function __construct(
        private readonly ?int   $id,
        private readonly string $title,
        private BookStatus      $status,
    )
    {
    }

    public static function create(int $id, string $title): static
    {
        return new static($id, $title, BookStatus::AvailableForBorrow);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
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