<?php

if (userLogin()['level'] == 3) {
    header("location:" . $main_url . "error-page.php");
    exit();
}
// untuk membuat otomatis kode barang
function generateId()
{
    global $koneksi;

    $queryId    = mysqli_query($koneksi, "SELECT max(id_barang) as maxid FROM file_barang");
    $data       = mysqli_fetch_array($queryId);
    $maxid      = $data['maxid'];

    $noUrut     = (int) substr($maxid, 4, 3);
    $noUrut++;
    $maxid      = "BRG-" . sprintf("%03s", $noUrut);

    return $maxid;
}
function insert($data)
{
    global $koneksi;
    // membuat sanitasi data sebelum insert
    $id             = mysqli_real_escape_string($koneksi, $data['kode']);
    $barcode        = mysqli_real_escape_string($koneksi, $data['barcode']);
    $name           = mysqli_real_escape_string($koneksi, $data['name']);
    $satuan         = mysqli_real_escape_string($koneksi, $data['satuan']);
    $harga_beli     = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $harga_jual     = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $stok_min       = mysqli_real_escape_string($koneksi, $data['stok_minimal']);
    $gambar         = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);

    $cekBarcode     = mysqli_query($koneksi, "SELECT * FROM file_barang WHERE barcode = '$barcode'");
    if (mysqli_num_rows($cekBarcode)) {
        echo '<script>
            alert("Kode barcode sudah ada, barang gagal ditambahkan..")
        </script>';
        return false;
    }

    // upload gambar
    if ($gambar != null) {
        $gambar = uploadimg(null, $id);
    } else {
        $gambar = 'default-brg.png';
    }

    // gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }


    $sqlBrg    = "INSERT INTO file_barang VALUES ('$id','$barcode', '$name', '$harga_beli', '$harga_jual',0,'$satuan','$stok_min', '$gambar')";

    mysqli_query($koneksi, $sqlBrg);

    return mysqli_affected_rows($koneksi);
}
function delete($id, $gbr)
{
    global $koneksi;

    $sqldel = "DELETE FROM file_barang WHERE id_barang = '$id'";

    mysqli_query($koneksi, $sqldel);
    if ($gbr != 'default-brg.png') {
        unlink('../asset/image/' . $gbr);
    }
    return mysqli_affected_rows($koneksi);
}
