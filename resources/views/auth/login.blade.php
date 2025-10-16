<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | SaaS POS</title>

  <!-- Bootstrap & Icons -->
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

    /* === CARD === */
    .login-card {
      backdrop-filter: blur(18px);
      background: rgba(255, 255, 255, 0.92);
      border-radius: 18px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      z-index: 1;
      transition: all 0.4s ease;
      padding: 2.5rem 3rem;
      width: 100%;
      max-width: 850px;
      min-height: 500px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 2rem;
    }

    .login-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25);
    }

    /* === LEFT PANEL === */
    .card-left {
      flex: 1;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .card-left img {
      width: 100%;
      max-width: 230px;
      margin-bottom: 1rem;
    }

    /* === RIGHT FORM === */
    .card-right {
      flex: 1.2;
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
      box-shadow: 0 0 10px rgba(37,117,252,0.4);
      border-color: #2575fc;
    }

    /* === BUTTONS === */
    .btn-primary {
      transition: all 0.3s ease;
      font-weight: 600;
      background: linear-gradient(90deg, #2575fc, #6a11cb);
      border: none;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 14px rgba(37,117,252,0.4);
    }

    .btn-outline-primary {
      border-color: #2575fc;
      color: #2575fc;
      font-weight: 600;
    }

    .btn-outline-primary:hover {
      background: #2575fc;
      color: #fff;
    }

    h3 {
      letter-spacing: 0.5px;
      font-weight: 700;
    }

    /* === RESPONSIVE === */
    @media (max-width: 992px) {
      .login-card {
        flex-direction: column;
        max-width: 600px;
        min-height: auto;
        padding: 2rem;
      }
      .card-left img {
        max-width: 180px;
      }
    }

    @media (max-width: 576px) {
      .login-card {
        max-width: 95%;
        padding: 1.5rem 1.2rem;
        border-radius: 15px;
      }
      .card-left {
        display: none;
      }
      h3 {
        font-size: 1.4rem;
      }
      .btn {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

<div id="particles-js"></div>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="login-card" id="loginCard">

    <!-- LEFT PANEL -->
    <div class="card-left">
      <img src="https://cdn-icons-png.flaticon.com/512/9068/9068643.png" alt="POS Illustration">
      <h4 class="fw-semibold text-primary">Selamat Datang</h4>
      <p class="text-muted small">Masuk untuk mengelola toko Anda dengan SaaS POS.</p>
    </div>

    <!-- RIGHT FORM -->
    <div class="card-right">
      <div class="text-center mb-4">
        <h3 class="text-primary mb-1">Login Akun</h3>
        <p class="text-muted small mb-0">Masukkan email dan password Anda</p>
      </div>

      {{-- ✅ Pesan sukses --}}
      @if (session('success'))
        <div class="alert alert-success text-center py-2">
          {{ session('success') }}
        </div>
      @endif

      {{-- ❌ Pesan error --}}
      @if ($errors->any())
        <div class="alert alert-danger text-center py-2">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="form-group mb-3">
          <label class="form-label fw-semibold">Email</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill text-primary"></i></span>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email" required autofocus>
          </div>
        </div>

        <div class="form-group mb-3">
          <label class="form-label fw-semibold">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill text-primary"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
          </div>
        </div>

        <div class="form-check mb-3">
          <input type="checkbox" name="remember" class="form-check-input" id="remember">
          <label class="form-check-label small" for="remember">Ingat saya</label>
        </div>

        <a href="{{ route('google.login') }}" class="btn btn-outline-danger w-100 rounded-pill py-2 mb-3">
          <i class="bi bi-google me-1"></i> Login dengan Google
        </a>

        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 mb-3">
          <i class="bi bi-box-arrow-in-right me-1"></i> Login
        </button>

        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 rounded-pill">
          <i class="bi bi-person-plus-fill me-1"></i> Belum punya akun? Daftar
        </a>
      </form>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js/particles.min.js"></script>

<script>
  // Animasi GSAP
  gsap.from("#loginCard", { duration: 1, y: 60, opacity: 0, ease: "power3.out" });

  // Efek fokus input
  gsap.utils.toArray("input").forEach(el => {
    el.addEventListener("focus", () => gsap.to(el, { duration: 0.3, scale: 1.02, boxShadow: "0 0 10px rgba(37,117,252,0.6)" }));
    el.addEventListener("blur", () => gsap.to(el, { duration: 0.3, scale: 1, boxShadow: "none" }));
  });

  // Partikel background
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
    "interactivity": {
      "events": { "onhover": { "enable": true, "mode": "repulse" } }
    }
  });
</script>

</body>
</html>
