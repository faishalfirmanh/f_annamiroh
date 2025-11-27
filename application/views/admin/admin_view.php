<?php 
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>

<table width="100%" cellpadding="1" cellspacing="1" class="border_tabel">

       <tr>
        <td colspan="4" align="left" valign="middle" height="20">&nbsp;</td>
      </tr>
      <tr>
        <td width="25%" align="center" valign="middle">
		<?php 
	echo anchor('jamaah', '<img src='.base_url() .'images/48px-Crystal_Clear_app_kdmconfig.png><br>Jamaah Haji');
?></td> <td width="25%" align="center" valign="middle">
		<?php 
	echo anchor('jamaah/umroh', '<img src='.base_url() .'images/48px-Crystal_Clear_app_kdmconfig.png><br>Jamaah Umroh');
?></td>

</td> <td width="25%" align="center" valign="middle">
		<?php 
	echo anchor('agen/paket', '<img src='.base_url() .'images/48px-Crystal_Clear_app_kdmconfig.png><br>Paket Umroh');
?></td>
        <td width="26%" align="center" valign="middle"><?php 
	echo anchor('ubah-password', '<img src='.base_url() .'images/48px-Crystal_Clear_app_kservices.png><br>Ubah Password');
?></td>
        <td width="26%" align="center" valign="middle"><?php 
	echo anchor('master', '<img src='.base_url() .'images/48px-Crystal_Clear_action_configure.png><br>Data Master');
?></td>
 <!--<td width="26%" align="center" valign="middle"><?php 
	echo anchor('jamaah/kontak', '<img src='.base_url() .'images/48px-Crystal_Clear_action_configure.png><br>Kontak');
?></td>-->
      </tr>
       <tr>
        <td colspan="3" align="left" valign="middle" height="20">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" valign="middle"><?php 
	echo anchor('allt', '<img src='.base_url() .'images/postage_stamp_48.png><br>All Pembayaran');
?></td>
        <td align="center" valign="middle"><?php 
	echo anchor('laporan', '<img src='.base_url() .'images/48px-Crystal_Clear_mimetype_postscript.png><br>Laporan');
?></td>
<td align="center" valign="middle"><?php 
	echo anchor('welcome', '<img src='.base_url() .'images/file-sharing.png><br>File Sharing');?>
	</td><td align="center" valign="middle">
	    <?php 
	echo anchor('agen', '<img src='.base_url() .'images/48px-Crystal_Clear_app_kuser.png><br>Agen');

?></td>


        <td align="center" valign="middle"><?php 
	if ($this->session->userdata('level') == '1'){
		echo anchor('user', '<img src='.base_url() .'images/48px-Crystal_Clear_app_kuser.png><br>User').'</td></tr></tr><td align="center" valign="middle"><br>';
		
		echo anchor('log', '<img src='.base_url() .'images/log.png><br>Log');
	}
?></td>

	<td align="center" valign="middle"><?php 
		echo anchor('', '<img src='.base_url() .'images/48px-Crystal_Clear_app_kuser.png><br>Data Master Perlengkapan Jamaah');
		
?>	</td>
	
      </tr>
       <tr>
        <td colspan="2" align="left" valign="middle" height="20"><?php 
	//echo anchor('singkron', '<img src='.base_url() .'images/postage_stamp_48.png><br>Singkronisasi');
?></td>
      </tr>
      <tr>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle" class="style_orange_kecil">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
