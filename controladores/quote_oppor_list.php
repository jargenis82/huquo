<?php
// INCLUYENDO LIBRERÍAS, CLASES Y ARCHIVOS
include_once '../conf.inc.php';
include_once '../librerias/conexion_bd.php';
include_once '../librerias/tbs_class/tbs_class.php';
include_once '../librerias/xajax_0.2.4/xajax.inc.php';
include_once '../librerias/insightly.php';
include_once '../inc/funciones.php';
include_once '../clases/organisation.php';

// SE VERIFICA LA SESIÓN Y ACCESO DEL USUARIO
(session_id() == "") ? session_start () : null;
if (! comprobarVar ( $_SESSION ['user_id'] )) {
	exit ();
}

// XAJAX
$xajax = new xajax ( "../inc/ajax_funciones.php" );
$xajax->registerFunction ( "getCustomer" );
$js = $xajax->getJavascript ( '../librerias/' );

// Se consulta una copia local de customers para mejorar el desempeño en máquinas de desarrollo (provisional)
if (defined("CUSTOMERS")) {
	$customers = file_get_contents("../log/customers"); 
} else {
	// Se consulta en el API de Insightly la lista de organizaciones
	$customers = "";
	// $i = new Insightly ( APIKEY );
	// $options ['top'] = defined ( "TOP_LIMIT" ) ? TOP_LIMIT : null; // Limite de consulta para mejorar el desempeño en máquinas de desarrollo (Provisional)
	// $arrOrganization = $i->getOrganizations ( $options );
	$myOrganisation = new Organisation();
	$arrOrganization = $myOrganisation->consultar();
	foreach ( $arrOrganization as $j => $myOrganization ) {
		// $customers .= ',"' . $myOrganization->ORGANISATION_NAME . '"';
		$customers .= ',"' . $myOrganization->getAtributo("org_name") . '"';
	}
	$customers = substr ( $customers, 1 );
}

// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong ();
$TBS->LoadTemplate ( '../paginas/quote_oppor_list.tpl' );
$TBS->Show ();
?>