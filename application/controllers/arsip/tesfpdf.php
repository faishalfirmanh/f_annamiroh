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
class Tesfpdf extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('fpdf');
    }

    function index()
    {
        $header1 = array('no','nama','alamat','pekerjaan'); 
		
        
		header("Content-Type: application/pdf");
        $header = array('no','nama','alamat','pekerjaan');                
        $this->fpdf->FPDF('L','cm','A5');
        $this->fpdf->Ln();
        $this->fpdf->AddPage();

        $this->fpdf->setFont('Arial','B',17);
        $this->fpdf->Text(10,2,'TITLE');
        $this->fpdf->ln(1);
        $this->fpdf->setFont('Arial','',9);        
        $this->fpdf->write(2.5,'Tampilan Image Dengan FPDF');        
        //$this->fpdf->Image(base_url().'images/logo.png',1,4,0);   
        $this->fpdf->ln(6);
        $this->fpdf->setFont('Arial','',9);        
        $this->fpdf->write(0,'Tampilan Table Dengan FPDF');
        $this->fpdf->ln(1);
        $this->showTable($header);
                
        $this->fpdf->Output();                
    }

    function showTable($header)
    {        
        $this->fpdf->SetFillColor(255,0,0);
        $this->fpdf->SetTextColor(255);
        $this->fpdf->SetDrawColor(128,0,0);
        $this->fpdf->SetLineWidth(0);
        $this->fpdf->SetFont('','B');
                
        $w = array(2,2,2,2);
        for($i=0;$i<count($header);$i++)
            $this->fpdf->Cell($w[$i],1,$header[$i],1,0,'C',true);
        $this->fpdf->Ln();
        
        $this->fpdf->SetFillColor(224,235,255);
        $this->fpdf->SetTextColor(0);
        $this->fpdf->SetFont('');
                
        $fill = false;
        	for ($i=1; $i<=50; $i++){
            $this->fpdf->Cell($w[0],1,'1','LR',0,'C',$fill);
            $this->fpdf->Cell($w[1],1,'Fatur','LR',0,'C',$fill);
            $this->fpdf->Cell($w[2],1,'Jakarta','LR',0,'C',$fill);
            $this->fpdf->Cell($w[3],1,'swasta','LR',0,'C',$fill);
            $this->fpdf->Ln();
            $fill = !$fill;
        
        $this->fpdf->Cell(array_sum($w),0,'','T');
		}
    }

}
?>