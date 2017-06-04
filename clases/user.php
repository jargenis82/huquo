<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'clases/contact.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class User extends ClaseBd {
	function declararTabla() {
		$tabla = "user";
		$atributos ['user_id'] ['esPk'] = true;
		$atributos ['user_ins_id'] ['esPk'] = false;
		$objetos ['Contact'] ['id'] = "contact_id";
		$strOrderBy = "user_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>