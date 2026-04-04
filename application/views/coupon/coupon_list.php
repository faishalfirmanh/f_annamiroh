<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .coupon-code {
            font-family: monospace;
            font-size: 1.15rem;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><?= $title ?></h4>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#generateModal">
                    <i class="fas fa-plus"></i> Generate Coupon Baru
                </button>
            </div>
            <div class="card-body">

                <table id="couponTable" class="table table-striped table-bordered" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th width="50"><input type="checkbox" id="selectAll"></th>
                            <th>Kode Coupon</th>
                            <th>Status</th>
                            <th>Dibuat Pada</th>
                            <th>Digunakan Pada</th>
                        </tr>
                    </thead>
                </table>

                <button class="btn btn-primary mt-3" id="btnDownload">
                    📥 Download PDF yang Dipilih
                </button>

            </div>
        </div>
    </div>

    <!-- Modal Generate -->
    <div class="modal fade" id="generateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Coupon Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Masukkan Jumlah Coupon</label>
                        <input type="number" id="jumlah" class="form-control form-control-lg" min="1" max="500"
                            value="10">
                        <small class="text-muted">Maksimal 500 per generate</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btnGenerate">Generate Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            var table = $('#couponTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= site_url('coupon/get_coupons_ajax') ?>",
                    "type": "post"
                },
                "columns": [
                    { "data": "checkbox", "orderable": false },
                    { "data": "code_coupon" },
                    { "data": "status" },
                    { "data": "created_at" },
                    {
                        "data": "updated_at",
                        "render": function (data, type, row) {
                            console.log('data', data)
                            if (!data || data === "" || data === "0000-00-00 00:00:00") {
                                return '<span class="text-muted">-</span>';
                            }
                            return data;
                        }
                    }
                ],
                "order": [[3, "desc"]],
                "pageLength": 10,
                "deferRender": true,
                "searchDelay": 500
            });

            // Select All
            $('#selectAll').on('click', function () {
                $('.coupon-checkbox').prop('checked', this.checked);
            });

            // Download PDF
            $('#btnDownload').on('click', function () {
                var selected = [];
                $('.coupon-checkbox:checked').each(function () {
                    selected.push($(this).val());
                });

                if (selected.length === 0) {
                    alert('Pilih minimal 1 coupon untuk didownload!');
                    return;
                }

                $('<form action="<?= site_url('coupon/download_pdf') ?>" method="POST">' +
                    selected.map(code => `<input type="hidden" name="selected_coupons[]" value="${code}">`).join('') +
                    '</form>').appendTo('body').submit();
            });

            // Generate via Modal
            $('#btnGenerate').on('click', function () {
                var jumlah = $('#jumlah').val();
                if (jumlah < 1 || jumlah > 500) {
                    alert('Jumlah harus antara 1 - 500');
                    return;
                }

                $(this).prop('disabled', true).html('Sedang Generate...');

                $.post('<?= site_url('coupon/generate') ?>', { jumlah: jumlah }, function (res) {
                    if (res.success) {
                        alert('Berhasil generate ' + res.coupons.length + ' coupon!');
                        $('#generateModal').modal('hide');
                        table.ajax.reload();
                    } else {
                        alert('generate gagal');
                    }
                    $('#btnGenerate').prop('disabled', false).html('Generate Sekarang');
                }, 'json').fail(function () {
                    alert('Terjadi kesalahan saat generate');
                    $('#btnGenerate').prop('disabled', false).html('Generate Sekarang');
                });
            });
        });
    </script>
</body>

</html>