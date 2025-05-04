<?php
  include("komponen/koneksi.php");
  $alert = "";

  function isExist($koneksi, $email) {
    $cek_email = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE email='$email'");
    if ($cek_email && mysqli_num_rows($cek_email) > 0) {
        return true; 
    } else {
        return false;
    }
  }

  if (isset($_POST['btnRegister'])) {
    $nama = $_POST['inpNama'];
    $email = $_POST['inpEmail'];
    $pass = $_POST['inpPass'];
    $hash = md5("nolico" . $pass . "mantap" . md5($pass . "nolico"));
    if (isExist($koneksi, $email)) {
      $alert = "show";
    } else {
      $insert = mysqli_query($koneksi, "INSERT INTO pengguna(nama, email, password) VALUES ('$nama', '$email', '$hash')");
      header("Location: login.php");
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Register - Nolico</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.min-2.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link
      rel="stylesheet"
      href="../src/font-awesome/css/font-awesome.min.css"
    />
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="../src/assets/css/light/auth.css" />
  </head>

  <body data-theme="light" class="font-nunito">
    <!-- WRAPPER -->
    <div id="wrapper" class="theme-cyan">
      <div class="vertical-align-wrap">
        <div class="vertical-align-middle auth-main">
          <div class="auth-box">
            <div class="top">
              <h2>Nolico</h2>
            </div>
            <div class="card">
              <div class="header">
                <p class="lead">Buat akun baru</p>
              </div>
              <div class="body">
                <form class="form-auth-small" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                  <div class="form-group">
                    <label for="signup-name" class="control-label sr-only"
                      >Nama</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="signup-name"
                      name="inpNama"
                      placeholder="Nama"
                    />
                  </div>
                  <div class="form-group">
                    <label for="signup-email" class="control-label sr-only"
                      >Email</label
                    >
                    <input
                      type="email"
                      class="form-control"
                      id="signup-email"
                      name="inpEmail"
                      placeholder="Email"
                    />
                  </div>
                  <div class="form-group">
                    <label for="signup-password" class="control-label sr-only"
                      >Password</label
                    >
                    <input
                      type="password"
                      class="form-control"
                      id="signup-password"
                      name="inpPass"
                      placeholder="Password"
                    />
                  </div>
                  <div class="form-group">
                    <label for="signup-password" class="control-label sr-only"
                      >Password</label
                    >
                    <input
                      type="password"
                      class="form-control"
                      id="signup-password"
                      placeholder="Konfirmasi password"
                    />
                  </div>
                  <button
                    type="submit"
                    class="btn btn-primary btn-lg btn-block"
                    name="btnRegister"
                  >
                    Buat Akun
                  </button>
                  <div class="bottom">
                    <span class="helper-text"
                      >Sudah mempunyai akun?
                      <a href="login.php">Login</a></span
                    >
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END WRAPPER -->

    <!-- Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
      <div class="toast align-items-center text-bg-danger border-0 <?php echo $alert ?>">
        <div class="d-flex">
          <div class="toast-body">
            Email telah terdaftar, silakan login!
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
    <!-- End Notification -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
