<!-- untuk koneksi simpan data ke database -->

<?php

if (userLogin()['level'] != 1) {
    header("location:" . $main_url . "error-page.php");
    exit();
}



function insert($data)
{
    global $koneksi;


    $username  = strtolower(mysqli_real_escape_string($koneksi, $data['username']));
    $fullname  = mysqli_real_escape_string($koneksi, $data['fullname']);
    $password  = mysqli_real_escape_string($koneksi, $data['password']);
    $password2 = mysqli_real_escape_string($koneksi, $data['password2']);
    $level     = mysqli_real_escape_string($koneksi, $data['level']);
    $address   = mysqli_real_escape_string($koneksi, $data['address']);
    $gambar    = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);


    if ($password !== $password2) {
        echo "<script>
            alert('konfirmasi password tidak sesuai, user baru gagal di regristrasi !');
        </script>";
        return false;
    }

    $pass = password_hash($password, PASSWORD_DEFAULT);


    $cekUsername = mysqli_query($koneksi, "SELECT username FROM _fileuser WHERE username = '$username'");
    if (mysqli_num_rows($cekUsername) > 0) {
        echo "<script>
            alert('username sudah ada, user baru gagal di regristrasi !');
        </script>";
        return false;
    }

    if ($gambar != null) {
        $gambar = uploadimg();
    } else {
        $gambar = 'default.png';
    }

    // gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }

    $sqluser   = "INSERT INTO _fileuser VALUE (null, '$username', '$fullname', '$pass','$address', '$level', '$gambar' )";

    mysqli_query($koneksi, $sqluser);


    return mysqli_affected_rows($koneksi);
}


function delete($id, $foto)
{
    global $koneksi;


    $sqlDel = "DELETE FROM _fileUser WHERE userid = $id";
    mysqli_query($koneksi, $sqlDel);
    if ($foto != 'default.png') {
        unlink('../asset/image/' . $foto);
    }

    return mysqli_affected_rows($koneksi);
}

function update($data)
{
    global $koneksi;

    $iduser    = mysqli_real_escape_string($koneksi, $data['id']);
    $username  = strtolower(mysqli_real_escape_string($koneksi, $data['username']));
    $fullname  = mysqli_real_escape_string($koneksi, $data['fullname']);
    $level     = mysqli_real_escape_string($koneksi, $data['level']);
    $address   = mysqli_real_escape_string($koneksi, $data['address']);
    $gambar    = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);
    $fotoLama  = mysqli_real_escape_string($koneksi, $data['oldImg']);

    //cek username sekarang
    $queryUsername = mysqli_query($koneksi, "SELECT*FROM _fileUser WHERE userid = $iduser ");
    $dataUsername  = mysqli_fetch_assoc($queryUsername); // mengambil data user yang sekarang di update
    $curUsername   = $dataUsername['username'];

    // cek username baru
    $newUsername   = mysqli_query($koneksi, "SELECT username FROM _fileUser WHERE username = $username");


    // validasinya
    if ($username !== $curUsername) {
        if (mysqli_num_rows($newUsername)) {
            echo "<script>
            alert('username sudah dipakai, update data user Gagal !');
            document.location.href = 'data-user.php';
        </script>";
            return false;
        }
    }

    //cek gambar
    if ($gambar != null) {
        $url     = "data-user.php";
        $imgUser = uploadimg($url);
        if ($fotoLama != 'default.png') {
            @unlink('../asset/image' . $fotoLama);
        } else {
            $imgUser = $fotoLama;
        }
    }
    mysqli_query($koneksi, "UPDATE _fileUser SET 
                            username     = '$username',
                            fullname     = '$fullname',
                            address      = '$address',
                            level        = '$level',
                            foto         = '$imgUser'
                            WHERE userid = $iduser
    ");

    return mysqli_affected_rows($koneksi);
}






// fungsi untuk menampilkan data level user ketika di edit

// administrator
function selectUser1($level)
{
    $result = null;
    if ($level == 1) {
        $result = "selected"; //atribut dari html
    }
    return $result;
}

// supervisor

function selectUser2($level)
{
    $result = null;
    if ($level == 2) {
        $result = "selected"; //atribut dari html
    }
    return $result;
}

// Operator

function selectUser3($level)
{
    $result = null;
    if ($level == 3) {
        $result = "selected"; //atribut dari html
    }
    return $result;
}
