<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{

    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    public function store(Request $request)
    {
        \Gate::authorize('edit', 'roles');
        $role = Role::create($request->only('name'));

        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @return Role|Role[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        \Gate::authorize('view', 'roles');
        return Role::find($id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        \Gate::authorize('edit', 'roles');
        $role = Role::find($id);
        $role->update($request->only('name'));
        DB::table('role_permission')->where('role_id', $role->id)->delete();
        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }
        return response(new RoleResource($role), Response::HTTP_ACCEPTED);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \Gate::authorize('edit', 'roles');
        DB::table('role_permission')->where('role_id', $id)->delete();
        Role::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
