<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Book;
use App\Infrastructure\Storage;

class BookRepository
{
    private Storage $storage;

    public function __construct()
    {
        $this->storage = Storage::getInstance();
    }

    public function create(string $title): Book
    {
        $id = count($this->storage->data['Book']) + 1;

        $user = Book::create($id, $title);

        $this->storage->data['Book'][] = $user;

        return $user;
    }

    public function update(Book $book): void
    {
        foreach ($this->storage->data['Book'] as $k => $v) {
            /* @var $v Book */
            if ($v->id === $book->id) {
                $this->storage->data['Book'][$k] = $book;
            }
        }
    }

    public function getById(int $id): ?Book
    {
        foreach ($this->storage->data['Book'] as $v) {
            /* @var $v Book */
            if ($v->id === $id) {
                return clone($v);
            }
        }

        return null;
    }
}