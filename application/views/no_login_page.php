<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notif Edit Jamaah</title>
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="mb-4">
                <?= $notif_alert ?>
            </div>

            <?php if (isset($data_saved)): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0 text-center">
                            <i class="fa fa-user-circle"></i> Informasi Detail Jamaah
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr>
                                    <th class="bg-light w-40" style="padding: 15px;">Nama Jamaah</th>
                                    <td style="padding: 15px;"><?= $data_saved['nama_jamaah'] ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light" style="padding: 15px;">Jenis Jamaah</th>
                                    <td style="padding: 15px;">
                                        <span class="badge <?= strpos($data_saved['jenis_jamaah'], 'kantor') !== false ? 'badge-info' : 'badge-success' ?>">
                                            <?= $data_saved['jenis_jamaah'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light" style="padding: 15px;">Nama Paket</th>
                                    <td style="padding: 15px;">
                                        <strong class="text-primary"><?= $data_saved['nama_paket'] ?></strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white text-center py-3">
                         <small class="text-muted">kirim Screenshot  ini ke CS</small>
                        <br>
                        <small class="text-muted">Data ini berhasil diperbarui pada <?= date('d M Y H:i') ?></small>
                    </div>
                </div>

               
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
    /* Styling tambahan untuk mobile support */
    .w-40 { width: 40%; }
    
    @media (max-width: 576px) {
        .container { margin-top: 50px !important; }
        .card-title { font-size: 1.1rem; }
        .table th, .table td { 
            display: block; 
            width: 100% !important; 
        }
        .table th {
            border-bottom: none;
            padding-bottom: 5px !important;
        }
        .table td {
            padding-top: 0 !important;
            padding-bottom: 15px !important;
            border-top: none;
        }
        .btn-block-mobile {
            width: 100%;
        }
    }
</style>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
