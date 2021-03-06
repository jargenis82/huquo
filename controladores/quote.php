<?php
// INCLUYENDO LIBRERÍAS, CLASES Y ARCHIVOS
include_once '../conf.inc.php';
include_once '../librerias/conexion_bd.php';
include_once '../librerias/tbs_class/tbs_class.php';
include_once '../librerias/xajax_0.2.4/xajax.inc.php';
include_once '../librerias/insightly.php';
include_once '../clases/contact_ins.php';
include_once '../clases/opportunity.php';
include_once '../clases/organization.php';
include_once '../clases/product.php';
include_once '../clases/quote.php';
// include_once '../clases/inst_usua.php';
// include_once '../clases/menu.php';
include_once '../inc/funciones.php';

// SE VERIFICA LA SESIÓN Y ACCESO DEL USUARIO
(session_id() == "") ? session_start () : null;

if (! comprobarVar ( $_SESSION ['user_id'] )) {
	exit ();
}

// DEFINE LAS VARIABLES $_GET, $_POST Y $_SESSION
$opportunityId = comprobarVar ( $_GET ['opportunityId'] ) ? $_GET ['opportunityId'] : null;
$quoteId = (isset($_GET ['quoteId']) and comprobarVar ( $_GET ['quoteId'] )) ? $_GET ['quoteId'] : null;

// XAJAX
$xajax = new xajax ( "../inc/ajax_funciones.php" );
$xajax->registerFunction ( "getCustomer" );
$xajax->registerFunction ( "getDescripProduct" );
$xajax->registerFunction ( "addNewProduct" );
$xajax->registerFunction ( "calculateAmount" );
$xajax->registerFunction ( "calculateDiscount" );
$xajax->registerFunction ( "saveQuote" );
$xajax->registerFunction ( "getContactInfos" );
$js = $xajax->getJavascript ( '../librerias/' );

// Fecha Actual
// Si es una nueva cotización debe ser la fecha del día.
// Si se está consultando una cotización debe ser la fecha original de creación de la cotización
$fecha = date ( "Y-m-d" );

// Fecha de validez
// Si es una nueva cotización debe colocar por defecto 30 días más de la fecha de creación
// Si se está consultando una cotización deber la fecha de validez colocada durante la creación de la cotización
$myDateTime = DateTime::createFromFormat ( "Y-m-d", $fecha );
$myDateTime->setTimestamp ( $myDateTime->getTimestamp () + 30 * 24 * 60 * 60 );
$quoteValidUntil = $myDateTime->format ( "d-M-Y" );

// Ajuste de formato de fechas a d-M-Y (Ej: 24/Jan/2017)
$myDateTime = DateTime::createFromFormat ( "Y-m-d", $fecha );
$fecha = $myDateTime->format ( "d-M-Y" );

// Quote number
$miConexionBd = new ConexionBd ( "mysql" );
$quoteNumber = (new Quote ( $miConexionBd ))->getNextQuoteNumber ();

// Prepared by
$userName = $_SESSION ['user_name'];

// Se construye el arreglo en Javascript para el autocompletar de productos
$myProduct = new Product ( $miConexionBd );
$listaProducto = $myProduct->getListaProducto ();
$jsData = "";
$jsDataId = "";
$contador = 0;
foreach ( $listaProducto as $ii => $unProducto ) {
	$unProducto = str_replace ( '"', '\"', $unProducto );
	$jsData .= '"' . $unProducto . '",';
	$jsDataId .= '"' . $ii . '",';
	$contador ++;
}
$jsData = $jsData != "" ? substr ( $jsData, 0, - 1 ) : "";
$jsDataId = $jsDataId != "" ? substr ( $jsDataId, 0, - 1 ) : "";

// Se construye el arreglo en Javascript para el autocompletar de contactos
$jsDataContact = "";
$jsDataContactId = "";

// Se inicializan algunas variables a mostrar por pantalla
$customerRegionId = "";
$region = "";
$priceTypeId = "";

// Se cargan todos los datos asociados a la oportunidad y a la organizacion de la oportunidad
if (comprobarVar ( $opportunityId )) {
	// Se consulta una copia local de customers para mejorar el desempeño en máquinas de desarrollo (provisional)
	if (defined ( "CUSTOMERS" )) {
		$quote = file_get_contents ( "../log/quote" );
		$quote = explode ( ";", $quote );
		$organizationId = $quote [0];
		$organizationName = $quote [1];
		$address = $quote [2];
		$web = $quote [3];
		$phone = $quote [4];
		$country = $quote [5];
		$customerType = $quote [6];
		$city = $quote [7];
	} else {
		// Se consulta en el API de Insightly los datos de la oportunidad
		// $i = new Insightly ( APIKEY ); // Se instanció para la carga de los contactos
		// $myOpportunity = $i->getOpportunity ( $opportunityId );
		$myOpportunity = new Opportunity();
		$myOpportunity->setAtributo("recordid",$opportunityId);
		$arrOpportunity = $myOpportunity->consultar();
		$country = "";
		$city = "";
		$customerType = "";
		// Se consulta los links de la oportunidad para buscar los datos de la organización relacionada
		// $arrLinks = $myOpportunity->LINKS;
		// foreach ( $arrLinks as $aLink ) {
			// $organizationId = $aLink->ORGANISATION_ID;
		$organizationId = (isset($arrOpportunity[0])) ? $arrOpportunity[0]->getAtributo("organizationid") : null;
		if (comprobarVar ( $organizationId )) {
			// $myOrganization = $i->getOrganization ( $organizationId );
			$myOrganization = new Organization();
			$myOrganization->setAtributo("recordid",$organizationId);
			$arrMyOrganization = $myOrganization->consultar();
			if (count($arrMyOrganization) == 1) {
				$myOrganization = $arrMyOrganization[0];
				// $organizationName = $myOrganization->ORGANISATION_NAME;
				$organizationName = $myOrganization->getAtributo("organizationname");
				// $arrAddresses = $myOrganization->ADDRESSES;

				$address = "";
				$address1 = "";
				$address2 = "";
				$addChk1 = "";
				$addChk2 = "";
				$shipTo = "";
				// if (isset ( $arrAddresses [0] )) {
				// 	$addressTemp = $arrAddresses [0]->STREET . ", " . $arrAddresses [0]->CITY . ", " . $arrAddresses [0]->COUNTRY . ".";
				// 	$addressTemp = str_replace ( "\n", " ", $addressTemp );
				// 	$addressTemp = str_replace ( "\r", " ", $addressTemp );
				// 	$country = $arrAddresses [0]->COUNTRY;
				// 	$city = $arrAddresses [0]->CITY;
				// 	if (trim ( $addressTemp ) != "") {
				// 		if (strcmp ( trim ( $arrAddresses [0]->ADDRESS_TYPE ), 'POSTAL' ) == 0) {
				// 			$address1 = $addressTemp;
				// 		} else if (strcmp ( trim ( $arrAddresses [0]->ADDRESS_TYPE ), 'PRIMARY' ) == 0) {
				// 			$address2 = $addressTemp;
				// 		}
				// 	}
				// }
				// if (isset ( $arrAddresses [1] )) {
				// 	$addressTemp = $arrAddresses [1]->STREET . ", " . $arrAddresses [1]->CITY . ", " . $arrAddresses [1]->COUNTRY . ".";
				// 	$addressTemp = str_replace ( "\n", " ", $addressTemp );
				// 	$addressTemp = str_replace ( "\r", " ", $addressTemp );
				// 	if (trim ( $addressTemp ) != "") {
				// 		if (strcmp ( trim ( $arrAddresses [1]->ADDRESS_TYPE ), 'POSTAL' ) == 0) {
				// 			$address1 = $addressTemp;
				// 		} else if (strcmp ( trim ( $arrAddresses [1]->ADDRESS_TYPE ), 'PRIMARY' ) == 0) {
				// 			$address2 = $addressTemp;
				// 		}
				// 	}
				// }
				$address1 = $myOrganization->getAtributo("billingaddressstreet");
				$address1 .= ", ".$myOrganization->getAtributo("billingaddresscity");
				$address1 .= ", ".$myOrganization->getAtributo("billingaddresscountry");
				$address1 = str_replace ( "\n", " ", $address1 );
				$address1 = str_replace ( "\r", " ", $address1 );
				$address2 = $myOrganization->getAtributo("shippingaddressstreet");
				$address2 .= ", ".$myOrganization->getAtributo("shippingaddresscity");
				$address2 .= ", ".$myOrganization->getAtributo("shippingaddresscountry");
				$address2 = str_replace ( "\n", " ", $address2 );
				$address2 = str_replace ( "\r", " ", $address2 );
				if (trim ( $address2 ) != "") {
					$addChk2 = 'checked="checked"';
					$shipTo = $address2;
				} else {
					$addChk1 = 'checked="checked"';
					$shipTo = $address1;
				}
				if (trim ( $address1 ) != "") {
					$address = $address1;
				} else {
					$address = $address2;
				}
				
				// $arrContactInfos = $myOrganization->CONTACTINFOS;
				// $contadorW = 0;
				// $contadorP = 0;
				// foreach ( $arrContactInfos as $myContactInfos ) {
				// 	if ($myContactInfos->TYPE == "WEBSITE" and $contadorW == 0) {
				// 		$web = $myContactInfos->DETAIL;
				// 		$contadorW ++;
				// 	}
				// 	if ($myContactInfos->TYPE == "PHONE" and $contadorP == 0) {
				// 		$phone = $myContactInfos->DETAIL;
				// 		$contadorP ++;
				// 	}
				// 	if (($contadorP + $contadorW) == 2) {
				// 		break 1;
				// 	}
				// }
				$web = $myOrganization->getAtributo("website");
				$phone = $myOrganization->getAtributo("phone");

				// $arrCustomfields = $myOrganization->CUSTOMFIELDS;
				// foreach ( $arrCustomfields as $myCustomfield ) {
				// 	$customFieldId = $myCustomfield->CUSTOM_FIELD_ID;
				// 	if ($customFieldId == "ORGANISATION_FIELD_1") {
				// 		$customerType = $myCustomfield->FIELD_VALUE;
				// 		break 1;
				// 	}
				// }
				$customerType = $myOrganization->getAtributo("type");

				// $arrOrganizationLinks = $myOrganization->LINKS;
				$myContactIns = new ContactIns();
				$myContactIns->setAtributo("organizationrecordid",$organizationId);
				$arrContactIns = $myContactIns->consultar();
				$arrDataContact = array ();
				// foreach ( $arrOrganizationLinks as $aOrganizationLink ) {
				// 	$contactId = $aOrganizationLink->CONTACT_ID;
				// 	if (comprobarVar ( $contactId )) {
				// 		$myContact = $i->getContact ( $contactId );
				// 		$arrDataContact [$contactId] = $myContact->FIRST_NAME . " " . $myContact->LAST_NAME;
				// 	}
				// }
				foreach ($arrContactIns as $aContactIns) {
					$contactId = $aContactIns->getAtributo("recordid");
					$arrDataContact [$contactId] = $aContactIns->getAtributo("firstname") . " " . $aContactIns->getAtributo("lastname");
				}
				asort ( $arrDataContact );
				foreach ( $arrDataContact as $jj => $aDataContact ) {
					$jsDataContactId .= '"' . $jj . '",';
					$jsDataContact .= '"' . $aDataContact . '",';
				}
				$jsDataContact = $jsData != "" ? substr ( $jsDataContact, 0, - 1 ) : "";
				$jsDataContactId = $jsDataContactId != "" ? substr ( $jsDataContactId, 0, - 1 ) : "";
				// break 1;
			}
		}
		// }
	}
	// Se detecta el tipo de precio a aplicar en la cotización según el tipo de cliente
	if (comprobarVar ( $customerType )) {
		if ($customerType == "Customer") {
			$priceTypeId = "2";
		} else {
			$priceTypeId = "1";
		}
	}
	
	// Check the contry value
	if (comprobarVar ( $country )) {
		// Check the region of the customer
		if ($country == "United States" or $country == "Canada") {
			$customerRegionId = 2;
			$region = "US & Canada";
		} else {
			$customerRegionId = 1;
			$region = "Outside US & Canada";
		}
		// Check the use of canadian dollar
		if ($country == "Canada") {
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, "https://openexchangerates.org/api/latest.json?app_id=f683795877b449fc8b9aab01153e93f3&symbols=CAD" );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
			$json = json_decode ( curl_exec ( $ch ), true );
			curl_close ( $ch );
			$exchangeRate = $json ['rates'] ['CAD'];
			$displayExchangeRate = "";
			$currency = "CA$";
		} else {
			$exchangeRate = "1";
			$displayExchangeRate = "display: none;";
			$currency = "US$";
		}
	}
}

// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong ();
$TBS->LoadTemplate ( '../paginas/quote.tpl' );
$TBS->Show ();
?>