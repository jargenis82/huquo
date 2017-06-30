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
$xajax = new xajax ( "inc/ajax_funciones.php" );
// $xajax->registerFunction("getCustomer");
$xajax->registerFunction ( "validateUser" );
$js = $xajax->getJavascript ( 'librerias/' );

// Variable GET que indica si la página actual esta activa en una nueva pestaña o es la única página del sistema
$newPage = comprobarVar ( $_GET ['newPage'] ) ? trim ( $_GET ['newPage'] ) : "0";

// Si el usuario ya se validó debe tener una variable de sesión 'user_id' activa
if (comprobarVar ( $_SESSION ['user_id'] )) {
	$userLoginFtp = comprobarVar ( $_SESSION ['user_login_ftp'] ) ? $_SESSION ['user_login_ftp'] : "";
	$userLoginFtp = strtoupper ( $userLoginFtp );
	$userName = comprobarVar ( $_SESSION ['user_name'] ) ? $_SESSION ['user_name'] : "";
	// Se habilita menú de la barra superior
	$menuHid = "";
	// La página a consultar se solicita por parámetro GET o si no es por defecto quote_oppor_list
	$page = comprobarVar ( $_GET ['page'] ) ? trim ( $_GET ['page'] ) : "quote_oppor_list";
	$page .= ".php?";
	// Valida gets extra
	foreach ( $_GET as $get => $valor ) {
		if ($i != "page") {
			$page .= "$get=$valor&";
		}
	}
} else {
	// Valida la pagina del iframe
	$page = "quote_log_in.php";
	// Se deshabilita menu de la barra superior
	$menuHid = "disabled";
}

// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong ();
$TBS->LoadTemplate ( 'paginas/index.tpl' );
$TBS->Show ();
?>