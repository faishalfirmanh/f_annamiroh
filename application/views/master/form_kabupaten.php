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
<input type="hidden" class="form_field" name="id_kabupaten" value="<?php echo set_value('id_kabupaten', isset($default['id_kabupaten']) ? $default['id_kabupaten'] : ''); ?>" />
	<p>
		<label for="kabupaten">Kabupaten :</label>
		<input type="text" class="validate(required)" name="kabupaten" size="30" maxlength="100" value="<?php echo set_value('kabupaten', isset($default['kabupaten']) ? $default['kabupaten'] : ''); ?>" />
	</p>
	<?php echo form_error('kabupaten', '<p class="field_error">', '</p>');?>

<p>
<label for="id_status">&nbsp; </label>
<input type="submit" name="submit" id="submit" value=" Simpan " />
</p>
</form>