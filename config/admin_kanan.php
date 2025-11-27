<?
include "koneksi.php";
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
	var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'cekKecamatan.php?kode='+kode, true);
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
ojklh
   <table border="0" cellpadding="5" cellspacing="5">
      <form method="post" action="<?php echo $form_action; ?>">
        <!--DWLayoutTable-->
        <tr>
          <td valign="top">  <span class="style1">Kecamatan</span> </td><td> : <select name="id_kecamatan">
                <option value="">Semua</option>
                <?php
				  $sql=mysql_query("SELECT * FROM kecamatan ORDER BY kecamatan DESC");
				  while($row=mysql_fetch_array($sql))
				  {
				  echo ("<OPTION VALUE=\"$row[id_kecamatan]\">$row[kecamatan]</option>");
				  }
				  ?>
              </select></td>
			 </tr>
			 <tr>
                        <td valign="top"> <span class="style1">Kabupaten</span></td><td> : <select name="id_kabupaten" onchange="javascript:cekKecamatan(this);">
                <option value="">Semua</option>
                <?php
				  $sql=mysql_query("SELECT * FROM kabupaten ORDER BY kabupaten DESC");
				  while($row=mysql_fetch_array($sql))
				  {
				  echo ("<OPTION VALUE=\"$row[id_kabupaten]\">$row[kabupaten]</option>");
				  }
				  ?>
              </select><span id="kecamatan"></span></td>
			  </tr>
			  <tr>
                        <td valign="top"><span class="style1">Pilih Tahun </span></td><td> : <select name="id_th">
                <option value="">Semua</option>
                <?php
				  $sql=mysql_query("SELECT * FROM th_berangkat");
				  while($row=mysql_fetch_array($sql))
				  {
				  echo ("<OPTION VALUE=\"$row[id_th]\">$row[tahun]</option>");
				  }
				  ?>
            </select></td>
	      </tr>
		  <tr>
		  <td>&nbsp;</td>
            <td> &nbsp; <input type="submit" value="Cari"></td>
        </tr>
          </form>
    </table>