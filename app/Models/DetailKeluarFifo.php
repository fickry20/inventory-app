<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailKeluarFifo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_keluar_fifo';

    protected $primaryKey = 'detail_keluar_fifo_id';

    const CREATED_AT = 'fifo_created_at';
    const UPDATED_AT = 'fifo_updated_at';
    const DELETED_AT = 'fifo_deleted_at';

    protected $fillable = [
        'transaksi_keluar_id',
        'batch_masuk_id',
        'fifo_jumlah_diambil',
    ];

    public function transaksiKeluar()
    {
        return $this->belongsTo(TransaksiKeluar::class, 'transaksi_keluar_id', 'transaksi_keluar_id');
    }

    public function batchMasuk()
    {
        return $this->belongsTo(BatchMasuk::class, 'batch_masuk_id', 'batch_masuk_id');
    }
}
