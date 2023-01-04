<?php

namespace App\Infrastructure\Query;

use App\Domain\Entity\Book;
use App\Domain\Entity\BookCirculation;
use App\Infrastructure\Storage;

class GetBorrowingBookListQuery
{
    private Storage $storage;

    public function __construct()
    {
        $this->storage = Storage::getInstance();
    }

    /**
     * @param int $userId
     * @return array
     */
    public function execute(int $userId): array
    {
        $return = [];

        $books = [];
        foreach ($this->storage->data['Book'] as $v) {
            /* @var $v Book */
            $books[$v->getId()] = $v;
        }

        foreach ($this->storage->data['BookCirculation'] as $v) {
            /* @var $v BookCirculation */
            if ($v->getUserId() === $userId && !$v->getReturnDate()) {
                $return[] = [
                    'id' => $v->getBookId(),
                    'title' => $books[$v->getBookId()]->getTitle(),
                    'borrowDate' => $v->getBorrowDate()->format('Y-m-d H:i:s'),
                ];
            }
        }

        return $return;
    }
}