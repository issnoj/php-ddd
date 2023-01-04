<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Plan;

readonly class User
{
    private function __construct(
        public ?int     $id,
        public string   $name,
        public UserPlan $userPlan,
    )
    {
    }

    public static function create(int $id, string $name): static
    {
        $userPlan = UserPlan::create(Plan::Free);
        return new static($id, $name, $userPlan);
    }

    /**
     * @return UserPlan
     */
    public function getUserPlan(): UserPlan
    {
        return $this->userPlan;
    }
}