<?php
header('Content-Type: application/json');

// --- Konfigurasi koneksi database ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bankkv";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]);
    exit;
}

// Ambil data folder KV
$sql = "SELECT id, nama, kategori, tanggal FROM kv_folders";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
