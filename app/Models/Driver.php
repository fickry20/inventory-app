<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = [
        'supplier_id',
        'nama_driver',
        'plat_kendaraan',
        'no_surat_jalan',
        'foto_sj',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'driver_id');
    }
}
