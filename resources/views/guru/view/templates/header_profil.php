<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base_url" content="<?= base_url; ?>">
    <link rel="shortcut icon" href="<?= base_url ?>resources/img/logo/new_logo/logo-login.png">
    <link href="<?= base_url ?>resources/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url ?>resources/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php
    if ($data['title'] == 'Presensi' || $data['title'] == 'Jadwal') {
        echo '<!-- FLATPICKR -->';
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">';
        echo '<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>';
    } else {
        echo '';
    }

    if ($data['title'] == 'Siswa') {
        echo '<!-- page css -->
        <link href="' . base_url . 'resources/vendors/datatables/dataTables.bootstrap.min.css" rel="stylesheet">
        
        <!-- page js -->
        <script src="' . base_url . 'resources/vendors/datatables/jquery.dataTables.min.js"></script>
        <script src="' . base_url . 'resources/vendors/datatables/dataTables.bootstrap.min.js"></script>';
    } else {
        echo '';
    }
    ?>

    <!-- page js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
    <script src="<?= base_url ?>resources/js/vendors.min.js"></script>
    <script src="<?= base_url ?>resources/js/app.min.js"></script>
    <script src="<?= base_url ?>resources/js/main.js"></script>
    <script src="<?= base_url ?>resources/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url ?>resources/vendors/jquery-validation/jquery.validate.min.js"></script>
    <title>E Presensi | <?= $data['title'] ?>
    </title>
</head>

<body>
    <div class="app">
        <div class="layout">
            <div class="header">
                <div class="logo logo-dark" style="background-color: #F2FCEF;">
                    <a href="<?= base_url ?>dashboard">
                        <img src="<?= base_url ?>resources/img/logo/new_logo/logo.png" width="150px" class="mt-3"
                            alt="Logo">
                        <img class="logo-fold mt-2 ml-3" src="<?= base_url ?>resources/img/logo/new_logo/logo-fold.png"
                            width="40px" alt="Logo">
                    </a>
                </div>
                <div class="nav-wrap" style="background: #F2FCEF;">
                    <ul class="nav-left">

                    </ul>
                    <ul class="nav-right">
                        <li class="dropdown dropdown-animated scale-left">
                            <div class="pointer" data-toggle="dropdown">
                                <div class="avatar avatar-image m-h-10 m-r-15">
                                    <?php
                                    if ($data['admin']->foto_profile != null) {
                                        ?>
                                        <img src="<?= base_url ?>images/profile/<?= $data['guru']->foto_profile ?>" alt=""
                                            style="object-fit: cover;">
                                        <?php
                                    } else {
                                        ?>
                                        <img src="https://i.pinimg.com/564x/15/0f/a8/150fa8800b0a0d5633abc1d1c4db3d87.jpg"
                                            alt="">
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="p-b-15 p-t-20 dropdown-menu pop-profile">
                                <div class="p-h-20 p-b-15 m-b-10 border-bottom">
                                    <div class="">

                                        <div class="">
                                            <p class="m-b-0 text-dark font-weight-semibold">
                                                <?=(!isset($data['guru']->nama_guru) ? '' : $data['guru']->nama_guru) ?>
                                            </p>
                                            <p class="m-b-0 opacity-07">
                                                <?=(!isset($data['guru']->status_kepegawaian) ? '' : strtoupper($data['guru']->status_kepegawaian)) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?= base_url; ?>g/ubah/password" data-edit="<?= base_url ?>/user/edit"
                                    id="header-edit" class="dropdown-item d-block p-h-15 p-v-10">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="anticon opacity-04 font-size-16 anticon-lock"></i>
                                            <span class="m-l-10">Ganti Password</span>
                                        </div>
                                        <i class="anticon font-size-10 anticon-right"></i>
                                    </div>
                                </a>
                                <a href="<?= base_url; ?>auth/logout" id="header-logout"
                                    data-logout="<?= base_url ?>logout" class="dropdown-item d-block p-h-15 p-v-10">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="anticon opacity-04 font-size-16 anticon-logout"></i>
                                            <span class="m-l-10">Logout</span>
                                        </div>
                                        <i class="anticon font-size-10 anticon-right"></i>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>