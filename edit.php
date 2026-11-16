<?php
require_once 'auth.php';
$nim = $_GET['nim'] ?? '';
$stmt = mysqli_prepare($conn, "SELECT * FROM mahasiswa WHERE nim=?");
mysqli_stmt_bind_param($stmt, "s", $nim); mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt)->fetch_assoc();
if (!$data) { http_response_code(404); die('Data mahasiswa tidak ditemukan. <a href="mahasiswa.php">Kembali</a>'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama=trim($_POST['nama']??''); $prodi=trim($_POST['prodi']??''); $alamat=trim($_POST['alamat']??'');
    if ($nama==='' || $prodi==='' || $alamat==='') $error='Semua kolom wajib diisi.';
    else {
        $up=mysqli_prepare($conn,"UPDATE mahasiswa SET nama=?,prodi=?,alamat=? WHERE nim=?");
        mysqli_stmt_bind_param($up,"ssss",$nama,$prodi,$alamat,$nim);
        if(mysqli_stmt_execute($up)){header('Location: mahasiswa.php?pesan=edit');exit;}
        $error='Gagal memperbarui data.';
    }
    $data['nama']=$nama; $data['prodi']=$prodi; $data['alamat']=$alamat;
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Edit Mahasiswa • Akademia</title><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body><div class="app-layout">
<aside class="sidebar"><a class="brand" href="index.php"><span class="brand-mark">A</span><span>akademia</span></a><div class="side-label">MENU UTAMA</div><nav class="side-nav"><a class="nav-link" href="index.php"><span class="nav-icon">▦</span>Dashboard</a><a class="nav-link active" href="mahasiswa.php"><span class="nav-icon">♙</span>Data Mahasiswa</a></nav><div class="sidebar-bottom"><div class="help-card"><div class="help-icon">✦</div><strong>Perbarui informasi</strong><p>Periksa kembali perubahan sebelum menyimpan.</p></div><a class="nav-link logout-link" href="logout.php"><span class="nav-icon">↪</span>Keluar</a></div></aside>
<main class="main-area"><header class="topbar"><div><span class="breadcrumb">Data Mahasiswa /</span> <strong>Edit Data</strong></div><div class="top-user"><div class="avatar"><?= e(strtoupper(substr($_SESSION['nama'],0,1))) ?></div><div><strong><?= e($_SESSION['nama']) ?></strong><small>Administrator</small></div></div></header>
<div class="page-content narrow"><div class="welcome-row"><div><span class="eyebrow muted">FORMULIR MAHASISWA</span><h1>Edit mahasiswa</h1><p>Perbarui informasi mahasiswa yang dipilih.</p></div></div><section class="panel form-panel"><div class="panel-heading"><div><h2>Informasi mahasiswa</h2><p>NIM menjadi identitas tetap mahasiswa.</p></div><span class="form-step">01 / 01</span></div><?php if($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?><form method="POST" class="data-form"><div class="form-grid"><div class="field"><label>NIM</label><input value="<?= e($data['nim']) ?>" disabled><small class="field-help">NIM tidak dapat diubah.</small></div><div class="field"><label for="nama">Nama lengkap <b>*</b></label><input id="nama" name="nama" value="<?= e($data['nama']) ?>" required></div><div class="field full"><label for="prodi">Program studi <b>*</b></label><input id="prodi" name="prodi" value="<?= e($data['prodi']) ?>" required></div><div class="field full"><label for="alamat">Alamat <b>*</b></label><textarea id="alamat" name="alamat" rows="4" required><?= e($data['alamat']) ?></textarea></div></div><div class="form-actions"><a href="mahasiswa.php" class="btn btn-ghost">Batal</a><button type="submit" class="btn btn-primary">Simpan perubahan <span>→</span></button></div></form></section><div class="footer-note">© 2026 Akademia. Sistem Informasi Data Mahasiswa.</div></div></main></div></body></html>
