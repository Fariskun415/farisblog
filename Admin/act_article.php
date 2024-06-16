<?php
include '../connection.php';

// Set the timezone to Jakarta
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk mengubah format tanggal
function formatTanggal($tanggal)
{
    $hari = array(
        1 => 'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    );

    $bulan = array(
        1 => 'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    );


    $split = explode('-', date('Y-m-d', strtotime($tanggal)));
    $tgl = date('d', strtotime($tanggal));

    $num = date('N', strtotime($tanggal));
    $jam = date('H:i', strtotime($tanggal));

    return $hari[$num] . ', ' . $tgl . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0] . ' | ' . $jam;
}

// Add Article
if (isset($_POST['articlesave'])) {
    $penulis = $_POST['penulis'];
    $kategori = $_POST['kategori'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = date('Y-m-d H:i:s'); // Misalkan tanggal saat ini
    $gambar = null;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
    }

    $query = "INSERT INTO artikel (penulis, id_kategori, judul, isi, tanggal, gambar) VALUES ('$penulis', '$kategori', '$judul', '$isi', '$tanggal', '$gambar')";

    if (mysqli_query($conn, $query)) {
        header('Location: viewArticle.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Edit Article
if (isset($_POST['articleedit'])) {
    $id = $_POST['tid'];
    $penulis = $_POST['penulis'];
    $kategori = $_POST['kategori'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];

    if ($_FILES['gambar']['tmp_name']) {
        // If a new image is uploaded
        $file = $_FILES['gambar']['tmp_name'];
        $image = addslashes(file_get_contents($file));

        $query = "UPDATE artikel SET penulis='$penulis', id_kategori='$kategori', judul='$judul', isi='$isi', gambar='$image' WHERE id_artikel='$id'";
    } else {
        // If no new image is uploaded
        $query = "UPDATE artikel SET penulis='$penulis', id_kategori='$kategori', judul='$judul', isi='$isi' WHERE id_artikel='$id'";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
        alert('Article updated successfully');
        document.location='viewArticle.php';
        </script>";
    } else {
        echo "<script>
        alert('Article update failed');
        document.location='viewArticle.php';
        </script>";
    }
}

// Delete Article
if (isset($_POST['articledelete'])) {
    $id = $_POST['id_artikel'];

    $query = "DELETE FROM artikel WHERE id_artikel='$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
        alert('Article deleted successfully');
        document.location='viewArticle.php';
        </script>";
    } else {
        echo "<script>
        alert('Article deletion failed');
        document.location='viewArticle.php';
        </script>";
    }
}
