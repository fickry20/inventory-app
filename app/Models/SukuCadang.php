<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SukuCadang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suku_cadang';

    protected $primaryKey = 'suku_cadang_id';

    const CREATED_AT = 'suku_cadang_created_at';
    const UPDATED_AT = 'suku_cadang_updated_at';
    const DELETED_AT = 'suku_cadang_deleted_at';

    protected $fillable = [
        'suku_cadang_supplier_id',
        'suku_cadang_kode',
        'suku_cadang_nama',
        'suku_cadang_kategori',
        'suku_cadang_satuan',
        'suku_cadang_stok_total',
        'suku_cadang_reorder_point',
        'suku_cadang_stok_minimum',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'suku_cadang_supplier_id', 'supplier_id');
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'transaksi_masuk_suku_cadang_id', 'suku_cadang_id');
    }

    public function batchMasuk()
    {
        return $this->hasMany(BatchMasuk::class, 'suku_cadang_id', 'suku_cadang_id');
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'suku_cadang_id', 'suku_cadang_id');
    }

    public function notifikasiRop()
    {
        return $this->hasMany(NotifikasiRop::class, 'suku_cadang_id', 'suku_cadang_id');
    }
}
