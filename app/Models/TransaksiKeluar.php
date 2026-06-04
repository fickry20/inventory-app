<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiKeluar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_keluar';

    protected $primaryKey = 'transaksi_keluar_id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'suku_cadang_id',
        'users',
        'kendaraan_id',
        'no_surat_jalan',
        'tujuan_pt_id',
        'jumlah_diminta',
        'jumlah_terpenuhi',
        'status',
        'keterangan',
    ];

    public function sukuCadang()
    {
        return $this->belongsTo(SukuCadang::class, 'suku_cadang_id', 'suku_cadang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users', 'users_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id', 'kendaraan_id');
    }

    public function perusahaanTujuan()
    {
        return $this->belongsTo(PerusahaanTujuan::class, 'tujuan_pt_id');
    }

    public function detailKeluarFifo()
    {
        return $this->hasMany(DetailKeluarFifo::class, 'transaksi_keluar_id', 'transaksi_keluar_id');
    }
}
