<?php
function get_num_name($num){  
            switch($num){  
                case 1:return 'satu';  
                case 2:return 'dua';  
                case 3:return 'tiga';  
               case 4:return 'empat';  
               case 5:return 'lima';  
               case 6:return 'enam';  
               case 7:return 'tujuh';  
               case 8:return 'delapan';  
               case 9:return 'sembilan';  
           }  
       }  
function num_to_words($number, $real_name, $decimal_digit, $decimal_name){  
           $res = '';  
           $real = 0;  
           $decimal = 0;  
     
           if($number == 0)  
               return 'Nol'.(($real_name == '')?'':' '.$real_name);  
           if($number >= 0){  
               $real = floor($number);  
               $decimal = round($number - $real, $decimal_digit);  
           }else{  
               $real = ceil($number) * (-1);  
               $number = abs($number);  
               $decimal = $number - $real;  
           }  
           $decimal = (int)str_replace('.','',$decimal);  
     
           $unit_name[1] = 'ribu';  
           $unit_name[2] = 'juta';  
           $unit_name[3] = 'milliar';  
           $unit_name[4] = 'trilliun';  
     
           $packet = array();  
     
           $number = strrev($real);  
           $packet = str_split($number,3);  
     
           for($i=0;$i<count($packet);$i++){  
               $tmp = strrev($packet[$i]);  
               $unit = isset($unit_name[$i])?$unit_name[$i]:'';  
               if((int)$tmp == 0)  
                   continue;  
               $tmp_res = '';  
               if(strlen($tmp) >= 2){  
                   $tmp_proc = substr($tmp,-2);  
                   switch($tmp_proc){  
                       case '10':  
                           $tmp_res = 'sepuluh';  
                           break;  
                       case '11':  
                           $tmp_res = 'sebelas';  
                           break;  
                       case '12':  
                           $tmp_res = 'dua belas';  
                           break;  
                       case '13':  
                           $tmp_res = 'tiga belas';  
                           break;  
                       case '15':  
                           $tmp_res = 'lima belas';  
                           break;  
                       case '20':  
                           $tmp_res = 'dua puluh';  
                           break;  
                       case '30':  
                           $tmp_res = 'tiga puluh';  
                           break;  
                       case '40':  
                           $tmp_res = 'empat puluh';  
                           break;  
                       case '50':  
                         $tmp_res = 'lima puluh';  
                           break;  
                       case '70':  
                           $tmp_res = 'tujuh puluh';  
                           break;  
                       case '80':  
                           $tmp_res = 'delapan puluh';  
                           break;  
                       default:  
                           $tmp_begin = substr($tmp_proc,0,1);  
                           $tmp_end = substr($tmp_proc,1,1);  
     
                           if($tmp_begin == '1')  
                               $tmp_res = get_num_name($tmp_end).' belas';  
                           elseif($tmp_begin == '0')  
                               $tmp_res = get_num_name($tmp_end);  
                           elseif($tmp_end == '0')  
                               $tmp_res = get_num_name($tmp_begin).' puluh';  
                           else{  
                               if($tmp_begin == '2')  
                                  $tmp_res = 'dua puluh';  
                              elseif($tmp_begin == '3')  
                                  $tmp_res = 'tiga puluh';  
                              elseif($tmp_begin == '4')  
                                  $tmp_res = 'empat puluh';  
                              elseif($tmp_begin == '5')  
                                  $tmp_res = 'lima puluh';  
                              elseif($tmp_begin == '6')  
                                  $tmp_res = 'enam puluh';  
                              elseif($tmp_begin == '7')  
                                  $tmp_res = 'tujuh puluh';  
                              elseif($tmp_begin == '8')  
                                  $tmp_res = 'delapan puluh';  
                              elseif($tmp_begin == '9')  
                                  $tmp_res = 'sembilan puluh';  
    
                              $tmp_res = $tmp_res.' '.get_num_name($tmp_end);  
                          }  
                          break;  
                  }  
    
                  if(strlen($tmp) == 3){  
                          $tmp_begin = substr($tmp,0,1);  
                      $space = '';  
                      if(substr($tmp_res,0,1) != ' ' && $tmp_res != '')  
                          $space = ' ';  
                      if($tmp_begin != 0){  
                          if($tmp_begin == 1)  
                              $tmp_res = 'seratus'.$space.$tmp_res;  
                          else  
                              $tmp_res = get_num_name($tmp_begin).' ratus'.$space.$tmp_res;  
                      }  
                  }  
              }else  
                  $tmp_res = get_num_name($tmp);  
    
              $space = '';  
              if(substr($res,0,1) != ' ' && $res != '')  
                  $space = ' ';  
    
              if($tmp_res == 'satu' && $unit == 'ribu')  
                  $res = 'se'.$unit.$space.$res;  
              else  
                  $res = $tmp_res.' '.$unit.$space.$res;  
          }  
    
          $space = '';  
          if(substr($res,-1) != ' ' && $res != '')  
              $space = ' ';  
          $res .= $space.$real_name;  
    
          if($decimal > 0)  
              $res .= ' '.num_to_words($decimal, '', 0, '').' '.$decimal_name;  
          return ucfirst($res);  
      }  
	  
	  ?>
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 12">
<link rel=File-List href="kwitansi_files/filelist.xml">
<!--[if !mso]>
<style>
v\:* {behavior:url(#default#VML);}
o\:* {behavior:url(#default#VML);}
x\:* {behavior:url(#default#VML);}
.shape {behavior:url(#default#VML);}
</style>
<![endif]-->
<style id="kwitansi_20865_Styles">
<!--table
	{mso-displayed-decimal-separator:"\,";
	mso-displayed-thousand-separator:"\.";}
.xl1520865
	{padding:0px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6320865
	{padding:0px;
	mso-ignore:padding;
	color:blue;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6420865
	{padding:0px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
-->
</style>
</head>

<body>
<!--[if !excel]>&nbsp;&nbsp;<![endif]-->
<!--The following information was generated by Microsoft Office Excel's Publish
as Web Page wizard.-->
<!--If the same item is republished from Excel, all information between the DIV
tags will be replaced.-->
<!----------------------------->
<!--START OF OUTPUT FROM EXCEL PUBLISH AS WEB PAGE WIZARD -->
<!----------------------------->

<div id="kwitansi_20865" align=center x:publishsource="Excel">

<table border=0 cellpadding=0 cellspacing=0 width=612 style='border-collapse:
 collapse;table-layout:fixed;width:460pt'>
 <col width=141 style='mso-width-source:userset;mso-width-alt:5156;width:106pt'>
 <col width=20 style='mso-width-source:userset;mso-width-alt:731;width:15pt'>
 <col width=127 style='mso-width-source:userset;mso-width-alt:4644;width:95pt'>
 <col width=81 span=4 style='width:61pt'>
 <tr height=17 style='height:12.75pt'>
  <td rowspan=5 height=85 width=141 style='height:63.75pt;width:106pt'
  align=left valign=top><![if !vml]><span style='mso-ignore:vglayout;
  position:absolute;z-index:1;margin-left:28px;margin-top:5px;width:88px;
  height:70px'><img width=88 height=70
  src="<?php echo base_url();?>images/kwitansi_20865_image002.gif" v:shapes="Graphics_x0020_1"></span><![endif]><span
  style='mso-ignore:vglayout2'>
  <table cellpadding=0 cellspacing=0>
   <tr>
    <td rowspan=5 height=85 class=xl6420865 width=141 style='height:63.75pt;
    width:106pt'></td>
   </tr>
  </table>
  </span></td>
  <td colspan=3 class=xl6420865 width=228 style='width:171pt'>PT AN NAMIROH
  TRAVELINDO</td>
  <td colspan=2 class=xl6420865 width=162 style='width:122pt'>BUKTI PEMBAYARAN</td>
  <td class=xl1520865 width=81 style='width:61pt'></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td colspan=3 height=17 class=xl6420865 style='height:12.75pt'>Jl.Raya
  Menanggal Timur Polres</td>
  <td colspan=2 class=xl6420865>No :<span style='mso-spacerun:yes'> </span><?php echo $no ; ?></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td colspan=3 height=17 class=xl6420865 style='height:12.75pt'>Mojosari
  Mojokerto</td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td colspan=3 height=17 class=xl6420865 style='height:12.75pt'>0321-595145</td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'>Telah Terima Dari</td>
  <td class=xl1520865>:</td>
  <td colspan=4 class=xl1520865><?php echo $jamaah;?></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'>Paket</td>
  <td class=xl1520865>:<span style='mso-spacerun:yes'> </span></td>
  <td colspan=4 class=xl1520865><?php echo $paket;?></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'>Banyaknya Uang<span
  style='mso-spacerun:yes'>    </span></td>
  <td class=xl1520865>:</td>
  <td colspan=4 class=xl1520865><?php echo $banyaknya;?></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'>Untuk Pembayaran</td>
  <td class=xl1520865>:</td>
  <td colspan=4 class=xl1520865><?php echo $untuk ;?></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td colspan=3 rowspan=2 height=34 class=xl6420865 style='height:25.5pt'>Terbilang:<?php
  echo num_to_words($jumlah, '', 0, '')?><span
  style='mso-spacerun:yes'> </span></td>
  <td class=xl1520865></td>
  <td colspan=3 class=xl1520865>Mojosari, <?php
function tanggal_indo($tanggal){
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);
		
		// variabel pecahkan 0 = tanggal
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tahun

		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}


  echo tanggal_indo($tanggal);?></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865>:</td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td colspan=4 height=17 class=xl6320865 style='height:12.75pt'><a
  href="http://www.namiroh.com/"><span style='color:blue;font-size:10.0pt;
  font-weight:400;font-family:Arial, sans-serif;mso-font-charset:0'></span></a></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td colspan=4 height=17 class=xl1520865 style='height:12.75pt'></td>
  <td colspan=3 class=xl1520865>Penerima:<?php echo $teller;?></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl1520865 style='height:12.75pt'></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
  <td class=xl1520865></td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=141 style='width:106pt'></td>
  <td width=20 style='width:15pt'></td>
  <td width=127 style='width:95pt'></td>
  <td width=81 style='width:61pt'></td>
  <td width=81 style='width:61pt'></td>
  <td width=81 style='width:61pt'></td>
  <td width=81 style='width:61pt'></td>
 </tr>
 <![endif]>
</table>

</div>


<!----------------------------->
<!--END OF OUTPUT FROM EXCEL PUBLISH AS WEB PAGE WIZARD-->
<!----------------------------->
</body>

</html>
