<?php
require_once 'auth.php';
$total = (int)mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa"))['total'];
$prodiCount = (int)mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT prodi) AS total FROM mahasiswa"))['total'];
$latest = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY created_at DESC LIMIT 5");
$initial = strtoupper(substr($_SESSION['nama'] ?? 'A', 0, 1));
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard • Akademia</title><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="style.css"></head>
<body>
<div class="app-layout">
<aside class="sidebar">
  <a class="brand" href="index.php"><span class="brand-mark">A</span><span>akademia</span></a>
  <div class="side-label">MENU UTAMA</div>
  <nav class="side-nav">
    <a class="nav-link active" href="index.php"><span class="nav-icon">▦</span>Dashboard</a>
    <a class="nav-link" href="mahasiswa.php"><span class="nav-icon">♙</span>Data Mahasiswa</a>
  </nav>
  <div class="sidebar-bottom"><div class="help-card"><div class="help-icon">✦</div><strong>Butuh bantuan?</strong><p>Kelola data mahasiswa dengan mudah melalui menu yang tersedia.</p></div>
    <a class="nav-link logout-link" href="logout.php"><span class="nav-icon">↪</span>Keluar</a>
  </div>
</aside>
<main class="main-area">
  <header class="topbar"><div><span class="breadcrumb">Halaman /</span> <strong>Dashboard</strong></div><div class="top-user"><div class="avatar"><?= e($initial) ?></div><div><strong><?= e($_SESSION['nama']) ?></strong><small>Administrator</small></div></div></header>
  <div class="page-content">
    <div class="welcome-row"><div><span class="eyebrow muted">RINGKASAN AKADEMIK</span><h1>Dashboard</h1><p>Selamat datang kembali, <?= e($_SESSION['nama']) ?>. Ini ringkasan data mahasiswa saat ini.</p></div><a href="tambah.php" class="btn btn-primary">＋ Tambah mahasiswa</a></div>
    <div class="stats-grid">
      <article class="stat-card"><div class="stat-top"><span>Total mahasiswa</span><span class="stat-icon blue">♙</span></div><strong><?= $total ?></strong><small>Data mahasiswa terdaftar</small></article>
      <article class="stat-card"><div class="stat-top"><span>Program studi</span><span class="stat-icon purple">▤</span></div><strong><?= $prodiCount ?></strong><small>Program studi terdata</small></article>
      <article class="stat-card stat-accent"><div class="stat-top"><span>Status sistem</span><span class="stat-icon green">✓</span></div><strong>Aktif</strong><small>Sistem berjalan di localhost</small></article>
    </div>
    <section class="panel">
      <div class="panel-heading"><div><h2>Mahasiswa terbaru</h2><p>Lima data mahasiswa yang terakhir ditambahkan.</p></div><a class="text-link" href="mahasiswa.php">Lihat semua <span>→</span></a></div>
      <div class="table-scroll"><table><thead><tr><th>MAHASISWA</th><th>NIM</th><th>PROGRAM STUDI</th><th>ALAMAT</th></tr></thead><tbody>
      <?php if (mysqli_num_rows($latest)): while($m = mysqli_fetch_assoc($latest)): ?>
      <tr><td><div class="person-cell"><div class="mini-avatar"><?= e(strtoupper(substr($m['nama'],0,1))) ?></div><strong><?= e($m['nama']) ?></strong></div></td><td class="muted-cell"><?= e($m['nim']) ?></td><td><span class="tag"><?= e($m['prodi']) ?></span></td><td class="muted-cell"><?= e($m['alamat']) ?></td></tr>
      <?php endwhile; else: ?><tr><td colspan="4" class="empty-state"><div class="empty-icon">♙</div><strong>Belum ada data mahasiswa</strong><p>Tambahkan data pertama untuk mulai mengelola informasi.</p><a class="btn btn-primary" href="tambah.php">Tambah mahasiswa</a></td></tr><?php endif; ?>
      </tbody></table></div>
    </section>
    <div class="footer-note">© 2026 Akademia. Sistem Informasi Data Mahasiswa.</div>
  </div>
</main></div></body></html>
