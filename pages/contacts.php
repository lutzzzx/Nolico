<?php // ==================== INSERT =========================
    include('komponen/koneksi.php');
    session_start();
    if (!isset($_SESSION['id_user'])) {
      header("Location: login.php");
      exit();
    }
    $id_user = $_SESSION['id_user'];
    $view = "SELECT * FROM kontak WHERE id_user = '$id_user'";
    $queryv = mysqli_query($koneksi, $view);

if (isset($_POST['inpAdd'])){
    $name     = $_POST['inpNama'];
    $email    = $_POST['inpEmail'];
    $job      = $_POST['inpKerja'];
    $phnumber = $_POST['inpNohp'];
    $locate   = $_POST['inpLokasi'];

    if (!empty($_FILES['inpFoto']['name'])){
        $photo    = $_FILES['inpFoto']['name'];
        $upload   = '../src/assets/img/profile/'.$photo;
        move_uploaded_file($_FILES['inpFoto']['tmp_name'], $upload);  
    }

    $ins = "INSERT INTO kontak(id_user, foto, nama, email, pekerjaan, no_telp, alamat) VALUES ('$id_user','$upload','$name','$email','$job','$phnumber','$locate')";
    $resultins = mysqli_query($koneksi, $ins);
    header("location:contacts.php");
}  // ==================== END OF INSERT ======================
?> 

<?php // ==================== UPDATE ==========================
if (isset($_POST['inpUpdate'])){
    $id_contact     = $_POST['idcontact'];
    $updateName     = $_POST['updNama'];
    $updateEmail    = $_POST['updEmail'];
    $updateJob      = $_POST['updKerja'];
    $updatePhnumber = $_POST['updNohp'];
    $updateLocate   = $_POST['updLokasi'];

    $viewupd  = "SELECT * FROM kontak WHERE id = '$id_contact'";
    $queryupd = mysqli_query($koneksi, $viewupd);
    $col      = mysqli_fetch_assoc($queryupd);

    $updateUpload   = $col['foto'];

    if (!empty($_FILES['updFoto']['name'])){
        $updatePhoto    = $_FILES['updFoto']['name'];
        $updateUpload   = '../src/assets/img/profile/'.$updatePhoto;
        move_uploaded_file($_FILES['updFoto']['tmp_name'], $updateUpload);

        if (!empty($col['foto'])) {
            unlink($col['foto']);
        }
    }

    $upd = "UPDATE kontak SET foto='$updateUpload', nama='$updateName', email='$updateEmail', pekerjaan='$updateJob', no_telp='$updatePhnumber', alamat='$updateLocate' WHERE id = '$id_contact'";
    $resultupd = mysqli_query($koneksi, $upd);

    header("location:contacts.php");
}   // ==================== END OF UPDATE ======================
?> 

<?php // ==================== DELETE ==========================
if (isset($_POST['btndel'])){
    $id = $_POST['delcon'];
    
    $del    = "DELETE FROM kontak WHERE id = '$id'";
    $delete = mysqli_query($koneksi, $del);

    header("location: contacts.php");
} // ==================== END OF DELETE ======================
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Kontak - Nolico</title>

</head>
<body class="">
    
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <?php
        include "komponen/navbar.php";
    ?>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
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

                    <div class="row layout-spacing layout-top-spacing" id="cancel-row">
                        <div class="col-lg-12">
                            <div class="widget-content searchable-container list">
    
                                <div class="row">
                                    <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search layout-spacing align-self-center">
                                        <form class="form-inline my-2 my-lg-0">
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                <input type="text" class="form-control product-search" id="input-search" placeholder="Cari kontak...">
                                            </div>
                                        </form>
                                    </div>
    
                                    <div class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-sm-right text-center layout-spacing align-self-center">
                                        <div class="d-flex justify-content-sm-end justify-content-center">
                                            <svg id="btn-add-contact" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                        </div>
    
                                        <!-- Modal Insert-->
                                        <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title add-title" id="addContactModalTitleLabel1">Tambah Kontak</h5>
                                                        <h5 class="modal-title edit-title" style="display: none;" id="addContactModalTitleLabel2">Edit Contact</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="add-contact-box">
                                                            <div class="add-contact-content">

                                                                <form id="addContactModalTitle" method="POST" enctype="multipart/form-data">
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-name">
                                                                                <input type="text" id="c-name" name="inpNama" class="form-control" placeholder="Nama" required>
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-email">
                                                                                <input type="text" id="c-email" name="inpEmail" class="form-control" placeholder="Email">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-occupation">
                                                                                <input type="text" id="c-occupation" name="inpKerja" class="form-control" placeholder="Pekerjaan">
                                                                            </div>
                                                                        </div>
    
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-phone">
                                                                                <input type="text" id="c-phone" name="inpNohp" class="form-control" placeholder="Nomor Telepon">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="contact-location">
                                                                                <input type="text" id="c-location" name="inpLokasi" class="form-control" placeholder="Alamat">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" style="margin-top: 15px;">
                                                                        <div class="col-md-12">
                                                                            <div class="contact-location">
                                                                                <input type="file" name="inpFoto" class="form-control" accept=".jpg, .png, .jpeg">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="modal-footer">
                                                                <input id="btn-edit" class="float-left btn btn-success" type="submit" name="inpUpdate" value="Save">
    
                                                                <input class="btn" type="submit" data-bs-dismiss="modal" value="Batal"> <i class="flaticon-delete-1"></i>

                                                                <input id="btn-add" class="btn btn-primary" type="submit" name="inpAdd" value="Tambah">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- End of Modal Input-->

                            <!-- Modal Update -->
                                <div class="modal fade" id="addContactModalUpdate" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title add-title" id="addContactModalTitleLabel1" style="display: none;">Add Contact</h5>
                                                        <h5 class="modal-title edit-title" id="addContactModalTitleLabel2" >Edit Kontak</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body" id="modal-update">
                                                        <div class="add-contact-box">
                                                            <div class="add-contact-content">

                                                                <form id="addContactModalTitle" method="POST" enctype="multipart/form-data">
                                                                    <input type="hidden" name="idcontact" id="idcontact">
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-name">
                                                                                <input type="text" id="c-name" name="updNama" class="form-control" placeholder="Nama" required>
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-email">
                                                                                <input type="text" id="c-email" name="updEmail" class="form-control" placeholder="Email">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-occupation">
                                                                                <input type="text" id="c-occupation" name="updKerja" class="form-control" placeholder="Pekerjaan">
                                                                            </div>
                                                                        </div>
    
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-phone">
                                                                                <input type="text" id="c-phone" name="updNohp" class="form-control" placeholder="Nomor Telepon">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="contact-location">
                                                                                <input type="text" id="c-location" name="updLokasi" class="form-control" placeholder="Alamat">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" style="margin-top: 15px;">
                                                                        <div class="col-md-12">
                                                                            <div class="contact-location">
                                                                                <input class="form-control" type="text" id="filePhoto" readonly placeholder="Pilih Foto" style="cursor: pointer;">
                                                                                <input style="display: none;" id="c-photo" type="file" name="updFoto" class="form-control" accept=".jpg, .png, .jpeg">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                                <div class="modal-footer">

                                                                                <input id="btn-add" class="float-left btn btn-success" type="submit" name="inpUpdate" value="Simpan">
    
                                                                                <input id="btn-del" class="btn" type="submit" data-bs-dismiss="modal" value="Batal"> <i class="flaticon-delete-1"></i>

                                                                                <input id="btn-edit" class="btn btn-primary" type="submit" name="inpUpdate" value="Simpan">
                                                                </div>
                                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                <!-- End of Modal Update-->

                                <!-- Modal Delete -->
                                <div class="modal fade" id="addContactModalDelete" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title add-title" id="addContactModalTitleLabel1" style="display: none;">Tambah Kontak</h5>
                                                        <h5 class="modal-title edit-title" id="addContactModalTitleLabel2" >Hapus Kontak</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                        </button>
                                                    </div>
                                                    
                                                    <div id="modal-delete" class="modal-body">
                                                        <div class="add-contact-box">
                                                            <div class="add-contact-content">
                                                            <form method="POST">
                                                            <input id="valdel" type="hidden" name="delcon">
                                                            <p>Anda yakin untuk menghapus kontak ini?</p>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="modal-footer">
                                                                <input id="btn-edit" class="float-left btn btn-success" type="submit" name="inpUpdate" value="Save">

                                                                <Input name="btndel" id="btn-del" class="btn btn-warning" type="submit" data-bs-dismiss="modal" value="Hapus"> <i class="flaticon-delete-1"></i>

                                                                <input id="btn-add" class="btn btn-primary" type="submit" name="inpAdd" value="Add">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                <!--End of Modal Delete-->

                                <div class="searchable-items list">
                                    <div class="items items-header-section">
                                        <div class="item-content">
                                            <div class="d-inline-flex">    
                                                <h4 style="margin-left: 15px">Nama</h4>
                                            </div>
                                            <div class="user-email">
                                                <h4 style="margin-left: -90px">Email</h4>
                                            </div>
                                            <div class="user-location">
                                                <h4 style="margin-left: -150px;">Alamat</h4>
                                            </div>
                                            <div class="user-phone">
                                                <h4 style="margin-left: -250px;">Nomor Telepon</h4>
                                            </div>
                                        </div>
                                    </div>
    
                                    <!-- List Contact -->
                            <?php while ($row = mysqli_fetch_assoc($queryv)){ ?>                                     
                                    <div class="items">
                                        <div class="item-content">
                                            <div class="user-profile">
                                                
                                                <img src = "<?php echo ($row['foto'] != null) ? $row['foto'] : "../src/assets/img/user.png"; ?>" alt="avatar">
                                                <div class="user-meta-info">
                                                    <p class="user-name" ><?php echo $row['nama']; ?></p>
                                                    <p class="user-work" ><?php echo $row['pekerjaan']; ?></p>
                                                </div>
                                            </div>
                                            <div class="user-email">
                                                <p class="info-title">Email: </p>
                                                <p class="usr-email-addr"><?php echo $row['email']; ?></p>
                                            </div>
                                            <div class="user-location">
                                                <p class="info-title">Location: </p>
                                                <p class="usr-location" ><?php echo $row['alamat']; ?></p>
                                            </div>
                                            <div class="user-phone">
                                                <p class="info-title">Phone: </p>
                                                <p class="usr-ph-no" ><?php echo $row['no_telp']; ?></p>
                                            </div>
                                            <div class="action-btn">
                                            
                                                <a id="edit-right"
                                                data-id    ="<?= $row['id']; ?>"
                                                data-photo ="<?= $row['foto']; ?>"
                                                data-name  ="<?= $row['nama']; ?>"
                                                data-job   ="<?= $row['pekerjaan']; ?>"
                                                data-email ="<?= $row['email']; ?>"
                                                data-loc   ="<?= $row['alamat']; ?>"
                                                data-phone ="<?= $row['no_telp']; ?>"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                </a>

                                               <a id="del-right"
                                               data-id ="<?= $row['id']; ?>"
                                               >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-minus delete"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                               </a>
                                               
                                            </div>
                                        </div>
                                    </div>
                            <?php } ?>
                                    <!-- End of List Contact -->

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
    <script src="../src/plugins/src/jquery-ui/jquery-ui.min.js"></script>
    <script src="../src/assets/js/apps/contact.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        var currentURL = window.location.href;
        if (currentURL.indexOf("contacts.php") !== -1) {
            document.getElementById("app-contacts").classList.add("active");
        }

        $('.action-btn #edit-right').on('click', function() {
            $('#addContactModalUpdate #btn-add').hide();
            $('#addContactModalUpdate #btn-edit').show();

            $('#addContactModalUpdate').modal('show');

            let id      = $(this).data('id');
            let photo   = $(this).data('photo');
            let nama    = $(this).data('name');
            let email   = $(this).data('email');
            let job     = $(this).data('job')
            let phone   = $(this).data('phone');
            let locate  = $(this).data('loc');
    
            $('#modal-update #idcontact').val(id);
            $('#modal-update #filePhoto').val(photo);
            $('#modal-update #c-name').val(nama);
            $('#modal-update #c-email').val(email);
            $('#modal-update #c-occupation').val(job);
            $('#modal-update #c-phone').val(phone);
            $('#modal-update #c-location').val(locate);    
        })

        $("#filePhoto").on("click", function() {
        $("#c-photo").trigger("click");
        });

        $("#filePhoto").on("change", function() {
        $("#c-photo").val(this.files[0].name);
        });

    $('.action-btn #del-right').on('click', function() {
        $('#addContactModalDelete #btn-add').hide();
        $('#addContactModalDelete #btn-edit').hide();
        $('#addContactModalDelete #btn-del').show();
        $('#addContactModalDelete').modal('show');
        
        let id = $(this).data('id');
        $('#modal-delete #valdel').val(id);
        })

    $('#btn-add-contact').on('click', function(){
        $('#addContactModal #btn-add').show();
        $('#addContactModal #btn-edit').hide();
        $('#addContactModal').modal('show');
    })
    </script>
</body>
</html>