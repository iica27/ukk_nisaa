<?php include 'koneksi.php'; session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>UKK 2024 | Website Galeri Foto</title>
   <link rel="stylesheet" href="assets/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/css/style.css">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="assets/js/bootstrap.bundle.min.js"></script>
</head>
<body>
   <div class="container">
      <div class="row justify-content-center align-items-center vh-100">
         <div class="col-5">
            <div class="card shadow-lg border-0">
               <div class="card-body">
                  <h4 class="card-title text-center mb-4">Halaman Login</h4>
                  <p class="text-center mb-4">Login ke Akun Anda</p>
                  <?php 
                  // ambil data yang dikirimkan oleh <form> dengan method post
                  $submit = @$_POST['submit'];
                  if ($submit == 'Login') {
                     $username = $_POST['username'];
                     $password = md5($_POST['password']);
                     // cek apakah username dan password yang dimasukkan ada di database
                     $sql = mysqli_query($conn, "SELECT * FROM user WHERE Username='$username' AND Password='$password'");
                     $cek = mysqli_num_rows($sql);
                     if ($cek != 0) {
                        // ambil data dari database untuk membuat session
                        $sesi = mysqli_fetch_array($sql);
                        $_SESSION['username'] = $sesi['Username'];
                        $_SESSION['user_id'] = $sesi['UserID'];
                        $_SESSION['email'] = $sesi['Email'];
                        $_SESSION['nama_lengkap'] = $sesi['NamaLengkap'];
                        echo '<script>
                                 $(document).ready(function() {
                                    $("#loginSuccessModal").modal("show");
                                 });
                              </script>';
                     } else {
                        echo '<p class="text-danger text-center" id="login-failed">Login Gagal!</p>';
                        echo '<script>
                                 setTimeout(function() {
                                    window.location.href = "login.php";
                                 }, 3000); // redirect after 3 seconds
                              </script>';
                     }
                  }
                  ?>
                  <form action="login.php" method="post">
                     <div class="form-group mb-3">
                        <label class="text-secondary">Username</label>
                        <input type="text" class="form-control" name="username" required>
                     </div>
                     <div class="form-group mb-3">
                        <label class="text-secondary">Password</label>
                        <input type="password" class="form-control" name="password" required>
                     </div>
                     <input type="submit" value="Login" class="btn btn-primary w-100 my-3" name="submit">
                     <p class="text-center">Belum Punya Akun? <a href="daftar.php" class="link-primary">Daftar Sekarang</a></p>
                  </form>
                  <a href="index.php" class="btn btn-outline-primary w-100 mt-3">Kembali ke Home</a>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Modal for successful login -->
   <div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-labelledby="loginSuccessModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content rounded">
            <div class="modal-header">
               <h5 class="modal-title" id="loginSuccessModalLabel">Login Berhasil!</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
               Selamat datang, <?php echo isset($sesi['NamaLengkap']) ? $sesi['NamaLengkap'] : ''; ?>! Anda telah berhasil login.
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
         </div>
      </div>
   </div>

   <script>
      $(document).ready(function() {
         // Menangani pengalihan setelah modal ditutup
         $('#loginSuccessModal').on('hidden.bs.modal', function () {
            window.location.href = './'; // Ganti dengan URL halaman yang diinginkan
         });
      });
   </script>
</body>
</html>
