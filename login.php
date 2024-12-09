<?php
// File: register.php
require_once 'koneksi.php';

// Fungsi untuk register pengguna baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = 'pendaftar'; // Default role untuk pendaftar baru

    // Validasi input
    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Semua field wajib diisi."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Email tidak valid."]);
        exit;
    }

    // Cek apakah username atau email sudah terdaftar
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $name, 'email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(["status" => "error", "message" => "Username atau email sudah terdaftar."]);
        exit;
    }

    // Hash/enkripsi password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (:username, :email, :password, :role, NOW())");
    $success = $stmt->execute([
        'username' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => $role
    ]);

    if ($success) {
        echo json_encode(["status" => "success", "message" => "Registrasi berhasil."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Terjadi kesalahan saat registrasi."]);
    }
    exit;
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Sign Up - PMB IPWIJA</title>
    <link href="assets/css/tabler.min.css?1692870487" rel="stylesheet"/>
    <link href="assets/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
    <link href="assets/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
    <link href="assets/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
    <link href="assets/css/demo.min.css?1692870487" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
        font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body class="d-flex flex-column">
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark">
            <img src="assets/img/logo.jpg" width="110" height="32" alt="Tabler" class="navbar-brand-image">
          </a>
        </div>
        <form class="card card-md" id="registration-form" method="POST">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Create new account</h2>
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group input-group-flat">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
              </div>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Buat Akun Baru</button>
            </div>
          </div>
        </form>
        <div class="text-center text-secondary mt-3">
          sudah Punya Akun? <a href="login.php">Login</a>
        </div>
      </div>
    </div>
    <script>
      document.getElementById('registration-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        const response = await fetch(form.action, {
          method: form.method,
          body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
          Swal.fire('Success', result.message, 'success').then(() => {
            window.location.href = 'index.php';
          });
        } else {
          Swal.fire('Error', result.message, 'error');
        }
      });
    </script>
    <script src="assets/js/tabler.min.js?1692870487" defer></script>
    <script src="assets/js/demo.min.js?1692870487" defer></script>
  </body>
</html>
