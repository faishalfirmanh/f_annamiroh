<?php
session_start();
if (empty($_SESSION['nama_admin'])){
	header ("location:../../index.php?login=no");
}


include "../config/koneksi.php";
include "../config/uang.php";
define('FPDF_FONTPATH', 'fpdf/font/');
require('fpdf/fpdf.php');

class PDF extends FPDF{


  function Header(){
	$left_margin = 1;
	$this->SetX($left_margin);
	$this->Image('../images/Arofah.jpg',1.5,0.4,1.5);
	$this->SetFont('Arial','B','12');
    $this->Cell(18.5,0.5,'LAPORAN HARIAN',0,0,'C');
	$this->Ln();
	$this->SetX($left_margin);
	$this->SetFont('Arial','B','10');
	$this->SetLineWidth(0.05);
	$this->Cell(0,0.5,'PEMBAYARAN ADM JAMAAH KBIH AN NAMIROH MOJOKERTO','B',0,'C');
	
	$this->Ln();
	//$this->Line(1.5,2.2,18,2.2);
	$this->Ln(); 
	$this->SetLineWidth(0);
	$this->SetX($left_margin);
	$this->SetFillColor(200,200,200);
	//<th
	$col1=1;
	$col2=5.5;
	$col3=1.7;
	$col4=2.4;
	$col5=5.5;
	$col6=1;
	$col7=2;
	$this->SetFont('Arial','B','8');
	$this->Cell($col1,0.6,'No',1,0,'C',1);
	$this->Cell($col2,0.6,'Atas Nama',1,0,'C',1);
	$this->Cell($col3,0.6,'Tanggal',1,0,'C',1);
	$this->Cell($col4,0.6,'Nominal (Rp.)',1,0,'C',1);
	$this->Cell($col5,0.6,'Keterangan',1,0,'C',1);
	$this->Cell($col6,0.6,'Tahun',1,0,'C',1);
	$this->Cell($col7,0.6,'No Porsi',1,0,'C',1);
	//th>
	$this->Ln();
  }
   function Footer(){
   	$left_margin = 1;
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
$left_margin = 1;

$col1=1;
$col2=5.5;
$col3=1.7;
$col4=2.4;
$col5=5.5;
$col6=1;
$col7=2;

$pdf->SetTextColor(000);
$pdf->SetFont('arial','B',8);
$pdf->SetX($left_margin);
$pdf->SetFont('arial','',8);
$pdf->SetFillColor(240,240,240);
$tgl = $_GET['tgl'];
$sql = "SELECT * FROM adm ";
$sql.= "INNER JOIN data_jamaah ON adm.id_jamaah=data_jamaah.id_jamaah ";
$sql.= "INNER JOIN th_berangkat ON data_jamaah.id_th=th_berangkat.id_th ";
$sql.= "WHERE tgl_bayar='".$tgl."' ";
$qr2 = mysql_query($sql."order by tgl_bayar ASC");
		$i = 1;
		$total_nominal = 0;
		while ($row2 = mysql_fetch_array($qr2)){		
			if ($row2['nominal']!=0){
				$nominal = uang($row2['nominal']);
			}
			else{
				$nominal = '';
			}
			if ($i%2 == 0){
				$fill =1;
			}
			else{
				$fill =0;
			}
			
			$len_ket = strlen($row2['keterangan']);
			
			if ($len_ket >= 30){
				$explode = explode(" ",$row2['keterangan']);
				$jmlh_kata = count($explode)-1;
			
				for ($kt=0; $kt<=1; $kt++){
				
					if ($kt == 0){
						$pdf->SetX($left_margin);
						$pdf->Cell($col1,0.6,$i.'.',1,0,'R',$fill);
						$pdf->Cell($col2,0.6,$row2['nama_jamaah'].' ',1,0,'L',$fill);
						$pdf->Cell($col3,0.6,substr($row2['tgl_bayar'],0,10),1,0,'C',$fill);
						$pdf->Cell($col4,0.6,$nominal,1,0,'R',$fill);
						$pdf->Cell($col5,0.6,$explode[0].' '.$explode[1].' '.$explode[2],1,0,'L',$fill);
						$pdf->Cell($col6,0.6,$row2['tahun'],1,0,'C',$fill);
						$pdf->Cell($col7,0.6,$row2['no_porsi'],1,0,'R',$fill);
					}
					else{
						$pdf->SetX($left_margin);
						$pdf->Cell($col1,0.6,'',1,0,'R',$fill);
						$pdf->Cell($col2,0.6,'',1,0,'L',$fill);
						$pdf->Cell($col3,0.6,'',1,0,'C',$fill);
						$pdf->Cell($col4,0.6,'',1,0,'R',$fill);
						$pdf->Cell($col5,0.6,$explode[3].' '.$explode[4].' '.$explode[5].' '.$explode[6].' '.$explode[7].' '.$explode[8],1,0,'L',$fill);
						$pdf->Cell($col6,0.6,'',1,0,'C',$fill);
						$pdf->Cell($col7,0.6,'',1,0,'R',$fill);
					}
					$pdf->Ln();
				}//end for
			}//end if
			else{
				$pdf->SetX($left_margin);
					$pdf->Cell($col1,0.6,$i.'.',1,0,'R',$fill);
					$pdf->Cell($col2,0.6,$row2['nama_jamaah'].' ',1,0,'L',$fill);
					$pdf->Cell($col3,0.6,substr($row2['tgl_bayar'],0,10),1,0,'C',$fill);
					$pdf->Cell($col4,0.6,$nominal,1,0,'R',$fill);
					$pdf->Cell($col5,0.6,$row2['keterangan'],1,0,'L',$fill);
					$pdf->Cell($col6,0.6,$row2['tahun'],1,0,'C',$fill);
					$pdf->Cell($col7,0.6,$row2['no_porsi'],1,0,'R',$fill);
					$pdf->Ln();
			}
		
		$i++;
		$total_nominal = $total_nominal + $row2['nominal'];
		}
			$pdf->SetFillColor(200,200,200);
			$pdf->SetX($left_margin);
			$pdf->SetFont('arial','B',8);
			$pdf->Cell($col1,0.6,'','TLB',0,'R',1);
			$pdf->Cell($col2,0.6,'Jumlah (Rp.)','TB',0,'C',1);
			$pdf->Cell($col3,0.6,'','TB',0,'C',1);
			$pdf->Cell($col4,0.6,uang($total_nominal).' ',1,0,'R',1);
			$pdf->Cell($col5,0.6,'',1,0,'R',1);
			$pdf->Cell($col6,0.6,'',1,0,'C',1);
			$pdf->Cell($col7,0.6,'','TBR',0,'R',1);
		//2
			$pdf->Ln();
			$pdf->Ln();
			$pdf->SetX($left_margin);
			$pdf->SetFont('arial','B',8);
			$pdf->Cell(6,0.6,'',0,0,'R',0);
			$pdf->Cell(5.5,0.6,'',0,0,'C',0);
			$pdf->Cell(6,0.6,'Petugas KBIH An Namiroh',0,0,'C',0);
			
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