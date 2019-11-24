<?php namespace Modules\Transactions\Http\Controllers;

use Modules\Core\Http\Controllers\AbstractController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Transactions\Repositories\TransactionsInterface as Repository;

class TransactionsController extends AbstractController {

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            $data['transaction'] = $this->repository->create($inputs);
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
            $data['transaction'] = $model = $this->repository->byId($id,[]);
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
            $input = $request->all();
            $input['id'] = $id;
            $data['transaction'] = $this->repository->update($input);
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
