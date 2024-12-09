<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier']); // Bisa username atau email
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validasi input
    if (empty($identifier) || empty($newPassword) || empty($confirmPassword)) {
        $message = "Semua field wajib diisi!";
        $status = "error";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Password baru dan konfirmasi password tidak cocok!";
        $status = "error";
    } else {
        require_once 'koneksi.php';

        try {
            // Cek apakah identifier adalah email atau username
            $query = "SELECT * FROM users WHERE username = :identifier OR email = :identifier";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['identifier' => $identifier]);

            if ($stmt->rowCount() > 0) {
                // User ditemukan, update password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $updateQuery = "UPDATE users SET password = :password WHERE username = :identifier OR email = :identifier";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->execute(['password' => $hashedPassword, 'identifier' => $identifier]);

                if ($updateStmt->rowCount() > 0) {
                    $message = "Password berhasil diperbarui!";
                    $status = "success";
                } else {
                    $message = "Tidak ada perubahan pada password.";
                    $status = "info";
                }
            } else {
                $message = "Username atau email tidak ditemukan.";
                $status = "error";
            }
        } catch (PDOException $e) {
            $message = "Terjadi kesalahan: " . $e->getMessage();
            $status = "error";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Reset Password</title>
    <link href="assets/css/tabler.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body class="d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <form class="card card-md" id="reset-password-form" method="POST">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Reset Password</h2>
                    <div class="mb-3">
                        <label class="form-label">Username atau Email</label>
                        <input type="text" name="identifier" id="identifier" class="form-control" placeholder="Masukkan username atau email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Masukkan password baru" required>
                            <span class="input-group-text password-toggle" id="toggle-new-password">
                                <i class="fa fa-eye" id="eye-icon-new"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Konfirmasi password baru" required>
                            <span class="input-group-text password-toggle" id="toggle-confirm-password">
                                <i class="fa fa-eye" id="eye-icon-confirm"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($message)): ?>
    <script>
        Swal.fire("<?php echo ucfirst($status); ?>", "<?php echo $message; ?>", "<?php echo $status; ?>");
    </script>
    <?php endif; ?>

    <script>
        // Show/Hide Password
        document.getElementById('toggle-new-password').addEventListener('click', function () {
            const passwordInput = document.getElementById('new_password');
            const eyeIcon = document.getElementById('eye-icon-new');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        document.getElementById('toggle-confirm-password').addEventListener('click', function () {
            const passwordInput = document.getElementById('confirm_password');
            const eyeIcon = document.getElementById('eye-icon-confirm');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
</body>
</html>
