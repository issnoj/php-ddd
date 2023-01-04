<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Infrastructure\Storage;

class UserRepository
{
    private Storage $storage;

    public function __construct()
    {
        $this->storage = Storage::getInstance();
    }

    public function create(string $name): User
    {
        $id = count($this->storage->data['User']) + 1;

        $user = User::create($id, $name);

        $this->storage->data['User'][] = $user;

        return $user;
    }

    public function update(User $user): ?User
    {
        foreach ($this->storage->data['User'] as $k => $v) {
            if ($v->getId() === $user->getId()) {
                $this->storage->data['User'][$k] = $user;
                return $user;
            }
        }

        return null;
    }

    public function getById(int $id): User
    {
        foreach ($this->storage->data['User'] as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
    }
}