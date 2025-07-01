// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Mobile menu toggle (would be added when implementing mobile view)
const mobileMenuToggle = document.createElement('div');
mobileMenuToggle.className = 'mobile-menu-toggle';
mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
document.querySelector('header .container').appendChild(mobileMenuToggle);

mobileMenuToggle.addEventListener('click', () => {
    document.querySelector('nav').classList.toggle('active');
});

// Animation on scroll
window.addEventListener('scroll', () => {
    const scrollPosition = window.scrollY;
    
    // Add header shadow when scrolling
    if (scrollPosition > 50) {
        document.querySelector('header').style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
    } else {
        document.querySelector('header').style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    }
});

// Sample function to handle KV upload (would be connected to backend in real implementation)
function handleKVUpload(file) {
    console.log('File uploaded:', file.name);
    // In a real implementation, this would upload to a server
    return Promise.resolve({
        success: true,
        message: 'KV berhasil diunggah!',
        pointsEarned: 10
    });
}
// Menghilangkan loader saat halaman selesai dimuat
window.addEventListener("load", function () {
    const loader = document.querySelector(".loader-wrapper");
    if (loader) {
        loader.style.display = "none";
    }
});

// Menampilkan modal login
document.querySelector('a[href="#login"]').addEventListener('click', function (e) {
  e.preventDefault();
  document.getElementById("login-modal").classList.remove("hidden");
});

// Menampilkan modal daftar
document.querySelector('a[href="#daftar"]').addEventListener('click', function (e) {
  e.preventDefault();
  document.getElementById("register-modal").classList.remove("hidden");
});

// Menutup modal
function closeModal(modalId) {
  document.getElementById(modalId).classList.add("hidden");
}

