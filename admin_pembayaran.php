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

// VERIFIKASI PEMBAYARAN
if(isset($_POST['verifikasi'])){

    $id = $_POST['id_pembayaran'];

    mysqli_query($conn,"
    UPDATE pembayaran
    SET status='lunas'
    WHERE id_pembayaran='$id'
    ");

    $success = "Pembayaran berhasil diverifikasi!";
}

// FILTER STATUS
$where = "";

if(isset($_GET['filter'])){

    $filter = $_GET['filter'];

    if($filter != ""){
        $where = "WHERE pembayaran.status='$filter'";
    }
}

// AMBIL DATA PEMBAYARAN
$data = mysqli_query($conn,"
SELECT
    pembayaran.*,
    booking.antrian,
    user.nama,
    dokter.nama_dokter

FROM pembayaran

JOIN booking
ON pembayaran.id_booking = booking.id_booking

JOIN user
ON booking.id_user = user.id_user

JOIN jadwal
ON booking.id_jadwal = jadwal.id_jadwal

JOIN dokter
ON jadwal.id_dokter = dokter.id_dokter

$where

ORDER BY pembayaran.id_pembayaran DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Verifikasi Pembayaran</title>

<link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        Verifikasi Pembayaran
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
            Filter Pembayaran
        </div>

        <br>

        <form method="GET">

            <select name="filter">

                <option value="">
                    Semua Status
                </option>

                <option value="belum bayar">
                    Belum Bayar
                </option>

                <option value="menunggu verifikasi">
                    Menunggu Verifikasi
                </option>

                <option value="lunas">
                    Lunas
                </option>

            </select>

            <button type="submit">
                Filter
            </button>

        </form>

    </div>

    <!-- DATA PEMBAYARAN -->
    <?php if(mysqli_num_rows($data) > 0){ ?>

        <?php while($d = mysqli_fetch_assoc($data)){ ?>

            <div class="card">

                <div class="title">
                    Pembayaran #<?= $d['id_pembayaran']; ?>
                </div>

                <br>

                <b>Nama Pasien :</b>
                <?= $d['nama']; ?>

                <br><br>

                <b>Dokter :</b>
                <?= $d['nama_dokter']; ?>

                <br><br>

                <b>Nomor Antrian :</b>
                <?= $d['antrian']; ?>

                <br><br>

                <b>Metode :</b>

                <?php
                if($d['metode'] != ""){
                    echo $d['metode'];
                }else{
                    echo "-";
                }
                ?>

                <br><br>

                <b>Total :</b>
                Rp 50.000

                <br><br>

                <b>Status :</b>

                <?php if($d['status'] == "belum bayar"){ ?>

                    <span class="status-menunggu">
                        Belum Bayar
                    </span>

                <?php } ?>

                <?php if($d['status'] == "menunggu verifikasi"){ ?>

                    <span class="status-dipanggil">
                        Menunggu Verifikasi
                    </span>

                <?php } ?>

                <?php if($d['status'] == "lunas"){ ?>

                    <span class="status-selesai">
                        Lunas
                    </span>

                <?php } ?>

                <!-- BUKTI -->
                <?php if($d['bukti'] != ""){ ?>

                    <br><br>

                    <b>Bukti Pembayaran :</b>

                    <br><br>

                    <img
                        src="bukti/<?= $d['bukti']; ?>"
                        width="100%"
                        style="border-radius:10px;"
                    >

                <?php } ?>

                <!-- VERIFIKASI -->
                <?php if($d['status'] == "menunggu verifikasi"){ ?>

                    <br><br>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="id_pembayaran"
                            value="<?= $d['id_pembayaran']; ?>"
                        >

                        <button
                            type="submit"
                            name="verifikasi"
                        >
                            Verifikasi Pembayaran
                        </button>

                    </form>

                <?php } ?>

            </div>

        <?php } ?>

    <?php }else{ ?>

        <div class="card">

            Tidak ada data pembayaran.

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
