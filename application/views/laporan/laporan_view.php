<?php
echo ! empty($petunjuk) ? '<p class="petunjuk">' . $petunjuk . '</p>': '';
echo "<div align=center>";
if (!empty($lap_pdf)){
	echo "<a href=$current_link onclick=MM_openBrWindow('".base_url()."pdf/".$lap_pdf."','','scrollbars=yes,width=900,height=600');>$nama_laporan</a>";
}
echo "</div>";
?>
