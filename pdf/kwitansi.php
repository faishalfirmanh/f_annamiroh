<?php
session_start();
if (empty($_SESSION['nama_admin'])){
	header ("location:../../index.php?login=no");
}

include "../config/uang.php";
include "../config/num_to_word.php";
include "../config/koneksi.php";
include "../config/fungsi.php";

include ('ezpdf/class.ezpdf.php');
$pdf = new Cezpdf();
$pdf = new Cezpdf('a6','Landscape');
 
// Set margin dan font
$pdf->ezSetCmMargins(0.5, 0, 0, 0.5);
$pdf->selectFont('ezpdf/fonts/Courier.afm');

$all = $pdf->openObject();

// Tampilkan logo
$pdf->setStrokeColor(0, 0, 0, 1);
$pdf->addJpegFromFile('../images/Arofah.jpg',165,780,40);

// Teks di tengah atas untuk judul header
$pdf->addText(220, 800, 16,'<b>BUKTI PEMBAYARAN</b>');
$pdf->addText(210, 785, 14,'<b>KBIH AN NAMIROH MOJOKERTO</b>');
// Garis atas untuk header


// Garis bawah untuk footer
// Teks kiri bawah
$pdf->addText(30,520,8,'Dicetak tgl:' . date( 'd-m-Y, H:i:s'));

$pdf->closeObject();

// Tampilkan object di semua halaman
$pdf->addObject($all, 'all');

// Koneksi ke database dan tampilkan datanya
//mysql_connect("localhost", "root", "");
//mysql_select_db("arofah2");

// Query untuk merelasikan kedua tabel
		
		$id=(int)$_GET['id'];
		$t = $_GET['t'];

	
	if ($t == 'allt'){
		$sql = "SELECT * FROM semua_data1 ";
		$sql.="INNER JOIN admin ON semua_data1.id_admin=admin.id_admin ";
		$sql.="WHERE id_semua_data='".$id."'";
		
		$qr = mysql_query($sql);
	
		$row2 = mysql_fetch_array($qr);
		$nama_jamaah=($row2['nama']);
		$jmlh = $row2['nominal'];
		$untuk = $row2['keterangan'];
		
		$penyetor = 'Penyetor';
		$penyetor2 = $row2['penyetor'];
	}
	else
	if ($t == 'adm'){
		$sql = "SELECT * FROM 
		adm INNER JOIN 
		data_jamaah ON adm.id_jamaah=data_jamaah.id_jamaah ";
		$sql.="INNER JOIN admin ON adm.id_admin=admin.id_admin ";
		$sql.="WHERE id_adm='".$id."'";
		
		$qr = mysql_query($sql);
	
		$row2 = mysql_fetch_array($qr);
		// print_r($row2);
		$nama_jamaah=($row2['nama_jamaah']);
		$jmlh = $row2['nominal'];
		$untuk = $row2[7];
		
		$penyetor = 'Penyetor';
		$penyetor2 = $row2['penyetor'];
	}
	else
	if ($t == 'kbih'){
		$sql = "SELECT * FROM 
		kbih INNER JOIN 
		data_jamaah ON kbih.id_jamaah=data_jamaah.id_jamaah ";
		$sql.="INNER JOIN admin ON kbih.id_admin=admin.id_admin ";
		$sql.="WHERE id_kbih='".$id."'";
		
		$qr = mysql_query($sql);
	
		$row2 = mysql_fetch_array($qr);
		
		$nama_jamaah=($row2['nama_jamaah']);
		$jmlh=$row2['nominal'];
		$untuk = 'Bimbingan';
		
		$penyetor = 'Penyetor';
		// $penyetor2 = $row2['penyetor'];
	}
	else
	if ($t == 'debet_onh'){
		$sql = "SELECT * FROM 
		tb_pembayaran INNER JOIN 
		data_jamaah ON tb_pembayaran.id_jamaah=data_jamaah.id_jamaah ";
		$sql.="INNER JOIN admin ON tb_pembayaran.id_admin=admin.id_admin ";
		$sql.="WHERE id_pembayaran='".$id."'";
		
		$qr = mysql_query($sql);
	
		$row2 = mysql_fetch_array($qr);
		
		$nama_jamaah=($row2['nama_jamaah']);
		$jmlh=$row2['debet'];
		
		$untuk = 'BIAYA ADMINISTRASI';
		
		$penyetor = 'Pengambil';
		$penyetor2 = $row2['pengambil'];
	}
	else
	if ($t == 'onh'){
		$sql = "SELECT * FROM 
		tb_pembayaran INNER JOIN 
		data_jamaah ON tb_pembayaran.id_jamaah=data_jamaah.id_jamaah ";
		$sql.="INNER JOIN admin ON tb_pembayaran.id_admin=admin.id_admin ";
		$sql.="WHERE id_pembayaran='".$id."'";
		
		$qr = mysql_query($sql);
	
		$row2 = mysql_fetch_array($qr);
		
		$nama_jamaah=($row2['nama_jamaah']);
		$jmlh=$row2['nominal'];
		
		$untuk = 'TAB ONH';
		$penyetor = 'Penyetor';
		//$penyetor2 = $row2['penyetor'];
	}
	
	
	
	$alamat_jamaah=($row2['alamat_jamaah']);
	$no_porsi=($row2['no_porsi']);
	$id_pembayaran=($t=='adm'||$t=='onh')?$id:($row2['id_pembayaran']);
	$label = 'rupiah';
	
	$tahun=($row2['tahun']);
	$tgl_bayar=($row2['tgl_bayar']);
	
	
	
	if($t=='allt'){
		$id_pembayaran = $row2['id_semua_data'];
		$label = $row2['mata_uang']==1?'dollar':'rupiah';
		$nominal =  $row2['mata_uang']==1?dolar($jmlh):uang($jmlh);
	}else $nominal = uang($jmlh);
	$teller = $row2['nama_admin'];
	$terbilang=num_to_words($jmlh, '', 0, '').' '.$label;
	if (!isset($penyetor2) || $penyetor2 == ''){
		$penyetor2= '_________________';
	}

$pdf->ezText("\n\n\n\n\n\n\n       Nama Jamaah :{$nama_jamaah}");
$pdf->ezText("       No Porsi    :{$no_porsi}");
$pdf->ezText("       Alamat      :{$alamat_jamaah}");
$pdf->ezText("       No Kwitansi :{$id_pembayaran}");
$pdf->ezText("   				Untuk 				  :{$untuk}");
$pdf->ezText("   				Tanggal 	 	 :{$tgl_bayar}");
$pdf->ezText("       Nominal     :{$nominal}");
$pdf->ezText("				   Terbilang   :{$terbilang}");
$pdf->ezText("  \n");

$data = array(
array('name'=>'','type'=>'')
,array('name'=>'','type'=>'')
,array('name'=>'('.$penyetor2.')','type'=>'('.$teller.')')
);

$cols = array('name'=>$penyetor,'type'=>'Teller');

$pdf->ezTable($data,$cols,'',
array('xPos'=>0,'xOrientation'=>'right','width'=>500,'showLines'=>0,'shaded'=>0,'cols'=>array(
'name'=>array('width'=>150, 'justification'=>'center')
,'type'=>array('width'=>250, 'justification'=>'center')
)));
// $pdf->ezStartPageNumbers(320, 15, 8);
 $pdf->ezStream();


?>
