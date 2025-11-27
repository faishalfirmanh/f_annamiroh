<form name="laporan_form" method="post" action="<?=$form_action?>">
<table align="center" cellpadding="1" cellspacing="1">
<tr valign="top">
<td align="right">
Mulai
</td>
<td>&nbsp;<input type="text" class="form_field" id="tgl_laporan" name="tgl_laporan" size="10" maxlength="10" value="<?php echo set_value('tgl_laporan', isset($default['tgl_laporan']) ? $default['tgl_laporan'] : ''); ?>" />
<font color="#ff0000"><?php echo form_error('tgl_laporan', '', '');?></font>
<br />
</td>
</tr>
<tr valign="top">
<td align="right">
Sampai 
</td>
<td>&nbsp;<input type="text" class="form_field" id="tgl_laporan2" name="tgl_laporan2" size="10" maxlength="10" value="<?php echo set_value('tgl_laporan2', isset($default['tgl_laporan2']) ? $default['tgl_laporan2'] : ''); ?>" />
<font color="#ff0000"><?php echo form_error('tgl_laporan2', '', '');?></font>
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