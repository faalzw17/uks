<?php
include "koneksi.php";

if(isset($_POST['id']) && isset($_POST['penanganan'])) {
    $id = $_POST['id'];
    $penanganan = $_POST['penanganan'];
    $kendala = isset($_POST['kendala']) ? $_POST['kendala'] : '';
    
    // Update kedua field secara bersamaan
    $stmt = $koneksi->prepare("UPDATE pasien SET penanganan = ?, kendala = ? WHERE id = ?");
    $stmt->bind_param("ssi", $penanganan, $kendala, $id);
    
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    $stmt->close();
} else {
    echo "invalid";
}
?>