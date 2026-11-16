<?php
require_once 'config.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, "SELECT id, nama, username, password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $user = mysqli_stmt_get_result($stmt)->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password yang kamu masukkan salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk • Akademia</title><link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
<div class="login-shell">
  <section class="login-visual">
    <div class="brand brand-light"><span class="brand-mark">A</span><span>akademia</span></div>
    <div class="visual-copy">
      <span class="eyebrow">STUDENT MANAGEMENT SYSTEM</span>
      <h1>Kelola data.<br><em>Bangun masa depan.</em></h1>
      <p>Satu ruang untuk mengelola informasi mahasiswa dengan lebih rapi, cepat, dan terorganisir.</p>
    </div>
    <div class="visual-bottom"><span class="dot-live"></span> Sistem informasi akademik</div>
    <div class="orb orb-one"></div><div class="orb orb-two"></div>
  </section>
  <section class="login-panel">
    <div class="login-form-wrap">
      <div class="mobile-brand brand"><span class="brand-mark">A</span><span>akademia</span></div>
      <span class="eyebrow muted">SELAMAT DATANG KEMBALI</span>
      <h2>Masuk ke akun</h2>
      <p class="form-intro">Silakan masuk untuk melanjutkan ke dashboard.</p>
      <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST" class="auth-form">
        <label for="username">Username</label>
        <div class="input-wrap"><span class="input-icon">✉</span><input id="username" name="username" type="text" placeholder="Masukkan username" required autocomplete="username"></div>
        <label for="password">Password</label>
        <div class="input-wrap"><span class="input-icon">⌑</span><input id="password" name="password" type="password" placeholder="Masukkan password" required autocomplete="current-password"></div>
        <button class="btn btn-primary btn-full" type="submit">Masuk ke dashboard <span>→</span></button>
      </form>
      <div class="login-hint"><strong>Akun demo</strong><span>Username: admin</span><span>Password: admin123</span></div>
      <p class="login-foot">© 2026 Akademia · Sistem Informasi Data Mahasiswa</p>
    </div>
  </section>
</div>
</body></html>
