<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected User $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Find by phone
     */
    public function findByPhone(string $phone): ?User
    {
        return $this->model->where('phone', $phone)->first();
    }

    /**
     * Create user
     */
    public function createUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->model->create($data);
    }

    /**
     * First or create user
     */
    public function firstOrCreate(array $attributes, array $values = []): User
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    /**
     * Get all users by pagination
     */
    public function getAllUsers(
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = $this->model->newQuery();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }
        if (isset($filters['phone'])) {
            $query->where('phone', 'like', '%'.$filters['phone'].'%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Update user fields
     */
    public function updateUser(int $id, array $data): bool
    {
        $user = $this->getUserById($id);

        if ($user) {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            return $user->update($data);
        }

        return false;
    }

    /**
     * Get user by id
     */
    public function getUserById(int $id): ?User
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Delete user
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);

        if ($user) {
            return $user->delete();
        }

        return false;
    }
}
