<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>File Sharing An Namiroh</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 0px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 0 0 0;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/jquery-ui/css/base/jquery-ui.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/elfinder/css/theme.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/elfinder/css/elfinder.min.css'); ?>" />
	<script type="text/javascript" src="<?php echo base_url('assets/jquery-1.7.2.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/jquery-ui/js/jquery-ui.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/elfinder/js/elfinder.min.js'); ?>"></script>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#elfinder-tag').elfinder({
				url: '<?php echo site_url('welcome/elfinder'); ?>',
			}).elfinder('instance');
		});
	</script>

<style type="text/css">@import url("<?php echo base_url() . 'css/style1.css'; ?>");</style>
	
</head>
<body>
<!-- ddddddddddddddddddddddddddddddd -->

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
<!-- ddddddddddddddddddddddddddddddd -->
<div id="container">
	<div id="body">
		<div id="elfinder-tag"></div>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>
</td>
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