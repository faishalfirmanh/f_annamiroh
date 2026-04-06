<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }

        .coupon-code {
            font-family: monospace;
            font-size: 1.8rem;
            letter-spacing: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0"><?= $title ?></h4>
                    </div>
                    <div class="card-body">
                        <form id="formInputVoucher" method="POST">
                            <div class="mb-4">
                                <label for="code_voucher" class="form-label fw-bold">Masukkan Kode Voucher</label>
                                <input type="text" id="code_voucher" name="code_voucher"
                                    class="form-control form-control-lg coupon-code text-center"
                                    placeholder="KODEVOUCHER123" maxlength="20" autocomplete="off" required autofocus>
                                <small class="text-muted">Masukkan kode voucher tanpa spasi</small>
                            </div>

                            <button type="submit" id="btnSubmit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-check"></i> Gunakan Voucher
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-center text-muted">
                        Status akan ditampilkan di bawah
                    </div>
                </div>

                <!-- Area untuk hasil -->
                <div id="result" class="mt-4"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#formInputVoucher').on('submit', function (e) {
                e.preventDefault();

                const code = $('#code_voucher').val().trim();
                if (code === '') return;

                $('#btnSubmit').prop('disabled', true).html('Sedang memproses...');

                $.post('<?= site_url('coupon/used_coupon') ?>', { code_voucher: code }, function (res) {
                    let html = '';

                    if (res.status === 'success') {
                        html = `
                    <div class="alert alert-success text-center">
                        <h5>Voucher Berhasil Digunakan!</h5>
                        <p class="mb-1">Kode: <strong>${res.code}</strong></p>
                        <p class="mb-1">Nominal: <strong>${res.nominal_voucher}</strong></p>
                        <small>Digunakan pada: ${res.updated_at}</small>
                    </div>`;
                        $('#code_voucher').val(''); // kosongkan input setelah sukses
                    }
                    else if (res.status === 'already_used') {
                        html = `
                    <div class="alert alert-warning text-center">
                        <h5>Kode Voucher Sudah Digunakan</h5>
                        <p>Kode <strong>${res.code}</strong> telah digunakan sebelumnya.</p>
                    </div>`;
                    }
                    else if (res.status === 'not_found') {
                        html = `
                    <div class="alert alert-danger text-center">
                        <h5>Kode Voucher Tidak Ditemukan</h5>
                        <p>Kode <strong>${res.code}</strong> tidak terdaftar di sistem.</p>
                    </div>`;
                    }
                    else {
                        html = `<div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>`;
                    }

                    $('#result').html(html);
                    $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-check"></i> Gunakan Voucher');

                }, 'json').fail(function () {
                    $('#result').html('<div class="alert alert-danger">Gagal terhubung ke server. Coba lagi.</div>');
                    $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-check"></i> Gunakan Voucher');
                });
            });

            // Tekan Enter langsung submit
            $('#code_voucher').on('keypress', function (e) {
                if (e.which === 13) $('#formInputVoucher').submit();
            });
        });
    </script>
</body>

</html>