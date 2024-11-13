<?php 
// Pastikan koneksi ke database sudah berhasil
$tampil = mysqli_query($conn, "
    SELECT foto.*, user.Username,
           COUNT(DISTINCT likefoto.FotoID) AS jumlah_like,
           COUNT(DISTINCT komentarfoto.komentarID) AS jumlah_komen
    FROM foto
    INNER JOIN user ON foto.UserID = user.UserID
    LEFT JOIN likefoto ON foto.FotoID = likefoto.FotoID
    LEFT JOIN komentarfoto ON foto.FotoID = komentarfoto.FotoID
    GROUP BY foto.FotoID
");

// Tampilkan setiap foto dengan jumlah like dan komentar
if ($tampil && mysqli_num_rows($tampil) > 0):
    foreach($tampil as $tampils): 
?>
<div class="col-6 col-md-4 col-lg-3 mb-4">
    <div class="card shadow-sm border-0">
        <img src="uploads/<?= htmlspecialchars($tampils['LokasiFile']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($tampils['JudulFoto']) ?></h5>
            <p class="card-text text-muted">Oleh: <?= htmlspecialchars($tampils['Username']) ?></p>
            <p class="card-text">
                <span class="text-success">❤️ <?= $tampils['jumlah_like'] ?> Likes</span> | 
                <span class="text-info">💬 <?= $tampils['jumlah_komen'] ?> Komentar</span>
            </p>
            <a href="?url=detail&id=<?= $tampils['FotoID'] ?>" class="btn btn-primary w-100">Detail</a>
        </div>
    </div>
</div>
<?php 
    endforeach;
else: 
?>
    <p class="text-center text-muted" style="font-size: 1.5rem; font-weight: bold;">Tidak ada foto yang tersedia.</p>
<?php endif; ?>
