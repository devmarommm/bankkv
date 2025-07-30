<?php
include '../Admin/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Guest | Bank KV</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    html {
      scroll-behavior: smooth;
    }

    #searchSection {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      background-color: #f9fafb;
      padding: 60px 20px;
      border-top: 2px solid #e5e7eb;
    }

    .search-section {
      max-width: 1000px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .search-controls {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: center;
      margin-bottom: 25px;
    }

    .input-group input {
      padding: 10px 15px;
      border-radius: 12px;
      border: 1px solid #d1d5db;
      width: 280px;
      outline: none;
    }

    .filter-group select {
      padding: 10px;
      border-radius: 12px;
      border: 1px solid #d1d5db;
    }

    .btn-primary {
      background: #4f46e5;
      color: #fff;
      padding: 10px 20px;
      border-radius: 12px;
      border: none;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .btn-primary:hover {
      background: #4338ca;
    }

    .results-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 20px;
    }

    .search-result-card {
      background: #fff;
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .search-result-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }

    .card-row {
      margin-bottom: 10px;
      font-size: 14px;
    }

    .card-label {
      font-weight: 600;
      color: #374151;
      display: inline-block;
      width: 90px;
    }

    .card-image-container img {
      width: 100%;
      border-radius: 10px;
      border: 1px solid #e5e7eb;
      margin-top: 5px;
    }

    @media (max-width: 600px) {
      .search-controls {
        flex-direction: column;
        align-items: center;
      }

      .input-group input, .filter-group select {
        width: 100%;
      }
    }
  </style>
  <script defer>
    document.addEventListener('DOMContentLoaded', function () {
      const searchBtn = document.getElementById('searchBtn');
      const searchInput = document.getElementById('searchInput');
      const resultsContainer = document.getElementById('resultsContainer');
    });
  </script>
</head>
<body>

  <header>
    <div class="container">
      <div class="logo">
        <img src="../images/logo.png" alt="Bank KV Logo">
        <h1>Bank<span>KV</span></h1>
      </div>
      <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <nav id="nav-menu">
        <ul class="nav-links">
          <li><a href="../index.html" class="nav-link active">Beranda</a></li>
          <li><a href="#searchSection" class="nav-link active">Cari KV</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="main-guest">
    <div class="intro-box fade-in">
      <div class="intro-inner">

       <div class="intro-image">
         <img src="../s/KV-illustration.png" alt="KV Illustration"/>
       </div>
      <div class="intro-content">
        <h2>Selamat Datang, Guest!</h2>
        <p>Temukan dan kelola Key Visual sesuai kebutuhanmu. Mulai dari pencarian hingga penyimpanan, semua ada di sini.</p>
        <a href="#searchSection" class="btn btn-primary">Mulai Pencarian KV</a>
      </div>
      </div>
    </div>
  </main>

  <!-- Search Section -->
  <section class="dashboard-main" id="searchSection">
    <div class="container">
      <h2 class="section-title" style="text-align: center; font-size: 28px; margin-bottom: 10px;">Pencarian Key Visual</h2>
      <p class="section-subtitle" style="text-align: center; color: #6b7280; margin-bottom: 30px;">Gunakan sistem pencarian kami untuk menemukan KV sesuai kebutuhan Anda.</p>

      <div class="search-section">
        <form class="search-controls" method="GET">
          <div class="input-group">
            <input type="text" name="searchInput" placeholder="🔍 Cari berdasarkan nama folder..."
              value="<?php echo isset($_GET['searchInput']) ? htmlspecialchars($_GET['searchInput']) : ''; ?>">
          </div>

          <div class="filter-group">
            <select name="filterYear">
              <option value="">Tahun</option>
              <?php
              $selectedYear = $_GET['filterYear'] ?? '';
              foreach (['2025', '2024', '2023'] as $year) {
                echo '<option value="'.$year.'"'.($selectedYear == $year ? ' selected' : '').'>'.$year.'</option>';
              }
              ?>
            </select>

            <select name="filterMonth">
              <option value="">Bulan</option>
              <?php
              $bulan = [
                '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
                '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu',
                '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
              ];
              $selectedMonth = $_GET['filterMonth'] ?? '';
              foreach ($bulan as $val => $label) {
                echo '<option value="'.$val.'"'.($selectedMonth == $val ? ' selected' : '').'>'.$label.'</option>';
              }
              ?>
            </select>

            <button type="submit" class="btn btn-primary">
              <i class="fas fa-search"></i> Cari
            </button>
          </div>
        </form>


        <div class="results-grid" id="resultsContainer">
          <!-- Hasil pencarian akan muncul di sini -->
          <?php
            // Ambil nilai filter dari query string
            $search = $_GET['searchInput'] ?? '';
            $filterYear = $_GET['filterYear'] ?? '';
            $filterMonth = $_GET['filterMonth'] ?? '';

            $showData = !empty($search) || !empty($filterYear) || !empty($filterMonth);

            if ($showData) {
              $query = "SELECT * FROM kv_folders WHERE 1=1";

              if (!empty($search)) {
                $searchEscaped = mysqli_real_escape_string($conn, $search);
                $query .= " AND nama LIKE '%$searchEscaped%'";
              }

              if (!empty($filterYear)) {
                $query .= " AND YEAR(tanggal) = '$filterYear'";
              }

              if (!empty($filterMonth)) {
                $query .= " AND MONTH(tanggal) = '$filterMonth'";
              }

              $query .= " ORDER BY tanggal DESC";
              $result = mysqli_query($conn, $query);

              if (mysqli_num_rows($result) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <div class="search-result-card">
                    <!-- Nomor -->
                    <div class="card-row"><span class="card-label">No:</span> <?= $no++ ?></div>

                    <!-- Foto KV -->
                    <?php if (!empty($row['image'])): ?>
                      <div class="card-image-container">
                        <img src="../Admin/uploads/<?= htmlspecialchars($row['image']) ?>" alt="Cover KV" style="max-height:150px; object-fit:cover; width:100%; border-radius:10px; border:1px solid #e5e7eb;">
                      </div>
                    <?php endif; ?>

                    <!-- Nama -->
                    <div class="card-row"><span class="card-label">Nama:</span> <?= htmlspecialchars($row['nama']) ?></div>

                    <!-- Kategori -->
                    <div class="card-row"><span class="card-label">Kategori:</span> <?= htmlspecialchars($row['kategori']) ?></div>

                    <!-- Tag -->
                    <div class="card-row"><span class="card-label">Tag:</span> <?= htmlspecialchars($row['tag']) ?></div>

                    <!-- Kreator -->
                    <div class="card-row"><span class="card-label">Kreator:</span> <?= htmlspecialchars($row['kreator']) ?></div>

                    <!-- Tanggal -->
                    <div class="card-row"><span class="card-label">Tanggal:</span> <?= htmlspecialchars($row['tanggal']) ?></div>

                    <!-- Link -->
                    <div class="card-row"><span class="card-label">Link:</span>
                      <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank">Lihat KV</a>
                    </div>
                  </div>
                <?php
                }
              } else {
                echo '<p class="no-results">Tidak ada folder KV ditemukan sesuai filter.</p>';
              }
            } else {
              echo '<p class="info">Silakan gunakan fitur pencarian atau filter untuk melihat data KV.</p>';
            }
          ?>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="container">
      <p>&copy; 2025 Bank KV. All rights reserved.</p>
    </div>
  </footer>

  <script>
    // Hamburger dashboard guest
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav-menu');

    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      navMenu.classList.toggle('active');
    });
    // Navigasi dashboard guest
    const links = document.querySelectorAll('.nav-link');

    links.forEach(link => {
      link.addEventListener('click', function () {
        links.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
      });
    });
  </script>

</body>
</html>
