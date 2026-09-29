<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user's notifications.
     */
    public function getNotifications()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Não autorizado.'], 401);
        }

        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->limit(30)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'message' => $item->message,
                    'is_read' => $item->is_read,
                    'created_at' => $item->created_at->format('d/m H:i'),
                ];
            });

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function readAll()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Não autorizado.'], 401);
        }

        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true
        ]);
    }
}
