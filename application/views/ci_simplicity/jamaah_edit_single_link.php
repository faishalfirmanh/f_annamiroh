<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Jamaah</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(to right, #d04ed6, #834d9b);
            --primary-color: #d04ed6;
            --text-color: #333;
            --label-color: #666;
            --border-color: #e0e0e0;
            --bg-color: #f4f7f6;
        }

        body { font-family: 'Poppins', sans-serif; background: var(--bg-color); color: var(--text-color); padding: 20px; margin: 0; }
        
        .main-wrapper { max-width: 1300px; margin: 0 auto; }
        .header-section { text-align: center; margin-bottom: 30px; }

        /* Grid Utama 2 Kolom */
        .jamaah-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        @media (max-width: 1024px) { .jamaah-grid { grid-template-columns: 1fr; } }

        .card-jamaah {
            background: #fff;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }

        /* Sub-grid di dalam Card agar field rapi */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group { display: flex; flex-direction: column; margin-bottom: 15px; }
        .full-width { grid-column: span 2; }

        label { font-size: 13px; font-weight: 600; color: var(--label-color); margin-bottom: 5px; }

        input[type="text"], input[type="date"], input[type="number"], textarea, select {
            width: 100%; padding: 10px 0; font-size: 15px; border: none;
            border-bottom: 2px solid var(--border-color); background: transparent; outline: none; transition: 0.3s;
        }

        input:focus, textarea:focus { border-bottom-color: var(--primary-color); }

        /* Kustomisasi Select2 */
        .select2-container--default .select2-selection--single {
            border: none !important; border-bottom: 2px solid var(--border-color) !important; border-radius: 0 !important; height: 40px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left: 0 !important; line-height: 40px !important; }

        /* Preview KTP */
        .preview-container {
            margin-top: 10px;
            width: 100%;
            height: 180px;
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fafafa;
        }
        .preview-container img { max-width: 100%; max-height: 100%; object-fit: contain; }

        .btn-submit {
            width: 100%; padding: 15px; border: none; border-radius: 30px;
            background: var(--primary-gradient); color: white; font-size: 16px;
            font-weight: 700; cursor: pointer; transition: 0.3s; margin-top: 10px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(208, 78, 214, 0.4); }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="header-section">
        <h2 style="font-weight: 700; margin-bottom: 5px;">Edit Data Jamaah</h2>
        <p style="color: #888;">Kelola informasi data jamaah</p>
    </div>

    <div class="jamaah-grid">
     
   
        <?php foreach ($users as $index => $jamaah): ?>
        <?php $cek_kondisi_update =  $jamaah->status_generate > 1 ? 'disabled': '' ?>
        <div class="card-jamaah">
            <h4 style="color: var(--primary-color); border-left: 4px solid var(--primary-color); padding-left: 10px; margin-bottom: 25px;">
                Jamaah #<?= $index + 1 ?>
            </h4>

            <?php if ($this->session->flashdata('error_edit')): ?>
                <div class="alert alert-danger" role="alert">
                    <strong style="color:red !important"><i class="fa fa-exclamation-circle"></i> Terjadi Kesalahan!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.style.display='none';">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="small" style="margin-top: 10px;color:red !important">
                        <?= $this->session->flashdata('error_edit'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" action="<?= base_url('JamaahLinkShare/submitEditData/'.$jamaah->random_uuid) ?>">
                <input type="hidden" name="random_uuid" value="<?= $jamaah->random_uuid ?>"/>
                <input type="hidden" name="ktp_compressed" class="ktp_compressed_input">
                <input type="hidden" name="form_multi_jamaah" value="1"/>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Nama Lengkap (Sesuai KTP) <span style="color:red;">*</span></label>
                        <input type="text" <?= $cek_kondisi_update ?>  name="nama_jamaah" required value="<?= htmlspecialchars($jamaah->nama_jamaah) ?>" placeholder="Contoh: Ahmad Subagja">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Sebutan <span style="color:red;">*</span></label>
                        <select  <?= $cek_kondisi_update ?> name="title">
                            <option value="Mr" <?= $jamaah->title=='Bpk'?'selected':'' ?>>Bpk</option>
                            <option value="Mrs" <?= $jamaah->title=='Ibu'?'selected':'' ?>>Ibu</option>
                            <option value="Chd" <?= $jamaah->title=='Chd'?'selected':'' ?>>Chd</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nomor HP / WhatsApp <span style="color:red;">*</span></label>
                        <input  <?= $cek_kondisi_update ?> type="number" name="no_tlp" value="<?= $jamaah->no_tlp ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat Lahir <span style="color:red;">*</span></label>
                        <input  <?= $cek_kondisi_update ?> type="text" required name="tempat_lahir" value="<?= htmlspecialchars($jamaah->tempat_lahir) ?>">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <span style="color:red;">*</span></label>
                        <input  <?= $cek_kondisi_update ?> type="date" required name="tgl_lahir" value="<?= $jamaah->tgl_lahir ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Nomor KTP (NIK) <span style="color:red;">*</span></label>
                        <input  <?= $cek_kondisi_update ?> type="number" name="no_ktp" value="<?= $jamaah->no_ktp ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Provinsi <span style="color:red;">*</span></label>
                        <select  <?= $cek_kondisi_update ?> name="location_prov" class="provinsi select2-js" data-selected="<?= $jamaah->location_prov ?>" required></select>
                    </div>
                    <div class="form-group">
                        <label>Kota / Kabupaten <span style="color:red;">*</span></label>
                        <select  <?= $cek_kondisi_update ?> name="location_city" class="kota select2-js" data-selected="<?= $jamaah->location_city ?>" required></select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Kecamatan <span style="color:red;">*</span></label>
                        <select  <?= $cek_kondisi_update ?> name="location_disct" class="kecamatan select2-js" data-selected="<?= $jamaah->location_disct ?>" required></select>
                    </div>
                    <div class="form-group">
                        <label>Kelurahan / Desa <span style="color:red;">*</span></label>
                        <select  <?= $cek_kondisi_update ?> name="location_village" class="kelurahan select2-js" data-selected="<?= $jamaah->location_village ?>" required></select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Kantor Imigrasi (Untuk Paspor)</label>
                        <select  <?= $cek_kondisi_update ?> name="imigrasi" class="imigrasi select2-js" data-selected="<?= $jamaah->imigrasi ?>"></select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Details (rt,rw/ jalan) </label>
                    <textarea  <?= $cek_kondisi_update ?> name="alamat_jamaah" rows="2"><?= htmlspecialchars($jamaah->alamat_jamaah) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Upload KTP (JPG/PNG)</label>
                        <input  <?= $cek_kondisi_update ?> type="file" name="ktp" class="ktp_file_input" accept="image/*">
                        <div class="preview-container">
                            <img src="<?= !empty($jamaah->ktp) ? base_url('assets/uploads/ktp/'.$jamaah->ktp) : '' ?>" class="ktp_preview_img" alt="Preview KTP" style="<?= empty($jamaah->ktp) ? 'display:none;' : '' ?>">
                            <span class="placeholder_text" style="<?= !empty($jamaah->ktp) ? 'display:none;' : '' ?>"><i class="fa fa-image"></i> Belum ada foto</span>
                        </div>
                    </div>
                </div>

                <?php if($jamaah->status_generate < 2) : ?>
                    <button   type="submit" class="btn-submit" <?= $cek_kondisi_update ?>>Simpan Data Jamaah #<?= $index + 1 ?></button>
                <?php endif; ?>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    const baseUrl = "<?= base_url('location/') ?>";
    let isCompressing = false;
    // Inisialisasi Select2
    $('.select2-js').select2({ width: '100%', placeholder: 'Pilih...' });

    // --- LOGIKA LOKASI & IMIGRASI ---
    $('.card-jamaah').each(function() {
        const $card = $(this);
        const selProv = $card.find('.provinsi').data('selected');
        const selImigrasi = $card.find('.imigrasi').data('selected');

        // Load Imigrasi
        $.getJSON(baseUrl + "api_imigrasi", function (data) {
            let html = '<option value="">Pilih Imigrasi</option>';
            $.each(data, function (i, v) {
                let s = (v.id == selImigrasi) ? 'selected' : '';
                html += `<option value="${v.id}" ${s}>${v.nama_imigrasi}</option>`;
            });
            $card.find('.imigrasi').html(html).trigger('change');
        });

        // Load Provinsi
        $.getJSON(baseUrl + "api_provinces", function (data) {
            let html = '<option value="">Pilih Provinsi</option>';
            $.each(data, function (i, v) {
                let s = (v.id == selProv) ? 'selected' : '';
                html += `<option value="${v.id}" ${s}>${v.name}</option>`;
            });
            $card.find('.provinsi').html(html).trigger('change');
        });
    });

    // Event Change Chained Select (Prov -> Kota -> Kec -> Desa)
    $(document).on('change', '.provinsi', function () {
        const $card = $(this).closest('.card-jamaah');
        const id = $(this).val();
        const selected = $card.find('.kota').data('selected');
        if (!id) return;
        $.post(baseUrl + "api_cities", {id_prov: id}, function (data) {
            let html = '<option value="">Pilih Kota</option>';
            $.each(data, function (i, v) { html += `<option value="${v.id}" ${v.id == selected ? 'selected' : ''}>${v.name}</option>`; });
            $card.find('.kota').html(html).trigger('change');
        }, 'json');
    });

    $(document).on('change', '.kota', function () {
        const $card = $(this).closest('.card-jamaah');
        const id = $(this).val();
        const selected = $card.find('.kecamatan').data('selected');
        if (!id) return;
        $.post(baseUrl + "api_districts", {id_city: id}, function (data) {
            let html = '<option value="">Pilih Kecamatan</option>';
            $.each(data, function (i, v) { html += `<option value="${v.id}" ${v.id == selected ? 'selected' : ''}>${v.name}</option>`; });
            $card.find('.kecamatan').html(html).trigger('change');
        }, 'json');
    });

    $(document).on('change', '.kecamatan', function () {
        const $card = $(this).closest('.card-jamaah');
        const id = $(this).val();
        const selected = $card.find('.kelurahan').data('selected');
        if (!id) return;
        $.post(baseUrl + "api_villages", {id_district: id}, function (data) {
            let html = '<option value="">Pilih Desa</option>';
            $.each(data, function (i, v) { html += `<option value="${v.id}" ${v.id == selected ? 'selected' : ''}>${v.name}</option>`; });
            $card.find('.kelurahan').html(html).trigger('change');
        }, 'json');
    });

   
    $(document).on('change', '.ktp_file_input', function (e) {
        const $card = $(this).closest('.card-jamaah');
        const file = e.target.files[0];
        if (!file) return;

        // Hanya untuk Preview, jangan dikompres/diubah ke hidden input
        const reader = new FileReader();
        reader.onload = function(event) {
            $card.find('.ktp_preview_img').attr('src', event.target.result).show();
            $card.find('.placeholder_text').hide();
        };
        reader.readAsDataURL(file);
    });
});
</script>
</body>
</html>