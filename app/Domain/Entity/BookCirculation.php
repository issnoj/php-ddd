<?php

namespace App\Domain\Entity;

use DateTime;
use Exception;

class BookCirculation
{
    private function __construct(
        readonly int       $id,
        readonly int       $userId,
        readonly int       $bookId,
        readonly ?DateTime $borrowDate,
        private ?DateTime  $returnDate,
    )
    {
    }

    public static function create($id, $userId, $bookId, $borrowDate): static
    {
        return new static($id, $userId, $bookId, $borrowDate, null);
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
     * @throws Exception
     */
    public function setReturnDate(DateTime $date): void
    {
        if ($date < $this->borrowDate) {
            throw new Exception("日付が正しくありません");
        }

        $this->returnDate = $date;
    }
}