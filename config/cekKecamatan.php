<?php
include "koneksi.php";

if ($_GET['act']!='u'){
	
	$kode = trim($_GET['kode']);
}
else{
	$kode = $ru['id_kecamatan'];
}
//echo $kode;
?>
<select name="id_kecamatan" <?=$disabled?>>
<option value="">-Semua-</option>
<?php
$sqlKel = mysql_query("SELECT * FROM kecamatan WHERE id_kabupaten='".$kode."' ORDER BY kecamatan ASC");
while ($rKel = mysql_fetch_array($sqlKel)){
	if ($rKel['id_kecamatan']==$ru['id_kecamatan']){
		echo "<option value=\"$rKel[id_kecamatan]\" selected>$rKel[kecamatan]</option>";
	}
	else{
		echo "<option value=\"$rKel[id_kecamatan]\">$rKel[kecamatan]</option>";
	}
}
?>
</select>