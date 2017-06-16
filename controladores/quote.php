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
session_start();
// isset($_SESSION['instUsuaId']) ? $instUsuaId = $_SESSION['instUsuaId'] : $instUsuaId = null;
$opportunityId = comprobarVar($_GET['opportunityId']) ? $_GET['opportunityId'] : null;
$quoteId = comprobarVar($_GET['quoteId']) ? $_GET['quoteId'] : null;

// XAJAX
$xajax = new xajax("../inc/ajax_funciones.php");
$xajax->registerFunction("getCustomer");
$xajax->registerFunction("getDescripProduct");
$xajax->registerFunction("addNewProduct");
$js = $xajax->getJavascript('../librerias/');

// Fecha Actual
// Si es una nueva cotización debe ser la fecha del día.
// Si se está consultando una cotización debe ser la fecha original de creación de la cotización
$fecha = formatoFechaBd(null, "m/d/Y");

// Fecha de validez
// Si es una nueva cotización debe colocar por defecto 30 días más de la fecha de creación
// Si se está consultando una cotización deber la fecha de validez colocada durante la creación de la cotización
$fechaValidez = sumarFecha($fecha, 30);

// Quote number
$quoteNumber = "20170606-012";

// Prepared by
$userName = "Annie Wang";

// Se construye el arreglo en Javascript para el autocompletar de productos
$myProduct     = new Product();
$listaProducto = $myProduct->getListaProducto();
$jsData        = "";
foreach ($listaProducto as $unProducto) {
	$unProducto = str_replace('"', '\"', $unProducto);
	$jsData .= '"'.$unProducto.'",';
	$contador++;
}
$jsData = $jsData != ""?substr($jsData, 0, -1):"";

// Se consulta en el API de Insightly la lista de organizaciones
$customers       = "";
$i               = new Insightly(APIKEY);
$options['top']  = defined("TOP_LIMIT")?TOP_LIMIT:null;// Limite de consulta para mejorar el desempeño en máquinas de consulta (Provisional)
$arrOrganization = $i->getOrganizations($options);
foreach ($arrOrganization as $j => $myOrganization) {
	$customers .= ',"'.$myOrganization->ORGANISATION_NAME.'"';
}
$customers = substr($customers, 1);

// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong();
$TBS->LoadTemplate('../paginas/quote.tpl');
$TBS->Show();
?>