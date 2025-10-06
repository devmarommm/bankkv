<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pertukaran KV - Bank KV</title>
  <link rel="stylesheet" href="style.css" />
  <script defer src="script.js"></script>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="container">
    <div class="logo">
      <img src="images/logo.png" alt="Bank KV Logo">
      <h1>Bank KV</h1>
    </div>
    <nav>
      <ul class="nav-links">
        <li><a href="index.php" class="nav-link">Beranda</a></li>
        <li><a href="upload.html" class="nav-link">Penyimpanan KV</a></li>
        <li><a href="exchange.html" class="nav-link active">Pertukaran KV</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- HERO SECTION -->
<section id="hero" style="padding-top:160px;">
  <div class="container">
    <div class="hero-content">
      <h1 class="hero-title">Tukarkan <span>Visual Anda</span></h1>
      <p class="hero-subtitle">Tukarkan aset visual Anda dengan KV lain yang lebih Anda butuhkan. Kami bantu prosesnya jadi mudah dan cepat.</p>
    </div>
  </div>
</section>

<!-- EXCHANGE SECTION -->
<section id="exchange-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Daftar KV untuk Ditukar</h2>
      <p class="section-subtitle">Pilih salah satu KV yang Anda inginkan lalu ajukan pertukaran.</p>
    </div>

    <div class="services-grid">
      <!-- Contoh kartu KV -->
      <div class="service-card">
        <div class="service-icon"><i class="fas fa-image"></i></div>
        <h3>KV Promo Ramadhan</h3>
        <p>Ukuran: 1080x1080 | Format: JPG</p>
        <a href="#" class="service-link">Ajukan Tukar</a>
      </div>

      <div class="service-card">
        <div class="service-icon"><i class="fas fa-image"></i></div>
        <h3>KV Diskon Akhir Tahun</h3>
        <p>Ukuran: 1920x1080 | Format: PNG</p>
        <a href="#" class="service-link">Ajukan Tukar</a>
      </div>
    </div>

    <div class="section-header" style="margin-top:60px;">
      <h2 class="section-title">Tawarkan KV Anda</h2>
      <p class="section-subtitle">Unggah KV Anda untuk ditawarkan sebagai pertukaran.</p>
    </div>

    <form id="offer-form" class="modal-box" style="margin: 0 auto;">
      <input type="text" placeholder="Judul KV Anda" required />
      <input type="file" accept=".jpg, .jpeg, .png" required />
      <button type="submit" class="btn btn-primary">Unggah dan Tawarkan</button>
    </form>

  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <p style="text-align:center; color:#ccc;">&copy; 2025 Bank KV - Pertukaran Aset Visual</p>
  </div>
</footer>

</body>
</html>
