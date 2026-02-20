<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Jamaah</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- Variabel Warna & Font --- */
        :root {
            --primary-gradient: linear-gradient(to right, #d04ed6, #834d9b);
            --primary-color: #d04ed6;
            --text-color: #333;
            --label-color: #666;
            --border-color: #e0e0e0;
            --bg-color: #f4f7f6;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 35px;
            font-size: 28px;
        }

        /* --- Form Layout (1 Kolom Penuh) --- */
        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            position: relative;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: var(--label-color);
            margin-bottom: 8px;
            display: block;
        }

        /* --- Gaya Input "Single Line" (Hanya Garis Bawah) --- */
        input[type="text"],
        input[type="date"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            font-family: inherit;
            color: var(--text-color);
            border: none;
            border-bottom: 2px solid var(--border-color);
            background: transparent;
            border-radius: 0;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-bottom-color: var(--primary-color);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        input[type="file"] {
            padding: 10px 0;
            font-size: 14px;
        }

        /* --- Kustomisasi Select2 agar jadi Garis Bawah --- */
        .select2-container .select2-selection--single {
            height: auto !important;
            border: none !important;
            border-bottom: 2px solid var(--border-color) !important;
            border-radius: 0 !important;
            background: transparent !important;
            padding: 8px 0 !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0 !important;
            color: var(--text-color) !important;
            font-size: 16px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-bottom-color: var(--primary-color) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
            right: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--label-color) transparent transparent transparent !important;
        }

        /* --- Tombol Aksi --- */
        .actions {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 5px 15px rgba(208, 78, 214, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(208, 78, 214, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--label-color);
            font-weight: 600;
            font-size: 16px;
        }
        .btn-secondary:hover {
            color: var(--text-color);
        }

        small {
            color: var(--label-color);
            margin-top: 5px;
            display: block;
        }

        /* Alert styling */
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            background-color: #ffebee;
            color: #c62828;
            position: relative;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Tambah Data Jamaah</h2>
    
     <?php if ($this->session->flashdata('error_edit')): ?>
        <div class="alert alert-danger" role="alert">
            <strong><i class="fa fa-exclamation-circle"></i> Terjadi Kesalahan!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.style.display='none';">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="small" style="margin-top: 10px;">
                <?= $this->session->flashdata('error_edit'); ?>
            </div>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="<?= base_url('JamaahLinkShare/submitEditData/'.$jamaah->random_uuid) ?>">
        
        <input type="hidden" name="random_uuid" value="<?= $jamaah->random_uuid ?>"/>
        <input type="hidden" name="ktp_compressed" id="ktp_compressed">

        <div class="form-grid">
            
            <div class="form-group">
                <label>Provinsi</label>
                <select name="location_prov" id="provinsi"></select>
            </div>

            <div class="form-group">
                <label>Kota / Kabupaten</label>
                <select name="location_city" id="kota"></select>
            </div>

            <div class="form-group">
                <label>Kecamatan</label>
                <select name="location_disct" id="kecamatan"></select>
            </div>

            <div class="form-group">
                <label>Kelurahan / Desa</label>
                <select name="location_village" id="kelurahan"></select>
            </div>

            <div class="form-group">
                <label>Kantor Imigrasi</label>
                <select name="imigrasi" id="imigrasi"></select>
            </div>

            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="<?= htmlspecialchars($jamaah->tempat_lahir) ?>" placeholder="Masukkan tempat lahir">
            </div>

            <div class="form-group">
                <label>Sebutan</label>
                <select name="title">
                    <option value="Mr" <?= $jamaah->title=='Bpk'?'selected':'' ?>>Bpk</option>
                    <option value="Mrs" <?= $jamaah->title=='Ibu'?'selected':'' ?>>Ibu</option>
                    <option value="Chd" <?= $jamaah->title=='Chd'?'selected':'' ?>>Chd</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_jamaah" value="<?= htmlspecialchars($jamaah->nama_jamaah) ?>" placeholder="Contoh: Budi Santoso">
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" value="<?= $jamaah->tgl_lahir ?>">
            </div>

            <div class="form-group">
                <label>Nomor KTP</label>
                <input type="number" name="no_ktp" value="<?= $jamaah->no_ktp ?>" required placeholder="16 digit nomor KTP">
            </div>

            <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input type="number" name="no_tlp" value="<?= $jamaah->no_tlp ?>" required placeholder="Contoh: 08123456789">
            </div>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat_jamaah" placeholder="Nama jalan, RT/RW, No. Rumah..."><?= htmlspecialchars($jamaah->alamat_jamaah) ?></textarea>
            </div>

             <div class="form-group">
                <label>Upload Foto KTP</label>
                <input type="file" name="ktp" id="ktp" accept="image/jpeg,image/png">
                <small>Maks. 1 MB. Format: JPG, PNG.</small>
            </div>
            
        </div>

        <div class="actions">
            <button type="submit" class="btn-primary">Simpan Data</button>
            <a href="javascript:history.back()" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    $('#provinsi, #kota, #kecamatan, #kelurahan, #imigrasi').select2({
        width: '100%',
        placeholder: 'Pilih...',
        allowClear: true
    });
    
    const baseUrl = "<?= base_url('location/') ?>";

    const selectedProv   = "<?= $jamaah->location_prov ?>";
    const selectedCity   = "<?= $jamaah->location_city ?>";
    const selectedDist   = "<?= $jamaah->location_disct ?>";
    const selectedVillage= "<?= $jamaah->location_village ?>";
    const selectedImigrasi= "<?= $jamaah->imigrasi ?>";
    
    $.getJSON(baseUrl + "api_imigrasi", function (data) {
        let html = '<option value="">Pilih Imigrasi</option>';
        $.each(data, function (i, v) {
                let selected = (v.id == selectedImigrasi) ? 'selected' : '';
                html += `<option value="${v.id}" ${selected}>${v.nama_imigrasi}</option>`;
            });
        $('#imigrasi').html(html).trigger('change');
    });
    
    $('#imigrasi').on('change', function () {
        let id = $(this).val();
        if (!id) return;

        $.post(baseUrl + "api_imigrasiById", {id: id}, function (data) {
            let html = '<option value="">Pilih Imigrasi</option>';
            $.each(data, function (i, v) {
                let selected = (v.id == selectedImigrasi) ? 'selected' : '';
                html += `<option value="${v.id}" ${selected}>${v.nama_imigrasi}</option>`;
            });
            $('#imigrasi').html(html).trigger('change');
        }, 'json');
    });

    $.getJSON(baseUrl + "api_provinces", function (data) {
        let html = '<option value="">Pilih Provinsi</option>';
        $.each(data, function (i, v) {
            let selected = (v.id == selectedProv) ? 'selected' : '';
            html += `<option value="${v.id}" ${selected}>${v.name}</option>`;
        });
        $('#provinsi').html(html).trigger('change');
    });

    $('#provinsi').on('change', function () {
        let id_prov = $(this).val();
        $('#kota').html('<option>Loading...</option>');
        $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
        $('#kelurahan').html('<option value="">Pilih Desa</option>');

        if (!id_prov) return;

        $.post(baseUrl + "api_cities", {id_prov: id_prov}, function (data) {
            let html = '<option value="">Pilih Kota</option>';
            $.each(data, function (i, v) {
                let selected = (v.id == selectedCity) ? 'selected' : '';
                html += `<option value="${v.id}" ${selected}>${v.name}</option>`;
            });
            $('#kota').html(html).trigger('change');
        }, 'json');
    });

    $('#kota').on('change', function () {
        let id_city = $(this).val();
        $('#kecamatan').html('<option>Loading...</option>');
        $('#kelurahan').html('<option value="">Pilih Desa</option>');

        if (!id_city) return;

        $.post(baseUrl + "api_districts", {id_city: id_city}, function (data) {
            let html = '<option value="">Pilih Kecamatan</option>';
            $.each(data, function (i, v) {
                let selected = (v.id == selectedDist) ? 'selected' : '';
                html += `<option value="${v.id}" ${selected}>${v.name}</option>`;
            });
            $('#kecamatan').html(html).trigger('change');
        }, 'json');
    });

    $('#kecamatan').on('change', function () {
        let id_dist = $(this).val();
        $('#kelurahan').html('<option>Loading...</option>');

        if (!id_dist) return;

        $.post(baseUrl + "api_villages", {id_district: id_dist}, function (data) {
            let html = '<option value="">Pilih Desa</option>';
            $.each(data, function (i, v) {
                let selected = (v.id == selectedVillage) ? 'selected' : '';
                html += `<option value="${v.id}" ${selected}>${v.name}</option>`;
            });
            $('#kelurahan').html(html);
        }, 'json');
    });

});

    document.getElementById('ktp').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB sebelum kompres.');
            e.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            const img = new Image();
            img.src = event.target.result;

            img.onload = function () {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                const maxWidth = 1200;
                const scale = maxWidth / img.width;

                canvas.width = maxWidth;
                canvas.height = img.height * scale;

                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                const compressed = canvas.toDataURL('image/jpeg', 0.7); 

                document.getElementById('ktp_compressed').value = compressed;
            };
        };
        reader.readAsDataURL(file);
    });
</script>
</body>
</html>
