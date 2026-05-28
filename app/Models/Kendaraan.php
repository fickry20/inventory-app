<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kendaraan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kendaraan';

    protected $primaryKey = 'kendaraan_id';

    const CREATED_AT = 'kendaraan_created_at';
    const UPDATED_AT = 'kendaraan_updated_at';
    const DELETED_AT = 'kendaraan_deleted_at';

    protected $fillable = [
        'kendaraan_plat',
        'kendaraan_jenis',
        'kendaraan_nama_driver',
    ];

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'transaksi_masuk_kendaraan_id', 'kendaraan_id');
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'kendaraan_id', 'kendaraan_id');
    }
}
