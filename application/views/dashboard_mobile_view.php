<div class="main-content">
    <div class="container-fluid">
        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Halo, <?= explode(' ', $user)[0] ?>!</h5>
            <a href="<?= site_url('JamaahLinkShare/logout_api') ?>" class="btn btn-outline-danger btn-sm rounded-circle"><i class="bi bi-power"></i></a>
        </div>

        <div class="welcome-banner p-4 bg-primary text-white rounded-4 mb-4 shadow" style="background: linear-gradient(135deg, #0061f2, #6900f2);">
            <h2 class="fw-bold">Selamat Datang  <?=  $user ?></h2>
            <p class="mb-0">Portal informasi keberangkatan dan keuangan Anda.</p>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="<?= site_url('JamaahLinkShare/dashboard') ?>" style="text-decoration:none;">
                 <div class="card p-3 text-center border-0 shadow-sm rounded-3 bg-light border-primary">
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

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Riwayat Pembayaran</h5>
                <button class="btn btn-sm btn-light" onclick="loadPembayaran()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
            </div>
<div class="table-responsive p-4"> <table id="tabelPembayaran" class="table table-hover align-middle w-100">
        <thead class="table-light">
            <tr>
                <th class="ps-4">Tanggal</th>
                <th>Paket</th>
                <th>Nominal</th>
                <th class="pe-4 text-center">Status</th>
            </tr>
        </thead>
        <tbody id="listPembayaran">
            </tbody>
    </table>
</div>

            </div>
        </div>
    </div>
</div>

<script>
   
$(document).ready(function() {
    // 1. Inisialisasi variabel global di dalam ready
    let limit = 10;
    let currentPage = 1;
    let offset = 0;
   let table = null; 

    loadPembayaran();

    function loadPembayaran() {
        const idJamaah = "<?= trim($user_id) ?>";
        // Kalkulasi offset berdasarkan halaman aktif
        offset = (currentPage - 1) * limit;

        const apiUrl = "<?= site_url('JamaahLinkShare/pembayaran/') ?>" 
                        + idJamaah + "?limit=" + limit + "&offset=" + offset;

$('#listPembayaran').html('<tr><td colspan="4" class="text-center py-5"><div class="spinner-border text-primary"></div> Memuat...</td></tr>');
        
$.ajax({
            url: apiUrl,
            type: "GET",
            dataType: "json",
            success: function(response) {
                // 1. Jika DataTable sudah ada, hancurkan dulu agar bisa diisi data baru
                if ($.fn.DataTable.isDataTable('#tabelPembayaran')) {
                    $('#tabelPembayaran').DataTable().destroy();
                }

                let html = '';
                if (response.status === 'success' && response.data.length > 0) {
                    $.each(response.data, function(i, item) {
                        // Format Nominal
                        let nominalRaw = item.kredit > 0 ? item.kredit : item.debet;
                        let nominalFormatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                        }).format(nominalRaw);

                        let tipe_trans = item.debet > 0 ? '<small class="d-block text-muted">Debit</small>' : '';
                        let cek_class_nya = item.debet > 0 ? 'text-danger' : 'text-success';

                        // Format Status Badge
                        let badgeClass = (item.status_pembayaran == '1') ? 'bg-success' : 'bg-danger';
                        let name_status = (item.status_pembayaran == '1') ? 'Konfirmasi' : 'Belum di konfirmasi';

                        html += `<tr>
                            <td class="ps-4">
                                <div class="fw-bold">${item.tanggal_transfer}</div>
                                <small class="text-muted">-</small>
                            </td>
                            <td><small>${item.nama_paket_transaksi || '-'}</small></td>
                            <td class="fw-bold ${cek_class_nya}">
                                ${tipe_trans} ${nominalFormatted}
                            </td>
                            <td class="pe-4 text-center">
                                <span class="badge ${badgeClass}">${name_status}</span>
                            </td>
                        </tr>`;
                    });
                }

                // 2. Masukkan HTML ke dalam Tbody
                $('#listPembayaran').html(html);

                // 3. Inisialisasi DataTables
                $('#tabelPembayaran').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" // Terjemahan Indonesia
                    },
                    "order": [[0, "desc"]], // Urutkan berdasarkan tanggal (kolom pertama) secara default
                    "pageLength": 10,
                    "responsive": true,
                    "dom": '<"d-flex justify-content-between align-items-center mb-3"fl>rt<"d-flex justify-content-between align-items-center mt-3"ip>' 
                    // Layout Custom agar rapi dengan Bootstrap
                });
            },
            error: function() {
                $('#listPembayaran').html('<tr><td colspan="4" class="text-center py-4 text-danger">Gagal memuat data API.</td></tr>');
            }
        });

    }

    
window.loadPembayaran = loadPembayaran;


   });
</script>
