<?php
  include 'komponen/koneksi.php';
  session_start();

  $id_user = $_SESSION['id_user'];

  if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
  }

  $data_catatan = mysqli_query($koneksi, "SELECT * FROM catatan WHERE id_user=$id_user ORDER BY tanggal DESC");
  $data_label = mysqli_query($koneksi, "SELECT * FROM label WHERE id_user=$id_user");
  $fav_active = false;
  $label_active = "";
  $edit_catatan = false;

  //CRUD CATATAN
  if (isset($_POST['tambah-catatan'])) {
    $judul = $_POST['judul'];
    $konten = $_POST['konten'];
    $label = $_POST['label'];

    $insert_catatan = mysqli_query($koneksi, "INSERT INTO catatan(judul, konten, id_user) VALUES ('$judul', '$konten', '$id_user')");
    $id_catatan = mysqli_insert_id($koneksi);
    if (!empty($label)) {
      foreach ($label as $id_label) {
        $insert_label = mysqli_query($koneksi, "INSERT INTO label_catatan(id_catatan, id_label, id_user) VALUES ('$id_catatan', '$id_label','$id_user')");
      }
    }
    if ($insert_catatan) {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
  }
  if (isset($_GET['fav'])) {
    $id = $_GET['fav'];
    $stmt_select= mysqli_query($koneksi, "SELECT favorit FROM catatan WHERE id=$id AND id_user=$id_user");
    $data = mysqli_fetch_assoc($stmt_select);
    $new_favorite = ($data['favorit'] == 1) ? 0 : 1;
    $stmt_update = mysqli_query($koneksi, "UPDATE catatan SET favorit=$new_favorite WHERE id=$id");
    if ($stmt_update) {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
  }
  if (isset($_GET['del_note'])) {
    $id = $_GET['del_note'];
    $stmt_delete = mysqli_query($koneksi, "DELETE FROM catatan WHERE id=$id AND id_user=$id_user");
    $stmt_delete_label = mysqli_query($koneksi, "DELETE FROM label_catatan WHERE id_catatan=$id");
    if ($stmt_delete) {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
  }
  if (isset($_GET['edit_catatan'])) {
    $id = $_GET['edit_catatan'];
    $stmt_select_edit = mysqli_query($koneksi, "SELECT * FROM catatan WHERE id=$id");
    $data_edit_catatan = mysqli_fetch_assoc($stmt_select_edit);

    $label_query = mysqli_query($koneksi, "SELECT id_label FROM label_catatan WHERE id_catatan=$id");
    $label_edit = array();
    while ($data = mysqli_fetch_assoc($label_query)) {
        $label_edit[] = $data['id_label'];
    }
    $edit_catatan = true;
  }
  if (isset($_POST['edit-catatan'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $konten = $_POST['konten'];
    $label = $_POST['label'];
    $stmt_delete_label = mysqli_query($koneksi, "DELETE FROM label_catatan WHERE id_catatan = '$id'");
    $stmt_update = mysqli_query($koneksi, "UPDATE catatan SET judul='$judul', konten='$konten' WHERE id='$id' AND id_user='$id_user'");
    if (!empty($label)) {
      foreach ($label as $id_label) {
        $insert_label = mysqli_query($koneksi, "INSERT INTO label_catatan(id_catatan, id_label, id_user) VALUES ('$id', '$id_label','$id_user')");
      }
    }
    if ($stmt_update) {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
  }
  if (isset($_GET['label'])) {
    $label = $_GET['label'];
    if ($label == "fav") {
      $data_catatan = mysqli_query($koneksi, "SELECT * FROM catatan WHERE id_user=$id_user AND favorit=true ORDER BY tanggal DESC");
      $label_active = "fav";
    }
    else {
      $data_catatan = mysqli_query($koneksi, "SELECT catatan.* FROM catatan JOIN label_catatan
      ON catatan.id = label_catatan.id_catatan WHERE label_catatan.id_label = $label AND
      label_catatan.id_user=$id_user ORDER BY catatan.tanggal DESC");    
      $label_active = $label;  
    }
  }

  //CRUD LABEL
  if (isset($_POST['tambah-label'])) {
    $nama = $_POST['nama'];
    $insert_label = mysqli_query($koneksi, "INSERT INTO label(nama, id_user) VALUES ('$nama', '$id_user')");
    if ($insert_label) {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
  }
  if (isset($_GET['del_label'])) {
    $id = $_GET['del_label'];
    $stmt_delete = mysqli_query($koneksi, "DELETE FROM label WHERE id=$id AND id_user=$id_user");
    $stmt_delete_label = mysqli_query($koneksi, "DELETE FROM label_catatan WHERE id_label=$id");
    if ($stmt_delete) {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
  }
  if (isset($_POST['edit-label'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $stmt_update = mysqli_query($koneksi, "UPDATE label SET nama='$nama' WHERE id='$id' AND id_user='$id_user'");
    if ($stmt_update) {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
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
    <title>Catatan | Nolico</title>
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
            <div class="row app-notes layout-top-spacing" id="cancel-row">
              <div class="col-lg-12">
                <div class="app-hamburger-container">
                  <div class="hamburger">
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
                      class="feather feather-menu chat-menu d-xl-none"
                    >
                      <line x1="3" y1="12" x2="21" y2="12"></line>
                      <line x1="3" y1="6" x2="21" y2="6"></line>
                      <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                  </div>
                </div>

                <div class="app-container">
                  <div class="app-note-container">
                    <div class="app-note-overlay"></div>

                    <div class="tab-title">
                      <div class="row">
                        <div class="col-md-12 col-sm-12 col-12 mb-5">
                          <a
                            id="btn-tambah-catatan"
                            class="btn btn-primary w-100 mb-4"
                            href="javascript:void(0);"
                            >Tambah Catatan</a
                          >
                          <ul
                            class="nav nav-pills d-block"
                            id="pills-tab3"
                            role="tablist"
                          >
                            <li class="nav-item">
                              <a href="notes.php"
                                class="nav-link list-actions <?php echo ($label_active == null) ? 'active' : ''; ?>"
                                id="all-notes"
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
                                  class="feather feather-edit"
                                >
                                  <path
                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                  ></path>
                                  <path
                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                  ></path>
                                </svg>
                                Semua Catatan</a
                              >
                            </li>
                            <li class="nav-item">
                              <a href="notes.php?label=fav" class="nav-link list-actions <?php echo ($label_active == "fav") ? 'active' : ''; ?>" id="note-fav"
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
                                  class="feather feather-star"
                                >
                                  <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
                                  ></polygon>
                                </svg>
                                Favorit</a
                              >
                            </li>
                          </ul>

                          <p class="group-section">
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
                              class="feather feather-tag"
                            >
                              <path
                                d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"
                              ></path>
                              <line x1="7" y1="7" x2="7" y2="7"></line>
                            </svg>
                            Label
                          </p>

                          <a
                            id="btn-tambah-label"
                            class="btn btn-secondary w-100 mb-4"
                            href="javascript:void(0);"
                            >Tambah Label</a
                          >

                          <ul
                            class="nav nav-pills d-block group-list"
                            id="pills-tab"
                            role="tablist"
                          >
                            <?php
                              while ($label = mysqli_fetch_assoc($data_label)) {
                            ?>
                                <li class='mb-2'>
                                  <div class='btn-group mb-2 me-4 w-100'>
                                    <a href='notes.php?label=<?= $label['id'] ?>' class='btn <?= ($label_active == $label['id']) ? 'btn-secondary' : 'btn-outline-secondary' ?> w-75 text-start'><?= $label['nama'] ?></a>
                                    <button
                                      type='button'
                                      class='btn btn-secondary dropdown-toggle dropdown-toggle-split w-25'
                                      data-bs-toggle='dropdown'
                                      aria-haspopup='true'
                                      aria-expanded='false'
                                    >
                                      <svg
                                        xmlns='http://www.w3.org/2000/svg'
                                        width='24'
                                        height='24'
                                        viewBox='0 0 24 24'
                                        fill='none'
                                        stroke='currentColor'
                                        stroke-width='2'
                                        stroke-linecap='round'
                                        stroke-linejoin='round'
                                        class='feather feather-chevron-down'
                                      >
                                        <polyline points='6 9 12 15 18 9'></polyline>
                                      </svg>
                                      <span class='visually-hidden'>Toggle Dropdown</span>
                                    </button>
                                    <div class='dropdown-menu'>
                                      <a class='dropdown-item' href='javascript:void(0);' data-bs-toggle="modal" data-bs-target="#edit-label-<?= $label['id'] ?>">Edit</a>
                                      <a class='dropdown-item' href='javascript:void(0);' data-bs-toggle="modal" data-bs-target="#hapus-label-<?= $label['id'] ?>">Hapus</a>
                                    </div>
                                  </div>     
                                </li>
                                <div
                                  class="modal fade"
                                  id="edit-label-<?= $label['id'] ?>"
                                  tabindex="-1"
                                  role="dialog"
                                  aria-labelledby="notesMailModalTitle"
                                  aria-hidden="true"
                                >
                                  <div
                                    class="modal-dialog modal-dialog-centered"
                                    role="document"
                                  >
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5
                                          class="modal-title add-title"
                                          id="notesMailModalTitleeLabel"
                                        >
                                          Edit Label
                                        </h5>
                                        <button
                                          type="button"
                                          class="btn-close"
                                          data-bs-dismiss="modal"
                                          aria-label="Close"
                                        >
                                          <svg
                                            aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="feather feather-x"
                                          >
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                          </svg>
                                        </button>
                                      </div>

                                      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                        <div class="modal-body">
                                          <div class="notes-box">
                                            <div class="notes-content">
                                              <div class="row">
                                                <div class="col-md-12">
                                                  <div class="d-flex note-title">
                                                    <input type="hidden" name="id" value="<?= $label['id']; ?>">
                                                    <input
                                                      type="text"
                                                      id="n-title"
                                                      class="form-control"
                                                      maxlength="25"
                                                      placeholder="Nama label"
                                                      name="nama"
                                                      value="<?= $label['nama']; ?>"
                                                    />
                                                  </div>
                                                  <span class="validation-text"></span>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button class="btn" data-bs-dismiss="modal">
                                            Batal
                                          </button>
                                          <input type="submit" class="btn btn-primary" value="Simpan" name="edit-label">
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                                <div
                                  class="modal fade"
                                  id="hapus-label-<?= $label['id'] ?>"
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
                                        <p class="modal-text">Apakah anda yakin ingin menghapus label <b><?= $label['nama'] ?></b>?</p>
                                      </div>
                                      <div class="modal-footer">
                                        <button class="btn btn btn-light-dark" data-bs-dismiss="modal">
                                          <i class="flaticon-cancel-12"></i> Batal
                                        </button>
                                        <a
                                          href="notes.php?del_label=<?= $label['id'] ?>"
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
                          </ul>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12 text-center">

                        </div>
                      </div>
                    </div>

                    <div id="ct" class="note-container note-grid">
                      <?php
                        while ($catatan = mysqli_fetch_assoc($data_catatan)) {
                          $favorit = "";
                          if ($catatan['favorit'] == true) {
                              $favorit = "note-fav";
                          }  
                          $format_tanggal = date('d/m/Y', strtotime($catatan['tanggal']));
                          $data_label_catatan = mysqli_query($koneksi, "SELECT label.* FROM label JOIN label_catatan ON label.id = label_catatan.id_label WHERE label_catatan.id_catatan = $catatan[id] AND label_catatan.id_user=$id_user");
                      ?>
                          <div class='note-item all-notes <?= $favorit; ?>'>
                            <div class='note-inner-content'>
                              <a href='#' data-bs-toggle="modal" data-bs-target="#edit-catatan-<?= $catatan['id'] ?>">
                                <div class='note-content' >
                                  <p
                                    class='note-title'
                                  >
                                    <?= $catatan['judul']; ?>
                                  </p>
                                  <p class='meta-time'><?= $format_tanggal; ?></p>
                                  <div class='note-description-content'>
                                    <p
                                      class='note-description'
                                    >
                                      <?= $catatan['konten']; ?>
                                    </p>
                                  </div>
                                </div>
                              </a>
                              <div class='note-action d-flex justify-content-between mb-2'>
                                <a href='notes.php?del_note=<?= $catatan['id']; ?>'>
                                  <svg
                                    xmlns='http://www.w3.org/2000/svg'
                                    width='24'
                                    height='24'
                                    viewBox='0 0 24 24'
                                    fill='none'
                                    stroke='currentColor'
                                    stroke-width='2'
                                    stroke-linecap='round'
                                    stroke-linejoin='round'
                                    class='feather feather-trash-2 delete-note'
                                  >
                                    <polyline points='3 6 5 6 21 6'></polyline>
                                    <path
                                      d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'
                                    ></path>
                                    <line x1='10' y1='11' x2='10' y2='17'></line>
                                    <line x1='14' y1='11' x2='14' y2='17'></line>
                                  </svg>
                                </a>
                                <a href='notes.php?fav=<?= $catatan['id']; ?>'>
                                  <svg
                                    xmlns='http://www.w3.org/2000/svg'
                                    width='24'
                                    height='24'
                                    viewBox='0 0 24 24'
                                    fill='none'
                                    stroke='currentColor'
                                    stroke-width='2'
                                    stroke-linecap='round'
                                    stroke-linejoin='round'
                                    class='feather feather-star fav-note'
                                  >
                                    <polygon
                                      points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'
                                    ></polygon>
                                  </svg>
                                </a>
                              </div>
                              <?php
                                while ($label = mysqli_fetch_assoc($data_label_catatan)) {
                                  echo "<span class='badge badge-light-dark'>#$label[nama]</span> ";
                                }
                              ?>
                            </div>
                          </div>
                          <div
                            class="modal fade"
                            id="edit-catatan-<?= $catatan['id'] ?>"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="notesMailModalTitle"
                            aria-hidden="true"
                          >
                            <div
                              class="modal-dialog modal-dialog-centered"
                              role="document"
                            >
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5
                                    class="modal-title add-title"
                                    id="notesMailModalTitleeLabel"
                                  >
                                    Edit Catatan
                                  </h5>
                                  <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                                  >
                                    <svg
                                      aria-hidden="true"
                                      xmlns="http://www.w3.org/2000/svg"
                                      width="24"
                                      height="24"
                                      viewBox="0 0 24 24"
                                      fill="none"
                                      stroke="currentColor"
                                      stroke-width="2"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      class="feather feather-x"
                                    >
                                      <line x1="18" y1="6" x2="6" y2="18"></line>
                                      <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                  </button>
                                </div>

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                  <div class="modal-body">
                                    <div class="notes-box">
                                      <div class="notes-content">
                                        <div class="row">
                                          <div class="col-md-12 mb-4">
                                            <div class="d-flex note-title">
                                              <input
                                                type="hidden"
                                                name="id"
                                                value = "<?= $catatan['id']; ?>"
                                              />
                                              <input
                                                type="text"
                                                id="n-title"
                                                class="form-control"
                                                maxlength="25"
                                                placeholder="Judul"
                                                name="judul"
                                                value = "<?= $catatan['judul']; ?>"
                                              />
                                            </div>
                                            <span class="validation-text"></span>
                                          </div>

                                          <div class="col-md-12 mb-3">
                                            <div class="d-flex note-description">
                                              <textarea
                                                id="n-description"
                                                class="form-control"
                                                maxlength="500"
                                                placeholder="Isi Catatan"
                                                rows="8"
                                                name="konten"
                                              ><?= $catatan['konten']; ?></textarea>
                                            </div>
                                            <span class="validation-text"></span>
                                            <span class="d-inline-block mt-1 text-danger"
                                              >Maks. 500 karakter</span
                                            >
                                          </div>
                                          <div class="col-md-12">
                                            <select id="select-state-edit" name="label[]" multiple placeholder="Pilih label" autocomplete="off">
                                              <?php
                                                $label_catatan = [];
                                                $query = mysqli_query($koneksi, "SELECT * FROM label_catatan WHERE id_catatan='$catatan[id]'");
                                                while ($data = mysqli_fetch_assoc($query)) {
                                                  $label_catatan[] = $data['id_label'];
                                                }
                                                $query_label = mysqli_query($koneksi, "SELECT * FROM label WHERE id_user='$id_user'");
                                                while ($label = mysqli_fetch_assoc($query_label)) {
                                                  $selected = in_array($label['id'], $label_catatan) ? 'selected' : '';
                                                  echo "<option value='$label[id]' $selected>$label[nama]</option>";
                                                }
                                              ?>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button class="btn" data-bs-dismiss="modal">
                                      Batal
                                    </button>
                                    <input type="submit" class="btn btn-primary" value="Simpan" name="edit-catatan">
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                      <?php
                        }
                      ?>
                    </div>
                  </div>
                </div>

                <!-- MODAL -->
                <div
                  class="modal fade"
                  id="modal-tambah-catatan"
                  tabindex="-1"
                  role="dialog"
                  aria-labelledby="notesMailModalTitle"
                  aria-hidden="true"
                >
                  <div
                    class="modal-dialog modal-dialog-centered"
                    role="document"
                  >
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5
                          class="modal-title add-title"
                          id="notesMailModalTitleeLabel"
                        >
                          Tambah Catatan
                        </h5>
                        <button
                          type="button"
                          class="btn-close"
                          data-bs-dismiss="modal"
                          aria-label="Close"
                        >
                          <svg
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="feather feather-x"
                          >
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                          </svg>
                        </button>
                      </div>

                      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="modal-body">
                          <div class="notes-box">
                            <div class="notes-content">
                              <div class="row">
                                <div class="col-md-12 mb-4">
                                  <div class="d-flex note-title">
                                    <input
                                      type="text"
                                      id="n-title"
                                      class="form-control"
                                      maxlength="25"
                                      placeholder="Judul"
                                      name="judul"
                                    />
                                  </div>
                                  <span class="validation-text"></span>
                                </div>

                                <div class="col-md-12 mb-3">
                                  <div class="d-flex note-description">
                                    <textarea
                                      id="n-description"
                                      class="form-control"
                                      maxlength="500"
                                      placeholder="Isi Catatan"
                                      rows="8"
                                      name="konten"
                                    ></textarea>
                                  </div>
                                  <span class="validation-text"></span>
                                  <span class="d-inline-block mt-1 text-danger"
                                    >Maks. 500 karakter</span
                                  >
                                </div>
                                <div class="col-md-12">
                                  <select id="select-state" name="label[]" multiple placeholder="Pilih label" autocomplete="off">
                                    <?php
                                      $data_label = mysqli_query($koneksi, "SELECT * FROM label WHERE id_user = $id_user");
                                      while ($label = mysqli_fetch_assoc($data_label)) {
                                        echo "
                                          <option value='$label[id]'>$label[nama]</option>
                                        ";
                                      }
                                    ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button class="btn" data-bs-dismiss="modal">
                            Batal
                          </button>
                          <input type="submit" class="btn btn-primary" value="Tambah" name="tambah-catatan">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <div
                  class="modal fade"
                  id="modal-tambah-label"
                  tabindex="-1"
                  role="dialog"
                  aria-labelledby="notesMailModalTitle"
                  aria-hidden="true"
                >
                  <div
                    class="modal-dialog modal-dialog-centered"
                    role="document"
                  >
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5
                          class="modal-title add-title"
                          id="notesMailModalTitleeLabel"
                        >
                          Tambah Label
                        </h5>
                        <button
                          type="button"
                          class="btn-close"
                          data-bs-dismiss="modal"
                          aria-label="Close"
                        >
                          <svg
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="feather feather-x"
                          >
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                          </svg>
                        </button>
                      </div>

                      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="modal-body">
                          <div class="notes-box">
                            <div class="notes-content">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="d-flex note-title">
                                    <input
                                      type="text"
                                      id="n-title"
                                      class="form-control"
                                      maxlength="25"
                                      placeholder="Nama label"
                                      name="nama"
                                    />
                                  </div>
                                  <span class="validation-text"></span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button class="btn" data-bs-dismiss="modal">
                            Batal
                          </button>
                          <input type="submit" class="btn btn-primary" value="Tambah" name="tambah-label">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div
                  class="modal fade"
                  id="modal-edit-label"
                  tabindex="-1"
                  role="dialog"
                  aria-labelledby="notesMailModalTitle"
                  aria-hidden="true"
                >
                  <div
                    class="modal-dialog modal-dialog-centered"
                    role="document"
                  >
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5
                          class="modal-title add-title"
                          id="notesMailModalTitleeLabel"
                        >
                          Edit Label
                        </h5>
                        <button
                          type="button"
                          class="btn-close"
                          data-bs-dismiss="modal"
                          aria-label="Close"
                        >
                          <svg
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="feather feather-x"
                          >
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                          </svg>
                        </button>
                      </div>

                      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <?php
                          if (isset($_GET['edit_label'])) {
                            $id = $_GET['edit_label'];
                            $data_label = mysqli_query($koneksi, "SELECT * FROM label WHERE id='$id' AND id_user='$id_user'");
                            $label = mysqli_fetch_assoc($data_label);
                        ?>

                          <div class="modal-body">
                            <div class="notes-box">
                              <div class="notes-content">
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="d-flex note-title">
                                      <input type="hidden" name="id" value="<?= $label['id']; ?>">
                                      <input
                                        type="text"
                                        id="n-title"
                                        class="form-control"
                                        maxlength="25"
                                        placeholder="Nama label"
                                        name="nama"
                                        value="<?= $label['nama']; ?>"
                                      />
                                    </div>
                                    <span class="validation-text"></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button class="btn" data-bs-dismiss="modal">
                              Batal
                            </button>
                            <input type="submit" class="btn btn-primary" value="Simpan" name="edit-label">
                          </div>
                        <?php
                          }
                        ?>
                      </form>
                    </div>
                  </div>
                </div>
              <!--END MODAL -->
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
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- TOM SELECT SCRIPTS -->
    <script src="../src/plugins/src/tomSelect/tom-select.base.js"></script>
    <script src="../src/plugins/src/tomSelect/custom-tom-select.js"></script>
    <script>
      new TomSelect("#select-state",{
      });
      new TomSelect("#select-state-edit",{
      });
    </script>
    <!-- END TOM SELECT SCRIPTS -->

    <!--ACTIVE SIDEBAR -->
    <script>
      var currentURL = window.location.href;
      if (currentURL.indexOf("notes.php") !== -1) {
        document.getElementById("app-note").classList.add("active");
      }
    </script>
    <!-- END ACTIVE SIDEBAR -->
    
    <!--TAMPILKAN MODAL-->
    <script>
      $("#btn-tambah-catatan").on("click", function (event) {
        $("#modal-tambah-catatan").modal("show");
      });
      $("#btn-tambah-label").on("click", function (event) {
        $("#modal-tambah-label").modal("show");
      });
      $(".hamburger").on("click", function (event) {
        $(".app-note-container").find(".tab-title").toggleClass("note-menu-show");
        $(".app-note-container")
          .find(".app-note-overlay")
          .toggleClass("app-note-overlay-show");
      });
      $(".app-note-overlay").on("click", function (e) {
        $(this)
          .parents(".app-note-container")
          .children(".tab-title")
          .removeClass("note-menu-show");
        $(this).removeClass("app-note-overlay-show");
      });
      $(".tab-title .nav-pills a.nav-link").on("click", function (event) {
        $(this)
          .parents(".app-note-container")
          .find(".tab-title")
          .removeClass("note-menu-show");
        $(this)
          .parents(".app-note-container")
          .find(".app-note-overlay")
          .removeClass("app-note-overlay-show");
      });
    </script>
     <!-- END TAMPILKAN MODAL -->
  </body>
</html>
