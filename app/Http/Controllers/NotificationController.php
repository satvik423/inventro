<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function fetch()
    {
        $notifications = Notifications::where('user_id', Auth::id())->latest()->take(10)->get();

        $unread = $notifications->where('read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unread,
        ]);
    }

    public function markAllAsRead()
    {
        Notifications::where('user_id', auth()->id())->where('read', false)->update(['read' => true]);

        return response()->json(['success' => true]);
    }
}
