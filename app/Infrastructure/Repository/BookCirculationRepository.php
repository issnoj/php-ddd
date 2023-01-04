<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\BookCirculation;
use App\Infrastructure\Storage;
use DateTime;

class BookCirculationRepository
{
    private Storage $storage;

    public function __construct()
    {
        $this->storage = Storage::getInstance();
    }

    public function create(int $userId, int $bookId, DateTime $borrowDate): BookCirculation
    {
        $id = count($this->storage->data['BookCirculation']) + 1;

        $bookCirculation = BookCirculation::create($id, $userId, $bookId, $borrowDate);

        $this->storage->data['BookCirculation'][] = $bookCirculation;

        return $bookCirculation;
    }

    public function update(BookCirculation $bookCirculation): ?BookCirculation
    {
        foreach ($this->storage->data['BookCirculation'] as $k => $v) {
            if ($v->getId() === $bookCirculation->getId()) {
                $this->storage->data['BookCirculation'][$k] = $bookCirculation;
                return $bookCirculation;
            }
        }

        return null;
    }

    public function borrowingCount(int $userId): int
    {
        $count = 0;

        foreach ($this->storage->data['BookCirculation'] as $v) {
            if ($v->getUserId() === $userId && !$v->getReturnDate()) {
                $count++;
            }
        }

        return $count;
    }

    public function getByUserAndBook(int $userId, int $bookId): ?BookCirculation
    {
        foreach ($this->storage->data['BookCirculation'] as $v) {
            if ($v->getUserId() === $userId && $v->getBookId() === $bookId) {
                return $v;
            }
        }

        return null;
    }
}