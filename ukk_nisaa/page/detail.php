<?php 
// Mulai dengan output buffering (opsional)
ob_start(); 

// Koneksi database dan ambil detail foto
$details = mysqli_query($conn, "SELECT * FROM foto INNER JOIN user ON foto.UserID=user.UserID WHERE foto.FotoID='$_GET[id]'");
$data = mysqli_fetch_array($details);
$likes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM likefoto WHERE FotoID='$_GET[id]'"));
$cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM likefoto WHERE FotoID='$_GET[id]' AND UserID='".@$_SESSION['user_id']."'"));

// Proses hapus komentar
if (isset($_POST['hapus'])) {
   $komentar_id = $_POST['komentar_id'];
   mysqli_query($conn, "DELETE FROM komentarfoto WHERE KomentarID='$komentar_id'");
   header("Location: ?url=detail&&id=".$_POST['foto_id']);
   exit(); // Jangan lupa untuk keluar setelah pengalihan
}

// Proses menambahkan komentar
$submit = @$_POST['submit'];
if ($submit == 'Kirim') {
   $komentar = @$_POST['komentar'];
   $foto_id = @$_POST['foto_id'];
   $user_id = @$_SESSION['user_id'];
   $tanggal = date('Y-m-d');
   mysqli_query($conn, "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES ('$foto_id', '$user_id', '$komentar', '$tanggal')");
   header("Location: ?url=detail&&id=$foto_id");
   exit();
}
?>

<style>
    .card {
        width: 100%;
        max-width: 500px; /* Atur lebar maksimum card */
        margin: 0 auto; /* Pusatkan card */
        border-radius: 10px; /* Rounded corners */
        overflow: hidden; /* Menjaga elemen di dalam card */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Efek bayangan */
    }
    .card img {
        height: 300px; /* Tetapkan tinggi tetap untuk gambar */
        object-fit: cover; /* Mengatur gambar agar terjaga proporsinya */
        width: 100%; /* Lebar gambar penuh sesuai card */
    }
    .comment-section {
        margin-top: 20px;
    }
    .btn {
        margin-right: 5px; /* Jarak antar tombol */
    }
</style>

<div class="container">
   <div class="row">
      <div class="col-md-6 mb-4">
         <div class="card">
            <img src="uploads/<?= htmlspecialchars($data['LokasiFile']) ?>" alt="<?= htmlspecialchars($data['JudulFoto']) ?>">
            <div class="card-body">
               <h3 class="card-title mb-0"><?= htmlspecialchars($data['JudulFoto']) ?> 
                  <a href="<?php if(isset($_SESSION['user_id'])) {echo '?url=like&&id='.$data['FotoID'].'';}else{echo 'login.php';} ?>" class="btn btn-sm <?php if($cek==0){echo "text-secondary";}else{echo "text-danger";} ?>">
                     <i class="fa-solid fa-fw fa-heart"></i> <?= $likes ?>
                  </a>
               </h3>
               <small class="text-muted mb-3">by: <?= htmlspecialchars($data['Username']) ?>, <?= htmlspecialchars($data['TanggalUnggah']) ?></small>
               <p><?= htmlspecialchars($data['DeskripsiFoto']) ?></p>

               <form action="?url=detail" method="post" class="d-flex flex-row align-items-center">
                  <div class="form-group flex-grow-1 me-2">
                     <input type="hidden" name="foto_id" value="<?= $data['FotoID'] ?>">
                     <?php if (isset($_SESSION['user_id'])): ?>
                        <input type="text" class="form-control" name="komentar" required placeholder="Masukan Komentar">
                     <?php endif; ?>
                  </div>
                  <a href="?url=home" class="btn btn-secondary btn-sm">Kembali</a>
                  <?php if (isset($_SESSION['user_id'])): ?>
                     <input type="submit" value="Kirim" name="submit" class="btn btn-primary btn-sm"> <!-- Tombol Kirim -->
                  <?php endif; ?>
               </form>
            </div>
         </div>
      </div>
      <div class="col-md-6 comment-section">
    <?= @$alert ?>
    <h4 class="mb-3">Komentar</h4> <!-- Menambahkan judul untuk daftar komentar -->
    <?php 
    $UserID = @$_SESSION["user_id"]; 
    $komen = mysqli_query($conn, "SELECT * FROM komentarfoto INNER JOIN user ON komentarfoto.UserID=user.UserID WHERE komentarfoto.FotoID='$_GET[id]'");

    foreach($komen as $komens): ?>
        <div class="border rounded p-3 mb-3 bg-light"> <!-- Membuat kotak komentar -->
            <p class="mb-0 fw-bold"><?= htmlspecialchars($komens['Username']) ?></p>
            <p class="mb-1"><?= htmlspecialchars($komens['IsiKomentar']) ?></p>
            <p class="text-muted small mb-2"><?= htmlspecialchars($komens['TanggalKomentar']) ?></p>
            <div class="text-end"> <!-- Memposisikan tombol hapus di sebelah kanan -->
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $komens['UserID']): ?>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="komentar_id" value="<?= $komens['KomentarID'] ?>">
                        <input type="hidden" name="foto_id" value="<?= $data['FotoID'] ?>">
                        <input type="submit" name="hapus" value="Hapus" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');">
                    </form>
                <?php else: ?>
                    <button class="btn btn-danger btn-sm" disabled>Hapus</button> <!-- Tombol hapus dinonaktifkan -->
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php 
// Mengakhiri output buffering dan mengirim output ke browser
ob_end_flush();
?>
