<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin | Bank KV</title>
  <link rel="stylesheet" href="../style.css">
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

<header>
  <div class="container">
    <div class="logo">
      <img src="../images/logo.png" alt="Bank KV Logo" height="40">
    </div>
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
    <nav id="nav-menu">
      <ul class="nav-links">
        <li><a href="../Guest/dashboard_guest.php" class="nav-link">Beranda</a></li>
        <li><a href="analitik.html" class="nav-link">Analitik KV</a></li>
        <li><a href="#" onclick="logoutUser()" class="btn btn-outline">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

<main class="dashboard-admin">
  <div class="welcome-box">
    <h1>Selamat datang, <span>Admin</span>!</h1>
    <p class="welcome-sub">Senang bertemu lagi denganmu hari ini</p>
  </div>
</main>

<!-- Form Upload -->
<div class="dashboard-admin">
  <h2 class="section-tittle">Upload Folder KV Baru</h2>
  <form action="upload_kv.php" method="POST" class="upload-form" enctype="multipart/form-data">
    <div class="form-grid">
      <div class="form-group">
        <input type="text" name="nama" placeholder="Nama Folder" required>
      </div>
      <div class="form-group">
        <input type="text" name="kategori" placeholder="Kategori (misal Promo, Event)" required>
      </div>
      <div class="form-group">
        <input type="text" name="tag" placeholder="Tag (misal: ramadan)">
      </div>
      <div class="form-group">
        <input type="text" name="kreator" placeholder="Nama Kreator" required>
      </div>
      <div class="form-group">
        <input type="date" name="tanggal" required>
      </div>
      <div class="form-group">
        <input type="text" name="link" placeholder="Link Google Drive Folder" required>
      </div>
      <div class="form-group">
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
    </div>
    <button type="submit">Upload</button>
  </form>
</div>

<!-- Tabel Data -->
<h2 class="section-tittle">Daftar Folder KV</h2>
<div class="table-wrapper">
  <table class="kv-table">
    <thead>
      <tr>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Tag</th>
        <th>Kreator</th>
        <th>Tanggal</th>
        <th>Link</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $result = mysqli_query($conn, "SELECT * FROM kv_folders ORDER BY tanggal DESC");
        while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <tr>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['kategori'] ?></td>
        <td><?= $row['tag'] ?></td>
        <td><?= $row['kreator'] ?></td>
        <td><?= $row['tanggal'] ?></td>
        <td><a href="<?= $row['link'] ?>" target="_blank">Link</a></td>
        <td>
          <form method="POST" action="hapus_kv.php">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
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
