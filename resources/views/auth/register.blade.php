<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | SaaS POS</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* === BACKGROUND === */
    body {
      background: linear-gradient(-45deg, #6a11cb, #2575fc, #ff6a00, #ee0979);
      background-size: 400% 400%;
      animation: gradientMove 15s ease infinite;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      overflow: hidden;
      font-family: "Poppins", sans-serif;
    }

    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    #particles-js {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    .register-card {
      backdrop-filter: blur(20px);
      background: rgba(255, 255, 255, 0.93);
      border-radius: 18px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      z-index: 1;
      transition: all 0.4s ease;
      padding: 2.5rem 3rem;
      width: 100%;
      max-width: 900px; 
      min-height: 600px; 
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 2rem;
    }

    .register-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25);
    }

    .card-left {
      flex: 1;
      text-align: center;
      color: #333;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .card-left img {
      width: 100%;
      max-width: 240px;
      margin-bottom: 1rem;
    }

    .card-right {
      flex: 1.2;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .input-group-text {
      background-color: #fff;
      border-right: 0;
    }

    input.form-control {
      border-left: 0;
      transition: all 0.3s ease;
    }

    input.form-control:focus {
      box-shadow: 0 0 10px rgba(40, 167, 69, 0.4);
      border-color: #28a745;
    }

    .btn-success, .btn-outline-success {
      transition: all 0.3s ease;
      font-weight: 600;
    }

    .btn-success:hover, .btn-outline-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(40, 167, 69, 0.25);
    }

    .btn-outline-success:hover {
      background-color: #28a745;
      color: #fff;
    }

    h3 {
      letter-spacing: 0.5px;
      font-weight: 700;
    }

    @media (max-width: 992px) {
      .register-card {
        flex-direction: column;
        max-width: 650px;
        padding: 2rem;
        min-height: auto; 
      }

      .card-left img {
        max-width: 200px;
      }
    }

    @media (max-width: 576px) {
      .register-card {
        max-width: 95%;
        padding: 1.5rem 1.2rem;
        border-radius: 15px;
        flex-direction: column;
        min-height: auto;
      }

      h3 {
        font-size: 1.4rem;
      }

      .btn {
        font-size: 0.9rem;
      }

      .card-left {
        display: none;
      }
    }
  </style>
</head>
<body>

<div id="particles-js"></div>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="register-card" id="registerCard">

    <div class="card-left">
      <img src="https://cdn-icons-png.flaticon.com/512/2920/2920322.png" alt="POS Illustration">
      <h4 class="fw-semibold text-success mt-2">SaaS POS</h4>
      <p class="text-muted small">Solusi kasir modern untuk semua toko.</p>
    </div>

    <div class="card-right">
      <div class="text-center mb-4">
        <h3 class="text-success mb-1">Daftar Akun Baru</h3>
        <p class="text-muted small mb-0">Kelola toko Anda dengan mudah</p>
      </div>

      @if (session('success'))
        <div class="alert alert-success text-center py-2">{{ session('success') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger text-center py-2">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="form-group mb-3">
          <label class="form-label fw-semibold">Nama Lengkap</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-fill text-success"></i></span>
            <input type="text" name="name" class="form-control" placeholder="Nama lengkap" value="{{ old('name') }}" required>
          </div>
        </div>

        <div class="form-group mb-3">
          <label class="form-label fw-semibold">Email</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill text-success"></i></span>
            <input type="email" name="email" class="form-control" placeholder="Alamat email" value="{{ old('email') }}" required>
          </div>
        </div>

        <div class="form-group mb-3">
          <label class="form-label fw-semibold">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill text-success"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
        </div>

        <div class="form-group mb-3">
          <label class="form-label fw-semibold">Konfirmasi Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill text-success"></i></span>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
          </div>
        </div>

        <div class="form-group mb-3">
          <label class="form-label fw-semibold">Nama Toko</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-shop text-success"></i></span>
            <input type="text" name="store_name" class="form-control" placeholder="Nama toko Anda" value="{{ old('store_name') }}" required>
          </div>
        </div>

        <div class="form-group mb-3">
          <label class="form-label fw-semibold">Alamat (opsional)</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt-fill text-success"></i></span>
            <input type="text" name="address" class="form-control" placeholder="Alamat toko" value="{{ old('address') }}">
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="form-label fw-semibold">No. Telepon (opsional)</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone-fill text-success"></i></span>
            <input type="text" name="phone" class="form-control" placeholder="Nomor telepon" value="{{ old('phone') }}">
          </div>
        </div>

        <button type="submit" class="btn btn-success w-100 rounded-pill py-2 mb-3">
          <i class="bi bi-person-plus-fill me-1"></i> Daftar Sekarang
        </button>

        <a href="{{ route('login') }}" class="btn btn-outline-success w-100 rounded-pill">
          <i class="bi bi-box-arrow-in-right me-1"></i> Sudah punya akun? Login
        </a>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js/particles.min.js"></script>

<script>
  gsap.from("#registerCard", { duration: 1, y: 60, opacity: 0, ease: "power3.out" });

  gsap.utils.toArray("input").forEach(el => {
    el.addEventListener("focus", () => gsap.to(el, { duration: 0.3, scale: 1.02 }));
    el.addEventListener("blur", () => gsap.to(el, { duration: 0.3, scale: 1 }));
  });

  particlesJS("particles-js", {
    "particles": {
      "number": { "value": 70 },
      "color": { "value": "#ffffff" },
      "shape": { "type": "circle" },
      "opacity": { "value": 0.3 },
      "size": { "value": 3 },
      "line_linked": { "enable": true, "distance": 120, "color": "#ffffff", "opacity": 0.2, "width": 1 },
      "move": { "enable": true, "speed": 2 }
    },
    "interactivity": { "events": { "onhover": { "enable": true, "mode": "repulse" } } }
  });
</script>

</body>
</html>
