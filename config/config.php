<?php

date_default_timezone_set('Asia/Jakarta');

$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'db_codinglinepos';

$koneksi = mysqli_connect($host, $user, $pass, $dbname);

// cara cek koneksi database
// if(mysqli_connect_errno()){
//     echo "gagal koneksi ke database";
//     exit();
// } else{
//     echo "berhasil koneksi ke database";
// }

$main_url = 'http://localhost/codingline-pos/';
