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

  $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengguna");
  $jumlah_pengguna = mysqli_fetch_assoc($query);
  $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM catatan");
  $jumlah_catatan = mysqli_fetch_assoc($query);
  $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tugas");
  $jumlah_tugas = mysqli_fetch_assoc($query);
  $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kontak");
  $jumlah_kontak = mysqli_fetch_assoc($query);
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
      Admin Dashboard - Nolico
    </title>
    <?php
      include "../pages/komponen/style.php";
    ?>
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
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
      <div class="search-overlay"></div>

      <!--  BEGIN SIDEBAR  -->
      <?php
        include "komponen/sidebar.php";
      ?>
      <!--  END SIDEBAR  -->

      <!--  BEGIN CONTENT AREA  -->
      <div id="content" class="main-content">
        <div class="layout-px-spacing">
          <div class="middle-content container-xxl p-0">
            <div class="row layout-top-spacing">
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                <div class="widget widget-t-sales-widget widget-m-customers">
                  <div class="media">
                    <div class="icon ml-2">
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
                        class="feather feather-users"
                      >
                        <path
                          d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"
                        ></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                      </svg>
                    </div>
                    <div class="media-body">
                      <p class="widget-text">Jumlah Pengguna</p>
                      <p class="widget-numeric-value"><?php echo $jumlah_pengguna['total'] ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                <div class="widget widget-t-sales-widget widget-m-sales">
                  <div class="media">
                    <div class="icon ml-2">
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
                        class="feather feather-file-text"
                      >
                        <path
                          d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                        ></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                      </svg>
                    </div>
                    <div class="media-body">
                      <p class="widget-text">Jumlah Catatan</p>
                      <p class="widget-numeric-value"><?php echo $jumlah_catatan['total'] ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                <div class="widget widget-t-sales-widget widget-m-orders">
                  <div class="media">
                    <div class="icon ml-2">
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
                        class="feather feather-edit"
                      >
                        <path
                          d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                        ></path>
                        <path
                          d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                        ></path>
                      </svg>
                    </div>
                    <div class="media-body">
                      <p class="widget-text">Jumlah Tugas</p>
                      <p class="widget-numeric-value"><?php echo $jumlah_tugas['total'] ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                <div class="widget widget-t-sales-widget widget-m-income">
                  <div class="media">
                    <div class="icon ml-2">
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
                        class="feather feather-map-pin"
                      >
                        <path
                          d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"
                        ></path>
                        <circle cx="12" cy="10" r="3"></circle>
                      </svg>
                    </div>
                    <div class="media-body">
                      <p class="widget-text">Jumlah Kontak</p>
                      <p class="widget-numeric-value"><?php echo $jumlah_tugas['total'] ?></p>
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

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="../src/plugins/src/apex/apexcharts.min.js"></script>
    <script src="../src/assets/js/dashboard/dash_2.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        var currentURL = window.location.href;
        if (currentURL.indexOf("index.php") !== -1) {
            document.getElementById("index").classList.add("active");
        }
    </script>
  </body>
</html>
