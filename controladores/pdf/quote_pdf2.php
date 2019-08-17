<?php
include_once '../../conf.inc.php';
include_once '../../inc/funciones.php';
include_once '../../librerias/conexion_bd.php';
include_once '../../librerias/tbs_class/tbs_class.php';
include_once '../../clases/organisation.php';
include_once '../../clases/quote.php';

// Require composer autoload
require_once '../../vendor/autoload.php';

// Se capturan el id de la cotización y el hash de ubicación de la cotización
$quoteId = comprobarVar($_GET['quoteId']) ? limpiarPalabra(aceptarComilla($_GET['quoteId'])) : null;
$pdf = comprobarVar($_GET['pdf']) ? limpiarPalabra(aceptarComilla($_GET['pdf'])) : null;
$format = comprobarVar($_GET['format']) ? limpiarPalabra(aceptarComilla($_GET['format'])) : 2;
$currency = comprobarVar($_GET['currency']) ? limpiarPalabra(aceptarComilla($_GET['currency'])) : 2;

if (isset($format) and $format == 3) {
	$format = "PROFORMA INVOICE";
} else {
	$format = "INVOICE";
}

if (isset($currency) and $currency == 2) {
	$currency = "EUR€";
} else {
	$currency = "US$";
}



// Valida si existe el id de la cotización y el hash de búsqueda de la cotización
if ((comprobarVar($quoteId) and !comprobarVar($_SESSION['user_id'])) or (!comprobarVar($quoteId) and !comprobarVar($pdf))) {
	exit();
}

// Se genera la instancia de conexión con la BD
$miConexionBd = new ConexionBd("mysql");

// Se genera la instancia con Quote
$myQuote = new Quote($miConexionBd);
if (comprobarVar($quoteId)) {
	$myQuote->setAtributo("quote_id", $quoteId);
} else {
	$myQuote->setAtributo("quote_hash", $pdf);
	$arrQuote = $myQuote->consultar();
	if (count($arrQuote) == 1) {
		$myQuote = $arrQuote[0];
	} else {
		exit();
	}
}

// Se capturan los valores a mostrar en el PDF
$quoteId = $myQuote->getAtributo("quote_id");
$myOrganisation = $myQuote->getObjeto("Organisation");
$myContact = $myQuote->getObjeto("Contact");
$myUser = $myQuote->getObjeto("User");
// $quote = "QUOTE";
// $logo = "hubrox1.png";
$myDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $myQuote->getAtributo("quote_date"));
$date = $myDateTime->format("M d Y");
$quoteNumber = $myQuote->getAtributo("quote_number");
// $customerId = $myOrganisation->getAtributo("org_ins_id");
// $myDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $myQuote->getAtributo("quote_valid_until"));
// $validUntil = $myDateTime->format("d-M-Y");
$prepared = $myUser->getAtributo("user_name");
// $userEmail = $myUser->getAtributo("user_email");
// $userSkype = $myUser->getAtributo("user_skype");
// $userSkype = comprobarVar($userSkype) ? "Skype: $userSkype, " : "";
$contactName = $myContact->getAtributo("contact_name");
$orgName = $myOrganisation->getAtributo("org_name");
$orgAddress = $myOrganisation->getAtributo("org_address");
$orgCity = $myOrganisation->getAtributo("org_city");
$orgCountry = $myOrganisation->getAtributo("org_country");
$orgPhone = $myOrganisation->getAtributo("org_phone");
$orgEmail = $myContact->getAtributo("contact_email");
$orgWeb = $myOrganisation->getAtributo("org_web");
$shipTo = $myQuote->getAtributo("quote_ship_to");


// $customerInfor = $myContact->getAtributo("contact_name") . "<br>";
// $customerInfor .= $myContact->getAtributo("contact_email") . "<br>";
// $customerInfor .= $myOrganisation->getAtributo("org_name") . "<br>";
// $customerInfor .= $myOrganisation->getAtributo("org_address") . "<br>";
// $customerInfor .= $myOrganisation->getAtributo("org_web") . "<br>";
// $customerInfor .= $myOrganisation->getAtributo("org_phone") . "<br>";
// $customerInfor .= $myOrganisation->getAtributo("org_city") . ", " . $myOrganisation->getAtributo("org_country");
// // Check the use of canadian dollar
// if ($myOrganisation->getAtributo("org_country") == "Canada") {
// 	$currency = "CA$";
// } else {
// 	$currency = "US$";
// }
// $shipTo = $myQuote->getAtributo("quote_ship_to");
// $quoteComment = $myQuote->getAtributo("quote_comment");
// // Delete this code when the system is in production state
// if (!comprobarVar($quoteComment)) {
// 	$quoteComment = "1. Customer will be billed after indicating acceptance of this quote.
// 2. Payment will be due prior to delivery of service and goods.
// 3. Please fax or mail the signed price quote to the address above.
// 4. Customers are responsible for import duties and brokerage fees if applied during the shipment.";
// }
$discountVal = doubleval($myQuote->getAtributo("quote_discount"));
$hstRate = doubleval($myQuote->getAtributo("quote_hst_rate"));
$hstUst = $hstRate / 100;
if ($hstRate == doubleval(intval($hstRate))) {
	$hstRate = intval($hstRate);
} else {
	$hstRate = number_format($hstRate, 2, ",", ".");
}
$hstRate .= "%";
$sunTotal = doubleval(0);
$total = doubleval(0);
$myQuoteLine = new QuoteLine($miConexionBd);
$myQuoteLine->setObjeto("Quote", $quoteId);
$products = array();
$arrQuoteLine = $myQuoteLine->consultar();
$line_color = 255;
foreach ($arrQuoteLine as $i => $aQuoteLine) {
	$desc = $aQuoteLine->getAtributo("quote_line_desc");
	$price = doubleval($aQuoteLine->getAtributo("quote_line_price"));
	$qty = $aQuoteLine->getAtributo("quote_line_qty");
	$amount = $price * $qty;
	$sunTotal += $amount;
	$products[$i] = array();
	$products[$i]['desc'] = $desc;
	$products[$i]['qty'] = $qty;
	$products[$i]['qty'] = $qty;
	$price = number_format($price, 2, ".", ",");
	$products[$i]['price'] = $price;
	$amount = number_format($amount, 2, ".", ",");
	$products[$i]['amount'] = $amount;
	$products[$i]['line_color'] = $line_color;
	$line_color = $line_color == 255 ? 243 : 255;
}

$sunTotal = $sunTotal - $discountVal;
$hstUst = $sunTotal * $hstUst;
$total = $sunTotal + $hstUst;

$discountVal = number_format($discountVal, 2, ".", ",");
$sunTotal = number_format($sunTotal, 2, ".", ",");
$hstUst = number_format($hstUst, 2, ".", ",");
$total = number_format($total, 2, ".", ",");

// Variables de configuración del MPDF
$config['mode'] = 'utf-8';
$config['format'] = 'Letter';
$config['orientation'] = 'P';
$config['margin_top'] = '18.7';
$config['margin_left'] = '15.9';
$config['margin_right'] = '30.3';
$config['margin_bottom'] = '4.9';

// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf($config);

// Write some HTML code:
$TBS = new clsTinyButStrong();
$TBS->LoadTemplate('../../paginas/quote_pdf_body.tpl');
$TBS->MergeBlock('products', $products);
$TBS->Show(TBS_NOTHING); // terminate the merging without leaving the script nor to display the result
$html = $TBS->Source;
//$mpdf->WriteHTML(file_get_contents('../../paginas/quote_pdf_body.tpl'));
$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
$mpdf->Output();
exit;
