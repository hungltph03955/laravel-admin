<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     title="Store User request",
 *     description="Store User request body data"
 * )
 * */
class UserController extends Controller
{
    /**
     * @OA\GET(path="/users",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200",
     *          description = "User Collection",
     *          @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          description="Pagination page",
     *          in="query",
     *          @OA\Schema(
     *              type = "integer"
     *          )
     *     )
     * )
     * */

    public function index()
    {
        \Gate::authorize('view', 'users');
        $users = User::paginate();
        return UserResource::collection($users);
    }

    /**
     * @OA\GET(path="/users/{id}",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200",
     *          description = "User",
     *          @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type = "integer"
     *          )
     *     )
     * )
     * */

    public function show($id)
    {
        \Gate::authorize('view', 'users');
        $user = User::find($id);
        return new UserResource($user);
    }

    /**
     * @OA\post(
     *     path="/users",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="201",
     *          description = "User",
     *          @OA\JsonContent()
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserCreateRequest")
     *     )
     * )
     * */

    public function store(UserCreateRequest $request)
    {
        \Gate::authorize('edit', 'users');
        $user = User::create($request->only('first_name', 'last_name', 'email', 'role_id') + [
                'password' => Hash::make($request->input('password')),
            ]);
        return response(new UserResource($user), Response::HTTP_CREATED);
    }


    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="202",
     *          description = "User update",
     *          @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type = "integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserCreateRequest")
     *     )
     * )
     * */

    public function update(UserUpdateRequest $request, $id)
    {
        \Gate::authorize('edit', 'users');
        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="204",
     *          description = "User delete",
     *          @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type = "integer"
     *          )
     *     ),
     * )
     * */

    public function destroy($id)
    {
        \Gate::authorize('edit', 'users');
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\GET(path="/user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200",
     *          description = "Authenticate User",
     *          @OA\JsonContent()
     *     ),
     * )
     * */
    public function user()
    {
        $user = \Auth::user();
        return (new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]);
    }

    public function updateInfo(Request $request)
    {
        $user = \Auth::user();
        $user->update($request->only('first_name', 'last_name', 'email'));
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function updatePassword(Request $request)
    {
        $user = \Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

}
