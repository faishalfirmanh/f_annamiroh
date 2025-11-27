<?php
session_start();
if (empty($_SESSION['nama_admin'])){
	header ("location:../../index.php?login=no");
}


include "../config/koneksi.php";
include "../config/uang.php";
define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdf.php');

class PDF extends FPDF{


  function Header(){
	$left_margin = 1.5;
	$this->SetX($left_margin);
	$this->Image('../images/Arofah.jpg',1.5,0.4,1.5);
	$this->SetFont('Arial','B','12');
    $this->Cell(18.5,0.5,'LAPORAN HARIAN',0,0,'C');
	$this->Ln();
	$this->SetX($left_margin);
	$this->SetFont('Arial','B','10');
	$this->SetLineWidth(0.05);
	$this->Cell(0,0.5,'TABUNGAN ONH JAMAAH KBIH AN NAMIROH MOJOKERTO','B',0,'C');
	
	$this->Ln();
	//$this->Line(1.5,2.2,18,2.2);
	$this->Ln(); 
	$this->SetLineWidth(0);
	$this->SetX(2);
	$this->SetFillColor(200,200,200);
	//<th
	$col1=1;
	$col2=6;
	$col3=2;
	$col4=3;
	$col5=2.7;
	$col6=1;
	$col7=2;
	$this->SetFont('Arial','B','8');
	$this->Cell($col1,0.6,'No',1,0,'C',1);
	$this->Cell($col2,0.6,'Atas Nama',1,0,'C',1);
	$this->Cell($col3,0.6,'Tanggal',1,0,'C',1);
	$this->Cell($col4,0.6,'Debet (Rp.)',1,0,'C',1);
	$this->Cell($col5,0.6,'Kredit (Rp.)',1,0,'C',1);
	$this->Cell($col6,0.6,'Tahun',1,0,'C',1);
	$this->Cell($col7,0.6,'No Porsi',1,0,'C',1);
	//th>
	$this->Ln();
  }
   function Footer(){
   	$left_margin = 1.5;
   	$this->SetY(-1.5,5);
	$this->SetX($left_margin);
	$this->SetFont('Arial','i','8');
    $this->Cell(9.25,0.5,'Dicetak tgl:06-10-2011, 22:56:42','T',0,'L');
	$this->SetFont('Arial','','9');
	$this->Cell(9.25,0.5,$this->PageNo(),'T',0,'L');
	$this->Ln();
	$this->SetX($left_margin);
	$this->SetFont('Arial','i','8');
	$this->Cell(9.25,0.2,'Oleh : '.$_SESSION['nama_admin'],0,0,'L');
  }
}

$pdf=new PDF('P','cm','A4');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true,2);
$left_margin = 2;

$col1=1;
$col2=6;
$col3=2;
$col4=3;
$col5=2.7;
$col6=1;
$col7=2;

$pdf->SetTextColor(000);
$pdf->SetFont('arial','B',8);
$pdf->SetX($left_margin);
$pdf->SetFont('arial','',8);
$pdf->SetFillColor(240,240,240);
$th = (int)$_GET['th'];$p = $_GET['p'];$pieces = explode("/", $p);$bulan=$pieces[0];$th=(int)$pieces[1];// var_dump($pieces);// $p = str_replace(' ', '', $p);// $p = preg_replace('/\s+/', '', $p);
$sql = "SELECT * FROM tb_pembayaran ";
$sql.= "INNER JOIN data_jamaah ON tb_pembayaran.id_jamaah=data_jamaah.id_jamaah ";// echo "ini:$p";exit();
$sql.= "WHERE year(tgl_bayar)='".$th."' and month(str_to_date('$bulan','%b')) =month(tgl_bayar)";
		$qr2 = mysql_query($sql."order by tgl_bayar ASC");
		$i = 1;
		$total_debet = 0;
		$total_kredit = 0;
		while ($row2 = mysql_fetch_array($qr2)){
			if ($row2['debet']!=0){
				$debet = uang($row2['debet']);
			}
			else{
				$debet = '';
			}
			
			if ($row2['nominal']!=0){
				$kredit = uang($row2['nominal']);
			}
			else{
				$kredit = '';
			}
			if ($i%2 == 0){
				$fill =1;
			}
			else{
				$fill =0;
			}
			$pdf->SetX($left_margin);
			$pdf->Cell($col1,0.6,$i.'.',1,0,'R',$fill);
			$pdf->Cell($col2,0.6,$row2['nama_jamaah'].' ',1,0,'L',$fill);
			$pdf->Cell($col3,0.6,substr($row2['tgl_bayar'],0,10),1,0,'C',$fill);
			$pdf->Cell($col4,0.6,$debet.' ',1,0,'R',$fill);
			$pdf->Cell($col5,0.6,$kredit.' ',1,0,'R',$fill);
			$pdf->Cell($col6,0.6,$row2['tahun'].' ',1,0,'C',$fill);
			$pdf->Cell($col7,0.6,$row2['no_porsi'],1,0,'R',$fill);
		$i++;
		$total_debet = $total_debet + $row2['debet'];
		$total_kredit = $total_kredit + $row2['nominal'];
		$pdf->Ln();
		}
			$pdf->SetFillColor(200,200,200);
			$pdf->SetX($left_margin);
			$pdf->SetFont('arial','B',8);
			$pdf->Cell($col1,0.6,'','TLB',0,'R',1);
			$pdf->Cell($col2,0.6,'Jumlah (Rp.)','TB',0,'C',1);
			$pdf->Cell($col3,0.6,'','TB',0,'C',1);
			$pdf->Cell($col4,0.6,uang($total_debet).' ',1,0,'R',1);
			$pdf->Cell($col5,0.6,uang($total_kredit).' ',1,0,'R',1);
			$pdf->Cell($col6,0.6,'',1,0,'C',1);
			$pdf->Cell($col7,0.6,'','TBR',0,'R',1);
		//2
			$pdf->Ln();
			$pdf->Ln();
			$pdf->SetX($left_margin);
			$pdf->SetFont('arial','B',8);
			$pdf->Cell(6,0.6,'',0,0,'R',0);
			$pdf->Cell(5.5,0.6,'',0,0,'C',0);
			$pdf->Cell(6,0.6,'Petugas KBIH AN NAMIROH',0,0,'C',0);
			
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();
			$pdf->SetX($left_margin);
			$pdf->SetFont('arial','B',8);
			$pdf->Cell(6,0.6,'',0,0,'R',0);
			$pdf->Cell(5.5,0.6,'',0,0,'L',0);
			$pdf->Cell(6,0.6,'(    ANA MAULIDA   )',0,0,'C',0);
	
$pdf->Ln();
$noR++;
$pdf->Output();
?>