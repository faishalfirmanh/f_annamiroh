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


 
// Set margin dan font
$pdf->ezSetCmMargins(3, 3, 3, 3);
$pdf->selectFont('ezpdf/fonts/Courier.afm');

$all = $pdf->openObject();

// Tampilkan logo
$pdf->setStrokeColor(0, 0, 0, 1);
$pdf->addJpegFromFile('../images/Arofah.jpg',150,797,40);

// Teks di tengah atas untuk judul header
$pdf->addText(250, 820, 13,'<b>LAPORAN TAHUNAN</b>');
$pdf->addText(190, 800, 10,'<b>TABUNGAN ONH JAMAAH KBIH AN NAMIROH MOJOKERTO</b>');
// Garis atas untuk header
$pdf->line(10, 795, 578, 795);

// Garis bawah untuk footer
$pdf->line(10, 50, 578, 50);
// Teks kiri bawah
$pdf->addText(30,34,8,'Dicetak tgl:' . date( 'd-m-Y, H:i:s'));

$pdf->closeObject();


$pdf->addObject($all, 'all');

	$tahun=$_GET['th'];

	$sql = "SELECT nama_jamaah,alamat_jamaah,no_porsi,no_tlp,tahun,sum(nominal) as jumlah from tb_pembayaran 
	inner join data_jamaah on tb_pembayaran.id_jamaah=data_jamaah.id_jamaah 
	where tahun = '".$tahun."' 
	group by nama_jamaah,alamat_jamaah,no_porsi,no_tlp,tahun 
	order by nama_jamaah asc";
	$qr2 = mysql_query($sql);
	$i = 1;
	while ($row2 = mysql_fetch_array($qr2)){
	$data[$i]=array('<b>No</b>' => $i,'<b>Nama</b>' => $row2[nama_jamaah],
					 '<b>Alamat</b>' => $row2[alamat_jamaah],
					 '<b>Tahun</b>' => $row2[tahun],
					 '<b>Jumlah Uang</b>' => uang($row2['jumlah']));
					 $i++;
	}
	
	//$sqlSum = "SELECT SUM(nominal) AS sub_jumlah FROM tb_pembayaran WHERE year(tgl_bayar)='$tgl_bayar' ";
	//$qrSum = mysql_query($sqlSum);
	//$rSum = mysql_fetch_array($qrSum);
	//$sub_jumlah=($rSum[sub_jumlah]);
	
	
		//$sqlTotal = "$rSum[sub_jumlah] - $_POST[biayaAdm]";
		//$qrTotal = mysql_query($sqlTotal);
		//$sqlTotal= mysql_fetch_array($qrTotal);
		//$sqlTotal=($sqlTotal[total]);
	

$pdf->ezTable($data, '', '', '');

$pdf->ezText(" \n\n\n\n\n\n\n\n                                          			   Petugas KBIH AN NAMIROH");
$pdf->ezText(" \n\n\n\n\n\n\n                                           				 (    ANA MAULIDA   )");
// Penomoran halaman
// Penomoran halaman
$pdf->ezStartPageNumbers(320, 15, 8);
$pdf->ezStream();

?>
