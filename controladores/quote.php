<?php
// INCLUYENDO LIBRERÍAS, CLASES Y ARCHIVOS
include_once '../conf.inc.php';
include_once '../librerias/conexion_bd.php';
include_once '../librerias/tbs_class/tbs_class.php';
include_once '../librerias/xajax_0.2.4/xajax.inc.php';
include_once '../librerias/insightly.php';
include_once '../clases/product.php';
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
$js = $xajax->getJavascript ( '../librerias/' );

// Fecha Actual
// Si es una nueva cotización debe ser la fecha del día.
// Si se está consultando una cotización debe ser la fecha original de creación de la cotización
$fecha = formatoFechaBd ( null, "m/d/Y" );

// Fecha de validez
// Si es una nueva cotización debe colocar por defecto 30 días más de la fecha de creación
// Si se está consultando una cotización deber la fecha de validez colocada durante la creación de la cotización
$fechaValidez = sumarFecha ( $fecha, 30 );

// Quote number
$quoteNumber = "20170606-012";

// Prepared by
$userName = "Annie Wang";

// Se construye el arreglo en Javascript para el autocompletar de productos
$myProduct = new Product ();
$listaProducto = $myProduct->getListaProducto ();
$jsData = "";
foreach ( $listaProducto as $unProducto ) {
	$unProducto = str_replace ( '"', '\"', $unProducto );
	$jsData .= '"' . $unProducto . '",';
	$contador ++;
}
$jsData = $jsData != "" ? substr ( $jsData, 0, - 1 ) : "";

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
			$quote = file_get_contents("../log/quote");
			$quote = explode(";", $quote);
			$organizationId = $quote[0];
			$organizationName = $quote[1];
			$address = $quote[2];
			$web = $quote[3];
			$phone = $quote[4];
		} else {
			// Se consulta en el API de Insightly los datos de la oportunidad
			$i = new Insightly ( APIKEY );
			$myOpportunity = $i->getOpportunity ( $opportunityId );
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
					break 1;
				}
			}
		}
	}
}

// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong ();
$TBS->LoadTemplate ( '../paginas/quote.tpl' );
$TBS->Show ();
?>