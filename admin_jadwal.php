php
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

// TAMBAH JADWAL
if(isset($_POST['tambah'])){

    $dokter = $_POST['dokter'];
    $hari = trim($_POST['hari']);
    $jam = trim($_POST['jam']);

    if($dokter == "" || $hari == "" || $jam == ""){

        $error = "Semua field wajib diisi!";

    }else{

        mysqli_query($conn,"
        INSERT INTO jadwal(
            id_dokter,
            hari,
            jam
        )
        VALUES(
            '$dokter',
            '$hari',
            '$jam'
        )
        ");

        $success = "Jadwal berhasil ditambahkan!";
    }
}

// HAPUS JADWAL
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($conn,"
    DELETE FROM jadwal
    WHERE id_jadwal='$id'
    ");

    header("Location: admin_jadwal.php");
}

// EDIT JADWAL
if(isset($_POST['edit'])){

    $id = $_POST['id'];
    $dokter = $_POST['dokter'];
    $hari = trim($_POST['hari']);
    $jam = trim($_POST['jam']);

    if($dokter == "" || $hari == "" || $jam == ""){

        $error = "Semua field wajib diisi!";

    }else{

        mysqli_query($conn,"
        UPDATE jadwal
        SET
            id_dokter='$dokter',
            hari='$hari',
            jam='$jam'
        WHERE id_jadwal='$id'
        ");

        $success = "Jadwal berhasil diupdate!";
    }
}

// AMBIL DATA EDIT
$edit = null;

if(isset($_GET['edit'])){

    $id_edit = $_GET['edit'];

    $edit = mysqli_fetch_assoc(
        mysqli_query($conn,"
        SELECT * FROM jadwal
        WHERE id_jadwal='$id_edit'
        ")
    );
}

// AMBIL DATA DOKTER
$dokter = mysqli_query($conn,"
SELECT * FROM dokter
");

// AMBIL DATA JADWAL
$data = mysqli_query($conn,"
SELECT *
FROM jadwal

JOIN dokter
ON jadwal.id_dokter = dokter.id_dokter

ORDER BY jadwal.id_jadwal DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Kelola Jadwal</title>

<link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        Kelola Jadwal
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
                Edit Jadwal
            <?php }else{ ?>
                Tambah Jadwal
            <?php } ?>

        </div>

        <br>

        <form method="POST">

            <?php if($edit){ ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= $edit['id_jadwal']; ?>"
                >

            <?php } ?>

            <div class="label">
                Pilih Dokter
            </div>

            <select name="dokter">

                <option value="">
                    -- Pilih Dokter --
                </option>

                <?php while($d = mysqli_fetch_assoc($dokter)){ ?>

                    <option
                        value="<?= $d['id_dokter']; ?>"

                        <?php
                        if($edit && $edit['id_dokter'] == $d['id_dokter']){
                            echo "selected";
                        }
                        ?>

                    >

                        <?= $d['nama_dokter']; ?>
                        -
                        <?= $d['spesialis']; ?>

                    </option>

                <?php } ?>

            </select>

            <div class="label">
                Hari
            </div>

            <input
                type="text"
                name="hari"
                placeholder="Contoh: Senin"
                value="<?= $edit ? $edit['hari'] : ''; ?>"
            >

            <div class="label">
                Jam
            </div>

            <input
                type="time"
                name="jam"
                value="<?= $edit ? $edit['jam'] : ''; ?>"
            >

            <?php if($edit){ ?>

                <button type="submit" name="edit">
                    Update Jadwal
                </button>

            <?php }else{ ?>

                <button type="submit" name="tambah">
                    Tambah Jadwal
                </button>

            <?php } ?>

        </form>

    </div>

    <!-- TABEL -->
    <div class="card">

        <div class="title">
            Data Jadwal
        </div>

        <br>

        <table>

            <tr>
                <th>No</th>
                <th>Dokter</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            while($j = mysqli_fetch_assoc($data)){
            ?>

            <tr>

                <td><?= $no++; ?></td>

                <td>
                    <?= $j['nama_dokter']; ?>
                </td>

                <td>
                    <?= $j['hari']; ?>
                </td>

                <td>
                    <?= $j['jam']; ?>
                </td>

                <td>

                    <a href="?edit=<?= $j['id_jadwal']; ?>">
                        Edit
                    </a>

                    |

                    <a
                        href="?hapus=<?= $j['id_jadwal']; ?>"
                        onclick="return confirm('Yakin hapus jadwal?')"
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
