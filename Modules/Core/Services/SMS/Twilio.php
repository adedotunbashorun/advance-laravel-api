<?php namespace Modules\Core\Services\SMS;

use Twilio\Rest\Client;
use Modules\Core\Services\SMS\SMSInterface;

class Twilio implements SMSInterface
{
    protected $client;
    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->client = new Client($sid,$token);
    }

    public function send($to,$message)
    {

        $this->client->messages->create(
        // the number you'd like to send the message to
            $to,
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+15412621714',
                // the body of the text message you'd like to send
                'body' => $message
            )
        );
    }
}
