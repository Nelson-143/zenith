<?php

namespace app\Listeners;

use Illuminate\Auth\Events\Login;

class LogUserLoginActivity
{
    public function handle(Login $event)
    {
        // Skip logging if user is an admin with excluded email
        if ($event->user instanceof \app\Models\Admin && 
            in_array($event->user->email, ['eggplant@gmail.com'])) {
            return;
        }

        activity()
            ->causedBy($event->user instanceof \Illuminate\Database\Eloquent\Model ? $event->user : null)
            ->performedOn($event->user instanceof \Illuminate\Database\Eloquent\Model ? $event->user : null)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ])
            ->log('Logged in');
    }
}