// Firebase Config & Init
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
            window.location.href = "index.php";
        });
    }).catch(err => {
        console.error("Logout error:", err);
        window.location.href = "index.php";
    });
}
