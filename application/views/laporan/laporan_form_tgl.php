<script type="text/javascript">
	$(document).ready(function(){
		$("#tgl_laporan").datepicker({
                          dateFormat  : "yy-mm-dd",
                          changeMonth : true,
                          changeYear  : true
		});
	});
</script>
<script type="text/javascript">
      $(document).ready(function() {
          $('#laporan_form').ketchup();
      });  
</script>
<form name="laporan_form" id="laporan_form" method="post" action="<?=$form_action?>">
<table align="center" cellpadding="1" cellspacing="1">
<tr valign="top">
<td align="right">
Tgl :
</td>
<td>&nbsp;<input type="text" class="validate(date)" id="tgl_laporan" name="tgl_laporan" size="10" maxlength="10" value="<?php echo set_value('tgl_laporan', isset($default['tgl_laporan']) ? $default['tgl_laporan'] : ''); ?>" />&nbsp;<input type="submit" name="submit" id="submit" value=" Cari " />
<font color="#ff0000"><?php echo form_error('tgl_laporan', '', '');?></font>
<br />
</td>
</tr>
</table>
</form>