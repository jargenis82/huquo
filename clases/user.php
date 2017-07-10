<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class User extends ClaseBd {
	function declararTabla() {
		$tabla = "user";
		$atributos ['user_id'] ['esPk'] = true;
		$atributos ['user_login_ftp'] ['esPk'] = false;
		$atributos ['user_name'] ['esPk'] = false;
		$atributos ['user_email'] ['esPk'] = false;
		$atributos ['user_creation_date'] ['esPk'] = false;
		$atributos ['user_skype'] ['esPk'] = false;
		$strOrderBy = "user_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>