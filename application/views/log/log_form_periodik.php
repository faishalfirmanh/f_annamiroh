<form name="Log_form" method="post" action="<?=$form_action?>">
<table align="center" cellpadding="1" cellspacing="1">
<tr valign="top">
<td align="right">
Mulai
</td>
<td>&nbsp;<input type="text" class="form_field" id="tgl_Log" name="tgl_Log" size="10" maxlength="10" value="<?php echo set_value('tgl_Log', isset($default['tgl_Log']) ? $default['tgl_Log'] : ''); ?>" />
<font color="#ff0000"><?php echo form_error('tgl_Log', '', '');?></font>
<br />
</td>
</tr>
<tr valign="top">
<td align="right">
Sampai 
</td>
<td>&nbsp;<input type="text" class="form_field" id="tgl_Log2" name="tgl_Log2" size="10" maxlength="10" value="<?php echo set_value('tgl_Log2', isset($default['tgl_Log2']) ? $default['tgl_Log2'] : ''); ?>" />
<font color="#ff0000"><?php echo form_error('tgl_Log2', '', '');?></font>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>
&nbsp;<input type="submit" name="submit" id="submit" value=" Cari " />
</td>
</tr>
</table>
</form>