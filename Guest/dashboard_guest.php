<?php
include '../Admin/koneksi.php';

// === STATISTIK TAHUNAN UNTUK KARTU DI DASHBOARD ===
// Ambil tahun dari filter (jika ada), kalau nggak ada pakai tahun terbaru di data
$__selectedYearStats = isset($_GET['filterYear']) && $_GET['filterYear'] !== '' ? intval($_GET['filterYear']) : null;

if (!$__selectedYearStats) {
    $__qYear = mysqli_query($conn, "SELECT MAX(YEAR(tanggal)) AS latest_year FROM kv_folders");
    $__rYear = $__qYear ? mysqli_fetch_assoc($__qYear) : null;
    $__selectedYearStats = $__rYear && !empty($__rYear['latest_year']) ? intval($__rYear['latest_year']) : intval(date('Y'));
}

// Hitung total, fix, dan mobile
$__statSql = "
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN LOWER(kategori)='fix' OR LOWER(kategori) LIKE '%fix%' THEN 1 ELSE 0 END) AS fix_count,
        SUM(CASE WHEN LOWER(kategori)='mobile' OR LOWER(kategori) LIKE '%mobile%' THEN 1 ELSE 0 END) AS mobile_count
    FROM kv_folders
    WHERE YEAR(tanggal) = {$__selectedYearStats}
";
$__statRes = mysqli_query($conn, $__statSql);
$__stats = $__statRes ? mysqli_fetch_assoc($__statRes) : ['total'=>0,'fix_count'=>0,'mobile_count'=>0];

// === QUERY DATA UNTUK TABEL ===
$filterKategoriTabel = $_GET['filterKategori'] ?? '';
$filterBulanTabel = $_GET['filterBulan'] ?? '';
$filterTahunTabel = $_GET['filterTahun'] ?? '';

$tableQuery = "SELECT * FROM kv_folders WHERE 1=1";

if (!empty($filterKategoriTabel)) {
    $kategoriEscaped = mysqli_real_escape_string($conn, $filterKategoriTabel);
    $tableQuery .= " AND LOWER(kategori) LIKE '%$kategoriEscaped%'";
}
if (!empty($filterBulanTabel)) {
    $bulanEscaped = intval($filterBulanTabel);
    $tableQuery .= " AND MONTH(tanggal) = '$bulanEscaped'";
}
if (!empty($filterTahunTabel)) {
    $tahunEscaped = intval($filterTahunTabel);
    $tableQuery .= " AND YEAR(tanggal) = '$tahunEscaped'";
}

$tableQuery .= " ORDER BY tanggal DESC";
$tableResult = mysqli_query($conn, $tableQuery);
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
    html { scroll-behavior: smooth; }
    #searchSection {
      display: flex; flex-direction: column; align-items: center; text-align: center;
      background-color: #f9fafb; padding: 60px 20px; border-top: 2px solid #e5e7eb;
    }
    .search-section {
      max-width: 1000px; margin: 0 auto; background: #fff; border-radius: 16px;
      padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .search-controls { display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; margin-bottom: 25px; }
    .input-group input, .filter-group select {
      padding: 10px 15px; border-radius: 12px; border: 1px solid #d1d5db; outline: none;
    }
    .btn-primary {
      background: #4f46e5; color: #fff; padding: 10px 20px; border-radius: 12px; border: none;
      cursor: pointer; font-weight: bold; transition: background 0.3s ease;
    }
    .btn-primary:hover { background: #4338ca; }
    .results-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
    .search-result-card {
      background: #fff; border-radius: 16px; padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .search-result-card:hover { transform: translateY(-5px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
    .card-row { margin-bottom: 10px; font-size: 14px; }
    .card-label { font-weight: 600; color: #374151; display: inline-block; width: 90px; }
    .card-image-container { overflow: hidden; border-radius: 10px; }
    .card-image-container img {
      width: 100%; height: 100%; border-radius: 10px; border: 1px solid #e5e7eb;
      transform: scale(1.05); transition: transform 0.6s ease;
    }
    .search-result-card:hover .card-image-container img { transform: scale(1); }

    /* Loading Screen Styles */
    #loading-screen {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
      background: #fff; display: flex; justify-content: center; align-items: center; z-index: 9999;
    }
    .loading-spinner {
      width: 50px; height: 50px; border: 4px solid #f3f3f3; 
      border-top: 4px solid #4f46e5; border-radius: 50%; 
      animation: spin 1s linear infinite; margin: 0 auto 20px;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* ===== KV Stats Cards ===== */
    .kv-stats { max-width: 1100px; margin: 30px auto 10px; padding: 0 16px; }
    .kv-card-horizontal {
      display: flex; align-items: center; justify-content: space-between;
      gap: 16px; padding: 22px; border-radius: 16px; background: #ffffff;
      border: 1px solid #e5e7eb; box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }
    .kv-card-horizontal .kv-title { font-size: 16px; color: #374151; font-weight: 600; }
    .kv-card-horizontal .kv-number { font-size: 36px; font-weight: 800; line-height: 1; }

    .kv-card-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 16px; margin-top: 16px; }
    .kv-card {
      padding: 20px; border-radius: 16px; background: #ffffff; border: 1px solid #e5e7eb;
      box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    }
    .kv-card .kv-title { font-size: 14px; color: #6b7280; font-weight: 600; margin-bottom: 6px; }
    .kv-card .kv-number { font-size: 28px; font-weight: 800; line-height: 1; }

    .kv-desc {
      font-size: 12px;
      color: #6b7280;
      margin-top: 4px;
    }

    @media (max-width: 640px) {
      .kv-card-horizontal { flex-direction: column; align-items: flex-start; }
      .kv-card-grid { grid-template-columns: 1fr; }
    }

    /* ===== Results Table ===== */
    .results-table { margin-top: 26px; }
    .results-table h3 { font-size: 20px; margin: 0 0 12px; }
    table.kv-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
    table.kv-table th, table.kv-table td { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 14px; }
    table.kv-table th { background: #f9fafb; font-weight: 700; }
    table.kv-table tr:hover { background: #fafafa; }
    table.kv-table td a { text-decoration: none; }

    /* Filter Tabel di Data KV */
    .results-table form {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
      align-items: center;
    }

    .results-table select,
    .results-table button {
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
    }

    .results-table select:focus,
    .results-table button:focus {
      outline: none;
      border-color: #007bff;
    }

    .results-table button {
      background-color: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .results-table button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<!-- Loading Screen -->
<div id="loading-screen">
  <div style="text-align: center;">
    <div class="loading-spinner"></div>
    <p>Memverifikasi akses guest...</p>
  </div>
</div>

<div id="main-content" style="display: none;">

<!-- == Header == -->
<header>
  <div class="container">
    <div class="logo">
      <img src="../images/logo.png" alt="Bank KV Logo">
      <h1>Bank<span>KV</span></h1>
    </div>
    <div class="hamburger" id="hamburger">
      <span></span><span></span><span></span>
    </div>
    <nav id="nav-menu">
      <ul class="nav-links">
        <li><a href="../index.php" class="nav-link">Beranda</a></li>
        <li><a href="#searchSection" class="nav-link">Cari KV</a></li>
        <li><a href="#" onclick="logoutUser()" class="btn btn-outline">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- == Section Intro Box == -->
<main class="main-guest">
  <div class="intro-box fade-in">
    <div class="intro-inner">
      <div class="intro-image">
        <img src="../images/KV-illustration.png" alt="KV Illustration"/>
      </div>
      <div class="intro-content">
        <h2>Selamat Datang, Di Website Bank KV Kami!</h2>
        <p>Temukan dan kelola Key Visual sesuai kebutuhanmu.<br>Mulai dari pencarian hingga penyimpanan, semua ada di sini.</p>
        <a href="#searchSection" class="btn btn-primary">Mulai Pencarian KV</a>
      </div>
    </div>
  </div>
</main>

<!-- === Tambahan Card Statistik === -->
<section class="kv-stats">
  <div class="kv-card-horizontal">
    <div class="kv-title">Total Key Visual Produk Telkomsel <?php echo $__selectedYearStats; ?></div>
    <div class="kv-number"><?php echo $__stats['total']; ?></div>
  </div>
  <div class="kv-card-grid">
    <div class="kv-card">
      <div class="kv-title">Fix</div>
      <div class="kv-number"><?php echo $__stats['fix_count']; ?></div>
      <div class="kv-desc">Key Visual Household Products</div>
    </div>
    <div class="kv-card">
      <div class="kv-title">Mobile</div>
      <div class="kv-number"><?php echo $__stats['mobile_count']; ?></div>
      <div class="kv-desc">Key Visual Mobile Products</div>
    </div>
  </div>
</section>
</main>

<!-- TABEL DATA SELALU TAMPIL -->
<section class="results-table">
  <h3>Data KV Tersedia</h3>

    <form method="GET" style="margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
      <select name="filterKategori">
        <option value="">Semua Kategori</option>
        <option value="fix" <?php echo (($_GET['filterKategori'] ?? '') == 'fix') ? 'selected' : ''; ?>>Fix</option>
        <option value="mobile" <?php echo (($_GET['filterKategori'] ?? '') == 'mobile') ? 'selected' : ''; ?>>Mobile</option>
      </select>

      <select name="filterBulan">
        <option value="">Semua Bulan</option>
        <?php
        $bulan = [
          '01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr',
          '05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu',
          '09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'
        ];
        $selectedBulanTabel = $_GET['filterBulan'] ?? '';
        foreach ($bulan as $val=>$label) {
          echo "<option value='$val'".($selectedBulanTabel==$val?' selected':'').">$label</option>";
        }
        ?>
      </select>

      <select name="filterTahun">
        <option value="">Semua Tahun</option>
        <?php
        $tahunSekarang = date('Y');
        for($t=$tahunSekarang; $t>=2020; $t--) {
          $selectedTahunTabel = $_GET['filterTahun'] ?? '';
          echo "<option value='$t'".($selectedTahunTabel==$t?' selected':'').">$t</option>";
        }
        ?>
      </select>

      <button type="submit" class="btn btn-primary">Filter</button>
    </form>

  <table class="kv-table">
    <thead>
      <tr>
        <th>No</th>
        <th>Cover</th>
        <th>Nama Folder</th>
        <th>Kategori</th>
        <th>Tag</th>
        <th>Kreator</th>
        <th>Tanggal</th>
        <th>Link</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $no = 1;
    if ($tableResult && mysqli_num_rows($tableResult) > 0) {
      while ($row = mysqli_fetch_assoc($tableResult)) {
        echo "<tr>
          <td>".($no++)."</td>
          <td>";
        if (!empty($row['image'])) {
          echo "<img src='../Admin/uploads/".htmlspecialchars($row['image'])."' style='width:50px;height:50px;border-radius:6px;'>";
        } else { echo "-"; }
        echo "</td>
          <td>".htmlspecialchars($row['nama'])."</td>
          <td>".htmlspecialchars($row['kategori'])."</td>
          <td>".htmlspecialchars($row['tag'])."</td>
          <td>".htmlspecialchars($row['kreator'])."</td>
          <td>".htmlspecialchars($row['tanggal'])."</td>
          <td><a href='".htmlspecialchars($row['link'])."' target='_blank'>Lihat</a></td>
        </tr>";
      }
    } else {
      echo "<tr><td colspan='8'>Belum ada data KV.</td></tr>";
    }
    ?>
    </tbody>
  </table>
</section>

<!-- == Search Section == -->
<section class="dashboard-main" id="searchSection">
  <div class="container">
    <h2 class="section-title" style="text-align:center; font-size:28px; margin-bottom:10px;">Pencarian Key Visual</h2>
    <p class="section-subtitle" style="text-align:center; color:#6b7280; margin-bottom:30px;">Gunakan sistem pencarian kami untuk menemukan KV sesuai kebutuhan Anda.</p>

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
            foreach (['2025','2024','2023'] as $year) {
              echo "<option value='$year'".($selectedYear==$year?' selected':'').">$year</option>";
            }
            ?>
          </select>

          <select name="filterMonth">
            <option value="">Bulan</option>
            <?php
            $bulan = [
              '01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr',
              '05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu',
              '09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'
            ];
            $selectedMonth = $_GET['filterMonth'] ?? '';
            foreach ($bulan as $val=>$label) {
              echo "<option value='$val'".($selectedMonth==$val?' selected':'').">$label</option>";
            }
            ?>
          </select>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Cari
          </button>
        </div>
      </form>

      <div class="results-grid">
        <?php
        $search = $_GET['searchInput'] ?? '';
        $filterYear = $_GET['filterYear'] ?? '';
        $filterMonth = $_GET['filterMonth'] ?? '';
        $showData = !empty($search) || !empty($filterYear) || !empty($filterMonth);

        if ($showData) {
          $query = "SELECT * FROM kv_folders WHERE 1=1";
          if ($search) {
            $searchEscaped = mysqli_real_escape_string($conn, $search);
            $query .= " AND nama LIKE '%$searchEscaped%'";
          }
          if ($filterYear) { $query .= " AND YEAR(tanggal)='$filterYear'"; }
          if ($filterMonth) { $query .= " AND MONTH(tanggal)='$filterMonth'"; }
          $query .= " ORDER BY tanggal DESC";
          $result = mysqli_query($conn, $query);

          if (mysqli_num_rows($result) > 0) {
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<div class='search-result-card'>
                <div class='card-row'><span class='card-label'>No:</span> ".($no++)."</div>";
              if (!empty($row['image'])) {
                echo "<div class='card-image-container'><img src='../Admin/uploads/".htmlspecialchars($row['image'])."' alt='Cover KV'></div>";
              }
              echo "<div class='card-row'><span class='card-label'>Nama:</span> ".htmlspecialchars($row['nama'])."</div>
                    <div class='card-row'><span class='card-label'>Kategori:</span> ".htmlspecialchars($row['kategori'])."</div>
                    <div class='card-row'><span class='card-label'>Tag:</span> ".htmlspecialchars($row['tag'])."</div>
                    <div class='card-row'><span class='card-label'>Kreator:</span> ".htmlspecialchars($row['kreator'])."</div>
                    <div class='card-row'><span class='card-label'>Tanggal:</span> ".htmlspecialchars($row['tanggal'])."</div>
                    <div class='card-row'><span class='card-label'>Link:</span> <a href='".htmlspecialchars($row['link'])."' target='_blank'>Lihat KV</a></div>
              </div>";
            }
          } else {
            echo "<p class='no-results'>Tidak ada folder KV ditemukan sesuai filter.</p>";
          }
        } else {
          echo "<p class='info'>Silakan gunakan fitur pencarian atau filter untuk melihat data KV.</p>";
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

<!-- Firebase -->
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-firestore-compat.js"></script>
<script>
    // === Firebase Configuration (HANYA SATU KALI) ===
    if (!firebase.apps.length) {
      const firebaseConfig = {
        apiKey: "AIzaSyA3wCPXQkoIpf_sYoVNrseoTWp5heH0VAE",
        authDomain: "bank-kv-1910f.firebaseapp.com",
        projectId: "bank-kv-1910f",
        storageBucket: "bank-kv-1910f.firebasestorage.app",
        messagingSenderId: "87795172113",
        appId: "1:87795172113:web:08b077dbfbdee9adbfc2b0",
        measurementId: "G-84VE29GC3W"
      };
      firebase.initializeApp(firebaseConfig);
    }
    const db = firebase.firestore();

    // === Simplified Auth Check ===
    let authCheckCompleted = false;
    let authTimeout;

    // Set maximum loading time (5 detik)
    authTimeout = setTimeout(() => {
      if (!authCheckCompleted) {
        console.error("Auth check timeout");
        alert("Loading terlalu lama. Silakan refresh halaman atau login ulang.");
        window.location.href = "../index.php";
      }
    }, 5000);

    // === Auth State Check ===
    firebase.auth().onAuthStateChanged(async (user) => {
      console.log("Guest Dashboard - Auth state:", user ? user.uid : "No user");

      clearTimeout(authTimeout);
      const loadingScreen = document.getElementById('loading-screen');
      const mainContent = document.getElementById('main-content');

      // Hapus shortcut admin dulu biar selalu fresh
      const oldShortcut = document.getElementById("admin-shortcut");
      if (oldShortcut) oldShortcut.remove();

      try {
        // Kalau belum login, langsung balikin ke home
        if (!user) {
          console.log("No user, redirecting to index");
          window.location.href = "../index.php";
          return;
        }

        // Ambil role dari Firestore
        let role = null;
        for (let i = 0; i < 2; i++) {
          try {
            const doc = await db.collection("users").doc(user.uid).get();
            if (doc.exists) {
              role = doc.data().role;
              break;
            }
          } catch (error) {
            console.log(`Attempt ${i + 1} failed:`, error.message);
            if (i === 0) await new Promise(resolve => setTimeout(resolve, 1000));
          }
        }

        // Kalau gak ada role → logout paksa
        if (!role) {
          console.error("No role found, redirecting");
          alert("Data pengguna tidak ditemukan. Silakan login ulang.");
          await firebase.auth().signOut();
          window.location.href = "../index.php";
          return;
        }

        // Simpan role & UID terbaru
        localStorage.setItem("userUID", user.uid);
        localStorage.setItem("userRole", role);

        // Tampilkan konten utama
        if (loadingScreen) loadingScreen.style.display = 'none';
        if (mainContent) mainContent.style.display = 'block';

        authCheckCompleted = true;

        // === Tambah shortcut Dashboard Admin kalau role = admin ===
        if (role === "admin") {
          setTimeout(() => {
            const navLinks = document.querySelector(".nav-links");
            if (navLinks && !document.getElementById("admin-shortcut")) {
              const adminBtn = document.createElement("li");
              adminBtn.id = "admin-shortcut";
              adminBtn.innerHTML = `<a href="../Admin/dashboard_admin.php" class="btn btn-primary">Dashboard Admin</a>`;
              navLinks.insertBefore(adminBtn, navLinks.lastElementChild); // taruh sebelum tombol Logout
            }
          }, 200); // jeda 0.2 detik biar DOM ready
        }

        console.log("User role verified:", role);

      } catch (error) {
        console.error("Auth error:", error);
        alert("Terjadi kesalahan. Silakan login ulang.");
        window.location.href = "../index.php";
      }
    });

    // === Logout Function ===
    async function logoutUser() {
      try {

        // Tandai logout baru saja dilakukan
        sessionStorage.setItem('justLoggedOut', '1');

        // Hapus semua cache user
        localStorage.removeItem("userUID");
        localStorage.removeItem("userRole");
        sessionStorage.removeItem("userRole");

        // Reset variabel global kalau ada
        if (typeof currentUser !== "undefined") currentUser = null;
        if (typeof currentUserRole !== "undefined") currentUserRole = null;

        // Firebase sign out
        await firebase.auth().signOut();
        console.log("Logout successful");

        // Redirect ke index
        window.location.href = "../index.php";
      } catch (error) {
        console.error("Logout error:", error);

        // Force clear meskipun gagal
        localStorage.removeItem("userUID");
        localStorage.removeItem("userRole");

        window.location.href = "../index.php";
      }
    }

    // === Mobile Menu ===
    document.addEventListener('DOMContentLoaded', function() {
      const hamburger = document.getElementById('hamburger');
      const navMenu = document.getElementById('nav-menu');
      
      if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
          hamburger.classList.toggle('active');
          navMenu.classList.toggle('active');
        });
      }
    });
</script>

</body>
</html>
