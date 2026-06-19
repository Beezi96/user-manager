<?php
declare(strict_types=1);

namespace App\Domain;
final class User
{
    public function __construct(
        private int $id,
        private string $firstName,
        private string $lastName,
        private string $email,
    )
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}