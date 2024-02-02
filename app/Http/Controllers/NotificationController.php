<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Get the notifications for the authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNotifications()
    {
        $notifications = DB::table('notifications')
            ->where('notifiable_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $notifications], 200);
    }

    /**
     * Get the unread notifications count for the authenticated user.
     *
     * @return int
     */
    public function getUnreadNotificationsCount()
    {
        return response()->json(['count' => auth()->user()->unreadNotifications->count()], 200);
    }

    /**
     * Mark all the unread notifications as read for the authenticated user.
     *
     * @return void
     */
    public function markAsReadAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read'], 200);
    }

    /**
     * Mark a specific notification as read for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function markAsRead(Request $request)
    {
        auth()->user()->notifications->where('id', $request->id)->markAsRead();
        return response()->json(['message' => 'Notification marked as read'], 200);
    }

    /**
     * Mark a specific notification as unread for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function markAsUnread(Request $request)
    {
        auth()->user()->notifications->where('id', $request->id)->markAsUnread();
        return response()->json(['message' => 'Notification marked as unread'], 200);
    }

    /**
     * Delete a specific notification for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function delete(Request $request)
    {
        DB::table('notifications')->where('id', $request->id)->delete();
        return response()->json(['message' => 'Notification deleted'], 200);
    }
}
