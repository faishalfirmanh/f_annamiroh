<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script type="text/javascript" src="<?php echo base_url() . 'js/jquery-1.4.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'js/ddaccordion.js'; ?>"></script>

<script type="text/javascript" src="<?php echo base_url() . 'js/jquery-1.4.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'js/ui.core.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'js/ui.datepicker.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'js/ui.datepicker-id.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'js/jquery.formatCurrency-1.4.0.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'js/jquery.ketchup.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'js/jquery.ketchup.messages.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'js/jquery.ketchup.validations.basic.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'js/jquery.autocomplete.js'; ?>"></script>
<script language="javascript">
<!--
function MM_openBrWindow(theURL,winName,features){
 window.open(theURL,winName,features);
}
</script>
<script type="text/javascript">
ddaccordion.init({
	headerclass: "expandable", //Shared CSS class name of headers group that are expandable
	contentclass: "categoryitems", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc]. [] denotes no content
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", "openheader"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["prefix", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})


</script>
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>

<title><?php echo isset($title) ? $title : ''; ?></title>
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border_tabel" bgcolor="#FFFFFF" onContextMenu="return rightClicOFF();">
  <tr>
    <td class="mainHeader">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
        <tr>
          <td width="9%" class="logos">&nbsp;</td>
          <td width="68%" class="app_Image">&nbsp;</td>
          <td width="23%" align="right" class="app_adm_image">&nbsp;</td>
        </tr>
      </table>
	  </td>
  </tr>
  <tr>
    <td class="bg_menus" valign="middle" align="center">User Loged in <b><?=$this->session->userdata('nama_admin');?></b> <a href="login/process_logout" class="style_merah">Logout</a></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#EAEAEA">
    <tr>
    	<td class="style_biru_badag_miring" align="center">
		<form name="jamaah_form" method="post" action="<?php echo site_url('jamaah/cari_proses'); ?>">
<!--<input type="text" class="form_field" name="no_porsi" value="<?php //echo set_value('no_porsi', isset($default['no_porsi']) ? $default['no_porsi'] : ''); ?>" />
<input type="submit" name="submit" id="submit" value=" Cari " />
<br />-->
		<?php echo form_error('no_porsi', '<font color="#FF0000">', '</font>');?>
		</form>		<br />
<?php echo ! empty($info) ? '<p class="info">' . $info . '</p>': ''; ?>
</td>
    	<td align="center">
		<?php 
	echo anchor('admin', '<img src='.base_url() .'images/48px-Crystal_Clear_app_kfm_home.png><br>Home');
?>		</td><td align="center">
		<?php 
	echo anchor('login/process_logout', '<img src='.base_url() .'images/48px-Crystal_Clear_action_lock.png><br>Logout', array('onclick' => "return confirm('Anda yakin akan logout?')"));
?>
		</td>
    </tr>
        <tr>
          <td class="style_isi_hitam_10" colspan="3">	
		  <table width="100%" cellspacing="1" cellpadding="1" class="border_tabel">
  <tr valign="top">
    <td width="100%"><table width="100%" cellpadding="1" cellspacing="1" class="border_tabel">
     
       <tr>
        <td colspan="4" align="left" valign="middle" height="20">
		<?php echo anchor('log/get_last_ten_log/tb_pembayaran_log', 'Log Pembayaran');?> |
<!--	<li><?php //echo anchor('log/get_last_ten_log/kecamatan_log', 'Log Kecamatan');?></li>
	<li><?php //echo anchor('log/get_last_ten_log/kabupaten_log', 'Log Kabupaten');?></li>		  
	<li><?php //echo anchor('log/get_last_ten_log/data_log_log', 'Log log');?></li>	-->	
	<?php echo anchor('log/get_last_ten_log/kbih_log', 'Log KBIH');?> |
	<?php echo anchor('log/get_last_ten_log/data_jamaah_log', 'Log Jamaah');?>	                      |
	<?php echo anchor('log/get_last_ten_log/semua_data1_log', 'Log Semua data');?> |
	<?php echo anchor('log/get_last_ten_log/adm_log', 'Log Administrasi');?>

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
	
	//$this->load->view('jamaah/jamaah_view'); ?>
	</td>
	</tr>
	</table>
</td>
      </tr></table></td></tr></table>
      

    </td>
  </tr>
</table>
          <?php echo $output;?>		  </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="39" align="center" valign="middle" background="images/bg_banner.gif" bgcolor="#CCCCCC" class="style_biru_kecil">Hak Cipta &copy; 2011<br>
      </td>
  </tr>
</table>
</body>
</html>