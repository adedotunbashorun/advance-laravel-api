<?php

namespace Modules\Core\Services;

use Modules\Core\Services\Utilities\General;
use stdClass;

class Payment extends General{

    protected $paymentToken;

    public function __construct()
    {
        $this->paymentToken = config('pennylender.payment.paystack.secret_key');
    }

    public function getRequest($url) {
        return $this->curlRestRequest("GET", $url, null, $this->paymentToken);
    }

    public function postRequest($url , $data) {
        return $this->curlRestRequest("POST", $url, $data, $this->paymentToken);
    }
}
