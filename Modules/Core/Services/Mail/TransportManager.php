<?php
namespace Modules\Core\Services\Mail;

class TransportManager extends \Illuminate\Mail\TransportManager {

    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->app['config']['mail'] = [
            'driver'     => config('pennylender.mail_driver'),
            'host'       => config('pennylender.mail_host'),
            'port'       => config('pennylender.mail_port'),
            'from'       => [
                'address' => config('pennylender.mail_from_address'),
                'name'    => config('pennylender.mail_from_name')
            ],
            'encryption' => config('pennylender.mail_encryption'),
            'username'   => config('pennylender.mail_username'),
            'password'   => config('pennylender.mail_password'),
            'sendmail'   => '/usr/sbin/sendmail -bs',
            'pretend'    => false,
            'stream'     => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ],
        ];

        $this->app['config']['services'] = [
            'mailgun' => [
                'domain' => 'mail.pennylender.com',
                'secret' => 'key-a219f60c6e59c8b5772b5a5298db229a',
            ],
        ];
    }

}
