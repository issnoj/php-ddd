<?php

namespace App\Application\UseCase;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Plan;
use App\Infrastructure\Repository\BookCirculationRepository;
use App\Infrastructure\Repository\UserRepository;
use Exception;

class ChangePlanUseCase
{
    private UserRepository $useRepository;
    private BookCirculationRepository $bookCirculationRepository;

    public function __construct()
    {
        $this->useRepository = new UserRepository();
        $this->bookCirculationRepository = new BookCirculationRepository();
    }

    /**
     * @throws Exception
     */
    public function execute(User $user, Plan $plan): void
    {
        if ($user->getUserPlan()->getPlan()->name === $plan->name) {
            throw new Exception("同じプランです");
        }

        $maxBorrowingCount = $plan->maxBorrowingCount();

        $nowBorrowingCount = $this->bookCirculationRepository->borrowingCount($user->getId());

        if ($nowBorrowingCount > $maxBorrowingCount) {
            throw new Exception("現在{$nowBorrowingCount}冊貸出中のため、{$plan->name()}にプランを変更できません");
        }

        $user->getUserPlan()->changePlan($plan);

        $this->useRepository->update($user);

        echo "プランを{$plan->name()}に変更しました" . PHP_EOL;
    }
}