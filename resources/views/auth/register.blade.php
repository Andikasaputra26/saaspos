<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | SaaS POS</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
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
      backdrop-filter: blur(10px);
      background: rgba(255,255,255,0.9);
      border-radius: 15px;
      z-index: 1;
    }
    .btn-success {
      transition: all 0.3s ease;
    }
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 14px rgba(0,0,0,0.25);
    }
    .input-group-text {
      background-color: #fff;
    }
  </style>
</head>
<body>

<div id="particles-js"></div>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="col-md-5 col-lg-4">
    <div class="card shadow-lg border-0 p-4 register-card" id="registerCard">
      <div class="card-header bg-transparent text-center border-0">
        <h3 class="fw-bold text-success mb-0">Register</h3>
        <p class="text-muted small">Buat akun baru Anda</p>
      </div>

      <div class="card-body">

        {{-- ✅ Alert pesan sukses / error --}}
        @if (session('success'))
          <div class="alert alert-success text-center py-2">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
          <div class="alert alert-danger text-center py-2">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
          @csrf

          <!-- Nama -->
          <div class="form-group mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person-fill text-success"></i></span>
              <input type="text" name="name" class="form-control" placeholder="Nama lengkap" value="{{ old('name') }}" required>
            </div>
          </div>

          <!-- Email -->
          <div class="form-group mb-3">
            <label class="form-label fw-semibold">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope-fill text-success"></i></span>
              <input type="email" name="email" class="form-control" placeholder="Alamat email" value="{{ old('email') }}" required>
            </div>
          </div>

          <!-- Password -->
          <div class="form-group mb-3">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock-fill text-success"></i></span>
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
          </div>

          <!-- Konfirmasi Password -->
          <div class="form-group mb-3">
            <label class="form-label fw-semibold">Konfirmasi Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock-fill text-success"></i></span>
              <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
            </div>
          </div>

          <!-- Nama Toko -->
          <div class="form-group mb-3">
            <label class="form-label fw-semibold">Nama Toko</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-shop text-success"></i></span>
              <input type="text" name="store_name" class="form-control" placeholder="Nama toko Anda" value="{{ old('store_name') }}" required>
            </div>
          </div>

          <!-- Alamat (opsional) -->
          <div class="form-group mb-3">
            <label class="form-label fw-semibold">Alamat (opsional)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-geo-alt-fill text-success"></i></span>
              <input type="text" name="address" class="form-control" placeholder="Alamat toko" value="{{ old('address') }}">
            </div>
          </div>

          <!-- Telepon (opsional) -->
          <div class="form-group mb-3">
            <label class="form-label fw-semibold">No. Telepon (opsional)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-telephone-fill text-success"></i></span>
              <input type="text" name="phone" class="form-control" placeholder="Nomor telepon" value="{{ old('phone') }}">
            </div>
          </div>

          <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-semibold">
            <i class="bi bi-person-plus-fill me-1"></i> Register
          </button>
        </form>

        <div class="mt-4 text-center">
          <p class="mb-0 small">Sudah punya akun?
            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none text-success">Login</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js/particles.min.js"></script>

<script>
  // GSAP animasi card register
  gsap.from("#registerCard", { duration: 1, y: 60, opacity: 0, ease: "power3.out" });

  // Fokus animasi input
  gsap.utils.toArray("input, select").forEach(el => {
    el.addEventListener("focus", () => {
      gsap.to(el, { duration: 0.3, scale: 1.02, boxShadow: "0 0 10px rgba(40,167,69,0.6)" });
    });
    el.addEventListener("blur", () => {
      gsap.to(el, { duration: 0.3, scale: 1, boxShadow: "none" });
    });
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
    "interactivity": { "events": { "onhover": { "enable": true, "mode": "repulse" } } }
  });
</script>

</body>
</html>
