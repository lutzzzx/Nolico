<?php
  include("komponen/koneksi.php");
  session_start();

  $alert_exist = "";
  $alert_wrong = "";

  if (isset($_POST['btnLogin'])) {
    $email = $_POST['inpEmail'];
    $pass = $_POST['inpPass'];
    $hash = md5("nolico" . $pass . "mantap" . md5($pass . "nolico"));
    
    $cari_email = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE email = '$email'");
    if ($cari_email && mysqli_num_rows($cari_email) > 0) {
      $data_user = mysqli_fetch_assoc($cari_email);
      if ($data_user['password'] == $hash) {
        $_SESSION['id_user'] = $data_user['id'];
        if ($data_user['admin']) {
          header("Location: ../admin/index.php");
          exit();
        } else {
          header("Location: index.php");
          exit();
        }
      } else {
        $alert_wrong = "show";
      }
    } else {
      $alert_exist = "show";
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login - Nolico</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="Iconic Bootstrap 4.5.0 Admin Template" />
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
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
                <p class="lead">Masuk ke akun anda</p>
              </div>
              <div class="body">
                <form class="form-auth-small"  action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                  <div class="form-group">
                    <label for="signin-email" class="control-label sr-only"
                      >Email</label
                    >
                    <input
                      type="email"
                      class="form-control"
                      id="signin-email"
                      name="inpEmail"
                      placeholder="Email"
                    />
                  </div>
                  <div class="form-group">
                    <label for="signin-password" class="control-label sr-only"
                      >Password</label
                    >
                    <input
                      type="password"
                      class="form-control"
                      id="signin-password"
                      name="inpPass"
                      placeholder="Password"
                    />
                  </div>
                  <button
                    type="submit"
                    class="btn btn-primary btn-lg btn-block"
                    name="btnLogin"
                  >
                    Login
                  </button>
                  <div class="bottom">
                    <span
                      >Belum mempunyai akun?
                      <a href="register.php">Buat akun</a></span
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
      <div class="toast align-items-center text-bg-danger border-0 <?php echo $alert_exist ?>">
        <div class="d-flex">
          <div class="toast-body">
            Email belum terdaftar, silakan buat akun!
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3">
      <div class="toast align-items-center text-bg-danger border-0 <?php echo $alert_wrong ?>">
        <div class="d-flex">
          <div class="toast-body">
            Kata sandi salah, silakan coba lagi!
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
    <!-- End Notification -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
