<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkOrder;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Mark notification as read and redirect to target URL.
     * If the target resource (like Work Order) has been deleted,
     * automatically purge the notification and redirect safely.
     */
    public function read($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if (!$notification) {
            return redirect()->route('dashboard')->with('info', 'Notifikasi tidak ditemukan.');
        }

        // Check if notification points to a WorkOrder that is deleted
        $data = $notification->data ?? [];
        $woId = $data['work_order_id'] ?? null;
        if ($woId && !WorkOrder::where('id', $woId)->exists()) {
            $notification->delete();
            return redirect()->route('dashboard')->with('info', 'Work Order atau data terkait telah dihapus. Notifikasi telah dibersihkan.');
        }

        // Mark as read
        $notification->markAsRead();

        $targetUrl = $data['url'] ?? route('dashboard');

        return redirect()->to($targetUrl);
    }

    /**
     * Delete a single notification.
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->delete();
            return back()->with('success', 'Notifikasi berhasil dihapus.');
        }

        return back()->with('info', 'Notifikasi tidak ditemukan.');
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    /**
     * Clear all notifications for the user.
     */
    public function clearAll()
    {
        Auth::user()->notifications()->delete();

        return back()->with('success', 'Semua notifikasi berhasil dibersihkan.');
    }
}
