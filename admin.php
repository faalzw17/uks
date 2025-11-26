<?php
session_start();
include "koneksi.php";

// Cek login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "admin" && $password === "4dm1n5677") {
        $_SESSION['logged_in'] = true;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Jika belum login tampilkan form login
if (!isset($_SESSION['logged_in'])):
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin UKS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #2E8B57, #20B2AA);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.login-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    width: 100%;
    max-width: 400px;
    position: relative;
    overflow: hidden;
}

.login-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2E8B57, #20B2AA);
}

.login-title {
    color: #2E8B57;
    font-weight: 700;
    text-align: center;
    margin-bottom: 25px;
}

.form-control {
    border-radius: 12px;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #20B2AA;
    box-shadow: 0 0 0 3px rgba(32, 178, 170, 0.1);
}

.btn-login {
    background: linear-gradient(135deg, #2E8B57, #20B2AA);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-login:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(46, 139, 87, 0.3);
}
</style>
</head>
<body>
    <div class="login-card">
        <h4 class="login-title"><i class="fas fa-user-shield me-2"></i>Login Admin UKS</h4>
        <?php if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>
<?php exit; endif; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin UKS - Data Pasien Lengkap</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
:root {
    --primary-color: #2E8B57;
    --secondary-color: #3CB371;
    --accent-color: #20B2AA;
    --light-color: #F0FFF0;
    --dark-color: #228B22;
    --gradient-start: #2E8B57;
    --gradient-end: #20B2AA;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    min-height: 100vh;
    padding: 20px;
}

/* Header Styling */
.admin-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 20px 30px;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    position: relative;
    overflow: hidden;
}

.admin-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
}

.header-title {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 5px;
}

.header-subtitle {
    color: #666;
    font-size: 1rem;
}

/* Card Styling */
.card-modern {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 25px;
}

.card-header-modern {
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: white;
    padding: 20px 25px;
    border-bottom: none;
}

.card-title-modern {
    font-weight: 700;
    font-size: 1.4rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body-modern {
    padding: 25px;
}

/* Button Styling */
.btn-modern {
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.btn-primary-modern {
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: white;
}

.btn-primary-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(46, 139, 87, 0.3);
}

.btn-danger-modern {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
}

.btn-danger-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
}

.btn-warning-modern {
    background: linear-gradient(135deg, #ffc107, #f39c12);
    color: white;
}

.btn-warning-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
}

.btn-success-modern {
    background: linear-gradient(135deg, #28a745, #2ecc71);
    color: white;
}

.btn-success-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
}

.btn-secondary-modern {
    background: linear-gradient(135deg, #6c757d, #95a5a6);
    color: white;
}

.btn-secondary-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
}

/* Form Styling */
.form-control-modern {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 12px 15px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.form-control-modern:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(32, 178, 170, 0.1);
    transform: translateY(-2px);
}

/* Table Styling */
.table-modern {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.table-modern thead th {
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: white;
    font-weight: 600;
    border: none;
    padding: 15px;
}

.table-modern tbody td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.table-modern tbody tr:hover {
    background-color: rgba(46, 139, 87, 0.05);
}

/* Status Badges */
.badge-penanganan {
    background: linear-gradient(135deg, #28a745, #2ecc71);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
}

.badge-belum {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
}

.badge-kendala {
    background: linear-gradient(135deg, #ffc107, #f39c12);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
}

.badge-tidak-kendala {
    background: linear-gradient(135deg, #6c757d, #95a5a6);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
}

/* Text Ellipsis */
.penanganan, .keluhan, .kendala {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Filter Section */
.filter-section {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.filter-title {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-header {
        padding: 15px 20px;
    }
    
    .card-body-modern {
        padding: 15px;
    }
    
    .btn-modern {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
    
    .table-modern thead th,
    .table-modern tbody td {
        padding: 10px;
    }
    
    .penanganan, .keluhan, .kendala {
        max-width: 150px;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease;
}
</style>
</head>
<body>

<div class="container-fluid">
    <!-- Header -->
    <div class="admin-header fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="header-title"><i class="fas fa-heartbeat me-2"></i>Dashboard Admin UKS</h2>
                <p class="header-subtitle">Kelola data keluhan dan penanganan pasien</p>
            </div>
            <div class="d-flex gap-2">
                <a href="?logout" class="btn btn-warning-modern">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <button id="clearAll" class="btn btn-danger-modern">
                    <i class="fas fa-trash-alt"></i> Clear All
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section fade-in">
        <h5 class="filter-title"><i class="fas fa-filter"></i> Filter Data</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" id="filterNama" class="form-control-modern" placeholder="Cari berdasarkan nama...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterKelas" class="form-control-modern" placeholder="Cari berdasarkan kelas...">
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2">
                    <input type="date" id="filterTanggal" class="form-control-modern">
                    <button id="filterBtn" class="btn btn-success-modern">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <button id="resetFilter" class="btn btn-secondary-modern">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-modern fade-in">
        <div class="card-header-modern">
            <h5 class="card-title-modern">
                <i class="fas fa-table"></i> Data Keluhan Pasien
            </h5>
        </div>
        <div class="card-body-modern">
            <table id="tabelPasien" class="table table-striped table-modern" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Keluhan</th>
                        <th>Penanganan</th>
                        <th>Kendala</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                $data = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY id DESC");
                while ($row = mysqli_fetch_assoc($data)) {
                    $penanganan = isset($row['penanganan']) ? $row['penanganan'] : '';
                    $kendala = isset($row['kendala']) ? $row['kendala'] : '';
                ?>
                    <tr id="row-<?= $row['id']; ?>">
                        <td><?= $no++; ?></td>
                        <td><?= $row['tanggal']; ?></td>
                        <td><?= $row['waktu']; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['kelas']; ?></td>
                        <td class="keluhan"><?= $row['keluhan']; ?></td>
                        <td>
                            <?php if (!empty($row['penanganan'])): ?>
                                <span class="badge-penanganan">Sudah Ditangani</span>
                                <div class="penanganan" title="<?= htmlspecialchars($row['penanganan']); ?>">
                                    <?= htmlspecialchars($row['penanganan']); ?>
                                </div>
                            <?php else: ?>
                                <span class="badge-belum">Belum Ditangani</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($row['kendala'])): ?>
                                <span class="badge-kendala">Ada Kendala</span>
                                <div class="kendala" title="<?= htmlspecialchars($row['kendala']); ?>">
                                    <?= htmlspecialchars($row['kendala']); ?>
                                </div>
                            <?php else: ?>
                                <span class="badge-tidak-kendala">Tidak Ada Kendala</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary-modern btn-sm editBtn" 
                                    data-id="<?= $row['id']; ?>" 
                                    data-nama="<?= htmlspecialchars($row['nama']); ?>" 
                                    data-penanganan="<?= htmlspecialchars($penanganan); ?>"
                                    data-kendala="<?= htmlspecialchars($kendala); ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger-modern btn-sm deleteBtn" data-id="<?= $row['id']; ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#tabelPasien').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Data Pasien UKS',
                className: 'btn btn-success-modern',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'Data Pasien UKS',
                className: 'btn btn-danger-modern',
                orientation: 'portrait',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
            {
                extend: 'print',
                title: 'Data Pasien UKS',
                className: 'btn btn-secondary-modern',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Filter
    $('#filterNama').on('keyup', function(){ table.column(3).search(this.value).draw(); });
    $('#filterKelas').on('keyup', function(){ table.column(4).search(this.value).draw(); });
    $('#filterBtn').on('click', function(){
        var tanggal = $('#filterTanggal').val();
        if(tanggal) table.column(1).search('^'+tanggal,true,false).draw();
    });
    $('#resetFilter').on('click', function(){
        $('#filterNama,#filterKelas,#filterTanggal').val('');
        table.search('').columns().search('').draw();
    });

    // Delete row
    $(document).on('click','.deleteBtn',function(){
        var id = $(this).data('id');
        Swal.fire({
            title:'Yakin hapus data?',
            text:'Data ini akan dihapus permanen!',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#d33',
            cancelButtonColor:'#3085d6',
            confirmButtonText:'Ya, hapus!',
            cancelButtonText:'Batal'
        }).then((result)=>{
            if(result.isConfirmed){
                $.post('delete.php',{id:id},function(res){
                    if(res=='success'){
                        $('#row-'+id).fadeOut(600,function(){ $(this).remove(); });
                        Swal.fire({
                            title:'Terhapus!',
                            text:'Data berhasil dihapus.',
                            icon:'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }else{
                        Swal.fire('Gagal!','Tidak dapat menghapus data.','error');
                    }
                });
            }
        });
    });

    // Clear all
    $('#clearAll').on('click',function(){
        Swal.fire({
            title:'Hapus semua data?',
            text:'Semua data pasien akan terhapus permanen!',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#d33',
            cancelButtonColor:'#3085d6',
            confirmButtonText:'Ya, hapus semua!',
            cancelButtonText:'Batal'
        }).then((result)=>{
            if(result.isConfirmed){
                $.post('clear_all.php',{clear:1},function(res){
                    if(res=='success'){
                        table.clear().draw();
                        Swal.fire({
                            title:'Terhapus!',
                            text:'Semua data berhasil dihapus.',
                            icon:'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }else{
                        Swal.fire('Gagal!','Tidak dapat menghapus semua data.','error');
                    }
                });
            }
        });
    });

    // Edit penanganan dan kendala - FIXED VERSION
    $(document).on('click', '.editBtn', function(){
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var currentPenanganan = $(this).data('penanganan');
        var currentKendala = $(this).data('kendala');

        Swal.fire({
            title: 'Kelola Penanganan & Kendala',
            html: `
                <div class="mb-3">
                    <p>Pasien: <strong>${nama}</strong></p>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-stethoscope me-2"></i>Penanganan:</label>
                    <textarea id="swalPenanganan" class="form-control" rows="3" placeholder="Masukkan penanganan yang diberikan...">${currentPenanganan}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-exclamation-triangle me-2"></i>Kendala:</label>
                    <textarea id="swalKendala" class="form-control" rows="2" placeholder="Masukkan kendala yang dihadapi...">${currentKendala}</textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2E8B57',
            width: '600px',
            preConfirm: () => {
                return {
                    penanganan: document.getElementById('swalPenanganan').value,
                    kendala: document.getElementById('swalKendala').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var data = result.value;
                $.post('update_penanganan.php', {
                    id: id, 
                    penanganan: data.penanganan,
                    kendala: data.kendala
                }, function(res){
                    if(res == 'success'){
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Data berhasil diperbarui',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#2E8B57'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', 'Tidak dapat memperbarui data', 'error');
                    }
                });
            }
        });
    });
});
</script>

</body>
</html>