<html lang="en">
<head>
    <title><?php if(!empty($tittle)) echo $tittle; else echo "Halaman Pengguna";?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> -->
</head>
<body>
    <?php echo $output; ?>

    <?php if(isset($js_files)): foreach($js_files as $file): ?>
     <script src="<?php echo $file; ?>"></script>
    <?php endforeach; endif; ?>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> -->

    <?php if(isset($inline_js)) echo $inline_js; ?>
</body>
</html>