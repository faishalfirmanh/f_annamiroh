<?php 
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>


<hr />
<div class="arrowlistmenu">
<h3 class="menuheader expandable">Jenis Laporan</h3>
<ul class="categoryitems">
<li><?php echo anchor('laporan/lap_harian_onh', 'Laporan Harian ONH');?></li>
<li><?php echo anchor('laporan/lap_th_onh', 'Laporan Tahunan ONH');?></li>
<li><?php echo anchor('laporan/lap_harian_kbih','Laporan Harian KBIH');?></li>
<li><?php echo anchor('laporan/lap_harian_adm','Laporan Harian Adm.');?></li>
<li><?php echo anchor('laporan/lap_harian_all','Laporan Harian All Pembayaran');?></li>
<li><?php echo anchor('laporan/lap_saldo_jamaah_onh_th','Laporan Saldo Jamaah ONH');?></li>
<li><?php echo anchor('laporan/lap_data_jamaah','Laporan Data Jamaah');?></li>
</ul>
</div>