<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\RoleRequest\DeleteRoleRequest;
use App\Http\Requests\API\RoleRequest\GetRoleRequest;
use App\Http\Requests\API\RoleRequest\StoreRoleRequest;
use App\Http\Requests\API\RoleRequest\UpdateRoleRequest;
use App\Http\Resources\API\Admin\Role\GetRoleResource;

class RoleController extends Controller
{
    public function get(GetRoleRequest $request)
    {
        $role = app('GetRoleService')->execute($request->all());

        $data = null;
        if (isset($role['data'])) {
            $data = ( isset($role['data']->id)) ? new GetRoleResource($role['data']) :
            GetRoleResource::collection($role['data']);
        }

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $data,
            'pagination' => $role['pagination'] ?? null
        ], $role['response_code']);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = app('StoreRoleService')->execute([
            'site_uuid' => $request->site_uuid,
            'name' => $request->name,
            'code' => $request->code,
            'location' => $request->location,
        ]);

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $role['data'],
        ], $role['response_code']);
    }

    public function update (UpdateRoleRequest $request)
    {
        $role = app('UpdateRoleService')->execute([
            'area_uuid' => $request->area_uuid,
            'site_uuid' => $request->site_uuid,
            'name' => $request->name,
            'code' => $request->code,
            'location' => $request->location,
        ]);

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $role['data'],
        ], $role['response_code']);
    }
    public function destroy (DeleteRoleRequest $request)
    {

        $role = app('DeleteRoleService')->execute([
            "area_uuid" => $request->area_uuid,
        ]);

        return response()->json([
            'success' => ( isset($role['error']) ? false : true ),
            'message' => $role['message'],
            'data' => $role['data'],
            'pagination' => $role['pagination'] ?? null
        ], $role['response_code']);
    }
}
