<?php namespace Modules\Core\Providers;


use Modules\Core\Services\Mail\TransportManager;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{
    protected function registerSwiftTransport()
    {
        $this->app->singleton('swift.transport',function ($app) {
            return new TransportManager($app);
        });
    }
}
