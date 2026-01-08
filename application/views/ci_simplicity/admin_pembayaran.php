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
 

foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach;
 
/** -- to here -- */
?>
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
<body>
    <div style="padding: 10px">
        <?php echo $output; ?>
    </div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
        console.log("=== SCRIPT V2 DIMULAI ===");
        var path = window.location.pathname;
        var result = path.split('/');
        console.log("arr0",result)
        // 1. Ambil Link
        var linkUrl = "<?php echo isset($link_tujuan_custom) ? $link_tujuan_custom : site_url('JamaahLinkShare/formInputLinkShare'); ?>";
        console.log("<?php echo $link_tujuan_custom ?>")
        // 2. Buat HTML Tombol
        // PERUBAHAN: Saya tambah style 'display:inline-block', 'z-index', dan 'position'
        // Warna saya ganti ORANGE (btn-warning) agar mencolok mata dulu
        var btnHtml = '<a href="' + linkUrl + '" class="btn btn-danger" style="margin-left: 5px; margin-right: 5px; display: inline-block; vertical-align: top; position: relative; z-index: 9999;"> <i class="fa fa-plus"></i> Tambah Link </a>';
        // 3. LOGIKA PENEMPATAN
        var targetInput = $('input[name="search_text"]');
        var targetDiv2  = $('#options-content'); // Toolbar container

        if (targetInput.length > 0) {
            console.log("Input Search Ketemu.",result[4]);
            const lastIndex = result.length - 1;
            const itemTerakhir = result[lastIndex];        // "255"
            const itemMin1     = result[lastIndex - 1];    // "pembayaran"
            const itemMin2     = result[lastIndex - 2];
            if (!isNaN(itemTerakhir) && itemTerakhir !== "" && 
                itemMin1 === "pembayaran" && 
                itemMin2 === "transaksi_op") {
                
                // console.log("tambah", result[1])
                targetDiv2.append(btnHtml);
            }
           
         
        } 
    });
</script>
</html>