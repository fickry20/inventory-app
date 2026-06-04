<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_masuk';

    protected $primaryKey = 'transaksi_masuk_id';

    const CREATED_AT = 'transaksi_masuk_created_at';
    const UPDATED_AT = 'transaksi_masuk_updated_at';
    const DELETED_AT = 'transaksi_masuk_deleted_at';

    protected $fillable = [
        'transaksi_masuk_suku_cadang_id',
        'transaksi_masuk_supplier_id',
        'transaksi_masuk_users_id',
        'transaksi_masuk_kendaraan_id',
        'driver_id',
        'transaksi_masuk_no_surat_jalan',
        'transaksi_masuk_jumlah',
        'transaksi_masuk_keterangan',
    ];

    public function sukuCadang()
    {
        return $this->belongsTo(SukuCadang::class, 'transaksi_masuk_suku_cadang_id', 'suku_cadang_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'transaksi_masuk_supplier_id', 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'transaksi_masuk_users_id', 'users_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'transaksi_masuk_kendaraan_id', 'kendaraan_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function batchMasuk()
    {
        return $this->hasMany(BatchMasuk::class, 'transaksi_masuk', 'transaksi_masuk_id');
    }
}
