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
                <div class="card p-3 text-center border-0 shadow-sm rounded-3">
                    <i class="bi bi-airplane-engines fs-1 text-warning"></i>
                    <span class="small fw-bold mt-2 d-block text-dark">Jadwal</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card p-3 text-center border-0 shadow-sm rounded-3 bg-light border-primary">
                    <i class="bi bi-credit-card-2-back fs-1 text-success"></i>
                    <span class="small fw-bold mt-2 d-block text-dark">Pembayaran</span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Riwayat Pembayaran</h5>
                <button class="btn btn-sm btn-light" onclick="loadPembayaran()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
            </div>
            <div class="card-body px-0 px-md-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Paket</th>
                                <th>Nominal</th>
                                <th class="pe-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="listPembayaran">
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="spinner-border text-primary spinner-border-sm"></div> Memuat data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-sm btn-primary me-2" id="prevPage">Prev</button>
                        <span class="align-self-center" id="pageInfo"></span>
                        <button class="btn btn-sm btn-primary ms-2" id="nextPage">Next</button>
                    </div>
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

    loadPembayaran();

    function loadPembayaran() {
        const idJamaah = "<?= trim($user_id) ?>";
        // Kalkulasi offset berdasarkan halaman aktif
        offset = (currentPage - 1) * limit;

        const apiUrl = "<?= site_url('JamaahLinkShare/pembayaran/') ?>" 
                        + idJamaah + "?limit=" + limit + "&offset=" + offset;

        $.ajax({
            url: apiUrl,
            type: "GET",
            dataType: "json",
            success: function(response) {
                let html = '';
                if (response.status === 'success' && response.data.length > 0) {
                    let no = 1;
                    $.each(response.data, function(i, item) {
                        i++
                        let nominal = new Intl.NumberFormat('id-ID', {
                            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                        }).format(item.kredit || 0);

                        // Sesuaikan dengan data database (biasanya '1' atau 'Lunas')
                        let badgeClass = (item.status_pembayaran == '1') ? 'bg-success' : 'bg-danger';
                        let name_status = (item.status_pembayaran == '1') ? 'Konfirmasi' : 'Gagal';
                        html += '<tr>';
                        html += `<tr>${i}</td>`;
                        html += '<td class="ps-4"><div class="fw-bold">' + item.tanggal_transfer + '</div><small class="text-muted">No: ' + i + '</small></td>';
                        html += '<td><small>' + (item.nama_paket_transaksi || '-') + '</small></td>';
                        html += '<td class="fw-bold text-success">' + nominal + '</td>';
                        html += '<td class="pe-4 text-center"><span class="badge ' + badgeClass + '">' + name_status + '</span></td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data pembayaran.</td></tr>';
                }

                $('#listPembayaran').html(html);
                renderPagination(response.pagination);
            },
            error: function() {
                $('#listPembayaran').html('<tr><td colspan="4" class="text-center py-4 text-danger">Gagal memuat data API.</td></tr>');
            }
        });
    }

    function renderPagination(pagination) {
        let totalPages = pagination.pages || 1;
        $('#pageInfo').text("Halaman " + currentPage + " dari " + totalPages);

        // Atur tombol aktif/nonaktif
        $('#prevPage').prop('disabled', currentPage <= 1);
        $('#nextPage').prop('disabled', currentPage >= totalPages);
    }

    $('#nextPage').click(function() {
        currentPage++;
        loadPembayaran();
    });

    $('#prevPage').click(function() {
        if (currentPage > 1) {
            currentPage--;
            loadPembayaran();
        }
    });
});
</script>