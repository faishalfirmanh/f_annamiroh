<?php 
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
	
?>
<script type="text/javascript">
      $(document).ready(function() {
          $('#jamaah_form').ketchup();
      });  
</script>
<script type="text/javascript">
      $(document).ready(function(){
        $("#tgl_daftar").datepicker({
                          dateFormat  : "yy-mm-dd",
                          changeMonth : true,
                          changeYear  : true
        });
		$("#tgl_porsi").datepicker({
                          dateFormat  : "yy-mm-dd",
                          changeMonth : true,
                          changeYear  : true
        });
		$("#tgl_tempo").datepicker({
                          dateFormat  : "yy-mm-dd",
                          changeMonth : true,
                          changeYear  : true
        });
      });
</script>
<script type="text/javascript">
var xmlhttp = createRequestObject();

function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}
function cekKecamatan(combobox){	
	document.getElementById('kecamatan2').style.display="none";
	var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', '<?=base_url()?>config/cekKecamatan2.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("kecamatan").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
	
}
</script>

<script type="text/javascript">
$().ready(function() {	
	$("#tahun").autocomplete("<?php echo base_url()."config/ambil_tahun.php"; ?>", {
		width: 150
  	});

	$("#tahun").result(function(event, data, formatted) {

	});
	
	$("#bank").autocomplete("<?php echo base_url()."config/ambil_bank.php"; ?>", {
		width: 150
  	});

	$("#bank").result(function(event, data, formatted) {

	});
});
</script>

<form id="jamaah_form" name="jamaah_form" method="post" action="<?php echo $form_action; ?>">
<input type="hidden" class="form_field" name="id_jamaah" size="30" value="<?php echo set_value('id_jamaah', isset($default['id_jamaah']) ? $default['id_jamaah'] : ''); ?>" />
	<p>
		<label for="nama_jamaah">Nama Jamaah :</label>
		<input type="text" class="validate(required)" name="nama_jamaah" size="30" maxlength="50" value="<?php echo set_value('nama_jamaah', isset($default['nama_jamaah']) ? $default['nama_jamaah'] : ''); ?>" />
	</p>
	<?php echo form_error('nama_jamaah', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="ortu">Nama Ortu :</label>
		<input type="text" class="validate(required)" name="ortu" size="30" maxlength="50" value="<?php echo set_value('nama_ortu', isset($default['nama_ortu']) ? $default['nama_ortu'] : ''); ?>" />
		
	</p>
	<?php echo form_error('ortu', '<p class="field_error">', '</p>');?>	
	
	<p>
		<label for="tgl_daftar">Tgl Daftar :</label>
		<input type="text" class="validate(date)" id="tgl_daftar" name="tgl_daftar" size="10" maxlength="10" value="<?php echo set_value('tgl_daftar', isset($default['tgl_daftar']) ? $default['tgl_daftar'] : ''); ?>" />
	</p>
	<?php echo form_error('tgl_daftar', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="tgl_porsi">Tgl Porsi :</label>
		<input type="text" class="validate(date)" id="tgl_porsi" name="tgl_porsi" size="10" maxlength="10" value="<?php echo set_value('tgl_porsi', isset($default['tgl_porsi']) ? $default['tgl_porsi'] : ''); ?>" />
	</p>
	<?php echo form_error('tgl_porsi', '<p class="field_error">', '</p>');?>

<p>
<label for="tgl_tempo">Tgl Tempo :</label>
<input type="text" class="validate(date)" id="tgl_tempo" name="tgl_tempo" size="10" maxlength="10" value="<?php echo set_value('tgl_tempo', isset($default['tgl_tempo']) ? $default['tgl_tempo'] : ''); ?>" />
</p>
<?php echo form_error('tgl_tempo', '<p class="field_error">', '</p>');?>

<p>
<label for="no_porsi">No Porsi :</label>
<input type="text" class="validate(required)" name="no_porsi" size="30" maxlength="30" value="<?php echo set_value('no_porsi', isset($default['no_porsi']) ? $default['no_porsi'] : ''); ?>" />
</p>
<?php echo form_error('no_porsi', '<p class="field_error">', '</p>');?>	
	
	<p>
		<label for="bank">Bank :</label>
<input type="text" class="validate(required)" id="bank" name="bank" size="40" maxlength="50" value="<?php echo set_value('bank', isset($default['bank']) ? $default['bank'] : ''); ?>" />	</p>
	<?php echo form_error('bank', '<p class="field_error">', '</p>');?>

<p>
<label for="no_rekening">No Rekening :</label>
<input type="text" class="validate(required)" name="no_rekening" size="20" maxlength="20" value="<?php echo set_value('no_rekening', isset($default['no_rekening']) ? $default['no_rekening'] : ''); ?>" />
</p>
<?php echo form_error('no_rekening', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="id_status">Status :</label>
        <?php echo form_dropdown('id_status', $options_status, isset($default['id_status']) ? $default['id_status'] : ''); ?>
	</p>
	<?php echo form_error('id_status', '<p class="field_error">', '</p>');?>

<p>
<label for="alamat_jamaah">Alamat Asli Jamaah :</label>
<input type="text" class="validate(required)" name="alamat_jamaah" size="50" maxlength="50" value="<?php echo set_value('alamat_jamaah', isset($default['alamat_jamaah']) ? $default['alamat_jamaah'] : ''); ?>" />
</p>
<?php echo form_error('alamat_jamaah', '<p class="field_error">', '</p>');?>


<p>
<label for="alamat_ktp">Alamat KTP :</label>
<input type="text" class="validate(required)" name="alamat_ktp" size="50" maxlength="50" value="<?php echo set_value('alamat_ktp', isset($default['alamat_ktp']) ? $default['alamat_ktp'] : ''); ?>" />
</p>
<?php echo form_error('alamat_ktp', '<p class="field_error">', '</p>');?>

<p>
<label for="kabupaten">Kabupaten :</label>
		<select name="id_kabupaten" onchange="javascript:cekKecamatan(this);" class="validate(required)">
		<option value="">-pilih-</option>
		<?php
		$sqlSb = mysql_query("SELECT * FROM kabupaten ORDER BY kabupaten ASC");
		while ($rSb = mysql_fetch_array($sqlSb)){
			if ($rSb['id_kabupaten']==$default['id_kabupaten']){
				echo "<option value=\"$rSb[id_kabupaten]\" selected>$rSb[kabupaten]</option>";
			}
			else{
				echo "<option value=\"$rSb[id_kabupaten]\">$rSb[kabupaten]</option>";
			}
		}
		?>
		</select>
</p>
<?php echo form_error('id_kabupaten', '<p class="field_error">', '</p>');?>	

<p>
<label for="kecamatan">Kecamatan :</label>
	<span id="kecamatan"></span>
	<span style="display:block" id="kecamatan2">
	 <?php echo form_dropdown('id_kecamatan2', $options_kecamatan, isset($default['id_kecamatan']) ? $default['id_kecamatan'] : ''); ?>
	</span>
</p>
<?php echo form_error('id_kecamatan', '<p class="field_error">', '</p>');?>
<?php echo form_error('id_kecamatan2', '<p class="field_error">', '</p>');?>

<p>
<label for="tlp">Tlp :</label>
<input type="text" class="validate(required)" name="tlp" size="40" maxlength="40" value="<?php echo set_value('tlp', isset($default['tlp']) ? $default['tlp'] : ''); ?>" />
</p>
<?php echo form_error('tlp', '<p class="field_error">', '</p>');?>

<p>
<label for="tlp">Th Berangkat :</label>
<input type="text" class="validate(number, minlength(4))" id="tahun" name="tahun" size="4" maxlength="4" value="<?php echo set_value('tahun', isset($default['tahun']) ? $default['tahun'] : ''); ?>" />
</p>
<?php echo form_error('tahun', '<p class="field_error">', '</p>');?>

<p>
<label for="id_status">&nbsp; </label>
<input type="submit" name="submit" id="submit" value=" Simpan " />
</p>
</form>