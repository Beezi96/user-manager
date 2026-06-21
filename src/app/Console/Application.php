<?php

declare(strict_types=1);

namespace App\Console;

use App\Domain\User;
use App\Exception\UserNotFoundException;
use App\Service\UserService;

final class Application
{
    public function __construct(
        private UserService $userService
    )
    {}

    public function run(array $argv): int
    {
        $command = $argv[1] ?? null;

        if ($command === null) {
            $this->printHelp();

            return 1;
        }

        return match ($command) {
            'list-users' => $this->listUsers(),
            'create-user' => $this->createUsers(),
            'delete-user' => $this->deleteUsers($argv),
            default => $this->unknownCommand($command),
        };
    }

    private function  listUsers(): int
    {
        $users = $this->userService->listUsers();

        if ($users === []) {
            echo 'Users list is empty' . PHP_EOL;

            return 0;
        }
        foreach ($users as $user) {
            $this->printUser($user);
        }

        return 0;

    }

    private function createUsers(): int
    {
        $user = $this->userService->createUser();

        echo 'User created: ' . PHP_EOL;
        $this->printUser($user);

        return 0;
    }

    private function deleteUsers(array $argv): int
    {
        $id = $argv[2] ?? null;

        if ($id === null) {
            echo 'User id is required' . PHP_EOL;

            return 1;
        }

        if (!ctype_digit($id)) {
            echo 'User id must be a positive integer' . PHP_EOL;

            return 1;
        }

        try {
            $this->userService->deleteUser((int) $id);
        } catch (UserNotFoundException $e) {
            echo $e->getMessage() . PHP_EOL;

            return 1;
        }

        echo 'User deleted' . PHP_EOL;

        return 0;
    }

    private function unknownCommand(string $command): int
    {
        echo 'Unknown command: ' . $command . PHP_EOL;
        $this->printHelp();

        return 1;
    }

    private function printHelp(): void
    {
        echo 'Available commands:' . PHP_EOL;
        echo 'list-users' . PHP_EOL;
        echo 'create-users' . PHP_EOL;
        echo 'delete-user {id}' . PHP_EOL;

    }

    private function printUser(User $user): void
    {
        echo $user->getId() . ' ';
        echo $user->getFirstName() . ' ';
        echo $user->getLastName() . ' ';
        echo $user->getEmail() . PHP_EOL;
    }
}