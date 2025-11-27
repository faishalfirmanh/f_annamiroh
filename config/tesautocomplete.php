<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>

<script type="text/javascript" src="jquery-1.4.js"></script>
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css"/>
<script type="text/javascript" src="jquery.autocomplete.js"></script>

<script type="text/javascript">
$().ready(function() {	
	$("#tahun").autocomplete("ambil_tahun.php", {
		width: 150
  	});

	$("#tahun").result(function(event, data, formatted) {

	});
	
	$("#karcis").autocomplete("modul/mod_laku_karcis/ambil_karcis.php", {
		width: 150
  	});

	$("#karcis").result(function(event, data, formatted) {

	});
	
});
</script>

</head>

<body>
<input type="text" id="tahun" name="tahun" value="<?=$rPeg['tahun']?>">
</body>
</html>
