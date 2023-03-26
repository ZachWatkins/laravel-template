<?php

namespace App\Interfaces;

interface UserStorageInterface
{
    public function setUser(int $id = -1): UserStorageInterface;
    public function files(string $path): \Generator;
    public function allFiles(string $path): \Generator;
    public function exists(string $path): bool;
    public function makeDirectory(string $path, bool $recursive): UserStorageInterface;
    public function delete(string $path, bool $quiet): bool;
    public function hasFileBefore(int|string|\DateTime $date): bool;
    public function deleteFilesBefore(int|string|\DateTime $date): UserStorageInterface;
}
