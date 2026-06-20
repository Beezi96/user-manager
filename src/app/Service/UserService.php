<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\User;
use App\Factory\UserFactory;
use App\Repository\UserRepositoryInterface;

final class UserService
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserFactory $userFactory
    ) {}

    public function listUsers(): array
    {
        return $this->repository->findAll();
    }

    public function createUser(): User
    {
        $users = $this->repository->findAll();

        $nextId = $this->getNextId($users);

        $user = $this->userFactory->create($nextId);

        $this->repository->save($user);

        return $user;
    }

    public function deleteUser(int $id): void
    {
        $this->repository->delete($id);
    }

    private function getNextId(array $users): int
    {
        if ($users === []) {
            return 1;
        }

        $ids = array_map(
            fn (User $user): int => $user->getId(),
            $users
        );

        return max($ids) + 1;
    }
}