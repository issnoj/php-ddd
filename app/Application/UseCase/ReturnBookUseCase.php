<?php

namespace App\Application\UseCase;

use App\Domain\Entity\Book;
use App\Domain\Entity\User;
use App\Infrastructure\Repository\BookCirculationRepository;
use App\Infrastructure\Repository\BookRepository;
use DateTime;
use Exception;

class ReturnBookUseCase
{
    private BookRepository $bookRepository;

    private BookCirculationRepository $bookCirculationRepository;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
        $this->bookCirculationRepository = new BookCirculationRepository();
    }

    /**
     * @throws Exception
     */
    public function execute(User $user, Book $book): void
    {
        if ($book->canBorrow()) {
            throw new Exception("この本は貸出中ではありません");
        }

        $bookCirculation = $this->bookCirculationRepository->getByUserAndBook($user->getId(), $book->getId());

        if (!$bookCirculation) {
            throw new Exception("この本は別の人が借りています");
        }

        $book->return();
        $bookCirculation->setReturnDate(new DateTime());

        $this->bookRepository->update($book);
        $this->bookCirculationRepository->update($bookCirculation);

        echo "{$book->getTitle()}を返却しました" . PHP_EOL;
    }
}