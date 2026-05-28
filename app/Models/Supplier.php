<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier';

    protected $primaryKey = 'supplier_id';

    const CREATED_AT = 'supplier_created_at';
    const UPDATED_AT = 'supplier_updated_at';
    const DELETED_AT = 'supplier_deleted_at';

    protected $fillable = [
        'supplier_nama',
        'supplier_kontak',
        'supplier_alamat',
        'supplier_plat_kendaraan',
        'supplier_nama_driver',
    ];

    public function sukuCadang()
    {
        return $this->hasMany(SukuCadang::class, 'suku_cadang_supplier_id', 'supplier_id');
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'transaksi_masuk_supplier_id', 'supplier_id');
    }
}
