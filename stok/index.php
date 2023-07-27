<?php

session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
    header("location: ../auth/login.php");
    exit();
}
require '../config/config.php';
require '../config/functions.php';
require '../module/mode-barang.php';


$title = 'Laporan';
require '../template/header.php';
require '../template/navbar.php';
require '../template/sidebar.php';

$stokBrg = getData("SELECT * FROM file_barang");
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Stok</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Stok</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list fa-sm"></i> Data Stok</h3>
                    <a href="<?= $main_url ?>report/lap_stok.php" class="btn btn-sm btn-outline-primary float-right" target="_blank"><i class="fas fa-print"></i> Cetak</a>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tblData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Jumlah Stok</th>
                                <th>Stok Minimal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($stokBrg as $stok) { ?>

                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $stok['id_barang'] ?></td>
                                    <td><?= $stok['nama_barang'] ?></td>
                                    <td><?= $stok['satuan'] ?></td>
                                    <td class="text-center"><?= $stok['stok'] ?></td>
                                    <td class="text-center"><?= $stok['stok_minimal'] ?></td>
                                    <td>
                                        <?php
                                        if ($stok['stok'] < $stok['stok_minimal']) {
                                            echo '<span class="text-danger">Stok Kurang</span>';
                                        } else {
                                            echo 'Stok Cukup';
                                        }

                                        ?>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>



    <?php require "../template/footer.php" ?>