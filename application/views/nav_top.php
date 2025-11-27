<table width="100%">
<tr>
<td class="style_biru_badag_miring">
<form name="jamaah_form" method="post" action="<?php echo $form_action; ?>">
<input type="text" class="form_field" name="no_jamaah" value="<?php echo set_value('no_jamaah', isset($default['no_jamaah']) ? $default['no_jamaah'] : ''); ?>" />
<input type="submit" name="submit" id="submit" value=" Cari " />
<br /><?php echo form_error('no_jamaah', '<font color="#FF0000">', '</font>');?>
</form>

</td>
<td>
<?php 
	echo anchor('admin', '<img src='.base_url() .'images/48px-Crystal_Clear_app_kfm_home.png><br>Home');
?>
</td>
<td>
<?php 
	echo anchor('login/process_logout', '<img src='.base_url() .'images/48px-Crystal_Clear_action_lock.png><br>Logout', array('onclick' => "return confirm('Anda yakin akan logout?')"));
?>
</td>
</tr>
</table>