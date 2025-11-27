<?php
include "koneksi.php";
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = mysql_query("SELECT DISTINCT(tahun) AS tahun from data_jamaah where tahun LIKE '%$q%' ORDER BY tahun ASC");
while($r = mysql_fetch_array($sql)) {
	$tahun = $r['tahun'];
	echo "$tahun  \n";
}
?>
