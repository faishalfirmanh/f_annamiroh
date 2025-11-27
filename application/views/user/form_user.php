<?php 
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
	
?>
<script type="text/javascript">
      $(document).ready(function() {
          $('#form_user').ketchup();
      });  
</script>

<form id="form_user" name="form_user" method="post" action="<?php echo $form_action; ?>">
<input type="hidden" class="form_field" name="id_kecamatan" value="<?php echo set_value('id_kecamatan', isset($default['id_kecamatan']) ? $default['id_kecamatan'] : ''); ?>" />

<p>
<label for="nama">Nama :</label>
<input type="text" class="validate(required)" name="nama" size="30" maxlength="100" value="<?php echo set_value('nama', isset($default['nama']) ? $default['nama'] : ''); ?>" />
</p>
<?php echo form_error('nama', '<p class="field_error">', '</p>');?>

<p>
<label for="username">Username :</label>
<input type="text" class="validate(minlength(6))" name="username" size="30" maxlength="100" value="<?php echo set_value('username', isset($default['username']) ? $default['username'] : ''); ?>" />
</p>
<?php echo form_error('username', '<p class="field_error">', '</p>');?>

<p>
<label for="id_status">&nbsp; </label>
<input type="submit" name="submit" id="submit" value=" Simpan " />
</p>
</form>