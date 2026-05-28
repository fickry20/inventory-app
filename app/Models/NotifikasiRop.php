<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotifikasiRop extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notifikasi_rop';

    protected $primaryKey = 'notifikasi_rop_id';

    const CREATED_AT = 'rop_created_at';
    const UPDATED_AT = 'rop_updated_at';
    const DELETED_AT = 'rop_deleted_at';

    protected $fillable = [
        'suku_cadang_id',
        'rop_stok_saat_notif',
        'rop_rop_saat_notif',
        'rop_sudah_ditangani',
    ];

    protected $casts = [
        'rop_sudah_ditangani' => 'boolean',
    ];

    public function sukuCadang()
    {
        return $this->belongsTo(SukuCadang::class, 'suku_cadang_id', 'suku_cadang_id');
    }
}
