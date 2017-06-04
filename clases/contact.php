<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Contact extends ClaseBd {
	function declararTabla() {
		$tabla = "contact";
		$atributos ['contact_id'] ['esPk'] = true;
		$atributos ['contact_name'] ['esPk'] = false;
		$atributos ['contact_email'] ['esPk'] = false;
		$atributos ['contact_ins_id'] ['esPk'] = false;
		$strOrderBy = "contact_name";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>