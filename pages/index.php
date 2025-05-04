<?php
include 'komponen/koneksi.php';
session_start();

$id_user = $_SESSION['id_user'];

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}
$data_catatan = mysqli_query($koneksi, "SELECT * FROM catatan WHERE id_user=$id_user ORDER BY tanggal DESC LIMIT 9");
$data_todo = mysqli_query($koneksi,"SELECT * FROM tugas WHERE id_user = '$id_user'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Beranda - Nolico</title>
</head>
<body class="layout-boxed">
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
    <div class="main-container" id="container">

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

                    <div class="row layout-top-spacing">

                        <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-chart-three">
                                <div class="widget-heading">
                                    <div class="">
                                        <h5 class="">Catatan</h5>
                                    </div>
                                </div>

                                <div class="widget-content">
                                    <div id="ct" class="note-container note-grid">
                                        <?php
                                        if (mysqli_num_rows($data_catatan) > 0) {
                                            while ($catatan = mysqli_fetch_assoc($data_catatan)) {
                                            $favorit = "";
                                            if ($catatan['favorit'] == true) {
                                                $favorit = "note-fav";
                                            }  
                                            $format_tanggal = date('d/m/Y', strtotime($catatan['tanggal']));
                                            $data_label_catatan = mysqli_query($koneksi, "SELECT label.* FROM label JOIN label_catatan ON label.id = label_catatan.id_label WHERE label_catatan.id_catatan = $catatan[id] AND label_catatan.id_user=$id_user");
                                            echo "
                                                <div class='note-item all-notes $favorit'>
                                                <div class='note-inner-content'>
                                                    <div class='note-content'>
                                                    <p
                                                        class='note-title'
                                                    >
                                                        $catatan[judul]
                                                    </p>
                                                    <p class='meta-time'>$format_tanggal</p>
                                                    <div class='note-description-content'>
                                                        <p
                                                        class='note-description'
                                                        >
                                                        $catatan[konten]
                                                        </p>
                                                    </div>
                                                    </div>";
                                                    while ($label = mysqli_fetch_assoc($data_label_catatan)) {
                                                    echo "<span class='badge badge-light-dark'>#$label[nama]</span> ";
                                                    }
                                                    echo "
                                                </div>
                                                </div>
                                            ";
                                            }                                              
                                        } else {
                                            echo "
                                            <div class='text-center note-item'></div>
                                            <div class='text-center note-item mb-5'>
                                                <h6 class='text-center mb-3'>Belum ada catatan</h6>
                                                <a class='btn btn-secondary' href='notes.php'>Tambah Catatan</a>
                                            </div>
                                            ";
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-activity-five">

                                <div class="widget-heading">
                                    <h5 class="">Daftar Tugas</h5>

                                </div>

                                <div class="widget-content">

                                    <div class="w-shadow-top"></div>

                                    <div class="mt-container mx-auto">
                                        <div class="timeline-line">
                                            
                                        <?php
                                        if (mysqli_num_rows($data_todo) > 0) {

                                            while ($data = mysqli_fetch_assoc($data_todo)) {
                                                $format_tanggal = date('d/m/Y', strtotime($data['tenggat']));
                                                echo "
                                                <div class='item-timeline timeline-new'>
                                                    <div class='t-dot'>
                                                        <div class='t-secondary'></div>
                                                    </div>
                                                    <div class='t-content'>
                                                        <div class='t-uppercontent'>
                                                            <h5>$data[judul]</h5>
                                                        </div>
                                                        <p>$format_tanggal</p>
                                                    </div> 
                                                </div>  
                                                ";
                                            }
                                        } else {
                                            echo "
                                            <div class='text-center'>
                                                <h6 class='text-center mb-4 mt-5'>Belum ada tugas</h6>
                                                <a class='btn btn-secondary' href='todoList.php'>Tambah Tugas</a>
                                            </div>
                                            ";
                                        }
                                        ?>
                                 
                                        </div>                                    
                                    </div>

                                    <div class="w-shadow-bottom"></div>
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
    <script src="../src/assets/js/dashboard/dash_1.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->]

    <!--ACTIVE SIDEBAR -->
    <script>
        var currentURL = window.location.href;
        if (currentURL.indexOf("index.php") !== -1) {
            document.getElementById("index").classList.add("active");
        }
    </script>
    <!-- END ACTIVE SIDEBAR -->
</body>
</html>