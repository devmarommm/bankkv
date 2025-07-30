<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin | Bank KV</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <header>
    <div class="container">
    <div class="logo">
      <img src="../images/logo.png" alt="Bank KV Logo" height="40">
      <h1>Bank<span style="color:#2563eb;">KV</span></h1>
    </div>
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
    <nav id="nav-menu">
      <ul class="nav-links">
        <li><a href="../index.html" class="nav-link">Beranda</a></li>
        <li><a href="analitik.html" class="nav-link">Analitik KV</a></li>
      </ul>
    </nav>
    </div>
  </header>

  <div class="dashboard-admin">
    <h2 class="section-tittle">Upload Folder KV Baru</h2>
    <form action="upload_kv.php" method="POST" class="upload-form" enctype="multipart/form-data">
      <div class="form-grid">
        <div class="form-group">
          <input type="text" name="nama" placeholder="Nama Folder" required>
        </div>
        <div class="form-group">
          <input type="text" name="kategori" placeholder="Kategori (misal Promo, Event)" required>
        </div>
        <div class="form-group">
          <input type="text" name="tag" placeholder="Tag (misal: ramadan)">
        </div>
        <div class="form-group">
          <input type="text" name="kreator" placeholder="Nama Kreator" required>
        </div>
        <div class="form-group">
          <input type="date" name="tanggal" required>
        </div>
        <div class="form-group">
          <input type="text" name="link" placeholder="Link Google Drive Folder" required>
        </div>
        <div class="form-group">
          <label for="imageUpload" style="font-weight: 500; margin-bottom: 8px; display: block; color: #333;">Upload Gambar KV (opsional)</label>
            <div id="drop-area" onclick="document.getElementById('imageInput').click();">
              <div class="icon-upload">
                <img src="../images/upload-icon.svg.svg" alt="Upload Icon" width="48">
              </div>
              <p class="drop-text"><strong>Drag & Drop</strong> gambar di sini atau <span class="highlight">klik untuk memilih</span></p>
              <input type="file" name="image" id="imageInput" accept="image/*" hidden required>
              <div id="preview-image" class="preview-image"></div>
              <p id="filename" class="filename-display"></p>
            </div>
        </div>
      </div>
      <button type="submit">Upload</button>
    </form>
  </div>

  <h2 class="section-tittle">Daftar Folder KV</h2>
    <div class="table-wrapper">
      <table class="kv-table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Tag</th>
            <th>Kreator</th>
            <th>Tanggal</th>
            <th>Link</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $result = mysqli_query($conn, "SELECT * FROM kv_folders ORDER BY tanggal DESC");
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
          <tr>
            <td data-label="Nama"><?= $row['nama'] ?></td>
            <td data-label="Kategori"><?= $row['kategori'] ?></td>
            <td data-label="Tag"><?= $row['tag'] ?></td>
            <td data-label="Kreator"><?= $row['kreator'] ?></td>
            <td data-label="Tanggal"><?= $row['tanggal'] ?></td>
            <td data-label="Link"><a href="<?= $row['link'] ?>" target="_blank">Link</a></td>
            <td data-label="Aksi">
              <form method="POST" action="hapus_kv.php">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn-delete">Hapus</button>
              </form>
            </td>
          </tr>
        <?php } ?>
    </tbody>
  </table>
  </div>
</div>
<script>
  // Hamburger dashboard admin
  const hamburger = document.getElementById('hamburger');
  const navMenu = document.getElementById('nav-menu');

  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
  });
  // Drag and drop dashboard admin
    const dropArea = document.getElementById('drop-area');
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('preview-image');
    const filenameDisplay = document.getElementById('filename');

    dropArea.addEventListener('click', () => input.click());

    input.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
          filenameDisplay.textContent = file.name;
        };
        reader.readAsDataURL(file);
      }
    });

    dropArea.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropArea.classList.add("dragging");
    });

    dropArea.addEventListener("dragleave", () => {
      dropArea.classList.remove("dragging");
    });

    dropArea.addEventListener("drop", (e) => {
      e.preventDefault();
      dropArea.classList.remove("dragging");

      input.files = e.dataTransfer.files;
      const event = new Event("change");
      input.dispatchEvent(event);
    });
</script>
</body>
</html>
