<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Supplier
        $supplier1Id = DB::table('supplier')->insertGetId([
            'supplier_nama' => 'PT. Global Spareparts',
            'supplier_kontak' => '081122334455',
            'supplier_alamat' => 'Kawasan Industri Jababeka, Bekasi',
            'supplier_created_at' => Carbon::now(),
            'supplier_updated_at' => Carbon::now(),
        ]);

        $supplier2Id = DB::table('supplier')->insertGetId([
            'supplier_nama' => 'CV. Jaya Mandiri',
            'supplier_kontak' => '089988776655',
            'supplier_alamat' => 'Jl. Bubutan No. 12, Surabaya',
            'supplier_created_at' => Carbon::now(),
            'supplier_updated_at' => Carbon::now(),
        ]);

        // 2. Seed Drivers for Suppliers
        DB::table('drivers')->insert([
            [
                'supplier_id' => $supplier1Id,
                'nama_driver' => 'Budi Santoso',
                'plat_kendaraan' => 'B 1234 ABC',
                'no_surat_jalan' => 'SJ-GLB-101',
                'foto_sj' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'supplier_id' => $supplier1Id,
                'nama_driver' => 'Joko Widodo',
                'plat_kendaraan' => 'B 5678 XYZ',
                'no_surat_jalan' => 'SJ-GLB-102',
                'foto_sj' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'supplier_id' => $supplier2Id,
                'nama_driver' => 'Ahmad Fauzi',
                'plat_kendaraan' => 'L 9999 JK',
                'no_surat_jalan' => 'SJ-JAY-201',
                'foto_sj' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // 3. Seed Perusahaan Tujuan
        DB::table('perusahaan_tujuan')->insert([
            [
                'nama' => 'PT. Harapan Bangsa',
                'kontak' => '081234567001',
                'alamat' => 'Kawasan Industri Pulogadung, Jakarta Timur',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'PT. Sinar Mas Logistik',
                'kontak' => '081234567002',
                'alamat' => 'Marga Mulya, Bekasi Utara',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'PT. Astra Otoparts',
                'kontak' => '081234567003',
                'alamat' => 'Jl. Pegangsaan Dua, Kelapa Gading',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // 4. Seed Kendaraan (Internal)
        DB::table('kendaraan')->insert([
            [
                'kendaraan_plat' => 'B 2233 KAA',
                'kendaraan_jenis' => 'box',
                'kendaraan_nama_driver' => 'Rudi Hermawan',
                'kendaraan_created_at' => Carbon::now(),
                'kendaraan_updated_at' => Carbon::now(),
            ],
            [
                'kendaraan_plat' => 'B 4455 KBB',
                'kendaraan_jenis' => 'engkel',
                'kendaraan_nama_driver' => 'Slamet Riyadi',
                'kendaraan_created_at' => Carbon::now(),
                'kendaraan_updated_at' => Carbon::now(),
            ]
        ]);

        // 5. Seed Suku Cadang (Spare parts)
        DB::table('suku_cadang')->insert([
            [
                'suku_cadang_supplier_id' => $supplier1Id,
                'suku_cadang_kode' => 'FL-OIL-001',
                'suku_cadang_nama' => 'Filter Oli Denso',
                'suku_cadang_kategori' => 'Filter',
                'suku_cadang_satuan' => 'Pcs',
                'suku_cadang_stok_total' => 100,
                'suku_cadang_reorder_point' => 20,
                'suku_cadang_stok_minimum' => 10,
                'suku_cadang_created_at' => Carbon::now(),
                'suku_cadang_updated_at' => Carbon::now(),
            ],
            [
                'suku_cadang_supplier_id' => $supplier1Id,
                'suku_cadang_kode' => 'BR-PAD-002',
                'suku_cadang_nama' => 'Kampas Rem Depan Avanza',
                'suku_cadang_kategori' => 'Brake System',
                'suku_cadang_satuan' => 'Set',
                'suku_cadang_stok_total' => 50,
                'suku_cadang_reorder_point' => 15,
                'suku_cadang_stok_minimum' => 5,
                'suku_cadang_created_at' => Carbon::now(),
                'suku_cadang_updated_at' => Carbon::now(),
            ],
            [
                'suku_cadang_supplier_id' => $supplier2Id,
                'suku_cadang_kode' => 'SP-ARK-003',
                'suku_cadang_nama' => 'Busi NGK Iridium',
                'suku_cadang_kategori' => 'Ignition',
                'suku_cadang_satuan' => 'Pcs',
                'suku_cadang_stok_total' => 200,
                'suku_cadang_reorder_point' => 30,
                'suku_cadang_stok_minimum' => 15,
                'suku_cadang_created_at' => Carbon::now(),
                'suku_cadang_updated_at' => Carbon::now(),
            ],
        ]);

        // 6. Seed Transaksi Masuk
        $adminUserId = DB::table('users')->where('users_role', 'admin_gudang')->value('users_id') ?? 1;
        
        $suku1Id = DB::table('suku_cadang')->where('suku_cadang_kode', 'FL-OIL-001')->value('suku_cadang_id');
        $suku3Id = DB::table('suku_cadang')->where('suku_cadang_kode', 'SP-ARK-003')->value('suku_cadang_id');

        $driver1Id = DB::table('drivers')->where('nama_driver', 'Budi Santoso')->value('id');
        $driver2Id = DB::table('drivers')->where('nama_driver', 'Ahmad Fauzi')->value('id');

        // Transaksi Masuk 1
        $tMasuk1Id = DB::table('transaksi_masuk')->insertGetId([
            'transaksi_masuk_suku_cadang_id' => $suku1Id,
            'transaksi_masuk_supplier_id' => $supplier1Id,
            'transaksi_masuk_users_id' => $adminUserId,
            'driver_id' => $driver1Id,
            'transaksi_masuk_no_surat_jalan' => 'SJ-GLB-101',
            'transaksi_masuk_jumlah' => 100,
            'transaksi_masuk_keterangan' => 'Stok awal dari supplier',
            'transaksi_masuk_created_at' => Carbon::now()->subDays(5),
            'transaksi_masuk_updated_at' => Carbon::now()->subDays(5),
        ]);

        // Batch Masuk 1 (consumed by FIFO)
        $batch1Id = DB::table('batch_masuk')->insertGetId([
            'suku_cadang_id' => $suku1Id,
            'transaksi_masuk' => $tMasuk1Id,
            'jumlah_awal' => 100,
            'jumlah_sisa' => 70, // 100 - 30 taken by outgoing
            'tanggal_masuk' => Carbon::now()->subDays(5),
            'is_habis' => false,
        ]);

        // Transaksi Masuk 2
        $tMasuk2Id = DB::table('transaksi_masuk')->insertGetId([
            'transaksi_masuk_suku_cadang_id' => $suku3Id,
            'transaksi_masuk_supplier_id' => $supplier2Id,
            'transaksi_masuk_users_id' => $adminUserId,
            'driver_id' => $driver2Id,
            'transaksi_masuk_no_surat_jalan' => 'SJ-JAY-201',
            'transaksi_masuk_jumlah' => 200,
            'transaksi_masuk_keterangan' => 'Pengadaan rutin busi',
            'transaksi_masuk_created_at' => Carbon::now()->subDays(2),
            'transaksi_masuk_updated_at' => Carbon::now()->subDays(2),
        ]);

        // Batch Masuk 2
        DB::table('batch_masuk')->insert([
            'suku_cadang_id' => $suku3Id,
            'transaksi_masuk' => $tMasuk2Id,
            'jumlah_awal' => 200,
            'jumlah_sisa' => 200,
            'tanggal_masuk' => Carbon::now()->subDays(2),
            'is_habis' => false,
        ]);

        // 7. Seed Transaksi Keluar
        $pt1Id = DB::table('perusahaan_tujuan')->where('nama', 'PT. Harapan Bangsa')->value('id');
        $kendaraan1Id = DB::table('kendaraan')->where('kendaraan_plat', 'B 2233 KAA')->value('kendaraan_id');

        $tKeluarId = DB::table('transaksi_keluar')->insertGetId([
            'suku_cadang_id' => $suku1Id,
            'users' => $adminUserId,
            'kendaraan_id' => $kendaraan1Id,
            'no_surat_jalan' => 'SJ-OUT-001',
            'tujuan_pt_id' => $pt1Id,
            'jumlah_diminta' => 30,
            'jumlah_terpenuhi' => 30,
            'status' => 'terpenuhi',
            'keterangan' => 'Kebutuhan maintenance PT Harapan Bangsa',
            'created_at' => Carbon::now()->subDays(1),
            'updated_at' => Carbon::now()->subDays(1),
        ]);

        // 8. Seed Detail Keluar FIFO
        DB::table('detail_keluar_fifo')->insert([
            'transaksi_keluar_id' => $tKeluarId,
            'batch_masuk_id' => $batch1Id,
            'fifo_jumlah_diambil' => 30,
        ]);

        // 9. Seed 10 Suppliers Tambahan (dengan nama perusahaan riil)
        $supplierNames = [
            'PT. Astra Otoparts Tbk',
            'PT. Denso Indonesia',
            'PT. Gajah Tunggal Tbk',
            'PT. Kayaba Indonesia',
            'PT. Nippondenso Indonesia',
            'PT. Selamat Sempurna Tbk',
            'PT. Pertamina Lubricants',
            'PT. GS Battery',
            'PT. IRC Gajah Tunggal Manufacturing',
            'PT. Robert Bosch Indonesia',
        ];

        $supplierIds = [];
        foreach ($supplierNames as $i => $name) {
            $supplierIds[] = DB::table('supplier')->insertGetId([
                'supplier_nama' => $name,
                'supplier_kontak' => "0812345678" . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'supplier_alamat' => "Kawasan Industri MM2100 Blok A" . ($i + 1) . ", Cikarang, Bekasi",
                'supplier_created_at' => Carbon::now(),
                'supplier_updated_at' => Carbon::now(),
            ]);
        }

        // 10. Seed 10 Drivers Tambahan
        foreach ($supplierIds as $idx => $supId) {
            DB::table('drivers')->insert([
                'supplier_id' => $supId,
                'nama_driver' => "Driver Mandiri " . ($idx + 1),
                'plat_kendaraan' => "B " . rand(1000, 9999) . " " . chr(rand(65, 90)) . chr(rand(65, 90)),
                'no_surat_jalan' => "SJ-SUP-" . (100 + $idx),
                'foto_sj' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // 11. Seed 10 Kendaraan Tambahan (Internal)
        $kendaraanJenis = ['box', 'engkel'];
        for ($i = 1; $i <= 10; $i++) {
            DB::table('kendaraan')->insert([
                'kendaraan_plat' => "B " . rand(1000, 9999) . " KB" . $i,
                'kendaraan_jenis' => $kendaraanJenis[array_rand($kendaraanJenis)],
                'kendaraan_nama_driver' => "Driver Internal " . $i,
                'kendaraan_created_at' => Carbon::now(),
                'kendaraan_updated_at' => Carbon::now(),
            ]);
        }

        // 12. Seed 50 Suku Cadang Tambahan
        $kategori = ['Filter', 'Brake System', 'Ignition', 'Suspensi', 'Pelumas', 'Electrical', 'Mesin'];
        
        $sparepartNames = [
            'Filter Oli', 'Filter Udara', 'Filter AC', 'Filter Bensin',
            'Kampas Rem Depan', 'Kampas Rem Belakang', 'Piringan Rem', 'Minyak Rem',
            'Busi Platinum', 'Busi Iridium', 'Kabel Busi', 'Koil Pengapian',
            'Shockbreaker Depan', 'Shockbreaker Belakang', 'Per Keong', 'Tierod End',
            'Oli Mesin 10W-40', 'Oli Mesin 5W-30', 'Oli Transmisi', 'Minyak Power Steering',
            'Aki Kering 45Ah', 'Aki Basah 60Ah', 'Bohlam Utama H4', 'Sekering Set',
            'V-Belt Alternator', 'Timing Belt', 'Piston Set', 'Ring Piston',
            'Packing Kopel', 'Radiator Assembly', 'Selang Radiator', 'Termostat',
            'Water Pump', 'Alternator Assembly', 'Motor Starter', 'Sensor Oksigen',
            'Kampas Kopling', 'Matahari Kopling', 'Master Kopling Atas', 'Master Kopling Bawah',
            'Laher Roda Depan', 'Laher Roda Belakang', 'Karet Wiper Set', 'Air Wiper Fluid',
            'Gasket Silinder Kop', 'Sil klep', 'Nosel Injektor', 'Klem Knalpot',
            'Dongkrak Botol', 'Kabel Jumper Aki'
        ];

        for ($i = 0; $i < 50; $i++) {
            $name = $sparepartNames[$i];
            $supplierId = $supplierIds[array_rand($supplierIds)];
            
            $kat = $kategori[array_rand($kategori)];
            if (strpos($name, 'Filter') !== false) $kat = 'Filter';
            elseif (strpos($name, 'Rem') !== false) $kat = 'Brake System';
            elseif (strpos($name, 'Busi') !== false || strpos($name, 'Pengapian') !== false) $kat = 'Ignition';
            elseif (strpos($name, 'Oli') !== false || strpos($name, 'Minyak') !== false) $kat = 'Pelumas';
            elseif (strpos($name, 'Aki') !== false || strpos($name, 'Bohlam') !== false || strpos($name, 'Sekering') !== false) $kat = 'Electrical';
            
            $sat = 'Pcs';
            if (strpos($name, 'Rem') !== false || strpos($name, 'Karet Wiper') !== false) $sat = 'Set';
            elseif (strpos($name, 'Oli') !== false || strpos($name, 'Fluid') !== false) $sat = 'Liter';

            $codePrefix = strtoupper(substr(str_replace(' ', '', $kat), 0, 3));
            $code = $codePrefix . "-" . str_pad($i + 1, 3, '0', STR_PAD_LEFT);

            DB::table('suku_cadang')->insert([
                'suku_cadang_supplier_id' => $supplierId,
                'suku_cadang_kode' => $code,
                'suku_cadang_nama' => $name,
                'suku_cadang_kategori' => $kat,
                'suku_cadang_satuan' => $sat,
                'suku_cadang_stok_total' => rand(5, 150),
                'suku_cadang_reorder_point' => rand(15, 30),
                'suku_cadang_stok_minimum' => rand(5, 10),
                'suku_cadang_created_at' => Carbon::now()->subDays(rand(10, 50)),
                'suku_cadang_updated_at' => Carbon::now(),
            ]);
        }

        // 13. Buat Notifikasi ROP untuk suku cadang yang stoknya sudah di bawah ROP
        $belowRop = DB::table('suku_cadang')
            ->whereNull('suku_cadang_deleted_at')
            ->whereRaw('suku_cadang_stok_total <= suku_cadang_reorder_point')
            ->get();

        foreach ($belowRop as $item) {
            DB::table('notifikasi_rop')->insert([
                'suku_cadang_id'      => $item->suku_cadang_id,
                'rop_stok_saat_notif' => $item->suku_cadang_stok_total,
                'rop_rop_saat_notif'  => $item->suku_cadang_reorder_point,
                'rop_sudah_ditangani' => false,
                'rop_created_at'      => Carbon::now(),
                'rop_updated_at'      => Carbon::now(),
            ]);
        }
    }
}
