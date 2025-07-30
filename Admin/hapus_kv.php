<?php
include 'koneksi.php';

$id = $_POST['id'];
mysqli_query($conn, "DELETE FROM kv_folders WHERE id = $id");

header("Location: dashboard_admin.php");
?>
