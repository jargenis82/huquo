<?php
// INCLUYENDO LIBRERÍAS, CLASES Y ARCHIVOS
include_once '../conf.inc.php';
include_once '../librerias/conexion_bd.php';
include_once '../librerias/tbs_class/tbs_class.php';
include_once '../librerias/xajax_0.2.4/xajax.inc.php';
include_once '../librerias/insightly.php';
include_once '../clases/product.php';
include_once '../clases/quote.php';
// include_once '../clases/inst_usua.php';
// include_once '../clases/menu.php';
include_once '../inc/funciones.php';

// SE VERIFICA LA SESIÓN Y ACCESO DEL USUARIO
// $miConexionBd = new ConexionBd();
// $miInstUsua = new InstUsua($miConexionBd);
// validarAcceso($miInstUsua);

// DEFINE LAS VARIABLES $_GET, $_POST Y $_SESSION
session_start ();
// isset($_SESSION['instUsuaId']) ? $instUsuaId = $_SESSION['instUsuaId'] : $instUsuaId = null;
$opportunityId = comprobarVar ( $_GET ['opportunityId'] ) ? $_GET ['opportunityId'] : null;
$quoteId = comprobarVar ( $_GET ['quoteId'] ) ? $_GET ['quoteId'] : null;

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

// Si quote_id tiene valor se está consultando una Cotización. Si es NULL se está cargando una nueva cotización
if (comprobarVar ( $quoteId )) {
/**
 * // Se consulta en el API de Insightly la lista de organizaciones
 * $i = new Insightly ( APIKEY );
 * $options ['top'] = defined ( "TOP_LIMIT" ) ? TOP_LIMIT : null; // Limite de consulta para mejorar el desempeño en máquinas de consulta (Provisional)
 * $arrOrganization = $i->getOrganizations ( $options );
 * foreach ( $arrOrganization as $j => $myOrganization ) {
 * $customers .= ',"' . $myOrganization->ORGANISATION_NAME . '"';
 * }
 * $customers = substr ( $customers, 1 );
 */
} else {
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
			$i = new Insightly ( APIKEY ); // Se instanció para la carga de los contactos
			$myOpportunity = $i->getOpportunity ( $opportunityId );
			$country = "";
			$city = "";
			$customerType = "";
			$priceTypeId = "";
			// Se consulta los links de la oportunidad para buscar los datos de la organización relacionada
			$arrLinks = $myOpportunity->LINKS;
			foreach ( $arrLinks as $aLink ) {
				$organizationId = $aLink->ORGANISATION_ID;
				if (comprobarVar ( $organizationId )) {
					$myOrganization = $i->getOrganization ( $organizationId );
					$organizationName = $myOrganization->ORGANISATION_NAME;
					$arrAddresses = $myOrganization->ADDRESSES;
					if (isset ( $arrAddresses [0] )) {
						$address = $arrAddresses [0]->STREET . ", " . $arrAddresses [0]->CITY . ", " . $arrAddresses [0]->COUNTRY . ".";
						$address = str_replace ( "\n", " ", $address );
						$address = str_replace ( "\r", " ", $address );
						$country = $arrAddresses [0]->COUNTRY;
						$city = $arrAddresses [0]->CITY;
					}
					$arrContactInfos = $myOrganization->CONTACTINFOS;
					$contadorW = 0;
					$contadorP = 0;
					foreach ( $arrContactInfos as $myContactInfos ) {
						if ($myContactInfos->TYPE == "WEBSITE" and $contadorW == 0) {
							$web = $myContactInfos->DETAIL;
							$contadorW ++;
						}
						if ($myContactInfos->TYPE == "PHONE" and $contadorP == 0) {
							$phone = $myContactInfos->DETAIL;
							$contadorP ++;
						}
						if (($contadorP + $contadorW) == 2) {
							break 1;
						}
					}
					$arrCustomfields = $myOrganization->CUSTOMFIELDS;
					foreach ( $arrCustomfields as $myCustomfield ) {
						$customFieldId = $myCustomfield->CUSTOM_FIELD_ID;
						if ($customFieldId == "ORGANISATION_FIELD_1") {
							$customerType = $myCustomfield->FIELD_VALUE;
							break 1;
						}
					}
					$arrOrganizationLinks = $myOrganization->LINKS;
					$arrDataContact = array ();
					foreach ( $arrOrganizationLinks as $aOrganizationLink ) {
						$contactId = $aOrganizationLink->CONTACT_ID;
						if (comprobarVar ( $contactId )) {
							$myContact = $i->getContact ( $contactId );
							$arrDataContact [$contactId] = $myContact->FIRST_NAME . " " . $myContact->LAST_NAME;
						}
					}
					asort ( $arrDataContact );
					foreach ( $arrDataContact as $jj => $aDataContact ) {
						$jsDataContactId .= '"' . $jj . '",';
						$jsDataContact .= '"' . $aDataContact . '",';
					}
					$jsDataContact = $jsData != "" ? substr ( $jsDataContact, 0, - 1 ) : "";
					$jsDataContactId = $jsDataContactId != "" ? substr ( $jsDataContactId, 0, - 1 ) : "";
					break 1;
				}
			}
		}
		// Se detecta el tipo de precio a aplicar en la cotización según el tipo de cliente
		if (comprobarVar ( $customerType )) {
			if ($customerType == "Customer") {
				$priceTypeId = "2";
			} else {
				$priceTypeId = "1";
			}
		}
		
		// Se detecta la región del cliente
		$customerRegionId = "";
		$region = "";
		if (comprobarVar ( $country )) {
			if ($country == "United States" or $country == "Canada") {
				$customerRegionId = 2;
				$region = "US & Canada";
			} else {
				$customerRegionId = 1;
				$region = "Outside US & Canada";
			}
		}
	}
}

// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong ();
$TBS->LoadTemplate ( '../paginas/quote.tpl' );
$TBS->Show ();
?>