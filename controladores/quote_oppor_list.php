<?php
// INCLUYENDO LIBRERÍAS, CLASES Y ARCHIVOS
include_once '../conf.inc.php';
include_once '../librerias/conexion_bd.php';
include_once '../librerias/tbs_class/tbs_class.php';
include_once '../librerias/xajax_0.2.4/xajax.inc.php';
include_once '../librerias/insightly.php';
include_once '../inc/funciones.php';

// XAJAX
$xajax = new xajax("../inc/ajax_funciones.php");
$xajax->registerFunction ( "getCustomer" );
$js = $xajax->getJavascript('../librerias/');

// Se consulta en el API de Insightly la lista de organizaciones
$customers = "";
$i = new Insightly ( APIKEY );
$options['top'] = 15;
$arrOrganization = $i->getOrganizations ($options);
foreach ( $arrOrganization as $j => $myOrganization ) {
	$customers .= ',"' . $myOrganization->ORGANISATION_NAME . '"';
}
$customers = substr ( $customers, 1 );

// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong ();
$TBS->LoadTemplate ( '../paginas/quote_oppor_list.tpl' );
$TBS->Show ();
?>