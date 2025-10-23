<?php
include 'koneksi.php';

// Ambil data dari form
$nama      = $_POST['nama'] ?? '';
$campaign  = $_POST['campaign'] ?? '';
$kategori  = $_POST['kategori'] ?? '';
$source    = $_POST['source'] ?? '';
$tanggal   = $_POST['tanggal'] ?? '';
$link      = $_POST['link'] ?? '';

$imageName = ''; // Default jika tidak ada gambar diupload

// === Cek dan proses upload gambar ===
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $targetDir = 'uploads/'; // Pastikan folder uploads/ sudah ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Buat folder jika belum ada
    }

    // Hindari nama file ganda dengan menambahkan timestamp
    $originalName = basename($_FILES['image']['name']);
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $safeName = pathinfo($originalName, PATHINFO_FILENAME);
    $imageName = $safeName . '_' . time() . '.' . $ext;
    $targetFile = $targetDir . $imageName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        echo "Gagal mengupload gambar.";
        exit;
    }
}

// === Simpan data ke database ===
$query = "
    INSERT INTO kv_folders (nama, campaign, kategori, source, tanggal, link, image, views, created_at)
    VALUES (
        '" . mysqli_real_escape_string($conn, $nama) . "',
        '" . mysqli_real_escape_string($conn, $campaign) . "',
        '" . mysqli_real_escape_string($conn, $kategori) . "',
        '" . mysqli_real_escape_string($conn, $source) . "',
        '" . mysqli_real_escape_string($conn, $tanggal) . "',
        '" . mysqli_real_escape_string($conn, $link) . "',
        '" . mysqli_real_escape_string($conn, $imageName) . "',
        0,
        NOW()
    )
";

if (mysqli_query($conn, $query)) {
    header("Location: dashboard_admin.php?status=success");
    exit;
} else {
    echo "Gagal menyimpan data: " . mysqli_error($conn);
}
?>
