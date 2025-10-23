<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bank KV - Aset Visual Premium</title>
  <link rel="stylesheet" href="style-new.css"/>
  <script src="script.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Nunito:wght@800&display=swap" rel="stylesheet">
</head>
<body>
  <header>
      <div class="logo">
        <img src="images/logo.png" alt="Logo Bank KV" />
      </div>
  </header>

  <section id="hero" class="hero-modern"> 
    <div class="container hero-content-wrapper">
      <div class="hero-content">
        <h1 class="hero-title">
          Tempat <span class="typing-effect">Premium</span> untuk Aset Visual Anda
        </h1>
        <p class="hero-subtitle">
          Simpan dan temukan key visual berkualitas tinggi untuk referensi proyek kreatif Anda
        </p>
        <div class="hero-buttons">
        <a href="#" class="btn btn-primary" id="btn-register">Buat Akun</a>
          <a href="#" class="btn btn-outline" id="btn-admin">Admin</a>
        </div>
      </div>

      <div class="hero-login">
        <div class="login-card">
          <h2>Halo!</h2>
            <form id="login-form" onsubmit="return false;">
                <input type="email" name="email" id="login-email" placeholder="Email" required /> 
                <input type="password" name="password" id="login-password" placeholder="Kata Sandi" required />
                <button type="submit" class="btn btn-login-primary" onclick="loginUser()">Login</button>
            </form>

            <div id="logout-section" class="hidden"></div>

            <div class="login-footer"> 
                <a href="#" id="btn-register-footer" onclick="openRegisterModal()">Belum Punya Akun</a>
                <a href="#" id="btn-forgot-password">Lupa Password</a>
            </div>
        </div>
      </div>

    <div class="bg-circle circle-top-right"></div>
    <div class="bg-circle circle-bottom-right"></div>

        <div id="register-modal" class="modal hidden">
            <div class="modal-content">
                <div class="login-card" id="register-card">
                    <span class="close-button" onclick="closeRegisterModal()">&times;</span>
                    <h2>Daftar!</h2>
                    <form id="register-form-modal" onsubmit="return false;">
                        <input type="text" id="register-name" placeholder="Nama Lengkap" required />
                        <input type="email" id="register-email" placeholder="Email" required />
                        <input type="password" id="register-password" placeholder="Kata Sandi" required />
                        <button type="submit" class="btn btn-login-primary" onclick="registerUser()">Daftar</button>
                        <p style="text-align: center; margin-top: 10px;">
                            <a href="#" onclick="closeRegisterModal()" style="color: #fff; font-size: 0.9em;">Sudah Punya Akun?</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
  </section>
      <!-- Firebase SDK -->
      <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
      <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js"></script>
      <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-firestore-compat.js"></script>
      <script>
        // === Konfigurasi Firebase ===
        const firebaseConfig = {
            apiKey: "AIzaSyA3wCPXQkoIpf_sYoVNrseoTWp5heH0VAE",
            authDomain: "bank-kv-1910f.firebaseapp.com",
            projectId: "bank-kv-1910f",
            storageBucket: "bank-kv-1910f.appspot.com",
            messagingSenderId: "87795172113",
            appId: "1:87795172113:web:08b077dbfbdee9adbfc2b0",
            measurementId: "G-84VE29GC3W"
        };
        firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        // === VARIABEL GLOBAL UNTUK TRACKING STATE ===
        let currentUser = null;
        let currentUserRole = null;
        let authStateInitialized = false;

        // === Fungsi Helper untuk Update localStorage ===
        function updateUserLocalStorage(uid, role) {
            localStorage.setItem("userUID", uid);
            localStorage.setItem("userRole", role);
            localStorage.setItem("loginTime", Date.now());
        }

        // === Fungsi Helper untuk Clear localStorage ===
        function clearUserLocalStorage() {
            localStorage.removeItem("userUID");
            localStorage.removeItem("userRole");
            localStorage.removeItem("loginTime");
        }

        // === Fungsi Helper untuk Validasi Role dari Firestore ===
        async function validateUserRole(uid) {
        if (!uid) return null;
            try {
                const doc = await db.collection("users").doc(uid).get();
                if (doc.exists && doc.data().role) {
                    return doc.data().role;
                }
                return null;
            } catch (e) {
                console.error("Error fetching user role:", e);
                return null;
            }
        }

        // Menyimpan UID dan Role ke LocalStorage
        function updateUserLocalStorage(uid, role) {
            localStorage.setItem("userUID", uid);
            localStorage.setItem("userRole", role);
        }

        // Menghapus data user dari LocalStorage
        function clearUserLocalStorage() {
            localStorage.removeItem("userUID");
            localStorage.removeItem("userRole");
        }

        // === Fungsi Modal Register Baru ===
        function openRegisterModal() {
            const modal = document.getElementById('register-modal');
            modal.classList.remove('hidden');
            // Tambahkan class visible setelah delay kecil untuk trigger transisi
            setTimeout(() => {
                modal.classList.add('visible');
            }, 10);
        }

        // === Tombol Buka Modal Register ===
        document.addEventListener("DOMContentLoaded", () => {
        const btnRegister = document.getElementById("btn-register");
        if (btnRegister) {
            btnRegister.addEventListener("click", (e) => {
            e.preventDefault(); // cegah reload halaman
            openRegisterModal(); // buka popup
            });
        }
        });

        function closeRegisterModal() {
            const modal = document.getElementById('register-modal');
            
            // Hapus class visible untuk trigger transisi
            modal.classList.remove('visible');
            
            // Sembunyikan setelah transisi selesai
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300); // Sesuaikan dengan durasi transisi CSS
        }

                // === Fungsi Register User dengan Firebase ===
        async function registerUser() {
        const name = document.getElementById("register-name").value.trim();
        const email = document.getElementById("register-email").value.trim();
        const password = document.getElementById("register-password").value;

        if (!name || !email || !password) {
            alert("Semua kolom wajib diisi!");
            return;
        }

        try {
            // Buat akun di Firebase Authentication
            const userCredential = await firebase.auth().createUserWithEmailAndPassword(email, password);
            const user = userCredential.user;

            // Simpan data user ke Firestore
            await db.collection("users").doc(user.uid).set({
            name: name,
            email: email,
            role: "guest", // default role
            createdAt: firebase.firestore.FieldValue.serverTimestamp(),
            });

            alert("Pendaftaran berhasil! Selamat datang, " + name);

            // Tutup modal setelah sukses
            closeRegisterModal();

            // Update local storage & redirect ke dashboard guest
            updateUserLocalStorage(user.uid, "guest");
            window.location.replace("Guest/dashboard_guest.php");

        } catch (error) {
            console.error("Register error:", error);
            let msg = error.message;
            if (error.code === "auth/email-already-in-use") msg = "Email sudah terdaftar.";
            else if (error.code === "auth/weak-password") msg = "Kata sandi terlalu lemah (minimal 6 karakter).";
            alert("Gagal mendaftar: " + msg);
        }
        }

        // === Fungsi Login - PERBAIKAN TOTAL ===
        async function loginUser() {
            // ... (Logika validasi email/password) ...
            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;

            if (!email || !password) {
                alert("Email dan password harus diisi.");
                return;
            }

            try {
                // Hapus flag logout
                sessionStorage.removeItem('justLoggedOut');
                
                // 1. Firebase Authentication
                const userCredential = await firebase.auth().signInWithEmailAndPassword(email, password);
                const user = userCredential.user;

                // 2. Get role dari Firestore dengan retry
                let role = null;
                let attempts = 0;
                const maxAttempts = 5;

                while (attempts < maxAttempts && !role) {
                    // Menggunakan fungsi validateUserRole yang kita definisikan di atas
                    role = await validateUserRole(user.uid); 
                    if (role) break;

                    attempts++;
                    console.log(`Attempt ${attempts} failed to get role. Retrying in 1s...`);
                    if (attempts < maxAttempts) {
                        await new Promise(resolve => setTimeout(resolve, 1000)); 
                    }
                }

                if (!role) {
                    await firebase.auth().signOut(); 
                    throw new Error("Tidak dapat mengambil data role pengguna. Akses dibatalkan.");
                }

                // 3. Sinkronisasi data ke LocalStorage (WAJIB sebelum redirect)
                updateUserLocalStorage(user.uid, role);

                // 4. Redirection TEGAS & LANGSUNG
                const redirectURL = (role === "admin") 
                ? "Admin/dashboard_admin.php" 
                : "Guest/dashboard_guest.php";

                console.log(`Login successful. Role: ${role}, Redirecting: ${redirectURL}`);

                // Ganti window.location.href dengan window.location.replace()
                window.location.replace(redirectURL); 

            } catch (error) {
                // ... (Logika error handling) ...
                console.error("Login error:", error);
                let errorMessage = error.message;
                if (error.code === 'auth/user-not-found' || error.code === 'auth/wrong-password') {
                    errorMessage = "Email atau Kata Sandi salah. Mohon periksa kembali.";
                }
                alert("Login gagal: " + errorMessage);
            }
        }

        // === Fungsi Logout ===
        async function logoutUser() {
        try {
            // Hapus semua cache user
            localStorage.clear();
            sessionStorage.clear();

            // Reset variabel global kalau ada
            if (typeof currentUser !== "undefined") currentUser = null;
            if (typeof currentUserRole !== "undefined") currentUserRole = null;

            // Firebase sign out
            await firebase.auth().signOut();
            console.log("Logout successful");

            // Redirect ke index
            window.location.href = "index.php";
        } catch (error) {
            console.error("Logout error:", error);

            // Force clear meskipun gagal
            localStorage.clear();
            sessionStorage.clear();

            window.location.href = "index.php";
        }
        }

        // === Cek Status Login (Autologin) - PERBAIKAN TOTAL ===
        firebase.auth().onAuthStateChanged(async (user) => {
            // ... (Logika justLoggedOut, navButtons, loginForm, logoutSection) ...
            // Note: Pastikan DOM element ID ini benar:
            const navButtons = document.getElementById('hero-buttons'); // Cek ID di HTML, biasanya 'nav-buttons' atau 'hero-buttons'
            const loginForm = document.getElementById("login-form");
            const logoutSection = document.getElementById("logout-section");

            if (user) {
                // 1. Ambil Role (dari localStorage dulu, lalu dari server)
                let role = localStorage.getItem("userRole") || await validateUserRole(user.uid); 
                
                if (!role) {
                    console.error("User logged in but role is missing/invalid. Signing out.");
                    await firebase.auth().signOut();
                    clearUserLocalStorage();
                    return;
                }
                updateUserLocalStorage(user.uid, role); 

                // ===============================================
                // *** CRITICAL FIX: PAKSA REDIRECT DI HALAMAN INDEX ***
                // ===============================================
                const dashboardURL = (role === "admin") 
                    ? "Admin/dashboard_admin.php" 
                    : "Guest/dashboard_guest.php";

                // Jika user terdeteksi login dan berada di halaman index.php (atau root)
                if (window.location.pathname.endsWith("index.php") || window.location.pathname === "/") {
                    console.log(`Auto-redirecting logged-in user to: ${dashboardURL}`);
                    window.location.replace(dashboardURL); 
                    return; // Hentikan semua eksekusi UI
                }
                // ===============================================

                // ... (Logika UI update seperti menampilkan "Selamat datang" HANYA jika redirect gagal)
                // ... (Ini hanya opsional di index.php, karena seharusnya sudah redirect)
                
            } else {
                // User is not logged in
                // ... (Logika clearUserLocalStorage, menampilkan form login, dll.) ...
                clearUserLocalStorage();
                if (loginForm && logoutSection) {
                    logoutSection.classList.add("hidden");
                    loginForm.classList.remove("hidden");
                }
            }
            // ... (authStateInitialized = true;) ...
        });

        // === Function to check auth state before navigation ===
        function checkAuthBeforeNavigation() {
            if (!authStateInitialized) {
                console.log("Auth state not initialized yet, waiting...");
                return false;
            }
            return true;
        }

        // === Debugging function ===
        function debugAuthState() {
            console.log("=== DEBUG AUTH STATE ===");
            console.log("Current User:", currentUser);
            console.log("Current Role:", currentUserRole);
            console.log("localStorage UID:", localStorage.getItem("userUID"));
            console.log("localStorage Role:", localStorage.getItem("userRole"));
            console.log("Auth Initialized:", authStateInitialized);
            console.log("========================");
        }

        // Add debug function to window for testing
        window.debugAuthState = debugAuthState;
      </script>
</body>
</html>