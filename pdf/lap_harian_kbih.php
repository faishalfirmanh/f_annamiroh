<?php
session_start();
if (empty($_SESSION['nama_admin'])){
	header ("location:../../index.php?login=no");
}
include "../config/uang.php";
include "../config/num_to_word.php";
include "../config/koneksi.php";
include "../config/fungsi.php";
//
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
$pdf->addText(250, 820, 13,'<b>LAPORAN HARIAN</b>');
$pdf->addText(190, 800, 10,'<b>PEMBAYARAN KBIH JAMAAH KBIH AN NAMIROH MOJOKERTO</b>');
// Garis atas untuk header
$pdf->line(10, 795, 578, 795);

// Garis bawah untuk footer
$pdf->line(10, 50, 578, 50);
// Teks kiri bawah
$pdf->addText(30,34,8,'Dicetak tgl:' . date( 'd-m-Y, H:i:s'));
$pdf->ezStartPageNumbers(320, 15, 8);
$pdf->closeObject();


$pdf->addObject($all, 'all');


	$tgl_bayar=$_GET['tgl'];

	
	$sql = "SELECT * FROM 
	kbih INNER JOIN 
	data_jamaah ON kbih.id_jamaah=data_jamaah.id_jamaah ";
	$sql.="where tgl_bayar='".$tgl_bayar."'";
	$qr2 = mysql_query($sql);
	$i = 1;
	$jumlah = 0;
	while ($row2 = mysql_fetch_array($qr2)){
	$data[$i]=array('no' => $i,
					'atas_nama' => $row2[nama_jamaah],
					'tgl_bayar' => $row2[tgl_bayar],
					'nominal' => uang($row2['nominal']),
					'tahun' => $row2['tahun'],
					'no_porsi' => $row2['no_porsi']
					);
					 $i++;
					 $jumlah = $jumlah + $row2['nominal'];
	}
	$sub_jumlah=uang($jumlah);
	
	
		//$sqlTotal = "$rSum[sub_jumlah] - $_POST[biayaAdm]";
		//$qrTotal = mysql_query($sqlTotal);
		//$sqlTotal= mysql_fetch_array($qrTotal);
		//$sqlTotal=($sqlTotal[total]);
	
$cols = array('no'=>"<b>No</b>", 'atas_nama'=>'<b>Atas Nama</b>', 'tgl_bayar'=>'<b>Tgl Bayar</b>', 'nominal'=>'<b>Nominal</b>','tahun'=>'<b>Tahun</b>','no_porsi'=>"<b>No Porsi</b>");

$pdf->ezTable($data,$cols,'',array('xPos'=>50,'xOrientation'=>'right','width'=>500,'cols'=>array(
'no'=>array('justification'=>'right')
,'atas_nama'=>array('width'=>150)
,'nominal'=>array('justification'=>'right')
)));

$pdf->ezText("\n\n Total keseluruhan : Rp. {$sub_jumlah}");
$pdf->ezText(" \n\n\n\n\n\n\n\n                                             Petugas KBIH AN NAMIROH");
$pdf->ezText(" \n\n\n\n\n\n\n                                            (    ANA MAULIDA   )");
// Penomoran halaman
// Penomoran halaman

$pdf->ezStream();

?>
ROFAH