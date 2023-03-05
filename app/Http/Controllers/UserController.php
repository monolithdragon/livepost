<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @group User Management
 *
 * APIs to manage the user resource.
 */
class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @queryParam page_size integer required Size per page. Default to 20. Example: 20
     * @queryParam page integer Page to view. Example: 1
     *
     * @apiResourceModel App\Models\User
     * @apiResourceCollection App\Http\Resources\UserResource
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 20;
        $users = User::query()->paginate($pageSize);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @bodyParam name string required Name of the user. Example: John Doe
     * @bodyParam email string required Email address of the user. Example: johndoe@doe.com
     * @bodyParam password string required Password of the user.
     *
     * @apiResourceModel App\Models\User
     * @apiResourceCollection App\Http\Resources\UserResource
     */
    public function store(StoreUserRequest $request, UserRepository $repository)
    {
        $created = $repository->create($request->only([
            'name',
            'email',
            'password'
        ]));

        return new UserResource($created);
    }

    /**
     * Display the specified user.
     *
     * @apiResourceModel App\Models\User
     * @apiResourceCollection App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @bodyParam name string required Name of the user. Example: John Doe
     * @bodyParam email string required Email address of the user. Example: johndoe@doe.com
     * @bodyParam password string required Password of the user.
     *
     * @apiResourceModel App\Models\User
     * @apiResourceCollection App\Http\Resources\UserResource
     */
    public function update(UpdateUserRequest $request, User $user, UserRepository $repository)
    {
        $user = $repository->update($user, $request->only([
            'name',
            'email',
            'password'
        ]));

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @response 200 { "data": "Successfully"}
     *
     * @apiResourceModel App\Models\User
     * @apiResourceCollection App\Http\Resources\UserResource
     */
    public function destroy(User $user, UserRepository $repository)
    {
        $user = $repository->forceDelete($user);

        return new JsonResponse([
            'data' => 'Successfully deleted'
        ]);
    }
}