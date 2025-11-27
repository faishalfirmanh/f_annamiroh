<form name="laporan_form" id="laporan_form" method="post" action="<?=$form_action?>">
<table align="center" cellpadding="1" cellspacing="1">
<tr valign="top">

<td>

<?php
$th = $this->Laporan_model->get_tahun();
$i = 0;
foreach ($th->result() as $row){
	// echo anchor($row->tahun;
	anchor('laporan/lap_harian_onh', 'Laporan Harian ONH');
	echo "<a href='".base_url()."pdf/lap_tahunan_all.php?th=$row->tahun' target='_blank'>$row->tahun</a>&nbsp; ". anchor("laporan/lap_harial_all_year/$row->tahun", "Detil...");
	if($i++%8==7)
		echo "<br>";
	else
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
}
?>

<font color="#ff0000"><?php echo form_error('id_th', '', '');?></font>
<br />
</td>
</tr>
</table>
</form>