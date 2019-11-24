<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\Register;
use Modules\Users\Http\Requests\Login;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Modules\Users\Services\UserRegistration;
use Modules\Users\Repositories\AuthInterface;
use Modules\Users\Http\Requests\ChangePasswordFormRequest;
use Modules\Core\Services\Payment;
use stdClass;
use Modules\Core\Services\Utilities\GeminiLoanService;

class AuthController extends Controller
{
    protected $auth,$payment;

    public function __construct( AuthInterface $auth, Payment $payment){
        $this->auth = $auth;
        $this->payment = $payment;
    }

    public function signup(Register $request)
    {
        \DB::beginTransaction();
        try{

            $user = app(UserRegistration::class)->register($request->all());

            \DB::commit();
            return response()->json([
                'message' => 'Successfully created user!',
                'user' => $user,
                'customer' => $user->customer,
                'success' => true
            ], 201);

        }catch(\Exception $e){
            \DB::rollback();
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
    public function login(Login $request)
    {
        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)) return response()->json([
                    'message' => 'Unauthorized',
                    'error' => true
                ], 401);

        $user = Auth::user();
        $tokenResult = $user->createToken('penny-lender');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'success' => true,
            'access_token' => $tokenResult->accessToken,
            'user' => $user,
            'customer' => $user->customer,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function getActivate($userId, $code)
    {
        try{
            $this->auth->activate($userId, $code);

            return response()->json([
                'message' => 'User account successfully activated!',
                'success' => true
            ], 201);

        }catch(\Exception $e){
            \DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function user()
    {
        return response()->json([
            'user' => Auth::user(),
            'success' => true
        ]);
    }

    public function verifyBVN($bvn){
        try{

            $data['bvn'] = $this->payment->getRequest("https://api.paystack.co/bank/resolve_bvn/".$bvn);
            $data['success'] = true;
            return response()->json($data, 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true
            ], 400);
        }
    }

    public function disableOtp(){
        try{

            $data['bvn'] = $this->payment->postRequest("https://api.paystack.co/transfer/disable_otp", []);
            $data['success'] = true;
            return response()->json($data, 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true
            ], 400);
        }
    }

    public function verifyDisableOtp($otp){
        try{

            $otp_code = [
                'otp' => $otp
            ];
            $data['bvn'] = $this->payment->postRequest("https://api.paystack.co/transfer/disable_otp_finalize",$otp_code);
            $data['success'] = true;
            return response()->json($data, 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true
            ], 400);
        }
    }


    public function forgetPassword(){

    }

    public function postChangePassword(ChangePasswordFormRequest $request)
    {
        $data = $request->all();
        $data['password']  = \Hash::make($data['password']);
        $model = $this->repository->update(current_user(), $data);

        $data['message'] = 'Your password has been successfully updated!';
        $data['success'] = true;
        return response()->json($data, 200);

        /*

        $email_data['password'] = $data['password'];
        $email_data['email'] = $model->email;
        $email_data['user_name'] = $model->first_name.' '.$model->last_name;

        event(new PasswordWasChangedEvent($email_data));*/
        /*flash()->success('Your account has been successfully updated');*/
    }

    public function state()
    {
        return app(GeminiLoanService::class)->getStates();
    }
}
