# 📦 Inventory App (Aplikasi Manajemen Inventaris)

Aplikasi Web Manajemen Inventaris yang dibangun menggunakan **Laravel 10**, dikemas dalam kontainer **Docker**, menggunakan **TiDB Cloud (MySQL Serverless)** sebagai database, serta di-deploy secara *cloud-native*.

Project ini dikembangkan sebagai bagian dari Tugas Akhir / Skripsi.

---

## 🚀 Fitur Utama

- 🔐 **Autentikasi & Otorisasi:** Sistem Login/Register dengan manajemen hak akses.
- 📦 **Manajemen Stok & Barang:** Transaksi barang masuk, barang keluar, dan pencatatan riwayat inventaris.
- 🔔 **Notifikasi ROP (Reorder Point):** Peringatan otomatis ketika stok barang mencapai batas minimum.
- 📊 **Laporan & Dashboard:** Ringkasan statistik data barang dan transaksi secara real-time.

---

## 🛠️ Stack Teknologi

- **Backend / Framework:** PHP 8.2 & Laravel 10
- **Web Server:** Nginx (via Docker Container)
- **Containerization:** Docker & Docker Compose
- **Database:** TiDB Cloud (Serverless Distributed SQL / MySQL Compatible)
- **Deployment Platform:** Koyeb / Render
- **CDN & Security:** Cloudflare (Proxy, SSL, WAF)

---

## 💻 Cara Menjalankan Project di Lokal (Local Development)

Project ini sudah sepenuhnya di-containerize dengan **Docker**, sehingga kamu tidak perlu menginstall PHP/Nginx/MySQL secara terpisah di mesin lokal.

### Prasyarat
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) sudah terinstall dan berjalan di komputer/laptop.
- [Git](https://git-scm.com/)

### Langkah Instalasi

1. **Clone Repository ini:**
   ```bash
   git clone [https://github.com/username-kamu/inventory-app.git](https://github.com/username-kamu/inventory-app.git)
   cd inventory-app
