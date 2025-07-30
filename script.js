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

// Simulasi upload KV
function handleKVUpload(file) {
    console.log('File uploaded:', file.name);
    return Promise.resolve({
        success: true,
        message: 'KV berhasil diunggah!',
        pointsEarned: 10
    });
}

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

// Drag and Drop Dashboard_Admin
const dropArea = document.getElementById('drop-area');
const imageInput = document.getElementById('imageInput');

dropArea.addEventListener('dragover', (e) => {
  e.preventDefault();
  dropArea.classList.add('highlight');
});

dropArea.addEventListener('dragleave', () => {
  dropArea.classList.remove('highlight');
});

dropArea.addEventListener('drop', (e) => {
  e.preventDefault();
  imageInput.files = e.dataTransfer.files;
  dropArea.classList.remove('highlight');
});

/*=== Js Animasi Typing ===*/
document.addEventListener("DOMContentLoaded", () => {
    const premiumText = document.querySelector(".typing-effect");

    const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
        // Reset animasi agar bisa diputar ulang
        premiumText.classList.remove("typing-effect");
        void premiumText.offsetWidth; // trigger reflow
        premiumText.classList.add("typing-effect");
    }
    });
}, {
    threshold: 0.6 // aktif saat 60% teks masuk layar
});

observer.observe(premiumText);
});