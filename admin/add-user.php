<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  include "../pages/komponen/koneksi.php";
  session_start();

  $id_user = $_SESSION['id_user'];

  if (!isset($_SESSION['id_user'])) {
      header("Location: login.php");
      exit();
  }
  if (isset($_POST['btnSave'])){
    $email = $_POST['emailUpd'];
    $pass = $_POST['passUpd'];
    $nama  = $_POST['userUpd'];
    $pekerjaan = $_POST['jobUpd'];
    $tgl_lahir = $_POST['ageUpd'];
    $no_hp     = $_POST['nohpUpd'];

    $updateUpload   = $col['foto'];

    if (!empty($_FILES['fotoUpd']['name'])){
        $updatePhoto    = $_FILES['fotoUpd']['name'];
        $updateUpload   = 'photo-profile/'.$updatePhoto;
        move_uploaded_file($_FILES['fotoUpd']['tmp_name'], $updateUpload);      
    }
    if ($tgl_lahir == null) {
        $sqlupd = "INSERT INTO pengguna (email, password, nama, foto, pekerjaan, tgl_lahir, no_hp, admin) VALUES ('$','','','','','','','','','',)";
    } else {
        $sqlupd = "UPDATE pengguna SET foto = '$updateUpload', nama = '$nama', tgl_lahir ='$tgl_lahir', pekerjaan ='$pekerjaan', no_hp='$no_hp' WHERE id = '$id_user'";
    }
    $queryupd = mysqli_query($koneksi, $sqlupd);

    if ($queryupd) {
        header("location: user-profile.php");
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
      Tambah pengguna - Nolico
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
                            <h4 class="">Tambah Pengguna</h4>
                            <div class="row">
                              <div class="col-lg-11 mx-auto">
                                <div class="row">
                                  <div class="col-xl-2 col-lg-12 col-md-4">
                                    <div class="profile-image mt-4 pe-md-4">
                                      <!-- // The classic file input element we'll enhance
                                                                        // to a file pond, we moved the configuration
                                                                        // properties to JavaScript -->

                                      <img id="fotoEdit" src="../src/assets/img/user.png"
                                      style="border-radius: 50%; width: 100px;
                                      height: 100px; object-fit: cover;"
                                      alt="Upload Foto">

                                      <input
                                        style="
                                          margin-top: 15px;
                                          margin-left: -15px;
                                        "
                                        type="file"
                                        id="c-photo"
                                        name="fotoUpd"
                                        class="form-control"
                                        accept=".jpg, .png, .jpeg"
                                      />
                                    </div>
                                  </div>
                                  <div
                                    class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4"
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
                                              required
                                            />
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="password">Kata sandi</label>
                                            <input
                                              type="password"
                                              name="passUpd"
                                              class="form-control mb-3"
                                              id="password"
                                              placeholder="Kata sandi "
                                              required
                                            />
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="fullName">Nama</label>
                                            <input
                                              type="text"
                                              name="userUpd"
                                              class="form-control mb-3"
                                              id="fullName"
                                              placeholder="Nama "
                                              required
                                            />
                                          </div>
                                        </div>

                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="profession"
                                              >Pekerjaan</label
                                            >
                                            <input
                                              type="text"
                                              name="jobUpd"
                                              class="form-control mb-3"
                                              id="profession"
                                              placeholder="Pekerjaan (opsional)"
                                            />
                                          </div>
                                        </div>

                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="address"
                                              >Tanggal Lahir (opsional)</label
                                            >
                                            <input
                                              type="date"
                                              name="ageUpd"
                                              class="form-control mb-3"
                                              id="address"
                                              placeholder="tgl_lahir "
                                            />
                                          </div>
                                        </div>

                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="phone"
                                              >Nomor Telepon</label
                                            >
                                            <input
                                              type="text"
                                              name="nohpUpd"
                                              class="form-control mb-3"
                                              id="phone"
                                              placeholder="Nomor telepon (opsional)"
                                            />
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="phone"
                                              >Role</label
                                            >
                                            <select class="form-select" name="role" aria-label="Default select example">
                                              <option value="0" selected>Pengguna</option>
                                              <option value="1">Admin</option>
                                            </select>
                                          </div>
                                        </div>

                                        <div class="col-md-12 mt-1">
                                          <div class="form-group text-end">
                                            <button
                                              class="btn btn-secondary"
                                              name="btnSave"
                                            >
                                              Tambah
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

        <!--  BEGIN FOOTER  -->
        <div class="footer-wrapper">
          <div class="footer-section f-section-2">
            <p class="">
              Coded with
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-heart"
              >
                <path
                  d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                ></path>
              </svg>
            </p>
          </div>
        </div>
        <!--  END FOOTER  -->
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
