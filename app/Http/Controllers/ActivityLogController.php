<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $actionFilter = $request->input('action_type');
        $userFilter = $request->input('user_id');

        $query = ActivityLog::with('user');

        // Access Control: Non-SPV can only see their own logs
        if ($user->users_role !== 'spv') {
            $query->where('users_id', $user->users_id);
        } else {
            // SPV can filter by user
            if ($userFilter) {
                $query->where('users_id', $userFilter);
            }
        }

        // Filter by action
        if ($actionFilter) {
            $query->where('action', $actionFilter);
        }

        // Search in description, username, action, ip_address
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('users_username', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        // Get list of users for dropdown filter (SPV only)
        $users = [];
        if ($user->users_role === 'spv') {
            $users = User::orderBy('users_username', 'asc')->get();
        }

        // Get unique action types for filter dropdown
        $actionQuery = ActivityLog::select('action')->distinct();
        if ($user->users_role !== 'spv') {
            $actionQuery->where('users_id', $user->users_id);
        }
        $actions = $actionQuery->pluck('action')->toArray();

        return view('activity_log.index', compact('logs', 'users', 'actions', 'search', 'actionFilter', 'userFilter'));
    }
}
