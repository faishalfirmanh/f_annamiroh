<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/search-lib/jquery-1.2.1.pack.js"></script>	
<!-- Autosuggest module -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/search-lib/jquery.watermarkinput.js"></script>	
<script type="text/javascript" src="<?php echo base_url(); ?>assets/search-lib/autosuggest/bsn.AutoSuggest_2.1.3.js"></script>	
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/search-lib/autosuggest/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8">	

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

 <style type='text/css'>		
	.search_example {
		margin:0px 20px 0px 10px;
	}
	.search_bar {
		position:relative;	
		color:#000000;
		font-weight:bold;
		margin:8px 0px;
		padding:0px 5px;
		height:20px;
	}
	.search_bar form {
		display:inline;
	}	
	.search_bar input {
		font-family:Arial,Helvetica,sans-serif;
		font-size:12px;
	}	
	.search_bar ul {
		line-height:19px;
		list-style-image:none;
		list-style-position:outside;
		list-style-type:none;
		margin:3px 0pt 0pt;
		padding:0pt;
		z-index:10000000;
	}	
	.search_bar li {
		color:#333333;
		float:left;
		font-family:Arial,Helvetica,sans-serif;
		font-size:12px;
		font-weight:bold;
		margin-left:5px;
		margin-right:0px;
		width:auto;
	}	
	.search_bar  input.search_txt {
		background:white url(img/searchglass.png) no-repeat scroll 3px 4px;
		border:1px solid #95A5C6;
		color:#000000;
		font-weight:normal;
		padding:2px 0px 2px 17px;
	}	
	.search_bar input.searchBtnOK {
		background:white none repeat scroll 0%;
		border:1px solid #95A5C6;
		color:#000000;
		font-weight:bold;
		padding:1px;
	}	
	
	.search_response {
		position:relative;
		border:2px solid #f8e89d;
		padding:10px;
		padding-left:50px;
		margin:0px;
		background:#ffffff url(img/kghostview.png) no-repeat 0px 10px;
	}
	
	/* 2.2.5 =Comments
	---------------------------------------------------------------------- */
	#comment_list {
		padding-bottom: 20px;
		margin: 0px 10px 0px 10px;
	}
	
	#comment_list h2 { margin: 50px 0 0; }
	#comment_list form input { margin-bottom: 4px; }
	#comment_list form textarea { width: 80%; padding: 7px 5px; margin-top:6px; }
	#comment_list form a {
		color: #555;
		text-decoration: none;
		border-bottom: 1px dotted #fff;
	}
	#comment_list form a:hover { color: #fff; }
	
	#comment_list ul {
		padding: 0;
		margin: 0;
	}
	#comment_list li {
		position: relative;
		display: block;
		padding: 10px 3px;
		margin: 10px 2px;
		background: #fefefe;
		font-family: Verdana;
		font-size: 13px;
		border: 1px solid #ccc;
		-webkit-box-shadow: 0px 0px 5px #000;
		-moz-box-shadow: 0px 0px 5px #000;
		box-shadow: 0px 0px 5px #000;
	}
	
	#comment_list li img.avatar {
		float: left;
		padding: 2px;
		background: #ccc;
		-webkit-box-shadow: 0 0 5px #000;
		-moz-box-shadow: 0 0 5px #000;
		box-shadow: 0 0 5px #000;
		margin: 3px 15px 3px 10px;
		width: 60px;
		height: 50px;
	}
	
	#comment_list li cite,
	#comment_list li cite a {
		font-weight: bold;
		color: #555;
		text-decoration: none;
		font-size: 14px;
	}
	
	#comment_list li p {
		font-size: 13px;
		line-height: 17px;
		padding: 7px 10px;
	}
	
	#comment_list li p a {
		color: #bf697f;
		text-decoration: none;
		border-bottom: 1px dotted #A839B2;
	}
	
	#comment_list li p a:visited { color: #9e3c80; }
	#comment_list li p a:hover { color: #A839B2; }
	
	#comment_list li p.date {
		position: absolute;
		top: 0px;
		right: 10px;
		text-transform: capitalize;
		font-size: 10px;
		padding: 2px 5px 0;
	}
	
	#comment_list li p.edit {
		position: absolute;
		bottom: 3px;
		right: 10px;
	}
	
	#comment_list li code, #comment_list li pre {
		position: relative;
		display: block;
		color: #262626;
		padding:  0 15px;
	}
	
	.pink { background-color:#d91e4e; color:#FFFFFF; border-color:#d91e4e; }
</style>

<script type="text/javascript">

/** Init autosuggest on Search Input **/
jQuery(function() {
		$("#tgl_bayar").datepicker({
                          dateFormat  : "yy-mm-dd",
                          changeMonth : true,
                          changeYear  : true
        });
		$("#tgl_debet").datepicker({
                          dateFormat  : "yy-mm-dd",
                          changeMonth : true,
                          changeYear  : true
        });
		$("#tgl_kredit").datepicker({
                          dateFormat  : "yy-mm-dd",
                          changeMonth : true,
                          changeYear  : true
        });
	//==================== Search With all plugins =================================================
	// Unbind form submit
	$('.home_searchEngine').bind('submit', function() {return false;} ) ;
	
	// Set autosuggest options with all plugins activated & response in xml
	var options = {
		script:"<?php echo base_url();?>AjaxSearch/_doAjaxSearch.action.php?limit=8&",
		varname:"input",
		shownoresults:true,				// If disable, display nothing if no results
		noresults:"Data tidak ada",			// String displayed when no results
		maxresults:8,					// Max num results displayed
		cache:false,					// To enable cache
		minchars:2,						// Start AJAX request with at leat 2 chars
		timeout:100000,					// AutoHide in XX ms
		callback: function (obj) { 		// Callback after click or selection
			// For example use :
						
			// Build HTML
			var html = "<table><tr><td>Nomor Register</td><td>: " + obj.id + "</td></tr><tr><td>Nama</td><td>: " + obj.nama + "</td></tr><tr><td>Alamat</td><td>: " + obj.alamat+ "</td></tr><tr><td>Nomor Porsi</td><td>: " + obj.porsi + "</td></tr><tr><td>Telepon</td><td>: " + obj.telepon+"</td></tr><tr><td>Estimasi Keberangkatan</td><td>: "+obj.tahun+" | <a href='http://haji.kemenag.go.id/v2/basisdata/xml/"+obj.porsi+"?sid=<?php echo substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);?> ' target='_blank'>Cek online di situs resmi Kemenag</a></td></tr><tr><td><a href='<?php echo base_url()?>index.php/admin/goto_transaksi/"+obj.id+"'>Lihat detil transaksi</a></td><td><a href='<?php echo base_url()?>index.php/jamaah/index/edit/"+obj.id+"' target='_blank'>Edit data pribadi</a></td></tr></table>";
			$('#input_search_all_response').html(html).show() ;
			
			// => TO submit form (general use)
			//$('#search_all_value').val(obj.id); 
			//$('#form_search_country').submit(); 
		}
	};
	// Init autosuggest
	var as_json = new bsn.AutoSuggest('input_search_all', options);
	
	// Display a little watermak	
	$("#input_search_all").Watermark("Contoh: Jauharoh Said,...");
	
	//==================== Search With "Country" plugin =================================================	
	// Set autosuggest options with all plugins activated
});
</script>

<title><?php echo isset($title) ? $title : ''; ?></title>
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
    	<td class="style_biru_badag_miring" align="center"><a href="<?php echo base_url();?>index.php/jamaah/umroh">Kembali</a>&nbsp;&nbsp;&nbsp;
Cari Nama / Nomor Porsi :
	<div class="search_example"> 
<div class="search_bar">
			<form method="post" action="/search_engine/" class="home_searchEngine" id="form_search_all">
			<input type="hidden" id="search_all_value" name="search_value">
						
			<input type="text" size="24" name="search_txt" id="input_search_all" class="search_txt">
			
			</form>		
			</div>
<div style="margin-top:0px;">
				<div class="search_response" style="display:none;" id="input_search_all_response"></div>
			</div>

</div>
 <br />
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
          <?php $this->load->view($main_view); ?>		  </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="39" align="center" valign="middle" background="images/bg_banner.gif" bgcolor="#CCCCCC" class="style_biru_kecil">Hak Cipta &copy; 2011<br>
      </td>
  </tr>
</table>
<?php //echo "Alamat server: ".$_SERVER['HTTP_HOST']; ?>
</body>
</html>