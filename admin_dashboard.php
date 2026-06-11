php
session_start();
include 'koneksi.php';

// CEK LOGIN
if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

// CEK ROLE ADMIN
if($_SESSION['user']['role'] != "admin"){
    header("Location: dashboard.php");
}

// TOTAL DATA
$total_pasien = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT * FROM user
    WHERE role='pasien'
    ")
);

$total_dokter = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT * FROM dokter
    ")
);

$total_booking = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT * FROM booking
    ")
);

$total_pembayaran = mysqli_num_rows(
    mysqli_query($conn,"
    SELECT * FROM pembayaran
    WHERE status='lunas'
    ")
);

?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        Dashboard Admin
    </div>

    <!-- WELCOME -->
    <div class="card">

        <div class="title">
            Selamat Datang Admin 👋
        </div>

        <br>

        <b>Nama :</b>
        <?= $_SESSION['user']['nama']; ?>

        <br><br>

        <b>Email :</b>
        <?= $_SESSION['user']['email']; ?>

    </div>

    <!-- STATISTIK -->
    <div class="menu">

        <div class="card">

            <div class="title">
                <?= $total_pasien; ?>
            </div>

            Total Pasien

        </div>

        <div class="card">

            <div class="title">
                <?= $total_dokter; ?>
            </div>

            Total Dokter

        </div>

        <div class="card">

            <div class="title">
                <?= $total_booking; ?>
            </div>

            Total Booking

        </div>

        <div class="card">

            <div class="title">
                <?= $total_pembayaran; ?>
            </div>

            Pembayaran Lunas

        </div>

    </div>

    <br>

    <!-- MENU ADMIN -->
    <div class="card">

        <div class="title">
            Menu Admin
        </div>

        <br>

        <a href="admin_dokter.php">
            <button>
                Kelola Dokter
            </button>
        </a>

        <a href="admin_jadwal.php">
            <button>
                Kelola Jadwal
            </button>
        </a>

        <a href="admin_antrian.php">
            <button>
                Kelola Antrian
            </button>
        </a>

        <a href="admin_pembayaran.php">
            <button>
                Verifikasi Pembayaran
            </button>
        </a>

    </div>

    <!-- INFO -->
    <div class="card">

        <div class="title">
            Informasi Sistem
        </div>

        <br>

        ✅ Sistem booking online

        <br><br>

        ✅ Nomor antrian otomatis

        <br><br>

        ✅ Pembayaran online

        <br><br>

        ✅ Monitoring status pasien

    </div>

    <!-- LOGOUT -->
    <a href="logout.php">
        <button>
            Logout
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
