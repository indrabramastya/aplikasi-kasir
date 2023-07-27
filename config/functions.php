<?php


function uploadimg($url = null, $name = null)
{
    $namafile = $_FILES['image']['name'];
    $ukuran   = $_FILES['image']['size'];
    $tmp      = $_FILES['image']['tmp_name'];

    // validasi file gambar yang boleh di upload
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensiGambar      = explode('.', $namafile);
    $ekstensiGambar      = strtolower(end($ekstensiGambar)); //mengubah menjadi kecil

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        if ($url != null) {
            echo '<script>
            alert("file yang anda upload bukan gambar, Data gagal diupdate!");
            document.location = "' . $url . '"
        </script>';
            die();
        } else {
            echo '<script>
            alert("file yang anda upload bukan gambar, Data gagal ditambahkan!");        
        </script>';
            return false;
        }
    }

    // validasi ukuran gambar max 1 MB
    if ($ukuran > 1000000) {
        if ($url != null) {
            echo '<script>
            alert("Ukuran gambar tidak boleh melebihi 1 MB, Data gagal diupdate!");
            document.location = "' . $url . '"
        </script>';
            die();
        } else {
            echo '<script>
            alert("Ukuran Gambar tidak boleh melebihi 1 MB");        
        </script>';
            return false;
        }
    }
    if ($name != null) {
        $namaFileBaru = $name . '.' . $ekstensiGambar;
    } else {
        $namaFileBaru = rand(10, 1000) . '-' . $namafile;
    }

    move_uploaded_file($tmp, '../asset/image/' . $namaFileBaru);
    return $namaFileBaru;
}

// fungsi untuk ambil data dari database
function getData($sql)
{
    global $koneksi;


    $result = mysqli_query($koneksi, $sql);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// untuk menghitung jumlah user dan di tampilkan di bagian navbar
function userLogin()
{
    $userActive = $_SESSION["ssUserPOS"];
    $dataUser   = getData("SELECT*FROM _fileUser WHERE username = '$userActive'")[0];
    return $dataUser;
}

// menghighlight menu sidebar

function userMenu()
{
    $uri_path       = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments   = explode('/', $uri_path);
    $menu           = $uri_segments[2]; //2 ini dari alamat / ketiga (0,1,2)
    return $menu;
}

function menuHome()
{
    if (userMenu() == 'dashboard.php') {
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}

function menuSetting()
{
    if (userMenu() == 'user') {
        $result = 'menu-is-opening menu-open'; //class milik adminLTE

    } else {
        $result = null;
    }
    return $result;
}

function menuUser()
{
    if (userMenu() == 'user') {
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}
function menuMaster()
{
    if (userMenu() == 'supplier' or userMenu() == 'customer' or userMenu() == 'barang') {
        $result = 'menu-is-opening menu-open'; //class milik adminLTE
    } else {
        $result = null;
    }
    return $result;
}
function menuSupplier()
{
    if (userMenu() == 'supplier') {
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}

function menuCustomer()
{
    if (userMenu() == 'customer') {
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}
function menuBarang()
{
    if (userMenu() == 'barang') {
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}
function menuBeli()
{
    if (userMenu() == 'pembelian') { //nama folder
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}
function menuJual()
{
    if (userMenu() == 'penjualan') {
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}

function laporanStok()
{
    if (userMenu() == 'stok') {
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}
function laporanBeli()
{
    if (userMenu() == 'laporan-pembelian') { //diambil dari nama folder
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}
function laporanJual()
{
    if (userMenu() == 'laporan-penjualan') { //diambil dari nama folder
        $result = 'active'; //class milik admin LTE
    } else {
        $result = null;
    }
    return $result;
}

function in_date($tgl)
{
    $tg     = substr($tgl, 8, 2);
    $bln    = substr($tgl, 5, 2);
    $thn    = substr($tgl, 0, 4);
    return $tg . "-" . $bln . "-" . $thn;
}

function omset()
{
    global $koneksi;
    $queryOmset = mysqli_query($koneksi, "SELECT sum(total) as omset FROM header_penjualan");
    $data       = mysqli_fetch_assoc($queryOmset);
    $omset      = number_format($data['omset'], 0, ',', '.');

    return $omset;
}
