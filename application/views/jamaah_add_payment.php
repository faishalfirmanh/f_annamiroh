<div class="main-content">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Gagal!</strong> <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Berhasil!</strong> <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>


        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Halo, <?= explode(' ', $user)[0] ?>!</h5>
            <a href="<?= site_url('JamaahLinkShare/logout_api') ?>" class="btn btn-outline-danger btn-sm rounded-circle"><i class="bi bi-power"></i></a>
        </div>

        <div class="welcome-banner p-4 bg-primary text-white rounded-4 mb-4 shadow" style="background: linear-gradient(135deg, #0061f2, #6900f2);">
            <h2 class="fw-bold">Selamat Datang  <?=  $user ?></h2>
            <p class="mb-0">Input pembayaran</p>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
              <a href="<?= site_url('JamaahLinkShare/dashboard') ?>" style="text-decoration:none;">
                <div class="card p-3 text-center border-0 shadow-sm rounded-3">
                    <i class="bi bi-credit-card-2-back fs-1 text-warning"></i>
                    <span class="small fw-bold mt-2 d-block text-dark">Riwayat Transaksi</span>
                </div>
              </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?= site_url('JamaahLinkShare/view_add_payment') ?>" style="text-decoration:none;">
                    <div class="card p-3 text-center border-0 shadow-sm rounded-3 bg-light border-primary">
                        <i class="bi bi-arrow-clockwise fs-1 text-success"></i>
                        <span class="small fw-bold mt-2 d-block text-dark">Input Pembayaran</span>
                    </div>
                </a>
            </div>
        </div>
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">

                <form action="<?= site_url('JamaahLinkShare/save_payment_jamaah') ?>" method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Bank*</label>
                        <select name="bank" class="form-select" required>
                              <option value="">Pilih Bank</option>
                            <?php foreach($bank as $b): ?>
                                <option value="<?= $b->id ?>">
                                    <?= $b->nama_bank ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                  

                    <input type="hidden" name="id_jamaah" value="<?=$user_id ?>"/>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih paket*</label>
                        <select name="paket" class="form-select paket-class" required>
                            <option value="">Pilih Paket</option>
                            <?php foreach($paket as $b): ?>
                                <option value="<?= $b->paket_id ?>" class="select2">
                                    <?= $b->estimasi_keberangkatan ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tgl Transfer*</label>
                        <div class="d-flex gap-2">
                            <input type="date" name="tgl_transfer" class="form-control" required>
                            <button type="button" class="btn btn-link p-0" onclick="clearTransfer()">Bersihkan</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal transfer (IDR)*</label>
                        <input type="number" name="kredit" class="form-control" placeholder="Masukkan nominal" required>
                    </div>

                    <!-- <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="4"></textarea>
                    </div> -->

                    <div class="mb-3">
                        <label class="form-label fw-bold">Bukti*</label>
                        <input type="file" name="bukti" id="bukti" class="form-control" accept="image/*" required>
                        
                        <div id="preview-area" class="mt-3" style="display: none;">
                            <p class="small text-muted mb-1">Preview Bukti Transfer:</p>
                            <img id="image-preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 250px;">
                            <button type="button" class="btn btn-sm btn-outline-danger d-block mt-2" onclick="resetPreview()">
                                <i class="bi bi-trash"></i> Hapus Gambar
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>

                        <a href="<?= site_url('JamaahLinkShare/dashboard') ?>" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
   
$(document).ready(function() {
   $('.paket-class').select2();
   
   $("#bukti").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Set src gambar dengan hasil pembacaan file
                $('#image-preview').attr('src', e.target.result);
                // Munculkan area preview
                $('#preview-area').fadeIn();
            }
            
            reader.readAsDataURL(file);
        }
    });
});

 function resetPreview() {
        $('#bukti').val(''); 
        $('#preview-area').fadeOut(function() {
            $('#image-preview').attr('src', '#');
        });
    }

    function clearTransfer() {
        $('input[name="tgl_transfer"]').val('');
    }
</script>
