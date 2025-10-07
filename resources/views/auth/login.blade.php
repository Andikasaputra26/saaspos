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
    body, html { height: 100%; margin: 0; overflow: hidden; }
    body {
      background: linear-gradient(-45deg, #6a11cb, #2575fc, #ff6a00, #ee0979);
      background-size: 400% 400%;
      animation: gradientMove 15s ease infinite;
      position: relative;
    }
    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    #particles-js { position: absolute; width: 100%; height: 100%; z-index: 0; }
    .login-card {
      backdrop-filter: blur(10px);
      background: rgba(255,255,255,0.9);
      border-radius: 15px;
      z-index: 1;
    }
    .btn-primary {
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 14px rgba(0,0,0,0.25);
    }
  </style>
</head>
<body>

<div id="particles-js"></div>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="col-md-5 col-lg-4">
    <div class="card shadow-lg border-0 p-4 login-card" id="loginCard">

      <div class="card-header bg-transparent text-center border-0">
        <h3 class="fw-bold text-primary mb-0">Login</h3>
        <p class="text-muted small">Masuk ke akun Anda</p>
      </div>

      <div class="card-body">

        {{-- ✅ Pesan sukses (misal setelah logout/register) --}}
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

          <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </button>
        </form>

        <div class="mt-4 text-center">
          <p class="mb-0 small">Belum punya akun?
            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none text-primary">Daftar</a>
          </p>
        </div>

      </div>
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
      "number": { "value": 80 },
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
