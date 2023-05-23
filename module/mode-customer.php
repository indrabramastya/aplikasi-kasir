<?php

if (userLogin()['level'] == 3) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

function insert($data)
{
    global $koneksi;
    // membuat sanitasi data sebelum insert
    $nama   = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon = mysqli_real_escape_string($koneksi, $data['telpon']);
    $ketr   = mysqli_real_escape_string($koneksi, $data['ketr']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);


    $sqlCustomer    = "INSERT INTO file_customer VALUES (null, '$nama','$telpon','$ketr','$alamat')";

    mysqli_query($koneksi, $sqlCustomer);

    return mysqli_affected_rows($koneksi);
}


function delete($id)
{
    global $koneksi;
    $sqlDelete  = "DELETE FROM file_customer WHERE id_customer = $id";
    mysqli_query($koneksi, $sqlDelete);

    return mysqli_affected_rows($koneksi);
}

function update($data)
{
    global $koneksi;
    $id     = mysqli_real_escape_string($koneksi, $data['id']);
    $nama   = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon = mysqli_real_escape_string($koneksi, $data['telpon']);
    $ketr   = mysqli_real_escape_string($koneksi, $data['ketr']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);


    $sqlcustomer    = "UPDATE file_customer SET
                nama        ='$nama',
                telpon      ='$telpon',
                deskripsi   ='$ketr',
                alamat      ='$alamat'
                WHERE id_customer = $id
    ";

    mysqli_query($koneksi, $sqlcustomer);

    return mysqli_affected_rows($koneksi);
}
