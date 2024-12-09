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
        <form class="card card-md" id="registration-form" novalidate>
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Create new account</h2>
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" id="name" class="form-control" placeholder="Enter name">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" id="email" class="form-control" placeholder="Enter email">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group input-group-flat">
                <input type="password" id="password" class="form-control" placeholder="Password">
              </div>
            </div>
            <div class="form-footer">
              <button type="button" id="register-btn" class="btn btn-primary w-100">Buat Akun Baru</button>
            </div>
          </div>
        </form>
        <div class="text-center text-secondary mt-3">
          sudah Punya Akun? <a href="index.php">Login</a>
        </div>
      </div>
    </div>
    <script>
      document.getElementById('register-btn').addEventListener('click', function() {
        // Ambil nilai dari input
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        // Validasi input
        if (!name) {
          Swal.fire('Error', 'Name is required', 'error');
          return;
        }

        if (!email || !validateEmail(email)) {
          Swal.fire('Error', 'Please enter a valid email', 'error');
          return;
        }

        if (!password || password.length < 6) {
          Swal.fire('Error', 'Password must be at least 6 characters long', 'error');
          return;
        }

        // Simpan data ke Local Storage
        const userData = { name, email, password };
        localStorage.setItem('user_' + email, JSON.stringify(userData));

        // Tampilkan notifikasi sukses
        Swal.fire('Success', 'Registration successful!', 'success');

        // Reset form
        document.getElementById('registration-form').reset();
      });

      // Fungsi untuk validasi email
      function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
      }
    </script>
    <script src="assets/js/tabler.min.js?1692870487" defer></script>
    <script src="assets/js/demo.min.js?1692870487" defer></script>
  </body>
</html>
