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
$js    = $xajax->getJavascript('../librerias/');

// Se consulta una copia local de customers para mejorar el desempeño en máquinas de desarrollo (provisional)

// CARGA DE LA PLANTILLA PRINCIPAL
$TBS = new clsTinyButStrong();
$TBS->LoadTemplate('../paginas/quote_log_in.tpl');
$TBS->Show();
?>