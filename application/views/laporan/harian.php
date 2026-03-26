<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi Harian</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <style>
        .summary-card { background: #f8f9fa; border-left: 5px solid #0d6efd; }
        .table th { background-color: #0d6efd; color: white; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid py-4">

    <h3 class="mb-4">📊 Data Transaksi Harian</h3>

    <!-- Summary Cards -->
    <div class="row mb-4" id="summaryCards">
        <!-- Akan diisi via JavaScript -->
    </div>

    <!-- DataTable -->
    <div class="card">
        <div class="card-body">
            <table id="tableHarian" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Kuitansi</th>
                        <th>Jamaah / Paket</th>
                        <th>Tanggal</th>
                        <th>Tgl Transfer</th>
                        <th>Debit (IDR)</th>
                        <th>Kredit (IDR)</th>
                        <th>Jenis Transaksi</th>
                        <th>Keterangan</th>
                        <th>Teller</th>
                     
                        <th>Histori</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
// ==================== DATA TABLES SERVER SIDE ====================
$(document).ready(function() {
    var table = $('#tableHarian').DataTable({
        processing: true,
        serverSide: true,
        search:{return : true},
        ajax: {
            url: "<?php echo site_url('AjaxController/harian'); ?>",   // sesuaikan dengan route kamu
            type: "GET",
            data: function(d) {
                // Kirim parameter tambahan ke API
                d.per_page = d.length;
                d.page = (d.start / d.length) + 1;
                d.search = d.search.value;
            }
        },
        columns: [
            { data: null, render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { data: 'nomor_kuitansi' },
            { data: 'jamaah_nik_paket' },
            { data: 'tanggal' },
            { data: 'tanggal_transfer' },
            { 
                data: 'debet',
                render: function(data) {
                    return parseFloat(data).toLocaleString('id-ID');
                }
            },
            { 
                data: 'kredit',
                render: function(data) {
                    return parseFloat(data).toLocaleString('id-ID');
                }
            },
            { data: 'jenis_transaksi' },
            { data: 'keterangan' },
            { data: 'teller' },
           
            { data: 'histori' }
        ],
        // order: [[1, 'desc']], 
        language: {
            processing: "Sedang memproses...",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // ==================== LOAD SUMMARY ====================
    loadSummary();

    // Refresh summary setiap kali tabel di-draw ulang
    table.on('draw', function() {
        loadSummary();
    });
});

// Fungsi untuk menampilkan Summary Cards
function loadSummary() {
    $.get("<?php echo site_url('AjaxController/harian'); ?>", function(response) {
        if (response.status === 'success' && response.summary) {
            var s = response.summary;
            var html = `
                <div class="col-md-3">
                    <div class="card summary-card">
                        <div class="card-body">
                            <h6 class="text-primary">Total Jamaah</h6>
                            <h3 class="mb-0">${s.jamaah_count}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card">
                        <div class="card-body">
                            <h6 class="text-success">Total Debit</h6>
                            <h3 class="mb-0">Rp ${s.debit_sum}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card">
                        <div class="card-body">
                            <h6 class="text-danger">Total Kredit</h6>
                            <h3 class="mb-0">Rp ${s.kredit_sum}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card">
                        <div class="card-body text-end">
                            <small class="text-muted">Last Updated</small><br>
                            <small>${new Date().toLocaleTimeString('id-ID')}</small>
                        </div>
                    </div>
                </div>`;
            $('#summaryCards').html(html);
        }
    });
}
</script>

</body>
</html>