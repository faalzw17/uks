<?php
include "koneksi.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = mysqli_query($koneksi, "DELETE FROM pasien WHERE id='$id'");

    if ($query) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
