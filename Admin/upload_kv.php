<?php
include 'koneksi.php';

$nama     = $_POST['nama'];
$kategori = $_POST['kategori'];
$tag      = $_POST['tag'];
$kreator  = $_POST['kreator'];
$tanggal  = $_POST['tanggal'];
$link     = $_POST['link'];

$imageName = ''; // Default jika tidak ada gambar diupload

// Cek apakah file gambar diunggah
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $targetDir = 'uploads/'; // Pastikan folder ini sudah dibuat
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Buat folder jika belum ada
    }

    $imageName = basename($_FILES['image']['name']);
    $targetFile = $targetDir . $imageName;

    // Pindahkan file ke folder upload
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        echo "Gagal mengupload gambar.";
        exit;
    }
}

// Simpan data ke database, termasuk nama file gambar (jika ada)
$query = "INSERT INTO kv_folders (nama, kategori, tag, kreator, tanggal, link, image)
          VALUES ('$nama', '$kategori', '$tag', '$kreator', '$tanggal', '$link', '$imageName')";

if (mysqli_query($conn, $query)) {
    header("Location: dashboard_admin.php");
    exit;
} else {
    echo "Gagal menyimpan data: " . mysqli_error($conn);
}
?>
