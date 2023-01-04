<?php

namespace App\Application\UseCase;

use App\Domain\Entity\Book;
use App\Domain\Entity\User;
use App\Infrastructure\Repository\BookCirculationRepository;
use App\Infrastructure\Repository\BookRepository;
use DateTime;
use Exception;

class BorrowBookUseCase
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
        if (!$book->canBorrow()) {
            throw new Exception("貸出中です");
        }

        $maxBorrowingCount = $user->getUserPlan()->getPlan()->maxBorrowingCount();

        $nowBorrowingCount = $this->bookCirculationRepository->borrowingCount($user->getId());

        if ($nowBorrowingCount >= $maxBorrowingCount) {
            throw new Exception("{$user->getUserPlan()->getPlan()->name()}では{$maxBorrowingCount}冊以上貸出できません");
        }

        $book->borrow();

        $this->bookRepository->update($book);
        $this->bookCirculationRepository->create($user->getId(), $book->getId(), new DateTime());

        echo "{$book->getTitle()}を借りました" . PHP_EOL;
    }
}