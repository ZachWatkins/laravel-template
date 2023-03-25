<?php

namespace App\Interfaces;

interface UserStorageInterface
{
    public function forUser(int $id = -1): UserStorageInterface;
    public function exists(string $path): bool;
    public function createMissingDirectories(string $path): UserStorageInterface;
    public function delete(string $path, bool $quiet): bool;
}
