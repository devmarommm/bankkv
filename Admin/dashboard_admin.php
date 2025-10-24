<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin | Bank KV</title>
  <link rel="stylesheet" href="dashboard_admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Loading Screen -->
<div id="loading-screen" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #fff; display: flex; justify-content: center; align-items: center; z-index: 9999;">
  <div style="text-align: center;">
    <div style="width: 50px; height: 50px; border: 4px solid #f3f3f3; border-top: 4px solid #2563eb; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
    <p>Memverifikasi akses...</p>
  </div>
</div>

<style>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<div id="main-content" style="display: none;"></div>

<header class="navbar-kv">
    <nav class="nav-container">
      <ul class="nav-left">
        <li><a href="../Guest/dashboard_guest.php" class="nav-link active">Beranda</a></li>
        <li><a href="analitik.html" class="nav-link">Analitik KV</a></li>
      </ul>
  
      <div class="logo-center">
        <img src="../images/logo.png" alt="Bank KV Logo" height="45">
      </div>
  
      <ul class="nav-right">
        <li><a href="#" onclick="logoutUser()" class="nav-link">Logout</a></li>
      </ul>
    </nav>
  </header>


  <div class="dashboard-admin">
  <div class="welcome-box">
    <h1>Selamat datang, Admin!</h1>
    <p class="welcome-sub">Senang bertemu lagi denganmu hari ini</p>
  </div>

  <div class="character-group">
    <img src="../images/admin.png" alt="Karakter Ilustrasi">
  </div>
</div>



<!-- Form Upload -->
<div class="dashboard-admin">
  <h2 class="section-tittle">Upload Folder KV Baru</h2>
  <form action="upload_kv.php" method="POST" class="upload-form" enctype="multipart/form-data">
    <div class="form-grid">

      <!-- 1. Tanggal -->
      <div class="form-group">
        <label>Tanggal</label>
        <input type="date" name="tanggal" required>
      </div>

      <!-- 2. Nama Folder -->
      <div class="form-group">
        <label>Nama Folder</label>
        <input type="text" name="nama" placeholder="Nama Folder" required>
      </div>

            <!-- 5. Campaign -->
      <div class="form-group">
        <label>Campaign</label>
        <select name="campaign" required>
          <option value="" disabled selected>Pilih Campaign</option>
          <option value="Digital">Campaign Digital</option>
          <option value="Branding Outlet">Branding Outlet</option>
        </select>
      </div>

      <!-- 3. Kategori -->
      <div class="form-group">
        <label>Kategori</label>
        <input type="text" name="kategori" placeholder="Kategori (misal: Promo, Event)" required>
      </div>

    <!-- 4. Source -->
    <div class="form-group">
      <label>Source</label>
      <select name="source" required>
        <option value="" disabled selected>Pilih Source</option>
        <option value="Area">Area</option>
        <option value="Region">Region</option>
        <option value="HQ">HQ</option>
      </select>
    </div>


      <!-- 5. Link -->
      <div class="form-group">
        <label>Link Google Drive Folder</label>
        <input type="text" name="link" placeholder="Masukkan link Google Drive folder" required>
      </div>

    <!-- 6. Upload Gambar -->
    <div class="form-group full-width">
      <label>Upload Gambar KV (opsional)</label>
      <div id="drop-area" onclick="document.getElementById('imageInput').click();">
        <div class="icon-upload">
          <img src="../images/upload-icon.svg.svg" alt="Upload Icon" width="48">
        </div>
        <p class="drop-text"><strong>Drag & Drop</strong> gambar di sini atau <span class="highlight">klik</span></p>
        <input type="file" name="image" id="imageInput" accept="image/*" hidden>
        <div id="preview-image" class="preview-image"></div>
        <p id="filename" class="filename-display"></p>
      </div>
    </div>


    <!-- 7. Tombol Upload -->
    <button type="submit">Upload</button>
  </form>
</div>

<h2 class="section-tittle">Daftar Folder KV</h2>
<div class="table-wrapper">
  <table class="kv-table">
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nama</th>
        <th>Campaign</th>
        <th>Kategori</th>
        <th>Source</th>
        <th>Link</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $result = mysqli_query($conn, "SELECT * FROM kv_folders ORDER BY tanggal DESC");
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
        <td><?= htmlspecialchars($row['nama']); ?></td>
        <td><?= htmlspecialchars($row['campaign']); ?></td> <!-- ✅ Tambahan isi kolom Campaign -->
        <td><?= htmlspecialchars($row['kategori']); ?></td>
        <td><?= htmlspecialchars($row['source']); ?></td>
        <td><a href="<?= htmlspecialchars($row['link']); ?>" target="_blank">Lihat</a></td>
        <td>
          <form method="POST" action="hapus_kv.php" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <button type="submit" class="btn-delete">Hapus</button>
          </form>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>


<!-- Firebase -->
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-firestore-compat.js"></script>
<script>
  // === Firebase Configuration ===
 if (!firebase.apps.length) {
const firebaseConfig = {
        apiKey: "AIzaSyC-OIn3tB1FdB6qqWKdJ5H23Y3KL9ruZtE",
        authDomain: "bank-kv-3d712.firebaseapp.com",
        projectId: "bank-kv-3d712",
        storageBucket: "bank-kv-3d712.firebasestorage.app",
        messagingSenderId: "107872137161",
        appId: "1:107872137161:web:ffed5a4afab10033ea8218",
        measurementId: "G-H6TY0MKPWS"
    };
    firebase.initializeApp(firebaseConfig);
  }
  const db = firebase.firestore();

  // === Simplified Auth Check for Admin ===
  let authCheckCompleted = false;
  
  const authTimeout = setTimeout(() => {
    if (!authCheckCompleted) {
      console.error("Auth timeout");
      alert("Loading terlalu lama. Silakan refresh atau login ulang.");
      window.location.href = "../index.php";
    }
  }, 5000);

  firebase.auth().onAuthStateChanged(async (user) => {
    console.log("Admin Dashboard - Auth state:", user ? user.uid : "No user");
    
    clearTimeout(authTimeout);
    const loadingScreen = document.getElementById('loading-screen');
    const mainContent = document.getElementById('main-content');

    try {
      if (!user) {
        window.location.href = "../index.php";
        return;
      }

      // Simplified role check
      let role = null;
      for (let i = 0; i < 2; i++) {
        try {
          const doc = await db.collection("users").doc(user.uid).get();
          if (doc.exists) {
            role = doc.data().role;
            break;
          }
        } catch (error) {
          if (i === 0) await new Promise(resolve => setTimeout(resolve, 1000));
        }
      }

      if (!role || role !== "admin") {
        console.log("Not admin, redirecting");
        if (role === "guest") {
          window.location.href = "../Guest/dashboard_guest.php";
        } else {
          window.location.href = "../index.php";
        }
        return;
      }

      // Success
      localStorage.setItem("userUID", user.uid);
      localStorage.setItem("userRole", role);
      
      if (loadingScreen) loadingScreen.style.display = 'none';
      if (mainContent) mainContent.style.display = 'block';
      
      authCheckCompleted = true;

    } catch (error) {
      console.error("Auth error:", error);
      window.location.href = "../index.php";
    }
  });

  // === Logout Function ===
  async function logoutUser() {
    try {

      // Tandai logout baru saja dilakukan
      sessionStorage.setItem('justLoggedOut', '1');

      // Hapus semua cache user
      localStorage.clear();
      sessionStorage.clear();

      // Reset variabel global kalau ada
      if (typeof currentUser !== "undefined") currentUser = null;
      if (typeof currentUserRole !== "undefined") currentUserRole = null;

      // Firebase sign out
      await firebase.auth().signOut();
      console.log("Logout successful");

      // Redirect ke index (karena dari folder Admin)
      window.location.href = "../index.php";
    } catch (error) {
      console.error("Logout error:", error);

      // Force clear meskipun gagal
      localStorage.clear();
      sessionStorage.clear();

      window.location.href = "../index.php";
    }
  }

  // === UI Handlers ===
  document.addEventListener('DOMContentLoaded', function() {
    // Hamburger Menu
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav-menu');
    if (hamburger && navMenu) {
      hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
      });
    }

    // Drag & Drop Upload
    const dropArea = document.getElementById('drop-area');
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('preview-image');
    const filenameDisplay = document.getElementById('filename');

    if (dropArea && input) {
      dropArea.addEventListener('click', () => input.click());
      
      input.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = e => {
            if (preview) preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            if (filenameDisplay) filenameDisplay.textContent = file.name;
          };
          reader.readAsDataURL(file);
        }
      });

      dropArea.addEventListener("dragover", e => {
        e.preventDefault();
        dropArea.classList.add("dragging");
      });

      dropArea.addEventListener("dragleave", () => {
        dropArea.classList.remove("dragging");
      });

      dropArea.addEventListener("drop", e => {
        e.preventDefault();
        dropArea.classList.remove("dragging");
        input.files = e.dataTransfer.files;
        input.dispatchEvent(new Event("change"));
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
</script>
</body>
</html>
