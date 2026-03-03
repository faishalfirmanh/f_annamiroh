<html lang="en">
<head>
    <title><?php if(!empty($tittle)) echo $tittle; else echo "Halaman Pengguna";?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->

    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> -->
</head>
<body>

    <style>
        
        .modal-approve{
            text-align :center;
            display: block;margin-left:auto;margin-right:auto;
        }
        
   </style>

   

    <?php echo $output; ?>

    <?php if(isset($js_files)): foreach($js_files as $file): ?>
     <script src="<?php echo $file; ?>"></script>
    <?php endforeach; endif; ?>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> -->

    <?php if(isset($inline_js)) echo $inline_js; ?>


   <div id="modalApprove" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h3>Approve Transaksi</h3>
        </div>
        <form id="formApprove" action="<?= site_url('GlobalController/approve_transaksi') ?>" method="POST">

            <div class="modal-body">
                <input type="hidden" name="id_pembayaran" id="app_id_pembayaran">
                <input type="hidden" name="id_paket" id="app_id_paket">

                <div class="control-group">
                    <label class="control-label">Keterangan Tambahan (Wajib diisi):</label>
                    <div class="controls">
                        <textarea name="keterangan" class="span12" rows="4" required placeholder="Masukkan alasan atau catatan approve..."></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan & Approve</button>
            </div>

        </form>
    </div>




<script>


window.addEventListener('load', function() {
    if (window.jQuery) {
        $(document).ready(function() {
            // Gunakan Event Delegation karena baris tabel di-load via AJAX
            $('body').on('click', '.btn-approve', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var paket = $(this).data('paket');
                
                $('#app_id_pembayaran').val(id);
                $('#app_id_paket').val(paket);
                $('#modalApprove').modal('show');
            });
        });
    } else {
        console.error("jQuery tetap tidak terdeteksi. Pastikan grocery_crud me-load file JS-nya.");
    }
});


</script>



</body>
</html>
