<?php
include '../Admin/koneksi.php';

// === STATISTIK TAHUNAN UNTUK KARTU DI DASHBOARD ===
$__selectedYearStats = isset($_GET['filterYear']) && $_GET['filterYear'] !== '' ? intval($_GET['filterYear']) : null;

if (!$__selectedYearStats) {
    $__qYear = mysqli_query($conn, "SELECT MAX(YEAR(tanggal)) AS latest_year FROM kv_folders");
    $__rYear = $__qYear ? mysqli_fetch_assoc($__qYear) : null;
    $__selectedYearStats = $__rYear && !empty($__rYear['latest_year']) ? intval($__rYear['latest_year']) : intval(date('Y'));
}

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

<!-- ========== HEADER ========== -->
<header>
  <div class="header-wrapper">
    <nav class="nav-left">
      <a href="../index.php" class="nav-link">Beranda</a>
      <a href="#searchSection" class="nav-link">Cari KV</a>
    </nav>

    <div class="nav-center">
      <div class="logo">
        <img src="../images/logo.png" alt="Bank KV Logo">
      </div>
    </div>

    <nav class="nav-right">
      <a href="#" onclick="logoutUser()" class="btn btn-outline">Logout</a>
      <!-- tombol Admin otomatis disisipkan oleh JS di sini -->
    </nav>
  </div>
</header>

<main class="main-guest">

<!-- == Section Intro Box == -->
<section class="intro-box">
  <div class="intro-wrapper">
    <div class="intro-left">
      <img src="../images/KV-illustration.png" alt="Ilustrasi Pengguna Bank KV">
    </div>
    <div class="intro-right">
      <h1>Selamat Datang, Di Website Bank KV Kami!</h1>
      <p>Temukan dan kelola Key Visual sesuai kebutuhanmu.<br>Mulai dari pencarian hingga penyimpanan, semua ada di sini.</p>
      <a href="#searchSection" class="btn-intro">Mulai Pencarian KV</a>
    </div>
  </div>

  <!-- Circle Dekorasi -->
  <div class="circle circle-top"></div>
  <div class="circle circle-bottom-left"></div>
  <div class="circle circle-bottom-right"></div>
</section>

<!-- === Card Statistik === -->
<section class="kv-stats">
  <div class="kv-stats-wrapper">
    <div class="kv-card kv-card-main">
      <div class="kv-number"><?php echo $__stats['total']; ?></div>
      <div class="kv-desc">
        Total Key Visual<br>
        Produk Telkomsel <?php echo $__selectedYearStats; ?>
      </div>
    </div>

    <div class="kv-card-side">
      <div class="kv-card small">
        <div class="kv-title">Fix</div>
        <div class="kv-number"><?php echo $__stats['fix_count']; ?></div>
        <div class="kv-desc">Key Visual Household Products</div>
      </div>
      <div class="kv-card small">
        <div class="kv-title">Mobile</div>
        <div class="kv-number"><?php echo $__stats['mobile_count']; ?></div>
        <div class="kv-desc">Key Visual Household Products</div>
      </div>
    </div>
  </div>
</section>

<!-- == Search Section == -->
<section id="searchSection">
  <div class="search-container">
    <h2 class="search-title">Pencarian Key Visual</h2>
    <p class="search-subtitle">Gunakan sistem pencarian kami untuk menemukan KV sesuai kebutuhan Anda</p>

    <div class="search-card">
      <form class="search-controls" method="GET">
        <input type="text" name="searchInput" placeholder="Cari Berdasarkan nama" 
          value="<?php echo isset($_GET['searchInput']) ? htmlspecialchars($_GET['searchInput']) : ''; ?>">

        <select name="filterYear">
          <option value="">Tahun</option>
          <?php
            $selectedYear = $_GET['filterYear'] ?? '';
            $tahunSekarang = date('Y');
            for($t=$tahunSekarang+1; $t>=2020; $t--) {
              echo "<option value='$t'".($selectedYear==$t?' selected':'').">$t</option>";
            }
          ?>
        </select>

        <select name="filterMonth">
          <option value="">Bulan</option>
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

        <button type="submit" class="btn-search">Cari</button>
      </form>

      <h3 class="section-terbaru">Terbaru</h3>

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
                <div class='card-image-container'>
                  <div class='card-image-inner'>";
                    if (!empty($row['image'])) {
                      echo "<img src='../Admin/uploads/".htmlspecialchars($row['image'])."' alt='Cover KV'>";
                    } else {
                      echo "<div class='no-image'>[ Tidak Ada Cover ]</div>";
                    }
                  echo "</div>
                </div>
                <div class='card-info'>
                  <h4>".htmlspecialchars($row['nama'])."</h4>
                  <p><span>Nama:</span> ".htmlspecialchars($row['kreator'])."</p>
                  <p><span>Kategori:</span> ".htmlspecialchars($row['kategori'])."</p>
                  <p><span>Tag:</span> ".htmlspecialchars($row['tag'])."</p>
                  <p><span>Tanggal:</span> ".htmlspecialchars($row['tanggal'])."</p>
                  <a href='view_counter.php?id=".$row['id']."' target='_blank' class='card-link'>Lihat Detail</a>
                </div>
              </div>";
            }
          } else {
            echo "<p class='no-results'>Tidak ada KV ditemukan sesuai filter.</p>";
          }
        } else {
          echo "<p class='info'>Silakan gunakan fitur pencarian di atas untuk melihat data KV dalam format kartu.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</section>

<!-- TABEL DATA -->
<section class="kv-data-section">
  <!-- === Dekorasi Circle === -->
  <div class="kv-circle circle-top-left"></div>
  <div class="kv-circle circle-top-right"></div>
  <div class="kv-circle circle-bottom-right"></div>

  <h2>Data KV Tersedia</h2>

  <form method="GET" class="kv-filter-form">
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

    <button type="submit" class="btn-filter">Filter</button>
  </form>

  <div class="kv-table-container">
    <table class="kv-table">
      <tbody>
      <?php
      $no = 1;
      if ($tableResult && mysqli_num_rows($tableResult) > 0) {
          while ($row = mysqli_fetch_assoc($tableResult)) {
              echo "<tr>
                  <td class='no'>$no.</td>
                  <td class='cover'>";
              if (!empty($row['image'])) {
                  echo "<img src='../Admin/uploads/".htmlspecialchars($row['image'])."' alt='Cover'>";
              } else { echo "-"; }
              echo "</td>
                  <td class='nama'>".htmlspecialchars($row['nama'])."</td>
                  <td class='kategori'>".htmlspecialchars($row['kategori'])."</td>
                  <td class='tag'>".htmlspecialchars($row['tag'])."</td>
                  <td class='kreator'>".htmlspecialchars($row['kreator'])."</td>
                  <td class='tanggal'>".htmlspecialchars(date('Y-m-d', strtotime($row['tanggal'])))."</td>
                  <td class='aksi'><a href='view_counter.php?id=".$row['id']."' target='_blank' class='btn-lihat'>Lihat</a></td>
              </tr>";
              $no++;
          }
      } else {
          echo "<tr><td colspan='8' class='no-data'>Tidak ada data KV yang tersedia berdasarkan filter yang dipilih.</td></tr>";
      }
      ?>
      </tbody>
    </table>
  </div>
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

firebase.auth().onAuthStateChanged(async (user) => {
  const loadingScreen = document.getElementById('loading-screen');
  const mainContent = document.getElementById('main-content');
  if (!user) {
    window.location.href = "../index.php";
    return;
  }

  const doc = await db.collection("users").doc(user.uid).get();
  const role = doc.exists ? doc.data().role : null;
  if (!role) {
    await firebase.auth().signOut();
    window.location.href = "../index.php";
    return;
  }

  localStorage.setItem("userUID", user.uid);
  localStorage.setItem("userRole", role);

  loadingScreen.style.display = 'none';
  mainContent.style.display = 'block';

  // Tambah tombol admin jika role = admin
  if (role === "admin") {
    setTimeout(() => {
      const navRight = document.querySelector(".nav-right");
      if (navRight && !document.getElementById("admin-shortcut")) {
        const adminBtn = document.createElement("a");
        adminBtn.id = "admin-shortcut";
        adminBtn.href = "../Admin/dashboard_admin.php";
        adminBtn.className = "btn-admin";
        adminBtn.textContent = "Admin";

        // Pindahkan Admin ke paling kanan (setelah logout)
        navRight.appendChild(adminBtn);
      }
    }, 200);
  }
});

window.logoutUser = async function() {
  await firebase.auth().signOut();
  localStorage.removeItem("userUID");
  localStorage.removeItem("userRole");
  window.location.href = "../index.php";
}
</script>

</body>
</html>
