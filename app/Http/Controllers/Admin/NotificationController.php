<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    // كل الإشعارات
    public function index() {
        $notifications = auth()->user()
                               ->notifications()
                               ->paginate(20);

        // تحديد الكل كمقروء عند فتح الصفحة
        auth()->user()->unreadNotifications->markAsRead();

        return view('admin.notifications.index', compact('notifications'));
    }

    // تحديد إشعار واحد كمقروء والتوجيه
    public function read(string $id) {
        $notification = auth()->user()
                              ->notifications()
                              ->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('admin.dashboard'));
    }

    // حذف كل الإشعارات
    public function clearAll() {
        auth()->user()->notifications()->delete();
        return redirect()->back()->with('success', 'تم حذف كل الإشعارات');
    }
}
