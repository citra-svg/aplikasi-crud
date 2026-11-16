
<?php
require_once 'auth.php';

$error = '';
$nim = $nama = $prodi = $alamat = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $prodi = trim($_POST['prodi'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($nim === '' || $nama === '' || $prodi === '' || $alamat === '') {
        $error = 'Semua kolom wajib diisi.';
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO mahasiswa (nim, nama, prodi, alamat) VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "ssss", $nim, $nama, $prodi, $alamat);

        try {
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: mahasiswa.php?pesan=tambah');
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $error = 'NIM tersebut sudah terdaftar. Silakan gunakan NIM lain.';
            } else {
                $error = 'Gagal menyimpan data mahasiswa. Silakan coba lagi.';
            }
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa • Akademia</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="app-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <a class="brand" href="index.php">
            <span class="brand-mark">A</span>
            <span>akademia</span>
        </a>

        <div class="side-label">MENU UTAMA</div>
        <nav class="side-nav">
            <a class="nav-link" href="index.php">
                <span class="nav-icon">▦</span>
                Dashboard
            </a>
            <a class="nav-link active" href="mahasiswa.php">
                <span class="nav-icon">♙</span>
                Data Mahasiswa
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="help-card">
                <div class="help-icon">✦</div>
                <strong>Tambah data baru</strong>
                <p>Pastikan informasi yang dimasukkan sudah benar.</p>
            </div>
            <a class="nav-link logout-link" href="logout.php">
                <span class="nav-icon">↪</span>
                Keluar
            </a>

        </div>
    </aside>
    <!-- MAIN CONTENT -->
    <main class="main-area">
        <!-- TOPBAR -->
        <header class="topbar">
            <div>
                <span class="breadcrumb">Data Mahasiswa /</span>
                <strong>Tambah Data</strong>
            </div>

            <div class="top-user">
                <div class="avatar">
                    <?= e(strtoupper(substr($_SESSION['nama'], 0, 1))) ?>
                </div>

                <div>
                    <strong><?= e($_SESSION['nama']) ?></strong>
                    <small>Administrator</small>
                </div>
            </div>

        </header>
        <!-- PAGE CONTENT -->
        <div class="page-content narrow">
            <!-- PAGE HEADER -->
            <div class="welcome-row">
                <div>
                    <span class="eyebrow muted">FORMULIR MAHASISWA</span>
                    <h1>Tambah mahasiswa</h1>
                    <p>Lengkapi informasi mahasiswa pada formulir berikut.</p>
                </div>
            </div>

            <!-- FORM PANEL -->
            <section class="panel form-panel">
                <div class="panel-heading">
                    <div>
                        <h2>Informasi mahasiswa</h2>
                        <p>Kolom bertanda * wajib diisi.</p>
                    </div>
                    <span class="form-step">01 / 01</span>
                </div>

                <!-- ERROR MESSAGE -->
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <!-- FORM -->
                <form method="POST" class="data-form">
                    <div class="form-grid">
                        <div class="field">
                            <label for="nim">NIM <b>*</b></label>
                            <input
                                id="nim"
                                type="text"
                                name="nim"
                                value="<?= e($nim) ?>"
                                placeholder="Contoh: 240101001"
                                required
                            >
                        </div>

                        <div class="field">
                            <label for="nama">Nama lengkap <b>*</b></label>
                            <input
                                id="nama"
                                type="text"
                                name="nama"
                                value="<?= e($nama) ?>"
                                placeholder="Masukkan nama mahasiswa"
                                required
                            >
                        </div>

                        <div class="field full">
                            <label for="prodi">Program studi <b>*</b></label>
                            <input
                                id="prodi"
                                type="text"
                                name="prodi"
                                value="<?= e($prodi) ?>"
                                placeholder="Contoh: Manajemen Informatika"
                                required
                            >
                        </div>

                        <div class="field full">
                            <label for="alamat">Alamat <b>*</b></label>
                            <textarea
                                id="alamat"
                                name="alamat"
                                rows="4"
                                placeholder="Masukkan alamat lengkap"
                                required
                            ><?= e($alamat) ?></textarea>
                        </div>
                    </div>

                    <!-- FORM ACTIONS -->
                    <div class="form-actions">
                        <a href="mahasiswa.php" class="btn btn-ghost">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan mahasiswa <span>→</span>
                        </button>
                    </div>
                </form>
            </section>

            <!-- FOOTER -->
            <div class="footer-note">
                © 2026 Akademia. Sistem Informasi Data Mahasiswa.
            </div>
        </div>
    </main>
</div>
</body>
</html>
