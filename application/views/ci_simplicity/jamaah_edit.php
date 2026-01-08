<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Jamaah</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 13px;
            margin-bottom: 4px;
            font-weight: bold;
        }

        input, select, textarea {
            padding: 7px 8px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
            min-height: 70px;
        }

        .actions {
            margin-top: 25px;
        }

        .actions button {
            padding: 8px 18px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #007bff;
            color: #fff;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 4px;
            margin-left: 8px;
        }

        @media(max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Edit Data Jamaah Umroh</h2>

    <form  method="post"  enctype="multipart/form-data" action="<?= base_url('JamaahLinkShare/submitEditData/'.$jamaah->random_uuid) ?>">

        <div class="form-grid">
            <input type="hidden" name="random_uuid" value="<?= $jamaah->random_uuid ?>"/>
            <div class="form-group">
                <label>Provinsi Jamaah</label>
                <select name="location_prov" class="" id="provinsi"></select>
            </div>

            <div class="form-group">
                <label>Kota / Kabupaten Jamaah</label>
                <select name="location_city" id="kota"></select>
            </div>

            <div class="form-group">
                <label>Kecamatan Jamaah</label>
                <select name="location_disct" id="kecamatan"></select>
            </div>

            <div class="form-group">
                <label>Kelurahan / Desa Jamaah</label>
                <select name="location_village" id="kelurahan"></select>
            </div>

            <div class="form-group">
                <label>Imigrasi</label>
                <select name="imigrasi" id="imigrasi">
                   
                </select>
            </div>

            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir"
                       value="<?= htmlspecialchars($jamaah->tempat_lahir) ?>">
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
                <input type="text" name="nama_jamaah"
                       value="<?= htmlspecialchars($jamaah->nama_jamaah) ?>">
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tgl_lahir"
                       value="<?= $jamaah->tgl_lahir ?>">
            </div>
            <div class="form-group">
                <label>No Ktp</label>
                <input type="number" name="no_ktp"
                       value="<?= $jamaah->no_ktp ?>">
            </div>

            <div class="form-group full">
                <label>Alamat Jamaah</label>
                <textarea name="alamat_jamaah"><?= htmlspecialchars($jamaah->alamat_jamaah) ?></textarea>
            </div>

             <div class="form-group">
                <label>Upload Foto KTP (Max 1 MB)</label>
                <input type="file"
                    name="ktp"
                    id="ktp"
                    accept="image/jpeg,image/png"
                    required>
            </div>
             <input type="hidden" name="ktp_compressed" id="ktp_compressed">
        </div>

        <div class="actions">
            <button type="submit" class="btn-primary">Update</button>
            <a href="javascript:history.back()" class="btn-secondary">Batal</a>
        </div>

    </form>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    console.log('wwoke');
    $('#provinsi, #kota, #kecamatan, #kelurahan, #imigrasi').select2({
        width: '100%',
        placeholder: 'Pilih...',
        allowClear: true
    });
    
    const baseUrl = "<?= base_url('location/') ?>";

    // ====== DATA EXISTING (EDIT MODE) ======
    const selectedProv   = "<?= $jamaah->location_prov ?>";
    const selectedCity   = "<?= $jamaah->location_city ?>";
    const selectedDist   = "<?= $jamaah->location_disct ?>";
    const selectedVillage= "<?= $jamaah->location_village ?>";
    
      const selectedImigrasi= "<?= $jamaah->imigrasi ?>";
    
    
    
    //==== load imigrasi =====
    
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

    // ====== LOAD PROVINSI ======
    $.getJSON(baseUrl + "api_provinces", function (data) {
        let html = '<option value="">Pilih Provinsi</option>';
        $.each(data, function (i, v) {
            let selected = (v.id == selectedProv) ? 'selected' : '';
            html += `<option value="${v.id}" ${selected}>${v.name}</option>`;
        });
        $('#provinsi').html(html).trigger('change');
    });

    // ====== PROVINSI → KOTA ======
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

    // ====== KOTA → KECAMATAN ======
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

    // ====== KECAMATAN → DESA ======
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

                const compressed = canvas.toDataURL('image/jpeg', 0.7); // quality 70%

                document.getElementById('ktp_compressed').value = compressed;
            };
        };
        reader.readAsDataURL(file);
    });
    
</script>


</html>
