<?php
include "koneksi.php";
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = mysql_query("SELECT DISTINCT(bank) AS bank from data_jamaah where bank LIKE '%$q%' ORDER BY bank ASC");
while($r = mysql_fetch_array($sql)) {
	$bank = $r['bank'];
	echo "$bank  \n";
}
?>
