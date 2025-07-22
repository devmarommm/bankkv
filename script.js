// Smooth scrolling untuk anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');
    const navButtons = document.getElementById('nav-buttons');

    hamburger.addEventListener('click', function() {
        hamburger.classList.toggle('active');
        navLinks.classList.toggle('active');
        navButtons.classList.toggle('active');
    });
});

// Header shadow saat scroll
window.addEventListener('scroll', () => {
    const header = document.querySelector('header');
    const scrollPosition = window.scrollY;
    header.style.boxShadow = scrollPosition > 50
        ? '0 4px 12px rgba(0, 0, 0, 0.1)'
        : '0 2px 10px rgba(0, 0, 0, 0.1)';
});

// Loader hilang saat halaman selesai dimuat
window.addEventListener("load", () => {
    const loader = document.querySelector(".loader-wrapper");
    if (loader) loader.style.display = "none";
});

// Fungsi buka modal
function openModal(id) {
    document.getElementById(id).classList.remove("hidden");
}

// Fungsi tutup modal
function closeModal(id) {
    document.getElementById(id).classList.add("hidden");
}

// Fungsi Login
function loginUser() {
    const email = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;

    if (!email || !password) {
        alert("Email dan password harus diisi.");
        return;
    }

    firebase.auth().signInWithEmailAndPassword(email, password)
        .then((userCredential) => {
            const user = userCredential.user;
            const redirectURL = email.includes('admin') ? "Admin/dashboard_admin.html" : "Guest/dashboard_guest.html";
            window.location.href = redirectURL;
        })
        .catch((error) => {
            alert("Login gagal: " + error.message);
        });
}

// Fungsi Daftar
function registerUser() {
    const name = document.getElementById('register-name').value.trim();
    const email = document.getElementById('register-email').value.trim();
    const password = document.getElementById('register-password').value;

    if (!name || !email || !password) {
        alert("Semua kolom harus diisi.");
        return;
    }

    firebase.auth().createUserWithEmailAndPassword(email, password)
        .then((userCredential) => {
            return userCredential.user.updateProfile({ displayName: name });
        })
        .then(() => {
            alert("Registrasi berhasil! Silakan login.");
            closeModal('register-modal');
            openModal('login-modal');
        })
        .catch((error) => {
            alert("Registrasi gagal: " + error.message);
        });
}

// Simulasi upload KV
function handleKVUpload(file) {
    console.log('File uploaded:', file.name);
    return Promise.resolve({
        success: true,
        message: 'KV berhasil diunggah!',
        pointsEarned: 10
    });
}

// Firebase config
const firebaseConfig = {
    apiKey: "AIzaSyA3wCPXQkoIpf_sYoVNrseoTWp5heH0VAE",
    authDomain: "bank-kv-1910f.firebaseapp.com",
    projectId: "bank-kv-1910f",
    storageBucket: "bank-kv-1910f.firebasestorage.app",
    messagingSenderId: "87795172113",
    appId: "1:87795172113:web:08b077dbfbdee9adbfc2b0",
    measurementId: "G-84VE29GC3W"
};

// Mobile animasi menu
document.querySelectorAll('.nav-links li a').forEach(link => {
    link.addEventListener('click', function() {
      document.querySelectorAll('.nav-links li a').forEach(el => el.classList.remove('active'));
      this.classList.add('active');
    });
});

// Event Listener tombol #btn-register to btn-mulai sekarang
document.addEventListener("DOMContentLoaded", function () {
    const btnRegister = document.getElementById('btn-register');
    if (btnRegister) {
        btnRegister.addEventListener('click', function () {
            openModal('register-modal');
        })
    }
});

// Event Listener Tombol #demo
document.addEventListener("DOMContentLoaded", function () {
    const btnDemo = document.getElementById('btn-demo');
    if (btnDemo) {
        btnDemo.addEventListener('click', function () {
            openModal('demo-modal');
        });
    }
});

firebase.initializeApp(firebaseConfig);