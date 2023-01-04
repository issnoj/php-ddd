<?php

namespace App\Domain\Entity;

use DateTime;

class BookCirculation
{
    private function __construct(
        private readonly int       $id,
        private readonly int       $userId,
        private readonly int       $bookId,
        private readonly ?DateTime $borrowDate,
        private ?DateTime          $returnDate,
    )
    {
    }

    public static function create($id, $userId, $bookId, $borrowDate): static
    {
        return new static($id, $userId, $bookId, $borrowDate, null);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getBookId(): int
    {
        return $this->bookId;
    }

    /**
     * @return DateTime|null
     */
    public function getBorrowDate(): ?DateTime
    {
        return $this->borrowDate;
    }

    /**
     * @return DateTime|null
     */
    public function getReturnDate(): ?DateTime
    {
        return $this->returnDate;
    }

    /**
     * @param DateTime $date
     * @return void
     */
    public function setReturnDate(DateTime $date): void
    {
        $this->returnDate = $date;
    }
}