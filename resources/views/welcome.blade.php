<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Inventaris Suku Cadang & Kendaraan Terintegrasi. Mengelola stok, transaksi masuk-keluar, dan notifikasi Reorder Point (ROP) secara real-time.">
    <meta name="author" content="Inventory App Team">

    <title>Selamat Datang - Sistem Inventaris Suku Cadang</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap CSS (Optional but useful for grid layout and modal/js) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS Kustom dengan Estetika Premium -->
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #e0e7ff;
            --secondary: #0ea5e9;
            --dark-900: #0f172a;
            --dark-800: #1e293b;
            --dark-700: #334155;
            --dark-600: #475569;
            --light-50: #f8fafc;
            --light-100: #f1f5f9;
            --accent: #f43f5e;
            --success: #10b981;
            --warning: #f59e0b;
            --card-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-50);
            color: var(--dark-700);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--dark-900);
        }

        /* Navigasi */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 1rem 2rem;
            transition: var(--transition);
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark-900);
            text-decoration: none;
        }

        .navbar-brand-custom i {
            color: var(--primary);
            font-size: 1.8rem;
        }

        .nav-link-custom {
            color: var(--dark-600) !important;
            font-weight: 500;
            transition: var(--transition);
            margin: 0 0.5rem;
            position: relative;
        }

        .nav-link-custom:hover {
            color: var(--primary) !important;
        }

        .nav-link-custom::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: var(--primary);
            transition: var(--transition);
        }

        .nav-link-custom:hover::after {
            width: 100%;
        }

        .btn-nav-login {
            background-color: var(--primary);
            color: white !important;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
            transition: var(--transition);
        }

        .btn-nav-login:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            padding: 9rem 0 6rem 0;
            background: radial-gradient(circle at top right, rgba(224, 231, 255, 0.6) 0%, rgba(255, 255, 255, 0) 60%),
                        radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.08) 0%, rgba(255, 255, 255, 0) 50%);
        }

        .hero-badge {
            background-color: var(--primary-light);
            color: var(--primary);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }

        .hero-title {
            font-size: 3.5rem;
            line-height: 1.15;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.125rem;
            color: var(--dark-600);
            line-height: 1.7;
            margin-bottom: 2.5rem;
            max-width: 580px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            color: white !important;
            font-weight: 600;
            font-size: 1.05rem;
            padding: 0.9rem 2.2rem;
            border-radius: 12px;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            text-decoration: none !important;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -5px rgba(79, 70, 229, 0.5);
        }

        .btn-hero-secondary {
            background-color: white;
            color: var(--dark-900) !important;
            border: 1px solid rgba(226, 232, 240, 0.8);
            font-weight: 600;
            font-size: 1.05rem;
            padding: 0.9rem 2.2rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            text-decoration: none !important;
        }

        .btn-hero-secondary:hover {
            background-color: var(--light-100);
            transform: translateY(-3px);
            border-color: rgba(203, 213, 225, 1);
        }

        /* Floating Cards Visual in Hero Right */
        .hero-visual {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-card-img {
            background: linear-gradient(135deg, var(--primary) 0%, #312e81 100%);
            border-radius: 24px;
            padding: 2.5rem;
            color: white;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 2;
            overflow: hidden;
        }

        .main-card-img::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -20%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.4) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }

        .floating-badge-1 {
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            top: 15%;
            left: -5%;
            z-index: 3;
            animation: float 4s ease-in-out infinite;
        }

        .floating-badge-2 {
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            bottom: 10%;
            right: -2%;
            z-index: 3;
            animation: float 4s ease-in-out infinite 2s;
        }

        /* Stats Grid below Hero */
        .stats-wrapper {
            margin-top: -3rem;
            position: relative;
            z-index: 10;
        }

        .stats-card-container {
            background-color: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--dark-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Features Section */
        .section-padding {
            padding: 7rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-title p {
            color: var(--dark-600);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            background-color: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.6);
            height: 100%;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(79, 70, 229, 0.3);
            box-shadow: 0 20px 45px -10px rgba(79, 70, 229, 0.1);
        }

        .feature-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transform: scaleX(0);
            transform-origin: left;
            transition: var(--transition);
        }

        .feature-card:hover::after {
            transform: scaleX(1);
        }

        .feature-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background-color: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.8rem;
            transition: var(--transition);
        }

        .feature-card:hover .feature-icon-wrapper {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .feature-card p {
            font-size: 0.95rem;
            color: var(--dark-600);
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* Role Workflow Section */
        .workflow-section {
            background-color: var(--light-100);
            position: relative;
        }

        .workflow-tabs {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 3.5rem;
            flex-wrap: wrap;
        }

        .workflow-tab-btn {
            background-color: white;
            border: 1px solid rgba(226, 232, 240, 1);
            border-radius: 50px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: var(--dark-700);
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .workflow-tab-btn.active, .workflow-tab-btn:hover {
            background-color: var(--primary);
            color: white !important;
            border-color: var(--primary);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.3);
        }

        .workflow-content {
            display: none;
            background-color: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.5);
            animation: fadeIn 0.5s ease;
        }

        .workflow-content.active {
            display: block;
        }

        .workflow-role-badge {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .workflow-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .workflow-list li {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1.2rem;
            font-size: 1.05rem;
            color: var(--dark-700);
        }

        .workflow-list li i {
            position: absolute;
            left: 0;
            top: 4px;
            color: var(--success);
            font-size: 1.1rem;
        }

        /* CTA Bottom Section */
        .cta-bottom-section {
            background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cta-bottom-section::before {
            content: '';
            position: absolute;
            top: -40%;
            left: -10%;
            width: 50%;
            height: 80%;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.25) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }

        .cta-bottom-section::after {
            content: '';
            position: absolute;
            bottom: -40%;
            right: -10%;
            width: 50%;
            height: 80%;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }

        .cta-title {
            color: white;
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
        }

        .cta-desc {
            color: rgba(241, 245, 249, 0.85);
            font-size: 1.15rem;
            line-height: 1.7;
            max-width: 640px;
            margin: 0 auto 2.5rem auto;
        }

        /* Footer */
        .footer {
            background-color: var(--dark-900);
            color: rgba(148, 163, 184, 0.8);
            border-top: 1px solid rgba(51, 65, 85, 0.5);
            padding: 3rem 0;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
        }

        .footer-brand i {
            color: var(--secondary);
        }

        .footer-links {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: rgba(148, 163, 184, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
        }

        /* Keyframes Animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .hero-title {
                font-size: 2.8rem;
            }
            .hero-visual {
                margin-top: 4rem;
            }
            .stats-wrapper {
                margin-top: 2rem;
            }
            .workflow-content {
                padding: 2rem;
            }
        }

        @media (max-width: 575.98px) {
            .navbar-custom {
                padding: 1rem;
            }
            .hero-title {
                font-size: 2.2rem;
            }
            .hero-buttons {
                flex-direction: column;
            }
            .btn-hero-primary, .btn-hero-secondary {
                width: 100%;
                justify-content: center;
            }
            .workflow-content {
                padding: 1.5rem;
            }
            .cta-title {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body id="landing-page">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom" id="landing-navbar">
        <div class="container">
            <a class="navbar-brand-custom" href="{{ url('/') }}" id="nav-logo">
                <i class="fas fa-boxes"></i> SpareTrack
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#fitur" id="nav-link-fitur">Fitur Utama</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#alur-kerja" id="nav-link-alur">Alur Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom mr-lg-3" href="#kontak" id="nav-link-kontak">Tentang</a>
                    </li>
                    <li class="nav-item mt-3 mt-lg-0">
                        <a class="btn btn-nav-login" href="{{ route('login') }}" id="btn-login-nav">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk ke Sistem
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-badge" id="hero-badge">
                        <i class="fas fa-sparkles"></i> Sistem Manajemen Inventaris Pintar
                    </div>
                    <h1 class="hero-title" id="hero-heading">
                        Kelola <span>Suku Cadang</span> & Kendaraan Secara Akurat
                    </h1>
                    <p class="hero-desc" id="hero-description">
                        Pantau sirkulasi barang, catat transaksi gudang secara real-time, dan cegah kekosongan stok dengan sistem peringatan otomatis Reorder Point (ROP).
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('login') }}" class="btn-hero-primary" id="btn-login-hero">
                            Mulai Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="#fitur" class="btn-hero-secondary" id="btn-features-scroll">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 hero-visual d-none d-lg-flex" id="hero-illustration">
                    <div class="main-card-img">
                        <h4 class="mb-3 text-white font-heading">Stok Ringkasan</h4>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="opacity-75">Ban Radial Tubeless</span>
                            <span class="badge badge-pill badge-success">Aman (15 unit)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="opacity-75">Aki Mobil GS Astra</span>
                            <span class="badge badge-pill badge-warning">Rendah (4 unit)</span>
                        </div>
                        <hr style="border-color: rgba(255,255,255,0.15)">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-bell text-warning mr-2" style="font-size: 1.25rem;"></i>
                            <span class="small opacity-75">Peringatan: 2 Suku cadang butuh pemesanan ulang.</span>
                        </div>
                    </div>
                    <div class="floating-badge-1" id="float-badge-1">
                        <div class="feature-icon-wrapper m-0" style="width: 40px; height: 40px; font-size: 1rem; border-radius: 8px;">
                            <i class="fas fa-check-double text-success"></i>
                        </div>
                        <div>
                            <div class="font-weight-bold text-dark-900" style="font-size: 0.9rem;">Log Aktivitas</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Dipantau Keamanannya</div>
                        </div>
                    </div>
                    <div class="floating-badge-2" id="float-badge-2">
                        <div class="feature-icon-wrapper m-0" style="width: 40px; height: 40px; font-size: 1rem; border-radius: 8px; background-color: rgba(244, 63, 94, 0.15); color: var(--accent);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="font-weight-bold text-dark-900" style="font-size: 0.9rem;">Auto ROP Alert</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Mencegah Kehabisan Stok</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Section -->
    <section class="stats-wrapper" id="stats">
        <div class="container">
            <div class="stats-card-container">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0 stat-item border-right" id="stat-opt-1">
                        <div class="stat-number">Real-Time</div>
                        <div class="stat-label">Pencatatan Transaksi</div>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0 stat-item border-right" id="stat-opt-2">
                        <div class="stat-number">Automated</div>
                        <div class="stat-label">Notifikasi Reorder Point</div>
                    </div>
                    <div class="col-md-4 stat-item" id="stat-opt-3">
                        <div class="stat-number">Secure</div>
                        <div class="stat-label">Otorisasi Berbasis Peran</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section class="section-padding" id="fitur">
        <div class="container">
            <div class="section-title">
                <h2>Fitur Utama & Keunggulan</h2>
                <p>Didukung oleh fungsionalitas cerdas yang membantu mempercepat alur kerja pergudangan dan pemesanan logistik suku cadang.</p>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="feature-card" id="feature-card-1">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <h3>Manajemen Katalog Terstruktur</h3>
                        <p>Kelola data suku cadang lengkap dengan kompatibilitas kendaraan terkait. Meminimalkan kesalahan alokasi suku cadang saat instalasi.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card" id="feature-card-2">
                        <div class="feature-icon-wrapper" style="background-color: rgba(14, 165, 233, 0.1); color: var(--secondary)">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h3>Alur Transaksi Dinamis</h3>
                        <p>Catat transaksi barang masuk dari supplier dan barang keluar ke perusahaan tujuan secara terdokumentasi dan akurat dalam hitungan detik.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card" id="feature-card-3">
                        <div class="feature-icon-wrapper" style="background-color: rgba(244, 63, 94, 0.1); color: var(--accent)">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3>Notifikasi ROP Pintar</h3>
                        <p>Sistem membandingkan sisa stok dengan batas Reorder Point otomatis. Notifikasi reorder akan terpicu secara dinamis bila stok berada di bawah batas aman.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section class="section-padding workflow-section" id="alur-kerja">
        <div class="container">
            <div class="section-title">
                <h2>Otorisasi Berbasis Peran (Role RBAC)</h2>
                <p>Setiap aktor memiliki ruang lingkup pekerjaan spesifik demi menjaga integritas data dan keamanan inventaris.</p>
            </div>

            <!-- Tab Buttons -->
            <div class="workflow-tabs" id="workflow-tabs-container">
                <button class="workflow-tab-btn active" onclick="switchTab('spv')" id="btn-tab-spv">
                    <i class="fas fa-user-shield mr-2"></i> Supervisor (SPV)
                </button>
                <button class="workflow-tab-btn" onclick="switchTab('staf')" id="btn-tab-staf">
                    <i class="fas fa-user-cog mr-2"></i> Staf Inventory
                </button>
                <button class="workflow-tab-btn" onclick="switchTab('admin')" id="btn-tab-admin">
                    <i class="fas fa-warehouse mr-2"></i> Admin Gudang
                </button>
            </div>

            <!-- Tab Content 1: SPV -->
            <div class="workflow-content active" id="content-spv">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <span class="workflow-role-badge">Tanggung Jawab SPV</span>
                        <h3>Pengawasan & Manajemen Strategis</h3>
                        <p class="text-muted mb-4">Supervisor memegang otorisasi tertinggi untuk memantau performa pergudangan, menganalisis laporan berkala, dan mengontrol hak akses pengguna.</p>
                        <ul class="workflow-list">
                            <li><i class="fas fa-check-circle"></i> Memantau grafik dasbor inventaris & sisa stok.</li>
                            <li><i class="fas fa-check-circle"></i> Mengelola data karyawan & pengguna sistem.</li>
                            <li><i class="fas fa-check-circle"></i> Mencetak dan mengekspor laporan inventaris berkala.</li>
                            <li><i class="fas fa-check-circle"></i> Memantau log aktivitas sistem untuk audit keamanan.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="p-4" style="background-color: var(--light-50); border-radius: 20px; border: 1px dashed rgba(226,232,240,1);">
                            <i class="fas fa-chart-line fa-5x text-indigo-500 mb-3 opacity-50" style="color: var(--primary)"></i>
                            <h5 class="font-weight-bold">Visualisasi Dasbor Analitik</h5>
                            <p class="small text-muted mb-0">Supervisor dibekali statistik grafik visual untuk meninjau sirkulasi suku cadang secara bulanan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content 2: Staf -->
            <div class="workflow-content" id="content-staf">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <span class="workflow-role-badge" style="background-color: rgba(14, 165, 233, 0.1); color: var(--secondary);">Tanggung Jawab Staf Inventory</span>
                        <h3>Manajemen Aset & Logistik</h3>
                        <p class="text-muted mb-4">Staf Inventory berfokus pada pemeliharaan data master, katalog suku cadang, kendaraan, driver, serta bertindak atas notifikasi peringatan ROP.</p>
                        <ul class="workflow-list">
                            <li><i class="fas fa-check-circle"></i> Mendaftarkan data Supplier dan Driver pengirim.</li>
                            <li><i class="fas fa-check-circle"></i> Menginput katalog suku cadang & unit kendaraan baru.</li>
                            <li><i class="fas fa-check-circle"></i> Memeriksa notifikasi sisa stok di bawah Reorder Point.</li>
                            <li><i class="fas fa-check-circle"></i> Menandai notifikasi ROP yang telah selesai ditindaklanjuti.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="p-4" style="background-color: var(--light-50); border-radius: 20px; border: 1px dashed rgba(226,232,240,1);">
                            <i class="fas fa-boxes-packing fa-5x mb-3 opacity-50" style="color: var(--secondary)"></i>
                            <h5 class="font-weight-bold">Katalog Suku Cadang Presisi</h5>
                            <p class="small text-muted mb-0">Memetakan kecocokan suku cadang dengan kode mesin kendaraan untuk menghindari miskalkulasi inventori.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content 3: Admin -->
            <div class="workflow-content" id="content-admin">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <span class="workflow-role-badge" style="background-color: rgba(244, 63, 94, 0.1); color: var(--accent);">Tanggung Jawab Admin Gudang</span>
                        <h3>Operasional Keluar Masuk Barang</h3>
                        <p class="text-muted mb-4">Admin Gudang bertugas sebagai eksekutor transaksi fisik di lapangan, mencatat setiap mutasi barang masuk maupun barang keluar.</p>
                        <ul class="workflow-list">
                            <li><i class="fas fa-check-circle"></i> Mencatat Transaksi Masuk berdasarkan faktur dari Supplier.</li>
                            <li><i class="fas fa-check-circle"></i> Memvalidasi Driver & armada yang mengirimkan suku cadang.</li>
                            <li><i class="fas fa-check-circle"></i> Mencatat Transaksi Keluar ke bengkel atau perusahaan tujuan.</li>
                            <li><i class="fas fa-check-circle"></i> Mencetak Surat Jalan pengeluaran barang resmi.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="p-4" style="background-color: var(--light-50); border-radius: 20px; border: 1px dashed rgba(226,232,240,1);">
                            <i class="fas fa-file-invoice-dollar fa-5x mb-3 opacity-50" style="color: var(--accent)"></i>
                            <h5 class="font-weight-bold">Validasi Transaksi Cepat</h5>
                            <p class="small text-muted mb-0">Setiap mutasi barang terikat dengan dokumen pengiriman sah untuk mencegah kehilangan stok fisik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action (CTA) Bottom -->
    <section class="section-padding cta-bottom-section" id="kontak">
        <div class="container text-center">
            <h2 class="cta-title">Siap Mengoptimalkan Inventaris Anda?</h2>
            <p class="cta-desc">Masuk ke platform menggunakan kredensial akun Anda untuk mulai memantau dan mengelola seluruh siklus logistik suku cadang hari ini.</p>
            <a href="{{ route('login') }}" class="btn-hero-primary" style="background: white; color: var(--primary) !important; box-shadow: 0 10px 25px rgba(255,255,255,0.15);" id="btn-login-cta">
                <i class="fas fa-sign-in-alt mr-2"></i> Masuk Ke Dasbor
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="footer-brand">
                        <i class="fas fa-boxes"></i> SpareTrack
                    </div>
                    <p class="mb-0 text-muted small">&copy; {{ date('Y') }} SpareTrack. Hak Cipta Dilindungi.</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <ul class="footer-links justify-content-md-end">
                        <li><a href="#fitur">Fitur</a></li>
                        <li><a href="#alur-kerja">Alur Kerja</a></li>
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap & jQuery JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

    <!-- Interactive JS for Tab Switching -->
    <script>
        function switchTab(role) {
            // Remove active classes from buttons
            document.querySelectorAll('.workflow-tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            // Hide all tab contents
            document.querySelectorAll('.workflow-content').forEach(content => {
                content.classList.remove('active');
            });

            // Add active class to clicked tab button
            const activeBtn = document.getElementById('btn-tab-' + role);
            if (activeBtn) activeBtn.classList.add('active');

            // Show target tab content
            const targetContent = document.getElementById('content-' + role);
            if (targetContent) targetContent.classList.add('active');
        }

        // Change navbar shadow on scroll
        $(window).scroll(function() {
            if ($(window).scrollTop() > 50) {
                $('#landing-navbar').css('box-shadow', '0 4px 20px -5px rgba(0, 0, 0, 0.08)');
            } else {
                $('#landing-navbar').css('box-shadow', 'none');
            }
        });
    </script>
</body>

</html>