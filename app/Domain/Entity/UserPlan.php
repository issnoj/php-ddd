<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Plan;

class UserPlan
{
    private function __construct(private Plan $plan)
    {
    }

    public static function create(Plan $plan): static
    {
        return new static($plan);
    }

    /**
     * @return Plan
     */
    public function getPlan(): Plan
    {
        return $this->plan;
    }

    /**
     * @param Plan $plan
     * @return void
     */
    public function changePlan(Plan $plan): void
    {
        $this->plan = $plan;
    }
}