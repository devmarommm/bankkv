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
  <link rel="stylesheet" href="../style-new.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

<!-- == Header (No Change) == -->
<header>
  <div class="container">

    <div class="nav-left">
      <ul class="nav-links">
        <li><a href="../index.php" class="nav-link">Beranda</a></li>
        <li><a href="#searchSection" class="nav-link">Cari KV</a></li>
      </ul>
    </div>

    <div class="nav-center">
      <div class="logo">
        <img src="../images/logo.png" alt="Bank KV Logo">
      </div>
    </div>

    <div class="nav-right">
      <ul class="nav-links">
        <li><a href="#" onclick="logoutUser()" class="btn btn-outline">Logout</a></li>
        <!-- tombol admin bakal disisipin dinamis via JS Firebase -->
      </ul>
    </div>

  </div>
</header>

<main class="main-guest">
<!-- == Section Intro Box == -->
<section class="intro-box fade-in">
    <div class="intro-inner container">
      <div class="intro-image">
        <img src="../images/KV-illustration.png" alt="KV Illustration"/>
      </div>
      <div class="intro-content">
        <h2>Selamat Datang, Di Website Bank KV Kami!</h2>
        <p>Temukan dan kelola Key Visual sesuai kebutuhanmu.<br>Mulai dari pencarian hingga penyimpanan, semua ada di sini.</p>
        <a href="#searchSection" class="btn btn-primary">Mulai Pencarian KV</a>
      </div>
    </div>
</section>

<!-- === Card Statistik === -->
<section class="kv-stats">
  <!-- Horizontal Card (Total) -->
  <div class="kv-card-horizontal">
    <div class="kv-info">
        <div class="kv-title">Total Key Visual Produk Telkomsel Tahun <?php echo $__selectedYearStats; ?></div>
    </div>
    <div class="kv-number"><?php echo $__stats['total']; ?></div>
  </div>
  <!-- Grid Cards (Fix & Mobile) -->
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

<!-- == Search Section (Pencarian KV) == -->
<section id="searchSection">
  <div class="search-container">
    <h2 class="section-title" style="text-align:center; font-size:28px; margin-bottom:10px;">Pencarian Key Visual</h2>
    <p class="section-subtitle" style="text-align:center; color:var(--color-text-muted); margin-bottom:30px;">Gunakan sistem pencarian atau filter untuk menemukan KV yang spesifik.</p>

    <div class="search-section-card">
      <form class="search-controls" method="GET">
        <div class="input-group">
          <input type="text" name="searchInput" placeholder="🔍 Cari berdasarkan nama folder..."
            value="<?php echo isset($_GET['searchInput']) ? htmlspecialchars($_GET['searchInput']) : ''; ?>">
        </div>

        <div class="filter-group">
          <select name="filterYear">
            <option value="">Semua Tahun</option>
            <?php
            // Ambil tahun dari query filter tabel (jika ada) untuk konsistensi
            $selectedYear = $_GET['filterYear'] ?? '';
            $tahunSekarang = date('Y');
            for($t=$tahunSekarang+1; $t>=2020; $t--) { // +1 untuk tahun depan
              echo "<option value='$t'".($selectedYear==$t?' selected':'').">$t</option>";
            }
            ?>
          </select>

          <select name="filterMonth">
            <option value="">Semua Bulan</option>
            <?php
            $bulan = [
              '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
              '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
              '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
            ];
            $selectedMonth = $_GET['filterMonth'] ?? '';
            foreach ($bulan as $val=>$label) {
              echo "<option value='$val'".($selectedMonth==$val?' selected':'').">$label</option>";
            }
            ?>
          </select>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Cari KV
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
              } else {
                echo "<div class='card-image-container' style='display:flex; justify-content:center; align-items:center; color:var(--color-text-muted); font-size:14px;'>[ Tidak Ada Cover ]</div>";
              }
              echo "<div class='card-row'><span class='card-label'>Nama:</span> ".htmlspecialchars($row['nama'])."</div>
                     <div class='card-row'><span class='card-label'>Kategori:</span> ".htmlspecialchars($row['kategori'])."</div>
                     <div class='card-row'><span class='card-label'>Tag:</span> ".htmlspecialchars($row['tag'])."</div>
                     <div class='card-row'><span class='card-label'>Kreator:</span> ".htmlspecialchars($row['kreator'])."</div>
                     <div class='card-row'><span class='card-label'>Tanggal:</span> ".htmlspecialchars(date('d F Y', strtotime($row['tanggal'])))."</div>
                     <div class='card-row'><span class='card-label'>Link:</span> <a href='view_counter.php?id=".$row['id']."' target='_blank'>Lihat</a></div>
              </div>";
            }
          } else {
            echo "<p class='no-results'>Tidak ada folder KV ditemukan sesuai filter.</p>";
          }
        } else {
          echo "<p class='info'>Silakan gunakan fitur pencarian di atas untuk melihat data KV dalam format kartu.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</section>

<!-- TABEL DATA (FULL DATA VIEW) -->
<section class="results-table">
    <h3>Data KV Tersedia (Filter Tabel)</h3>

    <form method="GET" class="table-controls">
        <select name="filterKategori">
            <option value="">Semua Kategori</option>
            <option value="fix" <?php echo (($_GET['filterKategori'] ?? '') == 'fix') ? 'selected' : ''; ?>>Fix</option>
            <option value="mobile" <?php echo (($_GET['filterKategori'] ?? '') == 'mobile') ? 'selected' : ''; ?>>Mobile</option>
        </select>

        <select name="filterBulan">
            <option value="">Semua Bulan</option>
            <?php
            $bulan = [
                '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
                '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
                '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
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

        <button type="submit">Tampilkan Data</button>
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
                    echo "<img src='../Admin/uploads/".htmlspecialchars($row['image'])."' class='table-image' alt='Cover'>";
                } else { echo "-"; }
                echo "</td>
                    <td>".htmlspecialchars($row['nama'])."</td>
                    <td>".htmlspecialchars($row['kategori'])."</td>
                    <td>".htmlspecialchars($row['tag'])."</td>
                    <td>".htmlspecialchars($row['kreator'])."</td>
                    <td>".htmlspecialchars(date('d F Y', strtotime($row['tanggal'])))."</td>
                    <td><a href='view_counter.php?id=".$row['id']."' target='_blank'>Lihat</a></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Tidak ada data KV yang tersedia berdasarkan filter yang dipilih.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</section>

</main>

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
        // Ganti alert dengan cara yang lebih aman di Canvas
        console.log("Loading terlalu lama. Silakan refresh halaman atau login ulang.");
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
          console.log("Data pengguna tidak ditemukan. Silakan login ulang.");
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
              // Mengubah class btn-primary (red/orange) menjadi btn-secondary (blue/purple) agar konsisten dengan desain tombol utama
              adminBtn.innerHTML = `<a href="../Admin/dashboard_admin.php" class="btn btn-primary">Dashboard Admin</a>`; 
              navLinks.insertBefore(adminBtn, navLinks.lastElementChild); // taruh sebelum tombol Logout
            }
          }, 200); // jeda 0.2 detik biar DOM ready
        }

        console.log("User role verified:", role);

      } catch (error) {
        console.error("Auth error:", error);
        console.log("Terjadi kesalahan. Silakan login ulang.");
        window.location.href = "../index.php";
      }
    });

    // Mengubah fungsi logout menjadi global
    window.logoutUser = async function() {
      try {
        sessionStorage.setItem('justLoggedOut', '1');
        localStorage.removeItem("userUID");
        localStorage.removeItem("userRole");
        sessionStorage.removeItem("userRole");

        if (typeof currentUser !== "undefined") currentUser = null;
        if (typeof currentUserRole !== "undefined") currentUserRole = null;

        await firebase.auth().signOut();
        console.log("Logout successful");

        window.location.href = "../index.php";
      } catch (error) {
        console.error("Logout error:", error);
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

    // === Verifikasi Akses Dashboard ===
    firebase.auth().onAuthStateChanged(async (user) => {
      const loadingScreen = document.getElementById('loading-screen');
      const mainContent = document.getElementById('main-content');
      
      if (user) {
          // 1. Ambil Role (Wajib)
          let role = localStorage.getItem("userRole") || await validateUserRole(user.uid); 
          
          // 2. Cek Kesesuaian Role dengan Halaman
          const expectedRole = window.location.pathname.includes("Admin") ? "admin" : "guest";
          
          // Cek jika role sesuai ATAU jika Admin yang login mencoba Guest Dashboard (yang mana OK)
          if (role === expectedRole || (role === "admin" && expectedRole === "guest")) {
            loadingScreen.style.display = 'none';
            mainContent.style.display = 'block';
          } else {
            // Role tidak sesuai, buang ke index.php
            console.log(`Role ${role} mencoba mengakses ${expectedRole} dashboard. Redirecting.`);
            window.location.replace("../index.php");
          }

      } else {
          // Tidak ada user yang login, Redirect ke index.php
          console.log("No user, redirecting to login page.");
          window.location.replace("../index.php");
      }
    });

    // Note: Fungsi update_views.php tidak bisa dipanggil dari sini tanpa PHP session,
    // jadi mekanisme ini sudah benar jika hanya mengandalkan link ke view_counter.php
    // document.querySelectorAll('.btn-lihat').forEach(...) dihapus karena sudah diganti 
    // dengan link langsung ke view_counter.php di bagian PHP loop.

</script>

</body>
</html>
