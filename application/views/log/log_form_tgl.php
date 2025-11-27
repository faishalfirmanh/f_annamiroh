<script type="text/javascript">
	$(document).ready(function(){
		$("#tgl_Log").datepicker({
                          dateFormat  : "yy-mm-dd",
                          changeMonth : true,
                          changeYear  : true
		});
	});
</script>
<script type="text/javascript">
      $(document).ready(function() {
          $('#Log_form').ketchup();
      });  
</script>
<form name="Log_form" id="Log_form" method="post" action="<?=$form_action?>">
<table align="center" cellpadding="1" cellspacing="1">
<tr valign="top">
<td align="right">
Tgl :
</td>
<td>&nbsp;<input type="text" class="validate(date)" id="tgl_Log" name="tgl_Log" size="10" maxlength="10" value="<?php echo set_value('tgl_Log', isset($default['tgl_Log']) ? $default['tgl_Log'] : ''); ?>" />&nbsp;<input type="submit" name="submit" id="submit" value=" Cari " />
<font color="#ff0000"><?php echo form_error('tgl_Log', '', '');?></font>
<br />
</td>
</tr>
</table>
</form>