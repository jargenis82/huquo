<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'clases/product_type.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Product extends ClaseBd {
	function declararTabla() {
		$tabla = "product";
		$atributos ['product_id'] ['esPk'] = true;
		$atributos ['product_desc'] ['esPk'] = false;
		$objetos ['ProductType'] ['id'] = "product_type_id";
		$strOrderBy = "product_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>