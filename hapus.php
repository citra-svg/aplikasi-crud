<?php
require_once 'auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'] ?? '';
    $stmt = mysqli_prepare($conn, "DELETE FROM mahasiswa WHERE nim=?");
    mysqli_stmt_bind_param($stmt, "s", $nim);
    mysqli_stmt_execute($stmt);
}
header('Location: mahasiswa.php?pesan=hapus');
exit;
