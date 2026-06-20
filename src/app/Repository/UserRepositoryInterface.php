<?php
declare(strict_types=1);

namespace App\Repository;

use App\Domain\User;

interface UserRepositoryInterface
{
    public function findAll(): array;

    public function save(User $user): void;

    public function delete(int $id): void;
}