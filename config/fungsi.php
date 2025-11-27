<?php
function getTglBayar($tl){
	$pecah = explode("-",$tl);
	$tgl1=$pecah['2'];
	$bln1=$pecah['1'];
	$thn1=$pecah['0'];
	if ($thn1='0000'){
		$thn = date('Y');
	}
	else{
		$thn = $thn1;
	}
	$tgl_skrg=date("d");
	echo "<select name=\"tglBayar\">";
	if ($tgl1==""){
		echo "<option value=\"$tgl_skrg selected\">$tgl_skrg</option>";
	}
	else{
		echo "<option value=\"$tgl1\" selected>$tgl1</option>";
	}
		  for ($tgl=1; $tgl<=31; $tgl++){
			 // Hitung panjang karakter     
			$panjang_karakter=strlen($tgl);
			// Apabila panjang karakter 1 digit, maka tambahkan 0 di depannya
			if ($panjang_karakter==1){
				$i="0".$tgl;
			}
			else{
				$i=$tgl;
			}
			echo "<option value=$i>$i</option>";
		  }
		  echo "</select> ";  
	$bln_skrg=date("m");      
	echo "<select name=\"blnBayar\">";
	if ($bln1==""){
		echo "<option value=$bln_skrg selected>$bln_skrg</option>";
	}
	else{
		echo "<option value=$bln1 selected>$bln1</option>";
	}
		  for ($bln=1; $bln<=12; $bln++){
			 // Hitung panjang karakter     
			$panjang_karakter=strlen($bln);
			// Apabila panjang karakter 1 digit, maka tambahkan 0 di depannya
			if ($panjang_karakter==1){
				$j="0".$bln;
			}
			else{
				$j=$bln;
			}
			echo "<option value=$j>$j</option>";
		  }   
		  echo "</select> ";
		  
		  echo "<input name=\"thnBayar\" type=\"text\" size=\"4\" maxlength=\"4\" value=\"$thn\"/>";
}
?>