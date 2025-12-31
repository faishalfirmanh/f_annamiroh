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
</html>