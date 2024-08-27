<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findByPhone(string $phone): ?User;

    public function createUser(array $data): User;

    public function firstOrCreate(array $attributes, array $values = []): User;

    public function getAllUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function getUserById(int $id): ?User;

    public function updateUser(int $id, array $data): bool;

    public function deleteUser(int $id): bool;
}
