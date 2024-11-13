<?php 
include 'koneksi.php'; // Pastikan koneksi ke database

// Jika form disubmit
$submit = @$_POST['submit'];
if ($submit == 'Daftar') {
    // Mengambil data dari form
    $username = @$_POST['username'];
    $password = md5(@$_POST['password']); // Menggunakan md5 untuk mengenkripsi password
    $email = @$_POST['email'];
    $nama_lengkap = @$_POST['nama_lengkap'];
    $alamat = @$_POST['alamat'];

    // Cek apakah ada username dan email yang sama
    $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE Username='$username' OR Email='$email'"));
    
    if ($cek == 0) {
        // Pastikan tidak mengirimkan nilai untuk id
        mysqli_query($conn, "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat) VALUES ('$username', '$password', '$email', '$nama_lengkap', '$alamat')");
        
        echo '<div class="alert alert-success text-center" role="alert">Daftar Berhasil, Silahkan Login!</div>';
        echo '<meta http-equiv="refresh" content="0.8; url=login.php">';
    } else {
        echo '<div class="alert alert-danger text-center" role="alert">Maaf, Username atau Email sudah terdaftar!</div>';
        echo '<meta http-equiv="refresh" content="0.8; url=daftar.php">';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UKK 2024 | Website Galeri Foto</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Halaman Daftar</h4>
                        <p class="text-center mb-4">Daftar Akun Baru</p>

                        <form action="daftar.php" method="post">
                            <div class="form-group mb-3">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_lengkap" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Alamat</label>
                                <input type="text" class="form-control" name="alamat" required>
                            </div>
                            <input type="submit" value="Daftar" class="btn btn-primary w-100 my-3" name="submit">
                            <p class="text-center">Sudah Punya Akun? <a href="login.php" class="link-primary">Login Sekarang</a></p>
                        </form>
                        <a href="index.php" class="btn btn-outline-primary w-100 mt-3">Kembali ke Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
