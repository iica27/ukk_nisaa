<?php include 'koneksi.php'; session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Galeri Foto</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Animasi untuk tombol logout */
        .nav-link {
            transition: color 0.3s ease, background-color 0.3s ease;
        }
        .nav-link:hover {
            color: #fff; /* Ganti warna teks saat hover */
            background-color: rgba(0, 0, 0, 0.2); /* Warna latar belakang saat hover */
        }
    </style>
</head>
<body class="bg-hero">
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container">
        <a class="navbar-brand text-white" href="?url=home" style="font-size: 1.5rem; font-weight: 700;">Gallery Foto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" href="?url=home">Home</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="nav-link" href="?url=upload">Upload</a>
                        <a class="nav-link" href="?url=album">Album</a>
                        <a class="nav-link" href="?url=profile"><?= ucwords($_SESSION['username']) ?></a>
                        <a href="?url=logout" class="nav-link" onclick="return confirmLogout();">Logout</a>
                    <?php else: ?>
                        <a class="nav-link" href="login.php">Login</a>
                        <a class="nav-link" href="daftar.php">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4"> <!-- Tambahkan kontainer dengan margin atas -->
        <?php 
            $url = @$_GET["url"];
            if ($url == 'home') {
                include 'page/home.php';
            } elseif ($url == 'profile') {
                include 'page/profil.php';
            } elseif ($url == 'upload') {
                include 'page/upload.php';
            } elseif ($url == 'album') {
                include 'page/album.php';
            } elseif ($url == 'like') {
                include 'page/like.php';
            } elseif ($url == 'komentar') {
                include 'page/komentar.php';
            } elseif ($url == 'detail') {
                include 'page/detail.php';
            } elseif ($url == 'kategori') {
                include 'page/kategori.php';
            } elseif ($url == 'logout') {
                session_destroy();
                header("Location: ?url=home");
            } else {
                include 'page/home.php';
            }
        ?>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmLogout() {
            return confirm("Apakah Anda yakin ingin keluar?");
        }
    </script>
</body>
</html>
