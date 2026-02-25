<?php

namespace app\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use app\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layouts.tabler', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function fetchNotifications()
    {
        $user = Auth::user();

        $notifications = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($notification) {
                return $notification->read_at ? 'read' : 'unread';
            });

        return response()->json([
            'unread' => $notifications['unread'] ?? [],
            'read' => $notifications['read'] ?? [],
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if ($notification && $notification->notifiable_id === Auth::id()) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read.']);
        }

        return response()->json(['error' => 'Notification not found or unauthorized.'], 404);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }
}