<?php
// INCLUYENDO LIBRERÍAS, CLASES Y ARCHIVOS
include_once '../conf.inc.php';
include_once '../librerias/conexion_bd.php';
include_once '../librerias/tbs_class/tbs_class.php';
include_once '../librerias/xajax_0.2.4/xajax.inc.php';
include_once '../librerias/insightly.php';
// include_once '../clases/inst_usua.php';
// include_once '../clases/menu.php';
include_once '../inc/funciones.php';

// SE VERIFICA LA SESIÓN Y ACCESO DEL USUARIO
// $miConexionBd = new ConexionBd();
// $miInstUsua = new InstUsua($miConexionBd);
// validarAcceso($miInstUsua);

// DEFINE LAS VARIABLES $_GET, $_POST Y $_SESSION
// session_start();
// isset($_SESSION['instUsuaId']) ? $instUsuaId = $_SESSION['instUsuaId'] : $instUsuaId = null;

// XAJAX
$xajax = new xajax("../inc/ajax_funciones.php");
//$xajax->registerFunction("getCustomer");
$js = $xajax->getJavascript('../librerias/');

// Se consulta en el API de Insightly la lista de organizaciones

// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong();
$TBS->LoadTemplate('../paginas/index.tpl');
$TBS->Show();
?>