<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Plan;

class User
{
    private function __construct(
        readonly ?int $id,
        private string        $name,
        private UserPlan      $userPlan,
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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}