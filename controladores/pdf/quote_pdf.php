<?php
include_once '../../conf.inc.php';
include_once '../../inc/funciones.php';
include_once '../../librerias/conexion_bd.php';
include_once '../../librerias/mpdf53/mpdf.php';
include_once '../../librerias/tbs_class/tbs_class.php';
include_once '../../clases/organisation.php';
include_once '../../clases/quote.php';
// include_once '../../clases/quote_line.php';

$quoteId = comprobarVar ( $_GET ['quoteId'] ) ? limpiarPalabra ( aceptarComilla ( $_GET ['quoteId'] ) ) : null;
$pdf = comprobarVar ( $_GET ['pdf'] ) ? limpiarPalabra ( aceptarComilla ( $_GET ['pdf'] ) ) : null;

// Valida si existe el quote
if ((comprobarVar ( $quoteId ) and ! comprobarVar ( $_SESSION ['user_id'] )) or (! comprobarVar ( $quoteId ) and ! comprobarVar ( $pdf ))) {
	exit ();
}

$miConexionBd = new ConexionBd ( "mysql" );
$myQuote = new Quote ( $miConexionBd );

if (comprobarVar ( $quoteId )) {
	$myQuote->setAtributo ( "quote_id", $quoteId );
} else {
	$myQuote->setAtributo ( "quote_hash", $pdf );
	$arrQuote = $myQuote->consultar();
	if (count($arrQuote) == 1) {
		$myQuote = $arrQuote[0];
	} else {
		exit;
	}
}
$quoteId = $myQuote->getAtributo ( "quote_id" );

$myOrganisation = $myQuote->getObjeto ( "Organisation" );
$myContact = $myQuote->getObjeto ( "Contact" );
$myUser = $myQuote->getObjeto ( "User" );
$quote = "QUOTE";
$logo = "hubrox1.png";
$myDateTime = DateTime::createFromFormat ( "Y-m-d H:i:s", $myQuote->getAtributo ( "quote_date" ) );
$date = $myDateTime->format ( "d-M-Y" );
$quoteNumber = $myQuote->getAtributo ( "quote_number" );
$customerId = $myOrganisation->getAtributo ( "org_ins_id" );
$myDateTime = DateTime::createFromFormat ( "Y-m-d H:i:s", $myQuote->getAtributo ( "quote_valid_until" ) );
$validUntil = $myDateTime->format ( "d-M-Y" );
$prepared = $myUser->getAtributo ( "user_name" );
$customerInfor = $myContact->getAtributo ( "contact_name" ) . "<br>";
$customerInfor .= $myContact->getAtributo ( "contact_email" ) . "<br>";
$customerInfor .= $myOrganisation->getAtributo ( "org_name" ) . "<br>";
$customerInfor .= $myOrganisation->getAtributo ( "org_address" ) . "<br>";
$customerInfor .= $myOrganisation->getAtributo ( "org_web" ) . "<br>";
$customerInfor .= $myOrganisation->getAtributo ( "org_phone" ) . "<br>";
$customerInfor .= $myOrganisation->getAtributo ( "org_city" ) . ", " . $myOrganisation->getAtributo ( "org_country" );
$shipTo = $myQuote->getAtributo ( "quote_ship_to" );
$discountVal = doubleval ( $myQuote->getAtributo ( "quote_discount" ) );
$hstRate = doubleval ( $myQuote->getAtributo ( "quote_hst_rate" ) );
$hstUst = $hstRate / 100;
if ($hstRate == doubleval ( intval ( $hstRate ) )) {
	$hstRate = intval ( $hstRate );
} else {
	$hstRate = number_format ( $hstRate, 2, ",", "." );
}
$hstRate .= "%";
$sunTotal = doubleval ( 0 );
$total = doubleval ( 0 );
$myQuoteLine = new QuoteLine ( $miConexionBd );
$myQuoteLine->setObjeto ( "Quote", $quoteId );
$products = array ();
$arrQuoteLine = $myQuoteLine->consultar ();
foreach ( $arrQuoteLine as $i => $aQuoteLine ) {
	$desc = $aQuoteLine->getAtributo ( "quote_line_desc" );
	$price = doubleval ( $aQuoteLine->getAtributo ( "quote_line_price" ) );
	$qty = $aQuoteLine->getAtributo ( "quote_line_qty" );
	$amount = $price * $qty;
	$sunTotal += $amount;
	$products [$i] = array ();
	$products [$i] ['desc'] = $desc;
	$products [$i] ['qty'] = $qty;
	$products [$i] ['qty'] = $qty;
	$price = number_format ( $price, 2, ".", "," );
	$products [$i] ['price'] = $price;
	$amount = number_format ( $amount, 2, ".", "," );
	$products [$i] ['amount'] = $amount;
}

$sunTotal = $sunTotal - $discountVal;
$hstUst = $sunTotal * $hstUst;
$total = $sunTotal + $hstUst;

$discountVal = number_format ( $discountVal, 2, ".", "," );
$sunTotal = number_format ( $sunTotal, 2, ".", "," );
$hstUst = number_format ( $hstUst, 2, ".", "," );
$total = number_format ( $total, 2, ".", "," );

$TBS1 = new clsTinyButStrong ();
$TBS1->LoadTemplate ( '../../paginas/quote_pdf_cabecera.tpl' );
$TBS1->Show ( TBS_NOTHING ); // terminate the merging without leaving the script nor to display the result
$cabecera = $TBS1->Source;

$TBS2 = new clsTinyButStrong ();
$TBS2->LoadTemplate ( '../../paginas/quote_pdf_html.tpl' );
$TBS2->MergeBlock ( 'products', $products );
$TBS2->Show ( TBS_NOTHING ); // terminate the merging without leaving the script nor to display the result
$html = $TBS2->Source;

$TBS3 = new clsTinyButStrong ();
$TBS3->LoadTemplate ( '../../paginas/quote_pdf_pie.tpl' );
$TBS3->Show ( TBS_NOTHING ); // terminate the merging without leaving the script nor to display the result
$piePagina = $TBS3->Source;

// mode,format,default_font_size,default_font,margin_left 15,margin_right 15,margin_top 16,
// margin_bottom 16,margin_header 9,margin_footer 9,orientation P o L,
$mpdf = new mPDF ( 'c', 'Letter', 10, null, 10, 10, 60, 18, 9, 5 );
$mpdf->SetHTMLHeader ( $cabecera );
// zoom 'fullpage,fullwidth,real,default o un entero representando el porcentaje',
// layout 'single,continuous,two,twoleft,tworight,default'
$mpdf->SetDisplayMode ( 'fullpage', 'single' );
$mpdf->SetHTMLFooter ( $piePagina );
$mpdf->WriteHTML ( $html );
$mpdf->Output ();
?>