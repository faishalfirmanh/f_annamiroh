<?php 
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
	
?>
<script type="text/javascript">
      $(document).ready(function() {
          $('#form_kabupaten').ketchup();
      });  
</script>

<form id="form_kabupaten" name="form_kabupaten" method="post" action="<?php echo $form_action; ?>">
<input type="hidden" class="form_field" name="id_kecamatan" value="<?php echo set_value('id_kecamatan', isset($default['id_kecamatan']) ? $default['id_kecamatan'] : ''); ?>" />

<p>
<label for="id_kabupaten">Kabupaten :</label>
<?php echo form_dropdown('id_kabupaten', $options_kabupaten, isset($default['id_kabupaten']) ? $default['id_kabupaten'] : ''); ?>
</p>
<?php echo form_error('id_kabupaten', '<p class="field_error">', '</p>');?>
	
<p>
<label for="kecamatan">Kecamatan :</label>
<input type="text" class="validate(required)" name="kecamatan" size="30" maxlength="100" value="<?php echo set_value('kecamatan', isset($default['kecamatan']) ? $default['kecamatan'] : ''); ?>" />
</p>
<?php echo form_error('kecamatan', '<p class="field_error">', '</p>');?>

<p>
<label for="id_status">&nbsp; </label>
<input type="submit" name="submit" id="submit" value=" Simpan " />
</p>
</form>