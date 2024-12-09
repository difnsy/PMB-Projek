<?php
// File: login.php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Email dan password wajib diisi."]);
        exit;
    }

    // Cek apakah email terdaftar
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Cek role dan arahkan ke halaman sesuai
        session_start();
        $_SESSION['user'] = $user;
        if ($user['role'] === 'admin') {
            echo json_encode(["status" => "success", "redirect" => "dashboard/admin.php"]);
        } elseif ($user['role'] === 'pendaftar') {
            echo json_encode(["status" => "success", "redirect" => "dashboard/user.php"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Role tidak valid."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email atau password salah."]);
    }
    exit;
}

// Validasi akses menu dashboard
if (isset($_GET['dashboard'])) {
    session_start();
    if (!isset($_SESSION['user'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: 'Anda harus login untuk mengakses menu ini!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>";
        exit;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Sistem Informasi Penerimaan Mahasiswa Baru</title>
    <link href="assets/css/tabler.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "pb2immsqvl");
</script>

    <style>
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body class="d-flex flex-column">
    
    <div class="page page-center">
        <div class="container container-tight py-4">
        <h2 class="card-title text-center mb-4">Penerimaan Mahasiswa Baru Universitas IPWIJA</h2>

            <form class="card card-md" id="login-form" method="POST">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                            <span class="input-group-text password-toggle" id="toggle-password">
                                <i class="fa fa-eye" id="eye-icon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 text-center">
                        <a href="reset_password.php">Lupa password?</a> | <a href="register.php">Registrasi</a>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        document.getElementById('login-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.status === 'success') {
                window.location.href = result.redirect;
            } else {
                Swal.fire('Error', result.message, 'error');
            }
        });
    </script>
</body>
</html>
