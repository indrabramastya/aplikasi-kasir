<?php

function generateNo()
{
    global $koneksi;

    $queryNo = mysqli_query($koneksi, "SELECT max(no_beli) as maxno FROM header_pembelian");

    $row    = mysqli_fetch_assoc($queryNo);

    $maxno  = $row["maxno"];

    $noUrut = (int) substr($maxno, 2, 4);
    $noUrut++;
    $maxno  = 'PB' . sprintf("%04s", $noUrut);

    return $maxno;
}

function totalBeli($nobeli)
{
    global $koneksi;
    $totalBeli = mysqli_query($koneksi, "SELECT sum(jml_harga) AS total FROM detail_pembelian WHERE no_beli = '$nobeli'");
    $data = mysqli_fetch_assoc($totalBeli);
    $total = $data["total"];

    return $total;
}

function insert($data)
{
    global $koneksi;

    $no         = mysqli_real_escape_string($koneksi, $data['nobeli']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $kode       = mysqli_real_escape_string($koneksi, $data['kodeBrg']);
    $nama       = mysqli_real_escape_string($koneksi, $data['namaBrg']);
    $qty        = mysqli_real_escape_string($koneksi, $data['qty']);
    $harga      = mysqli_real_escape_string($koneksi, $data['harga']);
    $jmlharga   = mysqli_real_escape_string($koneksi, $data['jmlHarga']);


    // validasi
    $cekBarang  = mysqli_query($koneksi, "SELECT * FROM detail_pembelian WHERE no_beli = '$no' AND kode_brg = '$kode'");
    if (mysqli_num_rows($cekBarang)) {
        echo "<script>
            alert('barang sudah ada, anda harus mengubahnya dulu jika ingin mengubah qty nya..');
        </script> ";
        return false;
    }
    if (empty($qty)) {
        echo "<script>
            alert('qty barang tidak boleh kososng ..');
        </script> ";
        return false;
    } else {
        $sqlbeli    = "INSERT INTO detail_pembelian VALUES (null, '$no', '$tgl', '$kode', '$nama', $qty, $harga, $jmlharga)";
        mysqli_query($koneksi, $sqlbeli);
    }

    mysqli_query($koneksi, "UPDATE file_barang SET stok = stok + $qty WHERE id_barang = '$kode'");

    return mysqli_affected_rows($koneksi);
}
function delete($idbrg, $idbeli, $qty)
{
    global $koneksi;

    $sqldel = "DELETE FROM detail_pembelian WHERE kode_brg = '$idbrg' AND no_beli = '$idbeli'";
    mysqli_query($koneksi, $sqldel);

    mysqli_query($koneksi, "UPDATE file_barang SET stok = stok - $qty WHERE id_barang = '$idbrg'");

    return mysqli_affected_rows($koneksi);
}
function simpan($data)
{
    global $koneksi;

    $nobeli     = mysqli_real_escape_string($koneksi, $data['nobeli']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $total      = mysqli_real_escape_string($koneksi, $data['total']);
    $suplier   = mysqli_real_escape_string($koneksi, $data['suplier']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['ketr']);

    $sqlbeli    = "INSERT INTO header_pembelian VALUES ('$nobeli', '$tgl', '$suplier', '$total', '$keterangan')";

    mysqli_query($koneksi, $sqlbeli);

    return mysqli_affected_rows($koneksi);
}
