<?php 
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>


<hr />
<div class="arrowlistmenu">
<h3 class="menuheader expandable">Jenis log</h3>
<ul class="categoryitems">
	<li><?php echo anchor('log/get_last_ten_log/tb_pembayaran_log', 'Log Pembayaran');?></li>
<!--	<li><?php //echo anchor('log/get_last_ten_log/kecamatan_log', 'Log Kecamatan');?></li>
	<li><?php //echo anchor('log/get_last_ten_log/kabupaten_log', 'Log Kabupaten');?></li>		  
	<li><?php //echo anchor('log/get_last_ten_log/data_log_log', 'Log log');?></li>	-->	
	<li><?php echo anchor('log/get_last_ten_log/kbih_log', 'Log KBIH');?></li>
	<li><?php echo anchor('log/get_last_ten_log/data_jamaah_log', 'Log Jamaah');?></li>		                     
	<li><?php echo anchor('log/get_last_ten_log/semua_data1_log', 'Log Semua data');?></li>
	<li><?php echo anchor('log/get_last_ten_log/adm_log', 'Log Administrasi');?></li>
</ul>
</div>