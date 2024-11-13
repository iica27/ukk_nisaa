<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Halaman Profil</h2>
                    <?php
                    $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM user WHERE UserID='{$_SESSION['user_id']}'"));
                    $alert = '';

                    // Cek apakah username yang login adalah 'admin'
                    $isAdmin = $user['Username'] === 'admin';

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['editprofile'])) {
                            // Proses update profil
                            $nama = $_POST['nama'];
                            $email = $_POST['email'];
                            $username = $_POST['username'];
                            $alamat = $_POST['alamat'];

                            // Update profil berdasarkan field yang berubah
                            $fieldsToUpdate = [];
                            if ($nama !== $user['NamaLengkap']) {
                                $fieldsToUpdate[] = "NamaLengkap='$nama'";
                            }
                            if ($email !== $user['Email']) {
                                $fieldsToUpdate[] = "Email='$email'";
                                $_SESSION['email'] = $email; // Update session email
                            }
                            if ($username !== $user['Username']) {
                                $fieldsToUpdate[] = "Username='$username'";
                                $_SESSION['username'] = $username; // Update session username
                            }
                            if ($alamat !== $user['Alamat']) {
                                $fieldsToUpdate[] = "Alamat='$alamat'";
                            }

                            if (!empty($fieldsToUpdate)) {
                                $ubah = mysqli_query($conn, "UPDATE user SET " . implode(', ', $fieldsToUpdate) . " WHERE UserID='$_SESSION[user_id]'");
                                $alert = $ubah ? 'Profil berhasil diperbarui' : 'Gagal memperbarui profil';
                                if ($ubah) {
                                    $_SESSION['namalengkap'] = $nama; // Update session namalengkap
                                }
                            }
                        } elseif (isset($_POST['editpassword'])) {
                            // Proses ubah password
                            $password = md5($_POST['password']);
                            if ($password != $user['Password']) {
                                $ubah = mysqli_query($conn, "UPDATE user SET Password='$password' WHERE UserID='$_SESSION[user_id]'");
                                $alert = $ubah ? 'Password berhasil diubah' : 'Gagal mengubah password';
                            } else {
                                $alert = 'Password tidak berubah';
                            }
                        } elseif (isset($_POST['deleteaccount'])) {
                            // Proses hapus akun
                            $delete = mysqli_query($conn, "DELETE FROM user WHERE UserID='{$_POST['userid']}'");
                            $alert = $delete ? 'Akun berhasil dihapus' : 'Gagal menghapus akun';
                        }
                    }

                    // Pesan alert
                    echo "<script>";
                    if (!empty($alert)) {
                        echo "alert('{$alert}');";
                        echo "setTimeout(function() { window.location.href = '?url=profile'; }, 0);"; // Redirect after alert
                    }
                    echo "</script>";

                    // Form edit profil
                    if (isset($_GET['proses']) && $_GET['proses'] === 'editprofile') : ?>
                        <form action="?url=profile&&proses=editprofile" method="post">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user['NamaLengkap']) ?>" id="nama" name="nama" required placeholder="Masukan Nama Lengkap">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?= htmlspecialchars($user['Email']) ?>" id="email" name="email" required placeholder="Masukan Email Anda">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user['Username']) ?>" id="username" name="username" required placeholder="Masukan Username">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" value="<?= htmlspecialchars($user['Alamat']) ?>" name="alamat" required placeholder="Masukan Alamat Lengkap">
                            </div>
                            <a href="?url=profile" class="btn btn-dark fw-semibold">Kembali</a>
                            <input type="submit" value="Simpan Perubahan" name="editprofile" class="btn btn-primary fw-semibold">
                        </form>
                    <?php elseif (isset($_GET['proses']) && $_GET['proses'] === 'editpassword') : ?>
                        <form action="?url=profile&&proses=editpassword" method="post">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Masukan Password Baru">
                            </div>
                            <a href="?url=profile" class="btn btn-dark fw-semibold">Kembali</a>
                            <input type="submit" value="Simpan Perubahan" name="editpassword" class="btn btn-primary fw-semibold">
                        </form>
                    <?php else : ?>
                        <div class="table-responsive mb-3">
                            <table class="table table-white table-hover">
                                <tr>
                                    <th class="py-3">Nama Lengkap</th>
                                    <td class="py-3 text-muted"><?= htmlspecialchars($user['NamaLengkap']) ?></td>
                                </tr>
                                <tr>
                                    <th class="py-3">Email</th>
                                    <td class="py-3 text-muted"><?= htmlspecialchars($user['Email']) ?></td>
                                </tr>
                                <tr>
                                    <th class="py-3">Username</th>
                                    <td class="py-3 text-muted"><?= htmlspecialchars($user['Username']) ?></td>
                                </tr>
                                <tr>
                                    <th class="py-3">Alamat</th>
                                    <td class="py-3 text-muted"><?= htmlspecialchars($user['Alamat']) ?></td>
                                </tr>
                            </table>
                        </div>
                        <a href="?url=profile&&proses=editprofile" class="btn btn-danger btn-sm">Edit Profil</a>
                        <a href="?url=profile&&proses=editpassword" class="btn btn-primary btn-sm">Edit Password</a>
                    <?php endif; ?>

                    <?php if ($isAdmin) : ?>
                        <h2 class="mt-4">Daftar Akun</h2>
                        <div class="table-responsive mb-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = mysqli_query($conn, "SELECT * FROM user");
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['NamaLengkap']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                                        echo "<td>
                                            <a href='?url=profile&&proses=editprofile&userid=" . $row['UserID'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <form method='post' style='display:inline;'>
                                                <input type='hidden' name='userid' value='" . $row['UserID'] . "'>
                                                <input type='submit' name='deleteaccount' value='Hapus' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus akun ini?\")'>
                                            </form>
                                        </td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
