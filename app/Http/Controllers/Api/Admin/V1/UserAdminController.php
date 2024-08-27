<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Http\Collections\UserCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\V1\StoreUserRequest;
use App\Http\Requests\Api\Admin\V1\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\CollectionResponse;
use App\Http\Responses\JsonApiResponse;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UserAdminController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', auth()->user());

        $users = $this->userRepository->getAllUsers();

        return new CollectionResponse(
            data: new UserCollection($users),
            status: Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        Gate::authorize('create', auth()->user());

        $user = $this->userRepository->createUser($request->validated());

        return new JsonApiResponse(
            data: ['data' => new UserResource($user)],
            status: Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return new JsonApiResponse(
            data: ['data' => new UserResource($user)],
            status: Response::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $this->userRepository->updateUser($user->id, $request->validated());

        return new JsonApiResponse(
            data: ['message' => 'User updated successfully'],
            status: Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', auth()->user());

        $this->userRepository->deleteUser($user->id);

        return new JsonApiResponse(
            data: ['message' => 'User deleted successfully'],
            status: Response::HTTP_OK
        );
    }
}
