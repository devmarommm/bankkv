<?php
session_start();
include '../Admin/koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest'; // default guest kalau belum ada session

    // kalau bukan admin, tambahin views
    if ($role !== 'admin') {
        $update = "UPDATE kv_folders SET views = views + 1 WHERE id = $id";
        mysqli_query($conn, $update);
    }

    // Ambil link asli KV buat redirect
    $query = "SELECT link FROM kv_folders WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        header("Location: " . $row['link']);
        exit();
    }
}

header("Location: dashboard_guest.php");
exit();
?>
