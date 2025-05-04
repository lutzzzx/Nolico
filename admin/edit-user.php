<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  include "../pages/komponen/koneksi.php";
  session_start();

  $id_user = $_SESSION['id_user'];
  if (!isset($_SESSION['id_user'])) {
      header("Location: ../pages/login.php");
      exit();
  }
  $data_user = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id='$id_user'");
  $user = mysqli_fetch_assoc($data_user);
  if (!$user['admin']) {
    header("Location: access-denied.php");
    exit();
  }

  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $data_pengguna = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id = '$id'");
    $pengguna = mysqli_fetch_assoc($data_pengguna);
  } 
  else {
    header("Location: index.php");
    exit();
  }

  if (isset($_POST['btnSave'])) {
    $id = $_GET['edit'];
    $email = $_POST['emailUpd'];
    $pass = $_POST['passUpd'];
    $role = $_POST['role'];
    if ($pass == null) {
      $sqlupd = "UPDATE pengguna SET email='$email', admin='$role' WHERE id='$id'";
    } else {
      $hash = md5("nolico" . $pass . "mantap" . md5($pass . "nolico"));
      $sqlupd = "UPDATE pengguna SET email='$email', admin='$role', password='$hash' WHERE id='$id'";
    }

    $queryupd = mysqli_query($koneksi, $sqlupd);

    if ($queryupd) {
      if ($role) {
        header("location: admin.php");
      } else {
        header("location: user.php");
      }
    } else {
        echo "Gagal melakukan pembaruan: " . mysqli_error($koneksi);
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no"
    />
    <title>
      Edit Pengguna - Nolico
    </title>
  </head>
  <body class="">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
      <div class="loader">
        <div class="loader-content">
          <div class="spinner-grow align-self-center"></div>
        </div>
      </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <?php
        include "komponen/navbar.php";
    ?>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
      <div class="overlay"></div>
      <div class="cs-overlay"></div>
      <div class="search-overlay"></div>

      <!--  BEGIN SIDEBAR  -->
      <?php
            include "komponen/sidebar.php"
        ?>
      <!--  END SIDEBAR  -->

      <!--  BEGIN CONTENT AREA  -->
      <div id="content" class="main-content">
        <div class="layout-px-spacing">
          <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->

            <!-- /BREADCRUMB -->

            <div class="account-settings-container layout-top-spacing">
              <div class="account-content">
                <div class="tab-content" id="animateLineContent-4">
                  <div
                    class="tab-pane fade show active"
                    id="animated-underline-home"
                    role="tabpanel"
                    aria-labelledby="animated-underline-home-tab"
                  >
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                        <form
                          class="section general-info"
                          method="POST"
                          enctype="multipart/form-data"
                        >
                          <div class="info">
                            <h4 class="">Edit Pengguna</h4>
                            <div class="row">
                              <div class="col-lg-11 mx-auto">
                                <div class="row">
                                  <div
                                    class="col-xl-12 col-lg-12 col-md-8 mt-md-3 mt-4"
                                  >
                                    <div class="form">
                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="email">Email</label>
                                            <input
                                              type="text"
                                              name="emailUpd"
                                              class="form-control mb-3"
                                              id="email"
                                              placeholder="Email "
                                              value="<?= $pengguna['email']; ?>"
                                              required
                                            />
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="Password">Password</label>
                                            <input
                                              type="password"
                                              name="passUpd"
                                              class="form-control mb-3"
                                              id="Password"
                                              placeholder="Password (kosongkan jika tidak diubah) "
                                              
                                            />
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="phone"
                                              >Role</label
                                            >
                                            <select class="form-select" name="role" aria-label="Default select example">
                                              <option value="0" <?= ($pengguna['admin']) ? "" : "selected" ?> >Pengguna</option>
                                              <option value="1" <?= ($pengguna['admin']) ? "selected" : "" ?> >Admin</option>
                                            </select>
                                          </div>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                          <div class="form-group text-end">
                                            <button
                                              class="btn btn-secondary"
                                              name="btnSave"
                                            >
                                              Simpan
                                            </button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../src/plugins/src/waves/waves.min.js"></script>
    <script src="../layouts/vertical-light-menu/app.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <script src="../src/plugins/src/filepond/filepond.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginFileValidateType.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageCrop.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageResize.min.js"></script>
    <script src="../src/plugins/src/filepond/FilePondPluginImageTransform.min.js"></script>
    <script src="../src/plugins/src/filepond/filepondPluginFileValidateSize.min.js"></script>
    <script src="../src/plugins/src/notification/snackbar/snackbar.min.js"></script>
    <script src="../src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
    <script src="../src/assets/js/users/account-settings.js"></script>
    <!--  END CUSTOM SCRIPTS FILE  -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
      $("#uploadButton").on("click", function () {
        $("#c-photo").trigger("click");
      });
    </script>
  </body>
</html>
