<?php
// INCLUYENDO LIBRERÍAS, CLASES Y ARCHIVOS
include_once 'conf.inc.php';
include_once 'librerias/conexion_bd.php';
include_once 'librerias/tbs_class/tbs_class.php';
include_once 'librerias/xajax_0.2.4/xajax.inc.php';
include_once 'librerias/insightly.php';
// include_once '../clases/inst_usua.php';
// include_once '../clases/menu.php';
include_once 'inc/funciones.php';

// XAJAX
$xajax = new xajax("inc/ajax_funciones.php");
//$xajax->registerFunction("getCustomer");
$xajax->registerFunction ("validateUser");
$js = $xajax->getJavascript('librerias/');


 $menuHid="disabled";

if($_SESSION['page']=="quote_oppor_list.php"&&$_GET['page']==""){
$page=$_SESSION['page'];
 $menuHid=$_SESSION['menuHid'];
}
else {

// Valida la pagina del iframe
	$page = comprobarVar($_GET['page'])?trim($_GET['page']):"quote_log_in";
	$page .= ".php?";
	// Valida gets extra
	foreach ($_GET as $get => $valor) {
	  if ($i != "page") {
	    $page .= "$get=$valor&";
	  }
	}
	isset($_SESSION['page'])? $menuHid="":$menuHid="disabled";
}




// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong();
$TBS->LoadTemplate('paginas/index.tpl');
$TBS->Show();
?>