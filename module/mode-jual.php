<?php

function generateNo()
{
    global $koneksi;

    $queryNo = mysqli_query($koneksi, "SELECT max(no_jual) as maxno FROM header_penjualan");

    $row    = mysqli_fetch_assoc($queryNo);

    $maxno  = $row["maxno"];

    $noUrut = (int) substr($maxno, 2, 4);
    $noUrut++;
    $maxno  = 'PJ' . sprintf("%04s", $noUrut);

    return $maxno;
}

function insert($data)
{
    global $koneksi;

    $no         = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $kode       = mysqli_real_escape_string($koneksi, $data['barcode']);
    $nama       = mysqli_real_escape_string($koneksi, $data['namaBrg']);
    $qty        = mysqli_real_escape_string($koneksi, $data['qty']);
    $harga      = mysqli_real_escape_string($koneksi, $data['harga']);
    $jmlharga   = mysqli_real_escape_string($koneksi, $data['jmlHarga']);
    $stok   = mysqli_real_escape_string($koneksi, $data['stok']);

    // cek barang sudah di input atau belum

    $cekBarang  = mysqli_query($koneksi, "SELECT * FROM detail_penjualan WHERE no_jual = '$no' AND barcode = '$kode'");
    if (mysqli_num_rows($cekBarang)) {
        echo "<script>
            alert('barang sudah ada, anda harus mengubahnya dulu jika ingin mengubah qty nya..');
        </script> ";
        return false;
    }

    // qty barang tidak boleh kosong
    if (empty($qty)) {
        echo "<script>
            alert('qty barang tidak boleh kososng ..');
        </script> ";
        return false;
    } else if ($qty > $stok) {
        echo "<script>
            alert('stok barang tidak mencukupi ..');
        </script> ";
        return false;
    } else {
        $sqljual    = "INSERT INTO detail_penjualan VALUES (null, '$no', '$tgl', '$kode', '$nama', $qty, $harga, $jmlharga)";
        mysqli_query($koneksi, $sqljual);
    }

    mysqli_query($koneksi, "UPDATE file_barang SET stok = stok - $qty WHERE barcode = '$kode'");

    return mysqli_affected_rows($koneksi);
}
function totalJual($nojual)
{
    global $koneksi;
    $totalJual = mysqli_query($koneksi, "SELECT sum(jml_harga) AS total FROM detail_penjualan WHERE no_jual = '$nojual'");
    $data = mysqli_fetch_assoc($totalJual);
    $total = $data["total"];

    return $total;
}

function delete($barcode, $idjual, $qty)
{
    global $koneksi;

    $sqldel = "DELETE FROM detail_penjualan WHERE barcode = '$barcode' AND no_jual = '$idjual'";
    mysqli_query($koneksi, $sqldel);

    mysqli_query($koneksi, "UPDATE file_barang SET stok = stok + $qty WHERE barcode = '$barcode'");

    return mysqli_affected_rows($koneksi);
}
function simpan($data)
{
    global $koneksi;

    $nojual     = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $total      = mysqli_real_escape_string($koneksi, $data['total']);
    $customer   = mysqli_real_escape_string($koneksi, $data['customer']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['ketr']);
    $bayar = mysqli_real_escape_string($koneksi, $data['bayar']);
    $kembalian = mysqli_real_escape_string($koneksi, $data['kembalian']);

    $sqljual    = "INSERT INTO header_penjualan VALUES ('$nojual', '$tgl', '$customer', '$total', '$keterangan', '$bayar', '$kembalian')";

    mysqli_query($koneksi, $sqljual);

    return mysqli_affected_rows($koneksi);
}
