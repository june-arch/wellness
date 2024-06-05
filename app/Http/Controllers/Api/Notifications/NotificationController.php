<?php
namespace App\Http\Controllers\Api\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = collect($user->unreadNotifications);

        $totalUnread = $notifications->count();

        if ($totalUnread < 10) {
            $notifications->merge(
                $user->readNotifications
                    ->take(10 - $totalUnread)
                    ->toArray()
            );
        }

        return $this->success($notifications);
    }

    public function read(Request $request, DatabaseNotification $notification)
    {
        $notification = $request->user()->notifications()->find($notification->id);

        if (!$notification) {
            abort(404);
        }

        $notification->markAsRead();

        $this->success('', 'Success mark notification as read');
    }

    public function unread(Request $request, DatabaseNotification $notification)
    {
        $notification = $request->user()->notifications()->find($notification->id);

        if (!$notification) {
            abort(404);
        }

        $notification->update(['read_at' => null]);

        $this->success('', 'Success mark notification as unread');
    }
}
