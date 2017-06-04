<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'clases/product.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Price extends ClaseBd {
	function declararTabla() {
		$tabla = "price";
		$atributos ['price_id'] ['esPk'] = true;
		$atributos ['price_date'] ['esPk'] = false;
		$atributos ['price_value'] ['esPk'] = false;
		$objetos ['Product'] ['id'] = "product_id";
		$strOrderBy = "price_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>