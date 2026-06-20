<?php
declare(strict_types=1);

namespace App\Repository;

use App\Domain\User;
use http\Exception\RuntimeException;
use App\Exception\UserNotFoundException;

final class JsonUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private string $filePath
    )
    {}

    public function findAll(): array
    {
        $data = $this->readData();

        return array_map(
            fn (array $item): User => new User(
                id: (int) $item['id'],
                firstName: (string) $item['firstName'],
                lastName: (string) $item['lastName'],
                email: (string) $item['email'],

            ), $data
        );

    }

    public function save(User $user): void
    {
        $data = $this->readData();

        $data[] = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
        ];

        $this->writeData($data);
    }

    public function delete(int $id): void
    {
        $data = $this->readData();

        $initialCount = count($data);

        $data = array_filter($data,
            fn (array $item): bool => (int) $item['id'] !== $id
        );

        if (count($data) === $initialCount) {
            throw new UserNotFoundException('User with id ' . $id . ' not found');
        }

        $this->writeData(array_values($data));

    }

    private function readData(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $contents = file_get_contents($this->filePath);

        if ($contents === false) {
            throw new RuntimeException('Cannot read user file');
        }

        $data = json_decode($contents, true);

        if (!is_array($data)) {
            throw new RuntimeException('Invalid user JSON file');
        }

        return $data;
    }

    private function writeData(array $data): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            throw new RuntimeException('Cannot encode users data');
        }

        file_put_contents($this->filePath, $json);
    }
}