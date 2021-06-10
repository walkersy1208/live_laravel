<?php

namespace App\Listeners;

use App\Events\UserLogin;

class sendEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    //protected $user = '';

    public function __construct()
    {
        //$this->user = $user;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(UserLogin $event)
    {
        //send email to login user
        $event_user_info = $event->user->toArray();
    }
}
