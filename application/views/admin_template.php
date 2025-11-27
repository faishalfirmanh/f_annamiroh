<?php 
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>

<table width="100%" cellspacing="1" cellpadding="1" class="border_tabel">
  <tr>
    <td width="60%"><table width="100%" cellpadding="1" cellspacing="1" class="border_tabel">
      <tr>
        <td colspan="4" align="left" valign="middle" class="sub_menus_bg">&nbsp;Configuration Menu</td>
      </tr>
       <tr>
        <td colspan="4" align="left" valign="middle" height="20"><?php $this->load->view($isi); ?></td>
      </tr>
     
      
    </table></td>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="border_tabel" align="center">
     <tr>
        <td colspan="4" align="left" valign="middle" class="sub_menus_bg">&nbsp;</td>
      </tr>
    <tr>
	<td>
	<?php
	if (!empty($kanan_view)){ 
		$this->load->view($kanan_view); 
	}
	?></td>
      </tr></table></td></tr></table>
      

    </td>
  </tr>
</table>
