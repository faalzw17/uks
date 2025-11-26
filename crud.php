<?php
include "koneksi.php"; // koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil data dari form
    $tanggal = $_POST['tanggal'];
    $nama    = $_POST['nama'];
    $kelas   = $_POST['kelas'];
    $keluhan = $_POST['keluhan'];
    $jam = date("H:i:s"); // selalu waktu sekarang

    // Query insert ke tabel pasien
    $query = "INSERT INTO pasien (tanggal, nama, kelas, keluhan, waktu) 
              VALUES ('$tanggal', '$nama', '$kelas', '$keluhan', '$jam')";

    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        echo "success";  // dikirim ke AJAX
    } else {
        echo "error";
    }
}
?>
