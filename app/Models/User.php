<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $primaryKey = 'users_id';

    const CREATED_AT = 'users_created_at';
    const UPDATED_AT = 'users_updated_at';
    const DELETED_AT = 'users_deleted_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'users_nik',
        'users_username',
        'users_email',
        'users_password_hash',
        'users_jabatan',
        'users_nomor_telepon',
        'users_role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'users_password_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'users_password_hash' => 'hashed',
    ];

    /**
     * Override standard password column name.
     */
    public function getAuthPassword()
    {
        return $this->users_password_hash;
    }

    /**
     * Relationships
     */
    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'transaksi_masuk_users_id', 'users_id');
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'users', 'users_id');
    }
}
