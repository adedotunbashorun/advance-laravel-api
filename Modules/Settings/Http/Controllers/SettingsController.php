<?php namespace Modules\Settings\Http\Controllers;

use Modules\Core\Http\Controllers\AbstractController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Settings\Repositories\SettingsInterface as Repository;
use Modules\Settings\Entities\Settings;
use DB;

class SettingsController extends AbstractController {

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            $data['setting'] = $this->repository->store($inputs);
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

    public function sendTestMail()
    {
        try{
            \Mail::send('settings::_test_email', compact('user', 'code'), function ($m) {
                $email = request()->get('test_email');
                $from_address = config('myapp.mail_from_address');
                $from_name = config('myapp.mail_from_name');
                $m->from($from_address,$from_name);
                $m->to($email)->subject('Test email from '.$from_name.' website');
            });
            return [
                'response'=>'success',
                'message'=>'Email successfully sent to '.request()->get('test_email')
            ];
        }
        catch (\Exception $e){
            return [
                'response'=>'error',
                'message'=>$e->getMessage()
            ];
        }

    }

}
