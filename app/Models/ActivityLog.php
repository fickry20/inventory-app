<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'users_id',
        'users_username',
        'users_role',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
        'user_agent',
    ];

    /**
     * Relationship with the User who did the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }
}
