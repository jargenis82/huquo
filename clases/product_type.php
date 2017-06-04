<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class ProductType extends ClaseBd {
	function declararTabla() {
		$tabla = "product_type";
		$atributos ['product_type_id'] ['esPk'] = true;
		$atributos ['product_type_desc'] ['esPk'] = false;
		$strOrderBy = "product_type_desc";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>