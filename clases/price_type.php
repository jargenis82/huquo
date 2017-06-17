<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class PriceType extends ClaseBd {
	function declararTabla() {
		$tabla = "price_type";
		$atributos ['price_type_id'] ['esPk'] = true;
		$atributos ['price_type_name'] ['esPk'] = false;
		$strOrderBy = "price_type_name";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>