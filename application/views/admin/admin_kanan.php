<?php 
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
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
    xmlhttp.open('get', '<?=base_url()?>config/cekKecamatan.php?kode='+kode, true);
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


<form id="jamaah_form" name="jamaah_form" method="post" action="<?php echo $form_action; ?>">
<p>
<label for="kabupaten">Kabupaten :</label>
		<select name="kabupaten" onchange="javascript:cekKecamatan(this);" class="validate(required)">
		<option value="">-Semua-</option>
		<?php
		$sqlSb = mysql_query("SELECT * FROM kabupaten ORDER BY kabupaten ASC");
		while ($rSb = mysql_fetch_array($sqlSb)){
			if ($rSb['id_kabupaten']==$ru['id_kabupaten']){
				echo "<option value=\"$rSb[id_kabupaten]\" selected>$rSb[kabupaten]</option>";
			}
			else{
				echo "<option value=\"$rSb[id_kabupaten]\">$rSb[kabupaten]</option>";
			}
		}
		?>
		</select>
</p>
<?php echo form_error('kabupaten', '<p class="field_error">', '</p>');?>	

<p>
<label for="kecamatan">Kecamatan :</label>
	<span id="kecamatan"></span>
	<span style="display:block" id="kecamatan2">
	<select>
		<option value="">-Semua-</option>
	</select>
	</span>
</p>
<?php echo form_error('kecamatan', '<p class="field_error">', '</p>');?>

<p>
<label for="tahun">Tahun :</label>
<select name="th">
<option value="">-Semua-</option>
<?php
$th = $this->Laporan_model->get_tahun();
foreach ($th->result() as $row){
	echo "<option value=$row->tahun>$row->tahun</option>";
}
?>
</select>
</p>

<p>
<label for="id_status">&nbsp; </label>
<input type="submit" name="submit" id="submit" value=" cari " />
</p>
</form>