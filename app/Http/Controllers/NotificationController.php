<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function unread()
    {
        if (!Auth::check()) return response()->json(['count' => 0, 'notifications' => []]);

        $notifications = Auth::user()->customNotifications()->orderBy('created_at', 'desc')->take(10)->get();
        $unreadCount = Auth::user()->customNotifications()->where('is_read', false)->count();

        // format date
        foreach ($notifications as $n) {
            $n->time_ago = $n->created_at->diffForHumans();
        }

        return response()->json([
            'count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    public function markAsRead()
    {
        if (Auth::check()) {
            Auth::user()->customNotifications()->where('is_read', false)->update(['is_read' => true]);
        }
        return response()->json(['success' => true]);
    }
}
