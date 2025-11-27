<?php
session_start();
if (empty($_SESSION['nama_admin'])){
	header ("location:../../index.php?login=no");
}

include "../config/uang.php";
include "../config/num_to_word.php";
include "../config/koneksi.php";
include "../config/fungsi.php";

include ('ezpdf/class.ezpdf_a4_L.php');

$pdf = new Cezpdf();
 
// Set margin dan font
$pdf->ezSetCmMargins(3, 3, 3, 3);
$pdf->selectFont('ezpdf/fonts/Courier.afm');

$all = $pdf->openObject();

// Tampilkan logo
$pdf->setStrokeColor(0, 0, 0, 1);
$pdf->addJpegFromFile('../images/Arofah.jpg',50,540,40);

// Teks di tengah atas untuk judul header
$pdf->addText(350, 560, 13,'<b>LAPORAN HARIAN</b>');
$pdf->addText(285, 540, 10,'<b>PEMBAYARAN ADM JAMAAH KBIH AN NAMIROH MOJOKERTO</b>');// Garis atas untuk header
$pdf->line(10, 530, 825, 530);
//$pdf->addTextWrap ( 185, 800, 100,  20,  'PEMBAYARAN JAMAAH KBIH AN NAMIROH MOJOKERTO', 'left', 0, 0);
// Garis bawah untuk footer
$pdf->line(10, 50, 825, 50);
// Teks kiri bawah
$pdf->addText(30,34,8,'Dicetak tgl:' . date( 'd-m-Y, H:i:s'));
$pdf->ezStartPageNumbers(400, 15, 8);
$pdf->closeObject();


$pdf->addObject($all, 'all');

$tgl = $_GET['tgl'];
//sql
$sql = "SELECT * FROM adm ";
$sql.= "INNER JOIN data_jamaah ON adm.id_jamaah=data_jamaah.id_jamaah ";
$sql.= "WHERE tgl_bayar='".$tgl."' ";
$qr2 = mysql_query($sql."order by tgl_bayar ASC");
	$i = 1;
	$jumlah = 0;
	while ($row2 = mysql_fetch_array($qr2)){
		// print_r($row2);
		$data[$i]=array('no' => $i,
					'nama_jamaah' => $row2['nama_jamaah'],
					'tgl_bayar' => $row2['tgl_bayar'],
					'nominal' => uang($row2['nominal']),
					'ket' => $row2[7],
					'tahun' => $row2['tahun'],
					'no_porsi' => $row2['no_porsi'],
					);
					 $i++;
					$jumlah = $jumlah + $row2['nominal'];
	}
	
	$sub_jumlah=uang($jumlah);

$cols = array('no'=>"<b>No</b>", 'nama_jamaah'=>'<b>Penyetor</b>', 'tgl_bayar'=>'<b>Tanggal</b>', 'nominal'=>'<b>Nominal (Rp)</b>', 'ket'=>'<b>Keterangan</b>', 'tahun'=>'<b>Tahun</b>', 'no_porsi'=>'<b>No Porsi</b>');
	
$pdf->ezTable($data,$cols,'',array('xPos'=>50,'xOrientation'=>'right','width'=>770,'cols'=>array(
'no'=>array('justification'=>'right')
,'nama_jamaah'=>array('width'=>150)
,'nama'=>array('width'=>150)
,'nominal'=>array('justification'=>'right')
,'ket'=>array('width'=>270, 'justification'=>'full') )
));

$pdf->ezText("\n\n Total keseluruhan : Rp. {$sub_jumlah}");
$pdf->ezText(" \n\n\n\n\n\n\n\n                                             Petugas KBIH AN NAMIROH");
$pdf->ezText(" \n\n\n\n\n\n\n                                            (    ANA MAULIDA   )");
// Penomoran halaman
// Penomoran halaman

$pdf->ezStream();

?>
