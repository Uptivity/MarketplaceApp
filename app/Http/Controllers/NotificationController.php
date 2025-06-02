<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Check if notification belongs to the authenticated user
        if ($notification->user_id === Auth::id()) {
            $notification->markAsRead();
        }
        
        // Redirect to the notification URL if set, otherwise to notifications index
        if ($notification->url) {
            return redirect($notification->url);
        }
        
        return redirect()->back();
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
    
    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        // Check if notification belongs to the authenticated user
        if ($notification->user_id === Auth::id()) {
            $notification->delete();
        }
        
        return redirect()->back()->with('success', 'Notification deleted.');
    }
}
