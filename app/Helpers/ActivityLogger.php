<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log a user action.
     *
     * @param string $action Action name (e.g., 'CREATE', 'UPDATE', etc.)
     * @param mixed $subject Subject model or string
     * @param string|null $description Friendly description
     * @return \App\Models\ActivityLog
     */
    public static function log(string $action, $subject = null, ?string $description = null)
    {
        $user = Auth::user();

        $logData = [
            'users_id'       => $user ? $user->users_id : null,
            'users_username' => $user ? $user->users_username : 'System',
            'users_role'     => $user ? $user->users_role : null,
            'action'         => $action,
            'description'    => $description ?? '',
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ];

        if ($subject) {
            if (is_object($subject)) {
                $logData['subject_type'] = get_class($subject);
                $primaryKey = $subject->getKeyName();
                $logData['subject_id'] = $subject->$primaryKey ?? null;
            } else {
                $logData['subject_type'] = $subject;
            }
        }

        return ActivityLog::create($logData);
    }
}
