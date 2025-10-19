<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kasir POS - Solusi Kasir Modern</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #2563eb, #7c3aed);
      color: #f8fafc;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      margin: 0;
    }

    .hero {
      max-width: 500px;
      padding: 40px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
      animation: fadeIn 0.8s ease;
    }

    .hero h1 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .hero p {
      color: #e2e8f0;
      font-size: 1rem;
      margin-bottom: 30px;
    }

    .btn-login {
      background: #fff;
      color: #2563eb;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      padding: 12px 28px;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      background: #f1f5f9;
      color: #1e3a8a;
      transform: translateY(-2px);
      box-shadow: 0 6px 14px rgba(255, 255, 255, 0.25);
    }

    .footer-text {
      margin-top: 30px;
      font-size: 0.9rem;
      color: #cbd5e1;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      .hero { width: 90%; padding: 30px; }
      .hero h1 { font-size: 1.6rem; }
    }
  </style>
</head>
<body>
  <div class="hero">
    <img src="{{ asset('assets/img/logo-pos.png') }}" alt="Logo Kasir POS" width="80" class="mb-3">

    <h1>Selamat Datang di <span style="color:#fff;">Kasir POS</span></h1>
    <p>Sistem kasir modern untuk mengelola penjualan, stok, dan laporan toko Anda — cepat, aman, dan mudah digunakan.</p>

    <a href="{{ route('login') }}" class="btn btn-login btn-lg">
      <i class="icon-log-in me-2"></i> Login ke Kasir
    </a>

    <div class="footer-text">
      © {{ date('Y') }} Kasir POS — All rights reserved.
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
