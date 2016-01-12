<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
//use Carbon\Carbon;
use App\Models\UserLogin;
use Log;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     * @return void
     */
    public function handle(Login $event)
    {
        UserLogin::create(['user_id' => $event->user->id]);
        Log::info("User login: ".$event->user->id);
    }
}
