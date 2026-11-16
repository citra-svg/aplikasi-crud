
<?php
require_once 'auth.php';

$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $like = "%$q%";

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM mahasiswa
         WHERE nim LIKE ? OR nama LIKE ? OR prodi LIKE ?
         ORDER BY created_at DESC"
    );

    mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else {
    $result = mysqli_query(
        $conn,
        "SELECT * FROM mahasiswa ORDER BY created_at DESC"
    );
}

$total = mysqli_num_rows($result);
$initial = strtoupper(substr($_SESSION['nama'] ?? 'A', 0, 1));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa • Akademia</title>
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
                <strong>Data terorganisir</strong>
                <p>Temukan, perbarui, dan kelola data mahasiswa dari satu halaman.</p>
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
                <span class="breadcrumb">Menu /</span>
                <strong>Data Mahasiswa</strong>
            </div>

            <div class="top-user">
                <div class="avatar">
                    <?= e($initial) ?>
                </div>
                <div>
                    <strong><?= e($_SESSION['nama']) ?></strong>
                    <small>Administrator</small>
                </div>
            </div>
        </header>

        <div class="page-content">

            <!-- PAGE HEADER -->
            <div class="welcome-row">

                <div>
                    <span class="eyebrow muted">PENGELOLAAN DATA</span>
                    <h1>Data Mahasiswa</h1>
                    <p>Kelola seluruh informasi mahasiswa dalam satu tempat.</p>
                </div>

                <a href="tambah.php" class="btn btn-primary">
                    <span>＋</span>
                    Tambah mahasiswa
                </a>

            </div>

            <!-- NOTIFICATION -->
           <?php if (isset($_GET['pesan'])): ?>

         <div class="alert alert-success">
             <?= [
                'tambah' => 'Data mahasiswa berhasil ditambahkan.',
                'edit' => 'Data mahasiswa berhasil diperbarui.',
                'hapus' => 'Data mahasiswa berhasil dihapus.'
                ][$_GET['pesan']] ?? 'Proses berhasil.' ?>
             </div>

            <?php endif; ?>

            <!-- DATA TABLE -->
            <section class="panel">

                <div class="panel-heading">
                    <div>
                        <h2>
                            Daftar mahasiswa
                            <span class="count-pill"><?= $total ?></span>
                        </h2>

                        <p>
                            <?= $q !== ''
                                ? 'Hasil pencarian untuk “' . e($q) . '”'
                                : 'Semua data mahasiswa yang tersimpan.'
                            ?>
                        </p>
                    </div>

                    <!-- SEARCH -->
                    <form class="search-form" method="GET">

                        <span>⌕</span>

                        <input
                            type="text"
                            name="q"
                            value="<?= e($q) ?>"
                            placeholder="Cari nama, NIM, prodi..."
                        >

                        <button type="submit">Cari</button>
                    </form>
                </div>

                <!-- TABLE -->
                <div class="table-scroll">

                    <table>
                        <thead>
                            <tr>
                                <th>MAHASISWA</th>
                                <th>NIM</th>
                                <th>PROGRAM STUDI</th>
                                <th>ALAMAT</th>
                                <th class="right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php if ($total): ?>

                            <?php while ($m = mysqli_fetch_assoc($result)): ?>

                                <tr>

                                    <td>
                                        <div class="person-cell">

                                            <div class="mini-avatar">
                                                <?= e(strtoupper(substr($m['nama'], 0, 1))) ?>
                                            </div>

                                            <strong><?= e($m['nama']) ?></strong>

                                        </div>
                                    </td>

                                    <td class="muted-cell">
                                        <?= e($m['nim']) ?>
                                    </td>

                                    <td>
                                        <span class="tag">
                                            <?= e($m['prodi']) ?>
                                        </span>
                                    </td>

                                    <td class="muted-cell">
                                        <?= e($m['alamat']) ?>
                                    </td>

                                    <!-- ACTION BUTTON -->
                                    <td class="right actions">

                                        <div class="action-group">

                                            <a
                                                class="action-edit"
                                                href="edit.php?nim=<?= urlencode($m['nim']) ?>"
                                            >
                                                <span>✎</span>
                                                Edit
                                            </a>

                                            <form
                                                class="inline-delete"
                                                method="POST"
                                                action="hapus.php"
                                                onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?')"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="nim"
                                                    value="<?= e($m['nim']) ?>"                                                >

                                                <button
                                                    type="submit"
                                                    class="action-delete"                                                >
                                                    <span>♲</span>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <div class="empty-icon">⌕</div>
                                    <strong>
                                        <?= $q !== ''
                                            ? 'Data tidak ditemukan'
                                            : 'Belum ada data mahasiswa'
                                        ?>
                                    </strong>
                                    <p>
                                        <?= $q !== ''
                                            ? 'Coba kata kunci lain.'
                                            : 'Klik tombol tambah mahasiswa untuk memulai.'
                                        ?>
                                    </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <div class="footer-note">
                © 2026 Akademia. Sistem Informasi Data Mahasiswa.
            </div>
        </div>
    </main>
</div>

</body>
</html>
