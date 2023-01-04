<?php

namespace App\Application\UseCase;

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
    public function execute(User $user, int $bookId, DateTime $date): void
    {
        $book = $this->bookRepository->getById($bookId);

        if ($book->canBorrow()) {
            throw new Exception("この本は貸出中ではありません");
        }

        $bookCirculation = $this->bookCirculationRepository->getByUserAndBook($user->id, $book->id);

        if (!$bookCirculation) {
            throw new Exception("この本は別の人が借りています");
        }

        $book->return();
        $bookCirculation->setReturnDate($date);

        $this->bookRepository->update($book);
        $this->bookCirculationRepository->update($bookCirculation);

        echo "{$book->title}を返却しました" . PHP_EOL;
    }
}