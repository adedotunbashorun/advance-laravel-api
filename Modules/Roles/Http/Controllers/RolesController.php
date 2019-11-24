<?php

namespace Modules\Roles\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Http\Controllers\AbstractController;
use Modules\Roles\Repositories\RoleInterface as Repository;
use DB;
use Modules\Users\Services\PermissionManager;

class RolesController extends AbstractController
{
    public function __construct(Repository $repository,PermissionManager $permissions)
    {
        parent::__construct($repository);
        $this->permissions = $permissions;
    }

    public function index()
    {
        $data['datas'] = $this->repository->all(['permissions']);
        $data['permissions'] = $permissions = $this->permissions->all();
        $data['success'] = true;
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $inputs = $request->except('permissions');
            // $data = $this->mergeRequestWithPermissions($inputs);
            $data['roles'] = $role = $this->repository->create($inputs);
            $permissions = $request->input('permissions') ? $request->input('permissions') : [];
            $role->givePermissionTo($permissions);
            $data['success'] = true;
            DB::commit();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true
            ], 400);
        }

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $data['role'] = $this->repository->byId($id,['permissions']);
            $data['permissions'] = $this->permissions->all();
            $data['success'] = true;
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true
            ], 400);
        }

    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try{
            $input = $request->except('permissions','role_id');;
            $input['id'] = $id;
            $data['role'] = $role = $this->repository->update($input);
            $permissions = $request->input('permissions') ? $request->input('permissions') : [];
            $role->syncPermissions($permissions);
            $data['success'] = true;
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true
            ], 400);
        }
    }
}
