<?php

include_once ("../../librerias/mpdf53/mpdf.php");
$logo="hubrox1.png";
$quote="QUOTE";
//variable de secion de usuario
$prepared="Annie Wang";

//VARIABLE VIA GET

$date="01/01/2017";
$quoteNumber="20170220001";
$customerId="73793570";
$valiUntil="23/03/2017";
$customerInfor="José Darío Montoya Corral
				soporte@conaris.com.co
				Conaris
				Callejon las palmas C11, Juanchito, Candelaria, 
				Valle del cauca, Colombia
				http://www.aristizabalconstructores.com/
				+57 2 4359582
				";
$shipTo="Callejon las palmas C11, Juanchito, Candelaria, 
		Valle del cauca, Colombia";
$descritionProd="";
$unitPrice="";
$qty="";
$amount="";
$discountVal="";
$sunTotal="";
$hstRate="";
$hstRate="";
$total="";
$cabecera = '
<table align="left" border="0" width="100%">
	<tr>
		<td width="300px" align="left">	<img src="../../imagenes/'.$logo.'" width="120px"/>	</td>
		<td align= "center" colspan="2"><h1 color="#58ACFA">'.$quote.' </h1></td>
		
	</tr>
	<tr>
		<td><h5>370 Magnetic Dr. North York, ON M3J 2C4, Toronto, Canada</h5></td>
		<td><label>DATE</label></td>
		<td align= "center"><h4>'.$date.' </h4></td>
	</tr>
	<tr>
		<td><h5>Website: www.hubrox.com</h5></td>
		<td><label>	QUOTE # </label></td>
		<td align= "center"><h4>'.$quoteNumber.' </h4></td>
	</tr>
	<tr>
		<td><h5>Phone: + 1-647-499-5741</h5></td>
		<td><label>	CUSTOMER ID  </label></td>
		<td align= "center"><h4>'.$customerId.' </h4></td>
	</tr>
	<tr>
		<td><h5>Prepared by: '.$prepared.'</h5></td>
		<td><label>	VALID UNTIL   </label></td>
		<td align= "center"><h4>'.$valiUntil.' </h4></td>
	</tr>

</table> ';

$html = '<style>
th {
    background-color: #58ACFA;
    color: white;
}
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    padding: 4px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

tr:hover{background-color:#f5f5f5}

</style>
<br>
<table align=left  border="0" width="100%">
		<tr>
		<th align= "left"><label>	CUSTOMER  </label></td>
		<th align= "left"><label>	SHIP TO </label></td>
		</tr>
		<tr>
		<td>'.$customerInfor.'</td>
		<td>'.$shipTo.'</td>
		</tr>		
</table>
<br>

<table align=left  border="0" width="100%" >
		<tr>
		<th align= "left" width="400px"><label>DESCRIPTION</label></td>
		<th align= "left" width="50px"><label>UNIT PRICE</label></td>
		<th align= "left" width="95px"><label>QTY</label></td>
		<th align= "left" width="100px" ><label>AMOUNT (US$)</label></td>
		</tr>
		<tr>
		<td>'.$descritionProd.'A</td>
		<td>'.$unitPrice.'10</td>
		<td>'.$qty.'5</td>
		<td>'.$amount.'50</td>
		</tr>
		<tr>
		<td colspan="2">
			<table align=left  border="0" width="600px">
				<tr>
				<th align= "left"><label>TERMS AND CONDITIONS </label></td>
				
				</tr>
				<tr>
					<td><ol>
						  <li> Customer will be billed after indicating acceptance of this quote</li>
						  <li> Payment will be due prior to delivery of service and goods</li>
						  <li> CIF-Miami. Freight to Miami is included in this price. </li>
						  <li>Please fax or mail the signed price quote to the address above</li>
						</ol>

					</td>
				</tr>
				<tr>
					<td>
					<p>Customer Acceptance (sign below):</p>
					<label>Print Name_______________ </label>
					</td>
				</tr>		
		</table>
		</td>
		
		<td colspan="2">
			<table  border="0">
		<tr>
			<td width="50px" ><label>	Discount</label></td>
			<td width="100px">'.$discountVal.'1</td>
		</tr>
			<tr>
			<td><label>	SubTotal(US$)</label></td>
			<td>'.$sunTotal.'2</td>
		</tr>	
			<tr>
			<td><label>HST Rate</label></td>
			<td>'.$hstRate.'3</td>
		</tr>	
		</tr>	
			<tr>
			<td><label>HST (US$)</label></td>
			<td>'.$hstUst.'4</td>
		</tr>	
		</tr>	
			<tr>
			<td><label>TOTAL(US$)</label></td>
			<td>'.$total.'10</td>
		</tr>	
		</table>
		</td>
		</tr>

</table>
<br>';



$header = array('L' => array(), 'C' => array(), 'R' => array(
		'content'         => '{PAGENO}28',
		'font-family'     => 'sans',
		'font-style'      => '',
		'font-size'       => '9', ), 'line'       => 1, );


$pagina    = '{PAGENO}/{nb}';
$piePagina = '<div align="center" style="font-size:16px;color:#666666;"> If you have any questions about this price quote, please contact <strong>'.$prepared.'</strong></div>
			  <div align="center" style="font-size:25px;color:#666666;"> <strong>Thank You For Your Business!</strong></div>
			  <div align="center" style="font-size:9px;color:#666666;"></div>';
$piePagina .= '<table width=100% style="font-size:9px;color:#666666;"><tr><td align="left">'.$x.'</td>
			  <td align="right">'.$pagina.'</td></tr></table>';
//==============================================================
//==============================================================
//==============================================================
//mode,format,default_font_size,default_font,margin_left 15,margin_right 15,margin_top 16,
//margin_bottom 16,margin_header 9,margin_footer 9,orientation P o L,
$mpdf = new mPDF('c', 'Letter', 10, null, 10, 10, 60, 18, 9, 5);
$mpdf->SetHTMLHeader($cabecera);

//zoom 'fullpage,fullwidth,real,default o un entero representando el porcentaje',
//layout 'single,continuous,two,twoleft,tworight,default'
$mpdf->SetDisplayMode('fullpage', 'single');

$paginas = '{PAGENO}';
$mpdf->SetHTMLFooter($piePagina);

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>
