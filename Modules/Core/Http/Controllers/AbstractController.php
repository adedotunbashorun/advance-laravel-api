<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;

abstract class AbstractController extends Controller
{

    protected $repository;
    protected $permissions;

    public function __construct($repository = null)
    {
        // $this->middleware('auth:api');
        $this->repository = $repository;
    }

    public function index()
    {
        $data['datas'] = $this->repository->all();
        $data['success'] = true;
        return response()->json($data, 200);
    }

    public function destroy($model)
    {
        try {
            $this->repository->delete($model);
            return response()->json(['message' => 'data deleted successfully','success' => true], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true
            ], 400);
        }

    }

    /**
     * @param $request
     * @return array
     */
    protected function mergeRequestWithPermissions($request)
    {
        $permissions = [];

        if (! $this->permissions->permissionsAreAllFalse($request->permissions)) {
            $permissions = $this->permissions->clean($request->permissions);
        }

        return array_merge($request->all(), [ 'permissions' => $permissions ]);
    }
}
