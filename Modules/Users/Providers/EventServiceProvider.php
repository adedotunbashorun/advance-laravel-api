<?php namespace Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Users\Events\Handlers\SendRegistrationConfirmationEmail;
use Modules\Users\Events\Handlers\SendResetCodeEmail;
use Modules\Users\Events\RoleWasUpdated;
use Modules\Users\Events\UserHasBegunResetProcess;
use Modules\Users\Events\UserHasRegistered;
use Modules\Users\Events\UserWasUpdated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserHasRegistered::class => [
            SendRegistrationConfirmationEmail::class,
        ],
        UserHasBegunResetProcess::class => [
            SendResetCodeEmail::class,
        ],
    ];
}
