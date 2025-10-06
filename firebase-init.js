// Firebase Config & Init
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

// Logout Function (bisa dipanggil dari semua halaman)
function logoutUser() {
    // Tandai logout
    sessionStorage.setItem('justLoggedOut', '1');

    // Hapus cache user
    try {
        localStorage.removeItem("userUID");
        localStorage.removeItem("userRole");
        sessionStorage.removeItem("userRole");
    } catch(e) {}

    // Sign out Firebase
    firebase.auth().signOut().then(() => {
        // Tunggu state kosong sebelum redirect
        const waitForSignout = new Promise((resolve) => {
            const unsub = firebase.auth().onAuthStateChanged(u => {
                if (!u) { unsub(); resolve(); }
            });
            setTimeout(() => { unsub(); resolve(); }, 2000); // fallback
        });
        waitForSignout.then(() => {
            window.location.href = "../index.php";
        });
    }).catch(err => {
        console.error("Logout error:", err);
        window.location.href = "../index.php";
    });
}
