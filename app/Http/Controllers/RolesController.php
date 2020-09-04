<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{
    /**
     * @return Role[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Role::all();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create($request->only('name'));
        return response($role, Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @return Role|Role[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return Role::find($id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->update($request->only('name'));
        return response($role, Response::HTTP_ACCEPTED);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
