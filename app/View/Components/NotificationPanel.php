<?php

namespace app\View\Components;

use Illuminate\View\Component;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPanel extends Component
{
    public $notifications;

    public function __construct()
    {
        $this->notifications = auth()->user()->notifications()->latest()->get();
    }

    public function render()
    {
        return view('components.notification-panel');
    }
}
