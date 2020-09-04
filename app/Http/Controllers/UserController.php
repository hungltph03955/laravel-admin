<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = User::paginate();
        return UserResource::collection($users);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $user = User::find($id);
        return new UserResource($user);
    }

    /**
     * @param UserCreateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $user = User::create($request->only('first_name', 'last_name', 'email', 'role_id') + [
                'password' => Hash::make($request->input('password')),
            ]);
        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * @param UserUpdateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return UserResource
     */
    public function user()
    {
        return new UserResource(\Auth::user());
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
