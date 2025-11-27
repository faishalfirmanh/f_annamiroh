<script type="text/javascript">
	$(document).ready(function(){
		$("#th").datepicker({
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
Tahun :
</td>
<td>
<select name="th">
<?php
$th = $this->Laporan_model->get_tahun();
foreach ($th->result() as $row){
	echo "<option value=$row->tahun>$row->tahun</option>";
}
?>
</select>&nbsp;<input type="submit" name="submit" id="submit" value=" Cari " />
<font color="#ff0000"><?php echo form_error('id_th', '', '');?></font>
<br />
</td>
</tr>
</table>
</form>