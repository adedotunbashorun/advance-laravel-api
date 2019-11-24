<?php

namespace Modules\Users\Observers;


use Illuminate\Support\Facades\Request;
use Modules\Core\Services\Payment;

class CustomerObserver
{
    protected $payment;

    public function __construct(Payment $payment){
        $this->payment = $payment;
    }

    public function saved($model)
    {
        $data = Request::all();
        $data['user_id'] = $model->id;
        $check = app('Modules\Users\Repositories\CustomerInterface')->getFirstBy('user_id', $data['user_id']);
        $data['recipient_code'] = $this->createRecepient($data);
        if($check){
            $data['id'] = $check->id;
            app('Modules\Users\Repositories\CustomerInterface')->update($data);
        }
        else app('Modules\Users\Repositories\CustomerInterface')->create($data);
    }

    private function createRecepient($data) {
        if(is_null($data['bank_code'])) return;
        $full_name = $data['first_name'] . ' ' . $data['last_name'];
        $user = [
            "type" => "nuban",
            "name" =>  $full_name,
            "description" => $full_name ." Acct",
            "account_number" => $data['account_number'],
            "bank_code" => $data['bank_code'],
            "currency" => "NGN",
            // "metadata" => [
            //    "job" => "Flesh Eater"
            // ]
        ];
        $result = $this->payment->postRequest("https://api.paystack.co/transferrecipient",$user);

        return $result->data->recipient_code;
    }
}
