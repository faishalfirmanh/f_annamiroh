<table width="100%" cellspacing="1" cellpadding="1" class="border_tabel">
  <tr valign="top">
    <td width="25%"><table width="100%" cellpadding="1" cellspacing="1" class="border_tabel">
      <tr>
        <td colspan="4" align="center" valign="middle" class="sub_menus_bg">&nbsp;<?php echo ! empty($nama_menu) ?  $nama_menu : ''; ?></td>
      </tr>
       <tr>
        <td colspan="4" align="left" valign="middle" height="20">
		<?php
		if (!empty($menu_kiri)){ 
			$this->load->view($menu_kiri); 
		}
		?>
		</td>
      </tr>

    </table></td>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="border_tabel" align="center">
     <tr>
        <td colspan="4" align="left" valign="middle" class="sub_menus_bg">&nbsp;<?php echo ! empty($h2_title) ?  $h2_title : ''; ?></td>
      </tr>
    <tr>
	<td>
	<table width="100%" border="0" cellspacing="1" cellpadding="1" class="border_tabel">
	<tr>
	<td>
	<?php 
	if (!empty($data_jamaah)){
		$this->load->view($data_jamaah);
		echo "<hr>";
	}
	
	if ( ! empty($link))
	{
		echo '<p id="bottom_link">';
		foreach($link as $links)
		{
			echo $links . ' ';
		}
		echo '</p>';
	}
	
	$this->load->view($isi); ?>
	</td>
	</tr>
	</table>
</td>
      </tr></table></td></tr></table>
      

    </td>
  </tr>
</table>