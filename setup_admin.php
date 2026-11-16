<?php
require_once 'config.php';

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$nama = 'Administrator';

$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$exists = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$exists) {
    $insert = mysqli_prepare($conn, "INSERT INTO users (nama, username, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($insert, "sss", $nama, $username, $password);
    mysqli_stmt_execute($insert);
    echo "Akun admin berhasil dibuat. Username: admin | Password: admin123";
} else {
    echo "Akun admin sudah tersedia. Username: admin | Password: admin123 (jika belum pernah diubah).";
}
