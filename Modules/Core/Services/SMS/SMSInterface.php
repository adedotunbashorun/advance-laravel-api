<?php
namespace Modules\Core\Services\SMS;


interface SMSInterface {
    public function send($to,$message);
}