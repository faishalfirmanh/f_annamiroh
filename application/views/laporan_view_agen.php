<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php if(!empty($title)) echo $title; else echo "Halaman Pengguna";?></title>
    <meta name="resource-type" content="document" />
    <meta name="robots" content="all, index, follow"/>
    <meta name="googlebot" content="all, index, follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
</head>
<body>
    <div style="padding: 20px;">
        <h2>Laporan Penjualan Agen</h2>
        
        <form method="get" action="<?php echo current_url(); ?>" style="background: #f4f4f4; padding: 15px; border-radius: 5px;">
           
        <div class="filter-group">
            <label>Cari Nama Agen:</label>
            <input type="text" name="search_nama" class="form-control" value="<?php echo isset($search_nama) ? $search_nama : ''; ?>" placeholder="Nama agen...">
        </div>

        <div style="display: inline-block; margin-right: 10px;">
                <label>Periode Keberangkatan:</label><br>
                <input type="date" name="date_start" value="<?php echo $date_start; ?>">
                <input type="date" name="date_end" value="<?php echo $date_end; ?>">
            </div>

            <div style="display: inline-block; vertical-align: bottom;">
                <button type="submit" class="btn btn-primary">Filter Laporan</button>
            </div>
        </form>
        <hr>

        <?php echo $output; ?>
    </div>

    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

    <script type="text/javascript">
       jQuery(document).ready(function($) {
            console.log("JQuery Ready");

            // Gunakan 'body' on click agar tetap jalan setelah AJAX refresh tabel
            $('body').on('click', '.my-modal', function(e) {
                e.preventDefault();
                var btn = $(this);
                setTimeout(() => {
                    var myModal = $('#my-exact-modal');
                    var myId = btn.data('my-id'); 
                    myModal.data('selected-id', myId).modal('show');
                }, 500);
            });
        });
    </script>
</body>
</html>