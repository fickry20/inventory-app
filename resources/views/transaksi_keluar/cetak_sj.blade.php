<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $transaksi->no_surat_jalan }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-info h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #000;
        }
        .company-info p {
            margin: 2px 0 0 0;
            color: #444;
            font-size: 11px;
        }
        .doc-title {
            text-align: right;
        }
        .doc-title h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #000;
            letter-spacing: 1px;
        }
        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 12px;
            font-weight: bold;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 0.8fr;
            gap: 20px;
            margin-bottom: 20px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 15px;
        }
        .details-block h4 {
            margin: 0 0 6px 0;
            font-size: 11px;
            text-transform: uppercase;
            color: #555;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        .details-block p {
            margin: 3px 0;
        }
        .table-container {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #000;
            padding: 6px 8px;
        }
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            text-align: center;
            margin-top: 40px;
        }
        .signature-block {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 110px;
        }
        .sig-title {
            font-size: 11px;
            font-weight: bold;
            color: #000;
        }
        .sig-line {
            border-bottom: 1px solid #000;
            margin: 0 15px;
        }
        .sig-name {
            font-weight: bold;
            margin-top: 4px;
            font-size: 11px;
        }
        .no-print-btn {
            background-color: #4e73df;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .no-print-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background-color: #f8f9fc;
            padding: 10px 20px;
            border-radius: 6px;
            border: 1px solid #eaecf4;
        }
        @media print {
            .no-print-container, .no-print-btn {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Screen Actions -->
        <div class="no-print-container">
            <span style="align-self: center; font-weight: bold; color: #4e73df;">Pratinjau Cetak Surat Jalan</span>
            <div>
                <button onclick="window.print()" class="no-print-btn" style="margin-right: 10px;">
                    <i class="fas fa-print mr-1"></i> Cetak Surat Jalan
                </button>
                <button onclick="window.close()" class="no-print-btn" style="background-color: #858796;">
                    Tutup Halaman
                </button>
            </div>
        </div>

        <!-- Surat Jalan Header (Kop Surat Jalan) -->
        <div class="header">
            <div class="company-info">
                <h2>INVENTORY LOGISTICS SYSTEM</h2>
                <p>Gudang Utama Persediaan & Distribusi Suku Cadang</p>
                <p>Telp: (021) 555-0199 | Email: logistics@inventoryapp.com</p>
            </div>
            <div class="doc-title">
                <h1>SURAT JALAN</h1>
                <p>No. SJ: {{ $transaksi->no_surat_jalan }}</p>
            </div>
        </div>

        <!-- Kop Surat Jalan Details -->
        <div class="details-grid">
            <!-- Kop 1: Perusahaan Pengirim -->
            <div class="details-block">
                <h4>Perusahaan Pengirim</h4>
                <p><strong>INVENTORY LOGISTICS SYSTEM</strong></p>
                <p>Jl. Pembangunan No. 88, Blok C</p>
                <p>Kawasan Industri Cikarang, Bekasi</p>
                <p>Telp: (021) 555-0199</p>
            </div>

            <!-- Kop 2: Penerima / Kepada Yth. -->
            <div class="details-block">
                <h4>Kepada Yth.</h4>
                @if($transaksi->perusahaanTujuan)
                    <p><strong>{{ $transaksi->perusahaanTujuan->nama }}</strong></p>
                    <p>{{ $transaksi->perusahaanTujuan->alamat ?? '-' }}</p>
                    @if($transaksi->perusahaanTujuan->kontak)
                        <p>Kontak: {{ $transaksi->perusahaanTujuan->kontak }}</p>
                    @endif
                @else
                    <p class="text-danger">Data Perusahaan Tujuan Terhapus</p>
                @endif
            </div>

            <!-- Kop 3: Informasi Driver & Armada -->
            <div class="details-block">
                <h4>Informasi Pengiriman</h4>
                <p><strong>Driver Pengirim:</strong><br>{{ $transaksi->kendaraan->kendaraan_nama_driver ?? '-' }}</p>
                <p><strong>Plat Kendaraan:</strong><br><span style="text-transform: uppercase;">{{ $transaksi->kendaraan->kendaraan_plat ?? '-' }}</span></p>
                <p><strong>Tanggal Keluar:</strong><br>{{ $transaksi->created_at ? $transaksi->created_at->format('d-m-Y H:i') : '-' }}</p>
            </div>
        </div>

        <!-- Table Goods -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%; text-align: center;">No</th>
                        <th style="width: 50%;">Nama Barang</th>
                        <th style="width: 15%; text-align: center;">Qty</th>
                        <th style="width: 27%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td>
                            <strong>{{ $transaksi->sukuCadang->suku_cadang_nama ?? 'Barang Dihapus' }}</strong>
                            <br>
                            <span style="font-family: monospace; font-size: 10px; color: #555;">Kode: {{ $transaksi->sukuCadang->suku_cadang_kode ?? '-' }}</span>
                        </td>
                        <td style="text-align: center; font-weight: bold;">
                            {{ $transaksi->jumlah_terpenuhi }} {{ $transaksi->sukuCadang->suku_cadang_satuan ?? 'Pcs' }}
                        </td>
                        <td>{{ $transaksi->keterangan ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signatures (Tanda Tangan) -->
        <div class="signatures">
            <!-- 1. Dikeluarkan Oleh -->
            <div class="signature-block">
                <span class="sig-title">Dikeluarkan Oleh,</span>
                <div style="margin-top: auto;">
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $transaksi->user->users_username ?? 'Staff Logistik' }}</div>
                </div>
            </div>

            <!-- 2. Yang Menyerahkan -->
            <div class="signature-block">
                <span class="sig-title">Yang Menyerahkan,</span>
                <div style="margin-top: auto;">
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ \App\Models\User::where('users_role', 'spv')->first()->users_username ?? 'Supervisor' }}</div>
                </div>
            </div>

            <!-- 3. Yang Menerima -->
            <div class="signature-block">
                <span class="sig-title">Yang Menerima,</span>
                <div style="margin-top: auto;">
                    <div class="sig-line"></div>
                    <div class="sig-name">Penerima PT Tujuan</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Automatically trigger print dialog on page load
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
