<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a notification to a specific user.
     *
     * @param User $user The user to notify
     * @param string $title The notification title
     * @param string $content The notification content
     * @param string $type The notification type (info, warning, success, danger)
     * @param string|null $url Optional URL to redirect to when clicked
     * @return Notification|null The created notification or null if failed
     */
    public function sendToUser(User $user, string $title, string $content, string $type = 'info', ?string $url = null): ?Notification
    {
        try {
            return Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'content' => $content,
                'type' => $type,
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Send a notification to all users with a specific role.
     *
     * @param string $role The role of users to notify (admin, seller, buyer)
     * @param string $title The notification title
     * @param string $content The notification content
     * @param string $type The notification type (info, warning, success, danger)
     * @param string|null $url Optional URL to redirect to when clicked
     * @return bool Whether any notifications were successfully created
     */
    public function sendToRole(string $role, string $title, string $content, string $type = 'info', ?string $url = null): bool
    {
        try {
            $users = User::where('role', $role)->get();
            
            if ($users->isEmpty()) {
                return false;
            }
            
            $users->each(function ($user) use ($title, $content, $type, $url) {
                $this->sendToUser($user, $title, $content, $type, $url);
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to create notifications for role: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send a notification to all admins.
     *
     * @param string $title The notification title
     * @param string $content The notification content
     * @param string $type The notification type (info, warning, success, danger)
     * @param string|null $url Optional URL to redirect to when clicked
     * @return bool Whether any notifications were successfully created
     */
    public function notifyAdmins(string $title, string $content, string $type = 'info', ?string $url = null): bool
    {
        return $this->sendToRole('admin', $title, $content, $type, $url);
    }
    
    /**
     * Send a notification to all sellers.
     *
     * @param string $title The notification title
     * @param string $content The notification content
     * @param string $type The notification type (info, warning, success, danger)
     * @param string|null $url Optional URL to redirect to when clicked
     * @return bool Whether any notifications were successfully created
     */
    public function notifySellers(string $title, string $content, string $type = 'info', ?string $url = null): bool
    {
        return $this->sendToRole('seller', $title, $content, $type, $url);
    }
}
