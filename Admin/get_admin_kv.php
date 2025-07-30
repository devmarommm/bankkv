<?php
include 'koneksi.php';

$result = mysqli_query($conn, "SELECT * FROM kv_folders ORDER BY tanggal DESC");

while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>
    <td>{$row['nama']}</td>
    <td>{$row['kategori']}</td>
    <td>{$row['tag']}</td>
    <td>{$row['kreator']}</td>
    <td>{$row['tanggal']}</td>
    <td><a href='{$row['link']}' target='_blank'>Link</a></td>
    <td>
      <form method='POST' action='hapus_kv.php'>
        <input type='hidden' name='id' value='{$row['id']}'>
        <button type='submit'>Hapus</button>
      </form>
    </td>
  </tr>";
}
?>
