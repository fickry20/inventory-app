<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerusahaanTujuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perusahaan_tujuan';

    protected $fillable = [
        'nama',
        'kontak',
        'alamat',
    ];

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'tujuan_pt_id');
    }
}
