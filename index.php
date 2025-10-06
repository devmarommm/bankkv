<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank KV - Platform Key Visual Premium</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="loader-wrapper">
        <div class="loader">
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>

    <header>
        <div class="container">
            <div class="logo">
                <img src="images/logo.png" alt="Bank KV Logo">
                <h1>Bank<span>KV</span></h1>
            </div>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <!-- LOGIN MODAL -->
    <div id="login-modal" class="modal hidden">
    <div class="modal-box">
        <span class="close-btn" onclick="closeModal('login-modal')">&times;</span>
        <h2>Masuk ke <span style="color: #2575FC;">Bank</span><strong>KV</strong></h2>

        <form onsubmit="event.preventDefault(); loginUser();">
        <input type="email" id="login-email" placeholder="Email" required>
        <input type="password" id="login-password" placeholder="Password" required>
        <button type="submit" class="btn btn-primary">Masuk</button>
        </form>
    </div>
    </div>

    <!-- REGISTER MODAL -->
    <div id="register-modal" class="modal hidden">
    <div class="modal-box">
        <span class="close-btn" onclick="closeModal('register-modal')">&times;</span>
        <h2>Buat Akun Bank KV</h2>
        
        <form onsubmit="event.preventDefault(); registerUser();">
        <input type="text" id="register-name" placeholder="Nama Lengkap" required>
        <input type="email" id="register-email" placeholder="Email" required>
        <input type="password"id="register-password" placeholder="Password" required>
        <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
    </div>
    </div>

    <section id="hero">
        <div class="container">
        
            <!-- Kiri: teks utama -->
            <div class="hero-content">
                <h1 class="hero-title">
                    Tempat <span class="typing-effect">Premium</span> untuk Aset Visual Anda
                </h1>
                <p class="hero-subtitle">
                    Simpan dan temukan key visual berkualitas tinggi untuk referensi proyek kreatif Anda
                </p>

                <div class="hero-cta">
                    <a href="javascript:void(0);" id="btn-register" class="btn btn-primary btn-lg">Mulai Sekarang <i class="fas fa-arrow-right"></i></a>
                    <a href="javascript:void(0);" id="btn-demo" class="btn btn-outline btn-lg">Lihat Demo <i class="fas fa-play"></i></a>
                </div>

                <!-- Modal Demo tetap -->
                <div id="demo-modal" class="modal hidden demo-modal">
                    <div class="modal-box">
                        <span class="close-btn" onclick="closeModal('demo-modal')">&times;</span>
                            <h2>Cara Kerja Bank KV</h2>
                            <ol class="demo-steps-popup">
                                <li><strong>1. Daftar atau Masuk</strong>: Login sebagai Guest/Admin</li>
                                <li><strong>2. Upload KV</strong>: Unggah aset visual kamu</li>
                                <li><strong>3. Verifikasi</strong>: Admin memeriksa dan menyetujui</li>
                                <li><strong>4. Cari KV</strong>: Temukan inspirasi visual dari banyak kategori</li>
                            </ol>
                    </div>
                </div>

                <div class="hero-stats">
                    <div class="stat-item">
                        <h3>10K+</h3>
                        <p>KV Tersedia</p>
                    </div>
                    <div class="stat-item">
                        <h3>5K+</h3>
                        <p>Pengguna</p>
                    </div>
                    <div class="stat-item">
                        <h3>99%</h3>
                        <p>Kepuasan</p>
                    </div>
                </div>
                </div>

            <!-- Kanan: FORM (ganti gambar hero) -->
            <div class="auth-forms">
                <div class="auth-card">
                    <!-- Login Form -->
                    <form id="login-form" onsubmit="event.preventDefault(); loginUser();">
                        <h2>Masuk ke <span style="color:#2575FC;">Bank</span><strong>KV</strong></h2>
                        <input type="email" id="login-email" placeholder="Email" required>
                        <input type="password" id="login-password" placeholder="Password" required>
                        <button type="submit" class="btn btn-primary">Masuk</button>
                        <div class="auth-toggle">
                            Belum punya akun? <a onclick="showRegister()">Daftar</a>
                        </div>
                    </form>

                    <!-- Register Form (Hidden by default) -->
                    <form id="register-form" class="hidden" onsubmit="event.preventDefault(); registerUser();">
                        <h2>Buat Akun Bank KV</h2>
                        <input type="text" id="register-name" placeholder="Nama Lengkap" required>
                        <input type="email" id="register-email" placeholder="Email" required>
                        <input type="password" id="register-password" placeholder="Password" required>
                        <button type="submit" class="btn btn-primary">Daftar</button>
                        <div class="auth-toggle">
                            Sudah punya akun? <a onclick="showLogin()">Masuk</a>
                        </div>
                    </form>
                    <!-- Kontainer logout (kosong dulu, diisi JS kalau user login) -->
                    <div id="logout-section" class="hidden"></div>
                </div>
                    <!-- Floating Sample Images -->
                    <div class="floating-card card-1">
                        <img src="images/sample-1.png" alt="Sample 1">
                    </div>
                    <div class="floating-card card-2">
                        <img src="images/sample-2.png" alt="Sample 2">
                    </div>
                    <div class="floating-card card-3">
                        <img src="images/sample-3.png" alt="Sample 3">
                    </div>
            </div>
        </div>
    </section>

    <!--- footer --->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col footer-about">
                    <div class="logo">
                        <img src="images/logo.png" alt="Bank KV Logo">
                        <h1>Bank<span>KV</span></h1>
                    </div>
                    <p class="footer-about-text">Platform penyimpanan dan pertukaran key visual premium untuk mendukung kreativitas tanpa batas.</p>
                    
                <div class="footer-col">
                    <h3 class="footer-title">Kontak</h3>
                    <ul class="footer-contact">
                        <li><i class="fas fa-map-marker-alt"></i> Jl. Dr. Ir. H. Soekarno No.175, Klampis Ngasem</li>
                        <li><i class="fas fa-phone-alt"></i> +62 857-0601-6306</li>
                        <li><i class="fas fa-envelope"></i> Admin@bankkv.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 BankKV. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="#">Terms</a>
                    <a href="#">Privacy</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

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
            storageBucket: "bank-kv-1910f.firebasestorage.app",
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

        // === Toggle Form Login / Register ===
        function showLogin() {
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
        }
        function showRegister() {
            document.getElementById('register-form').classList.remove('hidden');
            document.getElementById('login-form').classList.add('hidden');
        }

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
            try {
                const doc = await db.collection("users").doc(uid).get();
                if (doc.exists) {
                    return doc.data().role;
                }
                throw new Error("User data not found in Firestore");
            } catch (error) {
                console.error("Error validating user role:", error);
                return null;
            }
        }

        // === Fungsi Registrasi (Guest) ===
        async function registerUser() {
            const name = document.getElementById('register-name').value.trim();
            const email = document.getElementById('register-email').value.trim();
            const password = document.getElementById('register-password').value;

            if (!name || !email || !password) {
                alert("Semua kolom harus diisi.");
                return;
            }

            try {
                // Create Firebase Auth user
                const userCredential = await firebase.auth().createUserWithEmailAndPassword(email, password);
                const user = userCredential.user;

                // Save to Firestore with retry mechanism
                let retryCount = 0;
                const maxRetries = 3;
                
                while (retryCount < maxRetries) {
                    try {
                        await db.collection("users").doc(user.uid).set({
                            name: name,
                            email: email,
                            role: "guest",
                            createdAt: firebase.firestore.FieldValue.serverTimestamp()
                        });
                        
                        // Tunggu sebentar untuk memastikan data tersimpan
                        await new Promise(resolve => setTimeout(resolve, 1000));
                        
                        // Verify data was saved
                        const doc = await db.collection("users").doc(user.uid).get();
                        if (doc.exists) {
                            alert("Registrasi berhasil! Silakan login.");
                            showLogin();
                            return;
                        }
                        throw new Error("Data verification failed");
                        
                    } catch (firestoreError) {
                        retryCount++;
                        if (retryCount >= maxRetries) {
                            throw firestoreError;
                        }
                        await new Promise(resolve => setTimeout(resolve, 1000));
                    }
                }
                
            } catch (error) {
                console.error("Registration error:", error);
                alert("Registrasi gagal: " + error.message);
            }
        }

        // === Fungsi Login ===
        async function loginUser() {
            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;

            if (!email || !password) {
                alert("Email dan password harus diisi.");
                return;
            }

            try {
                // Firebase Authentication
                const userCredential = await firebase.auth().signInWithEmailAndPassword(email, password);
                const user = userCredential.user;

                // Wait a moment for potential Firestore sync
                await new Promise(resolve => setTimeout(resolve, 500));

                // Get role from Firestore with retry
                let role = null;
                let attempts = 0;
                const maxAttempts = 5;

                while (attempts < maxAttempts && !role) {
                    try {
                        const doc = await db.collection("users").doc(user.uid).get();
                        if (doc.exists) {
                            role = doc.data().role;
                            break;
                        }
                        throw new Error("Document not found");
                    } catch (error) {
                        attempts++;
                        console.log(`Attempt ${attempts} failed:`, error.message);
                        if (attempts < maxAttempts) {
                            await new Promise(resolve => setTimeout(resolve, 1000));
                        }
                    }
                }

                if (!role) {
                    throw new Error("Tidak dapat mengambil data role pengguna. Silakan coba lagi.");
                }

                // Update global variables
                currentUser = user;
                currentUserRole = role;

                // Update localStorage
                updateUserLocalStorage(user.uid, role);

                // Redirect ke dashboard guest (admin juga masuk sini dulu)
                const redirectURL = "Guest/dashboard_guest.php";
                console.log(`Login successful. Role: ${role}, Redirecting: ${redirectURL}`);

                setTimeout(() => {
                    window.location.href = redirectURL;
                }, 300);

            } catch (error) {
                console.error("Login error:", error);
                alert("Login gagal: " + error.message);
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

        // === Cek Status Login (Autologin) ===
        firebase.auth().onAuthStateChanged(async (user) => {
            console.log("Auth state changed:", user ? user.uid : "No user");

            // Jika baru saja logout dari halaman lain, hentikan proses auto-sync sementara
            if (sessionStorage.getItem('justLoggedOut')) {
                console.log('Detected justLoggedOut flag — skipping auth handling to avoid race condition.');
                sessionStorage.removeItem('justLoggedOut');
                authStateInitialized = true; // pastikan flag inisialisasi tetap true
                return;
            }
            
            const navButtons = document.getElementById('nav-buttons');
            const loginForm = document.getElementById("login-form");
            const registerForm = document.getElementById("register-form");
            const logoutSection = document.getElementById("logout-section");

            if (user) {
                try {
                    // Validate role from Firestore
                    const role = await validateUserRole(user.uid);
                    
                    if (!role) {
                        console.error("Invalid user role, signing out");
                        await firebase.auth().signOut();
                        return;
                    }

                    // Update global variables
                    currentUser = user;
                    currentUserRole = role;

                    // Sync localStorage
                    const storedRole = localStorage.getItem("userRole");
                    const storedUID = localStorage.getItem("userUID");

                    if (storedRole !== role || storedUID !== user.uid) {
                        console.log("Syncing localStorage with Firestore data");
                        updateUserLocalStorage(user.uid, role);
                    }

                    // Get user name from Firestore
                    let userName = user.email;
                    try {
                        const doc = await db.collection("users").doc(user.uid).get();
                        if (doc.exists) {
                            userName = doc.data().name || user.email;
                        }
                    } catch (error) {
                        console.log("Could not fetch user name:", error);
                    }

                    const dashboardURL = (role === "admin") 
                        ? "Admin/dashboard_admin.php" 
                        : "Guest/dashboard_guest.php";

                    // Update navigation
                    if (navButtons) {
                        navButtons.innerHTML = `
                            <a href="${dashboardURL}" class="btn btn-primary" style="margin-right: 10px;">Ke Dashboard</a>
                            <a href="#" onclick="logoutUser()" class="btn btn-outline">Logout</a>
                        `;
                    }

                    // Update auth forms
                    if (loginForm && registerForm && logoutSection) {
                        loginForm.classList.add("hidden");
                        registerForm.classList.add("hidden");
                        logoutSection.classList.remove("hidden");
                        logoutSection.innerHTML = `
                            <h2>Selamat datang, ${userName}!</h2>
                            <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">Role: ${role}</p>
                            <a href="${dashboardURL}" class="btn btn-primary" style="margin-bottom:10px;">Ke Dashboard</a>
                            <button onclick="logoutUser()" class="btn btn-outline">Logout</button>
                        `;
                    }

                } catch (error) {
                    console.error("Error in auth state handler:", error);
                }
                
            } else {
                // User is not logged in
                currentUser = null;
                currentUserRole = null;
                clearUserLocalStorage();

                // Update navigation
                if (navButtons) {
                    navButtons.innerHTML = `
                        <a href="#" onclick="showLogin()" class="btn btn-outline" style="margin-right: 10px;">Masuk</a>
                        <a href="#" onclick="showRegister()" class="btn btn-primary">Daftar</a>
                    `;
                }

                // Update auth forms
                if (loginForm && registerForm && logoutSection) {
                    logoutSection.classList.add("hidden");
                    loginForm.classList.remove("hidden");
                    registerForm.classList.add("hidden");
                }
            }

            authStateInitialized = true;
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
    <script src="script.js"></script>
</body>
</html>