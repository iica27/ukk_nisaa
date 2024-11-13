<div class="container">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title" style="font-size: 1rem;">Halaman Upload</h4>
                    <?php
                    // Ambil data dari <form>
                    $submit = $_POST['submit'] ?? null;
                    $fotoid = $_GET['fotoid'] ?? null;

                    // Validasi ekstensi file yang diizinkan
                    $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];

                    // Fungsi untuk memvalidasi dan mengupload gambar
                    function uploadFile($file) {
                        global $valid_extensions;
                        $filename = $file['name'];
                        $tmp_name = $file['tmp_name'];
                        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                        if (in_array($file_extension, $valid_extensions)) {
                            $new_filename = uniqid() . '.' . $file_extension;
                            $target_file = 'uploads/' . $new_filename;
                            if (move_uploaded_file($tmp_name, $target_file)) {
                                return $new_filename;
                            } else {
                                return false;
                            }
                        } else {
                            return false;
                        }
                    }

                    // Proses untuk menyimpan foto
                    if ($submit == 'Simpan') {
                        $judul_foto = mysqli_real_escape_string($conn, $_POST['judul_foto']);
                        $deskripsi_foto = mysqli_real_escape_string($conn, $_POST['deskripsi_foto']);
                        $album_id = $_POST['album_id'];
                        $user_id = $_SESSION['user_id'];

                        $nama_file = uploadFile($_FILES['namafile']);
                        if ($nama_file) {
                            $tanggal = date('Y-m-d');
                            $insert = mysqli_query($conn, "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, LokasiFile, AlbumID, UserID) 
                                                          VALUES('$judul_foto', '$deskripsi_foto', '$tanggal', '$nama_file', '$album_id', '$user_id')");
                            if ($insert) {
                                echo '<div class="alert alert-success" style="font-size: 0.875rem;">Gambar Berhasil disimpan</div>';
                                echo '<meta http-equiv="refresh" content="0.8; url=?url=upload">';
                            } else {
                                echo '<div class="alert alert-danger" style="font-size: 0.875rem;">Gambar gagal disimpan</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger" style="font-size: 0.875rem;">File harus berupa gambar: *.jpg, *.jpeg, *.png, *.gif</div>';
                        }
                    } 
                    // Proses edit foto
                    elseif (isset($_GET['edit'])) {
                        if ($submit == "Ubah") {
                            $judul_foto = mysqli_real_escape_string($conn, $_POST['judul_foto']);
                            $deskripsi_foto = mysqli_real_escape_string($conn, $_POST['deskripsi_foto']);
                            $album_id = $_POST['album_id'];
                            $user_id = $_SESSION['user_id'];
                            $tanggal = date('Y-m-d');
                            $nama_file = $_FILES['namafile']['name'];

                            if (strlen($nama_file) == 0) {
                                // Jika tidak ada file baru, hanya update data
                                $update = mysqli_query($conn, "UPDATE foto SET JudulFoto='$judul_foto', DeskripsiFoto='$deskripsi_foto', 
                                                                TanggalUnggah='$tanggal', AlbumID='$album_id' WHERE FotoID='$fotoid'");
                                if ($update) {
                                    echo '<div class="alert alert-success" style="font-size: 0.875rem;">Gambar Berhasil diubah</div>';
                                    echo '<meta http-equiv="refresh" content="0.8; url=?url=upload">';
                                } else {
                                    echo '<div class="alert alert-danger" style="font-size: 0.875rem;">Gambar gagal diubah</div>';
                                }
                            } else {
                                // Jika ada file baru, upload dan update data
                                $nama_file = uploadFile($_FILES['namafile']);
                                if ($nama_file) {
                                    $update = mysqli_query($conn, "UPDATE foto SET JudulFoto='$judul_foto', DeskripsiFoto='$deskripsi_foto', 
                                                                 NamaFile='$nama_file', TanggalUnggah='$tanggal', AlbumID='$album_id' WHERE FotoID='$fotoid'");
                                    if ($update) {
                                        echo '<div class="alert alert-success" style="font-size: 0.875rem;">Gambar Berhasil diubah</div>';
                                        echo '<meta http-equiv="refresh" content="0.8; url=?url=upload">';
                                    } else {
                                        echo '<div class="alert alert-danger" style="font-size: 0.875rem;">Gambar gagal diubah</div>';
                                    }
                                } else {
                                    echo '<div class="alert alert-danger" style="font-size: 0.875rem;">File harus berupa gambar: *.jpg, *.jpeg, *.png, *.gif</div>';
                                }
                            }
                        }
                    } 
                    // Proses hapus foto
                    elseif (isset($_GET['hapus'])) {
                        $foto = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM foto WHERE FotoID='$fotoid'"));
                        $file_to_delete = 'uploads/' . $foto['LokasiFile'];
                        if (unlink($file_to_delete)) {
                            $delete = mysqli_query($conn, "DELETE FROM foto WHERE FotoID='$fotoid'");
                            if ($delete) {
                                echo '<div class="alert alert-success" style="font-size: 0.875rem;">Gambar Berhasil dihapus</div>';
                                echo '<meta http-equiv="refresh" content="0.8; url=?url=upload">';
                            } else {
                                echo '<div class="alert alert-danger" style="font-size: 0.875rem;">Gambar gagal dihapus dari database</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger" style="font-size: 0.875rem;">Gambar gagal dihapus dari server</div>';
                        }
                    }

                    // Mencari data album
                    $album = mysqli_query($conn, "SELECT * FROM album WHERE UserID='$_SESSION[user_id]'");
                    $val = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM foto WHERE FotoID='$fotoid'"));
                    ?>
                    <?php if (!isset($_GET['edit'])) : ?>
                        <form action="?url=upload" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Judul Foto</label>
                                <input type="text" class="form-control" required name="judul_foto" style="font-size: 0.875rem;">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Foto</label>
                                <textarea name="deskripsi_foto" class="form-control" required cols="30" rows="5" style="font-size: 0.875rem;"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Pilih Gambar</label>
                                <input type="file" name="namafile" class="form-control" required>
                                <small class="text-danger" style="font-size: 0.875rem;">File Harus Berupa: *.jpg, *.png *.gif</small>
                            </div>
                            <div class="form-group">
                                <label>Pilih Album</label>
                                <select name="album_id" class="form-select" style="font-size: 0.875rem;">
                                    <?php foreach ($album as $albums) : ?>
                                        <option value="<?= $albums['AlbumID'] ?>"><?= $albums['NamaAlbum'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <input type="submit" value="Simpan" name="submit" class="btn btn-primary btn-sm my-3">
                        </form>
                    <?php elseif (isset($_GET['edit'])) : ?>
                        <form action="?url=upload&&edit&&fotoid=<?= $val['FotoID'] ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Judul Foto</label>
                                <input type="text" class="form-control" value="<?= $val['JudulFoto'] ?>" required name="judul_foto" style="font-size: 0.875rem;">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Foto</label>
                                <textarea name="deskripsi_foto" class="form-control" required cols="30" rows="5" style="font-size: 0.875rem;"><?= $val['DeskripsiFoto'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Pilih Gambar</label>
                                <input type="file" name="namafile" class="form-control">
                                <small class="text-danger" style="font-size: 0.875rem;">File Harus Berupa: *.jpg, *.png *.gif</small>
                            </div>
                            <div class="form-group">
                                <label>Pilih Album</label>
                                <select name="album_id" class="form-select" style="font-size: 0.875rem;">
                                    <?php foreach ($album as $albums) : ?>
                                        <option value="<?= $albums['AlbumID'] ?>" <?= $albums['AlbumID'] == $val['AlbumID'] ? 'selected' : '' ?>><?= $albums['NamaAlbum'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <input type="submit" value="Ubah" name="submit" class="btn btn-warning btn-sm my-3">
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-6">
            <div class="row">
                <?php
                $username = $_SESSION['username'] ?? '';
                if ($username == 'admin') {
                    $fotos = mysqli_query($conn, "SELECT * FROM foto");
                } else {
                    $user_id = $_SESSION['user_id'];
                    $fotos = mysqli_query($conn, "SELECT * FROM foto WHERE UserID='$user_id'");
                }
                foreach ($fotos as $foto) :
                ?>
                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                        <div class="card" style="width: 110%;">
                            <img src="uploads/<?= $foto['LokasiFile'] ?>" class="card-img-top object-fit-cover" style="aspect-ratio: 16/9; height: 150px;">
                            <div class="card-body">
                                <p class="small" style="font-size: 0.875rem;"><?= $foto['JudulFoto'] ?></p>
                                <a href="?url=upload&&edit&fotoid=<?= $foto['FotoID'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="?url=upload&&hapus&fotoid=<?= $foto['FotoID'] ?>" class="btn btn-sm btn-danger">Hapus</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
