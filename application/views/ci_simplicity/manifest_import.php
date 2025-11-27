<html lang="en">
    <head>
        <title><?php if(!empty($tittle)) echo $tittle; else echo "Halaman Pengguna";?></title>
        <meta name="resource-type" content="document" />
        <meta name="robots" content="all, index, follow"/>
        <meta name="googlebot" content="all, index, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
/** -- Copy from here -- */
if(!empty($meta))
foreach($meta as $name=>$content){
echo "\n\t\t";
        ?><meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" /><?php
}
echo "\n";
 
if(!empty($canonical))
{
echo "\n\t\t";
        ?><link rel="canonical" href="<?php echo $canonical?>" /><?php
 
}
echo "\n\t";
 ?>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>/assets/grocery_crud/themes/twitter-bootstrap/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>/assets/grocery_crud/themes/twitter-bootstrap/css/bootstrap-responsive.min.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>/assets/grocery_crud/themes/twitter-bootstrap/css/style.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>/assets/grocery_crud/themes/twitter-bootstrap/css/jquery-ui/flick/jquery-ui-1.9.2.custom.css" />
    <script src="<?php echo base_url(); ?>/assets/grocery_crud/js/jquery-1.11.1.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/grocery_crud/themes/twitter-bootstrap/js/jquery-ui/jquery-ui-1.9.2.custom.js"></script>
    <script src="<?php echo base_url(); ?>/assets/grocery_crud/themes/twitter-bootstrap/js/libs/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/grocery_crud/themes/twitter-bootstrap/js/app/twitter-bootstrap.js"></script>
    <style>
        @media (min-width: 1200px){
            [class*="span"] {
                float: left;
                min-height: 1px;
                margin-left: 46px;
            }
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="span12">
                <h4>UNGGAH DOKUMEN MANIFEST</h4>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                    <div class="control-group">
                        <label class="control-label" for="fileInput">Unggah file excel sesuai format. 
                            <a href="<?php echo base_url() . "/assets/files/template_import_data.xlsx" ?>"><i class="icon-download"></i> Download template</a>
                        </label>
                        <div class="controls">
                            <form enctype="multipart/form-data" method="POST" action="<?php echo site_url() . '/transaksi_op/manifest_import_do' ?>">
                                <div>
                                    <input class="input-file" name="fileExcel" type="file">
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success">Unggah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php 
                    if(isset($data)) {
                    if(!$success && count($data) > 0) { 
                    ?>
                    <div>
                        <div class="alert alert-error">
                            <p>
                                <strong>Data gagal diimport karena duplikasi</strong>
                            </p>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No KTP</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data as $v){ ?>
                                <tr>
                                    <td><?php echo $v->no_ktp ?></td>
                                    <td><?php echo $v->nama_jamaah ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    } elseif(!$success && count($data) < 1) { ?>
                        <div class="alert alert-error">
                            <p>
                                <strong>File excel tidak ada</strong>
                            </p>
                        </div>
                   <?php } else {?>
                        <div class="alert alert-success">
                            <p>
                                <strong>Data sukses diimport sebanyak <?php echo count($data) ?></strong>
                            </p>
                        </div>  
                    <?php } 
                    }?>
                    
            </div>
            <div class="span5">
                <p>
                    <strong>1. Unduh template</strong>
                </p>
                <p>
                    Kami memiliki ketentuan format berkas. Silahkan unduh terlebih dahulu format berkas yang sudah kami sediakan.
                    <a href="<?php echo base_url() . "/assets/files/template_import_data.xlsx" ?>" class="btn btn-success btn-mini" type="submit">
                        Unduh Template
                    </a>
                </p>
                <p>
                    <strong>2. Salin Data Anda</strong>
                </p>
                <p>
                    Gunakan microsoft excel untuk menyalin data. Pastikan format kolomnya sesuai.
                </p>
                <p>
                    <strong>3. Unggah Berkas</strong>
                </p>
                <p>
                    Setelah selesai simpan berkas anda sesuai extensi .xlsx. Setelah itu unggah form berkas tersebut.
                </p>
            </div>
        </div>
        
    </div>
</body>
</html>