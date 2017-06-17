<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class ProductSale extends ClaseBd {
	function declararTabla() {
		$tabla = "product";
		$atributos ['product_sale_id'] ['esPk'] = true;
		$atributos ['product_master_id'] ['esPk'] = false;
		$atributos ['product_secondary_id'] ['esPk'] = false;
		$strOrderBy = "product_sale_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>