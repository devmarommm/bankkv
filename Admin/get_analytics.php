<?php
include 'koneksi.php';
header('Content-Type: application/json');

// Hitung total KV
$totalKVQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kv_folders");
$totalKV = mysqli_fetch_assoc($totalKVQuery)['total'] ?? 0;

// Ambil KV terpopuler berdasarkan views tertinggi
$populerQuery = mysqli_query($conn, "SELECT kategori, tag, views FROM kv_folders ORDER BY views DESC LIMIT 1");
$kvPopuler = mysqli_fetch_assoc($populerQuery) ?: ['kategori'=>'-', 'tag'=>'-', 'views'=>0];

// Ambil data 1 bulan terakhir untuk kategori dominan
$currentMonth = date('m');
$currentYear = date('Y');
$kategoriQuery = mysqli_query($conn, "
  SELECT kategori, COUNT(*) AS jumlah
  FROM kv_folders
  WHERE MONTH(tanggal) = '$currentMonth' AND YEAR(tanggal) = '$currentYear'
  GROUP BY kategori
");
$kategoriData = [];
$totalBulanIni = 0;
while ($row = mysqli_fetch_assoc($kategoriQuery)) {
  $kategoriData[$row['kategori']] = (int)$row['jumlah'];
  $totalBulanIni += (int)$row['jumlah'];
}
arsort($kategoriData);
$dominantKategori = !empty($kategoriData) ? array_key_first($kategoriData) : '-';
$dominantPersen = $totalBulanIni > 0 ? round(($kategoriData[$dominantKategori] / $totalBulanIni) * 100, 1) : 0;

// Ambil semua data buat chart (untuk perkembangan & kategori)
$dataQuery = mysqli_query($conn, "SELECT kategori, tanggal FROM kv_folders ORDER BY tanggal ASC");
$kvFolders = [];
while ($row = mysqli_fetch_assoc($dataQuery)) {
  $kvFolders[] = $row;
}

// Kirim hasil JSON ke frontend
echo json_encode([
  "total_kv" => (int)$totalKV,
  "kv_populer" => $kvPopuler,
  "kategori_dominan" => [
    "kategori" => $dominantKategori,
    "persen" => $dominantPersen
  ],
  "kategori_data" => $kategoriData,
  "data_kv" => $kvFolders
]);
?>
