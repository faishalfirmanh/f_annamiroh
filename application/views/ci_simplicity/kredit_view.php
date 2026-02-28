<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Kredit</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
</head>
<body>

<div class="container mt-4">

    <h3>Transaksi Kredit</h3>

    <!-- ===== INFORMASI HEADER ===== -->
    <div class="card mb-3">
        <div class="card-body">
            <strong>Jamaah :</strong> <?= htmlspecialchars($jamaah) ?><br>
            <strong>Paket :</strong> <?= htmlspecialchars($paket) ?><br>
            <strong>Harga :</strong> Rp <?= number_format($harga,0,',','.') ?><br>
            <strong>Total Pembayaran :</strong> Rp <?= number_format($total_kredit,0,',','.') ?><br>
            <strong>Total Debet :</strong> Rp <?= number_format($total_debet,0,',','.') ?><br>
            <strong>Sisa :</strong> 
            <span class="text-danger">
                Rp <?= number_format($kurang,0,',','.') ?>
            </span>
        </div>
    </div>

    <!-- ===== FLASH MESSAGE ===== -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>


    <!-- ===== FORM INPUT KREDIT ===== -->
    <div class="card mb-4">
        <div class="card-header">Tambah Kredit</div>
        <div class="card-body">

            <form method="post" 
                  action="<?= base_url('transaksi_op/store_kredit') ?>" 
                  enctype="multipart/form-data">

                <input type="hidden" name="id_transaksi_paket" value="<?= $id ?>">

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Tanggal Transfer</label>
                        <input type="date" name="tanggal_transfer" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Bank</label>
                        <select name="bank_id" class="form-control" required>
                            <option value="">-- Pilih Bank --</option>
                            <?php foreach($banks as $b): ?>
                                <option value="<?= $b->id ?>">
                                    <?= htmlspecialchars($b->nama_bank) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Nominal Kredit</label>
                        <input type="text" name="kredit" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jenis Transaksi</label>
                        <input type="text" name="jenis_transaksi" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Bukti Transfer</label>
                        <input type="file" name="bukti" class="form-control">
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

            </form>
        </div>
    </div>


    <!-- ===== TABEL HISTORY ===== -->
    <div class="card">
        <div class="card-header">History Pembayaran</div>
        <div class="card-body">

            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Transfer</th>
                        <th>Bank</th>
                        <th>Kredit</th>
                        <th>Keterangan</th>
                        <th>Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($history): ?>
                        <?php foreach ($history as $h): ?>
                            <tr>
                                <td><?= $h->tanggal ?></td>
                                <td><?= $h->tanggal_transfer ?></td>
                                <td>
                                    <?php
                                        $bank = $this->db
                                            ->where('id', $h->bank_id)
                                            ->get('master_bank')
                                            ->row();
                                        echo $bank ? $bank->nama_bank : '-';
                                    ?>
                                </td>
                                <td>
                                    Rp <?= number_format($h->kredit,0,',','.') ?>
                                </td>
                                <td><?= htmlspecialchars($h->keterangan) ?></td>
                                <td>
                                    <?php if($h->bukti): ?>
                                        <a href="<?= base_url('assets/uploads/bukti/'.$h->bukti) ?>" target="_blank">
                                            Lihat
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada transaksi
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>