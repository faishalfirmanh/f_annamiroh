<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tes
 *
 * @author Fatur
 */
class Fpdf_buku_onh extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('fpdf');
    }

  
    function index(){
        header("Content-Type: application/pdf");
        $left_margin = 0.4;
		
		$this->fpdf->SetX($left_margin);
		$header = array('no','nama','alamat','pekerjaan');
        
        $this->fpdf->setFont('Arial','B',17);
        $this->fpdf->ln(1);
        $this->showTable($header);      
        
    }



    function showTable($header)
    {        
        //$this->Header($header);
		$this->fpdf->SetY(2.2,2);
		$this->fpdf->FPDF('P','cm','A5');
		$this->fpdf->Open();
		$this->fpdf->AliasNbPages();
		$this->fpdf->AddPage();
		$this->fpdf->SetAutoPageBreak(true,2);
		$this->fpdf->SetY(2.2,2);
		$left_margin = 0.4;
		$h = 0.6;
		$this->fpdf->SetFillColor(255,0,0);
        $this->fpdf->SetTextColor(255);
        $this->fpdf->SetDrawColor(128,0,0);
        $this->fpdf->SetLineWidth(0);
        $this->fpdf->SetFont('arial','',8);
                
        $w = array(1.2,2.4,3,3,2.7,2);
		$this->fpdf->Ln();
        $this->fpdf->SetFont('arial','',8);
		$this->fpdf->SetX($left_margin);
        $this->fpdf->SetFillColor(224,235,255);
        $this->fpdf->SetTextColor(0);
        $this->fpdf->SetFont('');
        //
		$array = $_POST['id'];
		$jm=count($array)-1;
		for ($j=0; $j<=$jm; $j++){
			$sqlU = mysql_query("UPDATE tb_pembayaran SET print='1', print2='1' WHERE id_pembayaran='".(int)$array[$j]."'");
			//$pdf->Cell($col1,0.6,(int)$array[$j],0,0,'L',0);
		}
		//
		$sql = "SELECT * FROM tb_pembayaran ";
//$sql.= "WHERE id_jamaah='".$_POST['id_jamaah']."' ";
		$qr2 = mysql_query($sql."order by tgl_bayar ASC");
		//$i = 1;
		//$rSaldo=$rSaldo;
		while ($row2 = mysql_fetch_array($qr2)){
			//if ($row2['debet']!=0){
			//	$debet = uang($row2['debet']);
			//}
			//else{
			//	$debet = '';
			//}
			
			//if ($row2['nominal']!=0){
				//$kredit = uang($row2['nominal']);
			//}
			//else{
				//$kredit = '';
			//}
			//$rSaldo = ($row2['nominal'] + $rSaldo) - $row2['debet'];
			//if ($row2['print']=='1'){
				$this->fpdf->SetX($left_margin);
				
				$this->fpdf->Cell($w[0],$h,$row2['id_pembayaran'],1,0,'R',0);
				//$this->$pdf->Cell($w[1],$h,substr($row2['tgl_bayar'],1,10),0,0,'C',0);
				//$this->$pdf->Cell($w[2],$h,$debet,1,0,'R',0);
				//$this->$pdf->Cell($w[3],$h,$kredit,1,0,'R',0);
				//$this->$pdf->Cell($w[4],$h,uang($rSaldo),1,0,'R',0);
				//$this->$pdf->Cell($w[5],$h,'',1,0,'R',0);
			//}
			
		//$i++;
		$this->fpdf->Ln();
		}
        
        //$this->fpdf->Cell(array_sum($w),0,'','T');
		$this->fpdf->Output();
    }
	

}

?>