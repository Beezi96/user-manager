<?php
declare(strict_types=1);

namespace App\Factory;

use App\Domain\User;

final class UserFactory
{
    public function create(int $id): User
    {
        return new User(
            id: $id,
            firstName: 'John',
            lastName: 'Doe',
            email: 'john' . $id . '@example.com',
        );
    }
}