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
  
  if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_note = mysqli_query($koneksi, "DELETE FROM catatan WHERE id_user = '$id'");
    $delete_todo = mysqli_query($koneksi, "DELETE FROM tugas WHERE id_user = '$id'");
    $delete_contact = mysqli_query($koneksi, "DELETE FROM kontak WHERE id_user = '$id'");
    $delete_label = mysqli_query($koneksi, "DELETE FROM label WHERE id_user = '$id'");
    $delete_label_note = mysqli_query($koneksi, "DELETE FROM label_catatan WHERE id_user = '$id'");
    $delete_user = mysqli_query($koneksi, "DELETE FROM pengguna WHERE id = '$id'");
    if ($delete_user) {
      header("location: user.php");
      exit();
    } else {
      echo "Gagal melakukan pembaruan: " . mysqli_error($koneksi);
    }
  }
  $data_pengguna = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE admin = false ORDER BY id DESC");
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
      Daftar Pengguna - Nolico
    </title>
    <?php
      include "../pages/komponen/style.php";
    ?>
    <!-- END PAGE LEVEL STYLES -->
  </head>
  <body class="layout-boxed">
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
        include "komponen/sidebar.php";
      ?>
      <!--  END SIDEBAR  -->

      <!--  BEGIN CONTENT AREA  -->
      <div id="content" class="main-content">
        <div class="layout-px-spacing">
          <div class="middle-content container-xxl p-0">

            <div class="row mt-4">
              <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                  <div class="widget-content widget-content-area">
                    <table
                      id="html5-extension"
                      class="table dt-table-hover"
                      style="width: 100%"
                    >
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Email</th>
                          <th>Nama</th>
                          <th>Pekerjaan</th>
                          <th>Tanggal Lahir</th>
                          <th>Nomor Telepon</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $no = 0;
                          while ($pengguna = mysqli_fetch_assoc($data_pengguna)) {
                            $no++;
                        ?>
                            <tr>
                              <td><?= $no . "." ?></td>
                              <td><?= $pengguna['email'] ?></td>
                              <td><?= $pengguna['nama'] ?></td>
                              <td><?= $pengguna['pekerjaan'] ?></td>
                              <td><?= $pengguna['tgl_lahir'] ?></td>
                              <td><?= $pengguna['no_telp'] ?></td>
                              <td>
                                <a
                                  class="badge badge-light-primary text-start me-2 action-edit"
                                  href="edit-user.php?edit=<?= $pengguna['id'] ?>"
                                  ><svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-edit-3"
                                  >
                                    <path d="M12 20h9"></path>
                                    <path
                                      d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"
                                    ></path></svg
                                ></a>
                                <a
                                  class="badge badge-light-danger text-start action-delete"
                                  href="#"
                                  data-bs-toggle="modal"
                                  data-bs-target="#exampleModal<?= $pengguna['id'] ?>"
                                >
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
                                    class="feather feather-trash"
                                  >
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                      d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                    ></path>
                                  </svg>
                                </a>
                              </td>
                            </tr>
                            <div
                              class="modal fade"
                              id="exampleModal<?= $pengguna['id'] ?>"
                              tabindex="-1"
                              role="dialog"
                              aria-labelledby="exampleModalLabel"
                              aria-hidden="true"
                            >
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus</h5>
                                    <button
                                      type="button"
                                      class="btn-close"
                                      data-bs-dismiss="modal"
                                      aria-label="Close"
                                    >
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <p class="modal-text">Apakah anda yakin ingin menghapus <?= $pengguna['email'] ?>?</p>
                                  </div>
                                  <div class="modal-footer">
                                    <button class="btn btn btn-light-dark" data-bs-dismiss="modal">
                                      <i class="flaticon-cancel-12"></i> Batal
                                    </button>
                                    <a
                                      href="user.php?delete=<?= $pengguna['id'] ?>"
                                      class="btn btn-danger"
                                      >Hapus</a
                                    >
                                  </div>
                                </div>
                              </div>
                            </div>
                        <?php
                          }
                        ?>
                      </tbody>
                    </table>
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
    <script src="../src/plugins/src/global/vendors.min.js"></script>
    <script src="../src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../src/plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="../src/plugins/src/waves/waves.min.js"></script>
    <script src="../layouts/vertical-light-menu/app.js"></script>

    <script src="../src/assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="../src/plugins/src/table/datatable/datatables.js"></script>
    <script src="../src/plugins/src/table/datatable/button-ext/dataTables.buttons.min.js"></script>
    <script src="../src/plugins/src/table/datatable/button-ext/jszip.min.js"></script>
    <script src="../src/plugins/src/table/datatable/button-ext/buttons.html5.min.js"></script>
    <script src="../src/plugins/src/table/datatable/button-ext/buttons.print.min.js"></script>
    <script src="../src/plugins/src/table/datatable/custom_miscellaneous.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        var currentURL = window.location.href;
        if (currentURL.indexOf("user.php") !== -1) {
            document.getElementById("user").classList.add("active");
        }

    </script>
  </body>
</html>
