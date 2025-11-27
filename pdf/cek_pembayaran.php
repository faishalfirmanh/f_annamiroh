<?php
include "../config/koneksi.php";

$sql = "SELECT DISTINCT(id_jamaah) FROM tb_pembayaran";
$qr = mysql_query($sql);
while($r = mysql_fetch_array($qr)){
	$sql2 = mysql_query("SELECT * FROM data_jamaah WHERE id_jamaah='$r[id_jamaah]'");
	if (mysql_num_rows($sql2)<0){
		echo "miss";
	}
	else{
		echo "$r[id_jamaah] <br />";
	}
}
?>