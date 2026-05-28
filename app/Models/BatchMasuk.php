<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'batch_masuk';

    protected $primaryKey = 'batch_masuk_id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'suku_cadang_id',
        'transaksi_masuk',
        'jumlah_awal',
        'jumlah_sisa',
        'tanggal_masuk',
        'is_habis',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'is_habis' => 'boolean',
    ];

    public function sukuCadang()
    {
        return $this->belongsTo(SukuCadang::class, 'suku_cadang_id', 'suku_cadang_id');
    }

    public function transaksiMasukRelation()
    {
        return $this->belongsTo(TransaksiMasuk::class, 'transaksi_masuk', 'transaksi_masuk_id');
    }

    public function detailKeluarFifo()
    {
        return $this->hasMany(DetailKeluarFifo::class, 'batch_masuk_id', 'batch_masuk_id');
    }
}
