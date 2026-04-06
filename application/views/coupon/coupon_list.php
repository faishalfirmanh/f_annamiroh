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
                <?php
                if ($nama_level === 'it') {
                    ?>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#generateModal">
                        <i class="fas fa-plus"></i> Generate Coupon Baru
                    </button>
                <?php } ?>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label fw-bold">Filter Status:</label>
                    <select id="filterStatus" class="form-select w-auto d-inline-block">
                        <option value="">Semua Status</option>
                        <option value="0">Belum Digunakan</option>
                        <option value="1">Sudah Digunakan</option>
                    </select>
                </div>

                <table id="couponTable" class="table table-striped table-bordered" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th width="50"><input type="checkbox" id="selectAll"></th>
                            <th>Kode Coupon</th>
                            <th>Nominal Vocer</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Digunakan Pada</th>
                            <th>Diupdate Oleh</th>
                        </tr>
                    </thead>
                </table>

                <button class="btn btn-primary mt-3" id="btnDownload">
                    📥 Download PDF yang Dipilih
                </button>
                <button style="margin-left:10px;" type="button" id="btnDownloadExcel" class="btn btn-success mt-3">
                    <i class="fa fa-file-excel-o"></i> Download Excel (Selected)
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
                        <label class="form-label fw-bold">Masukkan Nominal Kupon</label>
                        <input type="number" id="nominal" class="form-control form-control-lg" min="1" value="0">

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
                    "type": "post",
                    "data": function (d) {
                        d.is_used = $('#filterStatus').val();   // kirim nilai filter ke controller
                    }
                },
                "columns": [
                    { "data": "checkbox", "orderable": false },
                    { "data": "code_coupon" },
                    {
                        "data": "nominal_vocher"
                    },
                    { "data": "status" },
                    { "data": "created_by" },
                    {
                        "data": "updated_at",
                        "render": function (data, type, row) {
                            if (!data || data === "" || data === "0000-00-00 00:00:00") {
                                return '<span class="text-muted">-</span>';
                            }
                            return data;
                        }
                    },
                    { "data": "updated_by" },
                ],
                "order": [[3, "desc"]],
                "pageLength": 10,
                "deferRender": true,
                "searchDelay": 500
            });


            $('#filterStatus').on('change', function () {
                table.ajax.reload();
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

            $('#btnDownloadExcel').on('click', function () {
                var selected = [];

                // Ambil semua checkbox yang diceklis
                $('#couponTable tbody input[type="checkbox"]:checked').each(function () {
                    selected.push($(this).val());
                });

                if (selected.length === 0) {
                    alert('Pilih minimal 1 coupon!');
                    return;
                }

                // Kirim ke controller (sama seperti PDF kamu)
                var form = $('<form>')
                    .attr({
                        method: 'POST',
                        action: '<?= site_url("coupon/download_excel") ?>'
                    })
                    .appendTo('body');

                $.each(selected, function (i, val) {
                    $('<input>')
                        .attr({
                            type: 'hidden',
                            name: 'selected_coupons[]',
                            value: val
                        })
                        .appendTo(form);
                });

                form.submit();
                form.remove();
            });

            // Generate via Modal
            $('#btnGenerate').on('click', function () {
                var jumlah = $('#jumlah').val();
                if (jumlah < 1 || jumlah > 500) {
                    alert('Jumlah harus antara 1 - 500');
                    return;
                }

                $(this).prop('disabled', true).html('Sedang Generate...');

                $.post('<?= site_url('coupon/generate') ?>', { jumlah: jumlah, nominal: $("#nominal").val() }, function (res) {
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