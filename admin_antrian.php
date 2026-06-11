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

$success = "";

// UPDATE STATUS
if(isset($_POST['update_status'])){

    $id_booking = $_POST['id_booking'];
    $status = $_POST['status'];

    mysqli_query($conn,"
    UPDATE booking
    SET status='$status'
    WHERE id_booking='$id_booking'
    ");

    $success = "Status antrian berhasil diupdate!";
}

// FILTER STATUS
$where = "";

if(isset($_GET['filter'])){

    $filter = $_GET['filter'];

    if($filter != ""){
        $where = "WHERE booking.status='$filter'";
    }
}

// AMBIL DATA ANTRIAN
$data = mysqli_query($conn,"
SELECT
    booking.*,
    user.nama,
    dokter.nama_dokter,
    dokter.spesialis,
    jadwal.hari,
    jadwal.jam

FROM booking

JOIN user
ON booking.id_user = user.id_user

JOIN jadwal
ON booking.id_jadwal = jadwal.id_jadwal

JOIN dokter
ON jadwal.id_dokter = dokter.id_dokter

$where

ORDER BY booking.antrian ASC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Kelola Antrian</title>

<link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        Kelola Antrian
    </div>

    <!-- ALERT -->
    <?php if($success != ""){ ?>
        <div class="alert-success">
            <?= $success; ?>
        </div>
    <?php } ?>

    <!-- FILTER -->
    <div class="card">

        <div class="title">
            Filter Status
        </div>

        <br>

        <form method="GET">

            <select name="filter">

                <option value="">
                    Semua Status
                </option>

                <option value="menunggu">
                    Menunggu
                </option>

                <option value="dipanggil">
                    Dipanggil
                </option>

                <option value="selesai">
                    Selesai
                </option>

            </select>

            <button type="submit">
                Filter
            </button>

        </form>

    </div>

    <!-- DATA ANTRIAN -->
    <?php if(mysqli_num_rows($data) > 0){ ?>

        <?php while($d = mysqli_fetch_assoc($data)){ ?>

            <div class="card">

                <div class="title">
                    Antrian #<?= $d['antrian']; ?>
                </div>

                <br>

                <b>Nama Pasien :</b>
                <?= $d['nama']; ?>

                <br><br>

                <b>Dokter :</b>
                <?= $d['nama_dokter']; ?>

                <br><br>

                <b>Spesialis :</b>
                <?= $d['spesialis']; ?>

                <br><br>

                <b>Hari :</b>
                <?= $d['hari']; ?>

                <br><br>

                <b>Jam :</b>
                <?= $d['jam']; ?>

                <br><br>

                <b>Tanggal :</b>
                <?= $d['tanggal']; ?>

                <br><br>

                <b>Status Saat Ini :</b>

                <?php if($d['status'] == "menunggu"){ ?>

                    <span class="status-menunggu">
                        Menunggu
                    </span>

                <?php } ?>

                <?php if($d['status'] == "dipanggil"){ ?>

                    <span class="status-dipanggil">
                        Dipanggil
                    </span>

                <?php } ?>

                <?php if($d['status'] == "selesai"){ ?>

                    <span class="status-selesai">
                        Selesai
                    </span>

                <?php } ?>

                <br><br>

                <!-- UPDATE STATUS -->
                <form method="POST">

                    <input
                        type="hidden"
                        name="id_booking"
                        value="<?= $d['id_booking']; ?>"
                    >

                    <div class="label">
                        Ubah Status
                    </div>

                    <select name="status">

                        <option value="menunggu">
                            Menunggu
                        </option>

                        <option value="dipanggil">
                            Dipanggil
                        </option>

                        <option value="selesai">
                            Selesai
                        </option>

                    </select>

                    <button
                        type="submit"
                        name="update_status"
                    >
                        Update Status
                    </button>

                </form>

            </div>

        <?php } ?>

    <?php }else{ ?>

        <div class="card">

            Tidak ada data antrian.

        </div>

    <?php } ?>

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
