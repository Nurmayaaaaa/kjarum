?php
session_start();
include 'koneksi.php';

// CEK LOGIN
if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

// CEK ADMIN
if($_SESSION['user']['role'] != "admin"){
    header("Location: dashboard.php");
}

$error = "";
$success = "";

// TAMBAH DOKTER
if(isset($_POST['tambah'])){

    $nama = trim($_POST['nama']);
    $spesialis = trim($_POST['spesialis']);

    if($nama == "" || $spesialis == ""){

        $error = "Semua field wajib diisi!";

    }else{

        mysqli_query($conn,"
        INSERT INTO dokter(
            nama_dokter,
            spesialis
        )
        VALUES(
            '$nama',
            '$spesialis'
        )
        ");

        $success = "Dokter berhasil ditambahkan!";
    }
}

// HAPUS DOKTER
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($conn,"
    DELETE FROM dokter
    WHERE id_dokter='$id'
    ");

    header("Location: admin_dokter.php");
}

// EDIT DOKTER
if(isset($_POST['edit'])){

    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $spesialis = trim($_POST['spesialis']);

    if($nama == "" || $spesialis == ""){

        $error = "Semua field wajib diisi!";

    }else{

        mysqli_query($conn,"
        UPDATE dokter
        SET
            nama_dokter='$nama',
            spesialis='$spesialis'
        WHERE id_dokter='$id'
        ");

        $success = "Data dokter berhasil diupdate!";
    }
}

// AMBIL DATA EDIT
$edit = null;

if(isset($_GET['edit'])){

    $id_edit = $_GET['edit'];

    $edit = mysqli_fetch_assoc(
        mysqli_query($conn,"
        SELECT * FROM dokter
        WHERE id_dokter='$id_edit'
        ")
    );
}

// AMBIL DATA DOKTER
$data = mysqli_query($conn,"
SELECT * FROM dokter
ORDER BY id_dokter DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Kelola Dokter</title>

<link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        Kelola Dokter
    </div>

    <!-- ALERT -->
    <?php if($error != ""){ ?>
        <div class="alert-error">
            <?= $error; ?>
        </div>
    <?php } ?>

    <?php if($success != ""){ ?>
        <div class="alert-success">
            <?= $success; ?>
        </div>
    <?php } ?>

    <!-- FORM -->
    <div class="card">

        <div class="title">

            <?php if($edit){ ?>
                Edit Dokter
            <?php }else{ ?>
                Tambah Dokter
            <?php } ?>

        </div>

        <br>

        <form method="POST">

            <?php if($edit){ ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= $edit['id_dokter']; ?>"
                >

            <?php } ?>

            <div class="label">
                Nama Dokter
            </div>

            <input
                type="text"
                name="nama"
                placeholder="Masukkan nama dokter"
                value="<?= $edit ? $edit['nama_dokter'] : ''; ?>"
            >

            <div class="label">
                Spesialis
            </div>

            <input
                type="text"
                name="spesialis"
                placeholder="Masukkan spesialis"
                value="<?= $edit ? $edit['spesialis'] : ''; ?>"
            >

            <?php if($edit){ ?>

                <button type="submit" name="edit">
                    Update Dokter
                </button>

            <?php }else{ ?>

                <button type="submit" name="tambah">
                    Tambah Dokter
                </button>

            <?php } ?>

        </form>

    </div>

    <!-- TABEL -->
    <div class="card">

        <div class="title">
            Data Dokter
        </div>

        <br>

        <table>

            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Spesialis</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            while($d = mysqli_fetch_assoc($data)){
            ?>

            <tr>

                <td><?= $no++; ?></td>

                <td>
                    <?= $d['nama_dokter']; ?>
                </td>

                <td>
                    <?= $d['spesialis']; ?>
                </td>

                <td>

                    <a href="?edit=<?= $d['id_dokter']; ?>">
                        Edit
                    </a>

                    |

                    <a
                        href="?hapus=<?= $d['id_dokter']; ?>"
                        onclick="return confirm('Yakin hapus dokter?')"
                    >
                        Hapus
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

    <!-- BUTTON -->
    <a href="admin_dashboard.php">
        <button>
            Kembali
        </button>
    </a>

</div>

<!-- NAVBAR -->
<div class="navbar">

    <a href="admin_dashboard.php">
        Dashboard
    </a>

    <a href="admin_dokter.php">
        Dokter
    </a>

    <a href="admin_jadwal.php">
        Jadwal
    </a>

    <a href="admin_antrian.php">
        Antrian
    </a>

</div>

</body>
</html>
