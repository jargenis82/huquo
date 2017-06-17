<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'clases/customer_region.php';
include_once RUTA_SISTEMA . 'clases/price_type.php';
include_once RUTA_SISTEMA . 'clases/product_sale.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Price extends ClaseBd {
	function declararTabla() {
		$tabla = "price";
		$atributos ['price_id'] ['esPk'] = true;
		$atributos ['price_value'] ['esPk'] = false;
		$objetos ['ProductSale'] ['id'] = "product_sale_id";
		$objetos ['CustomerRegion'] ['id'] = "customer_region_id";
		$objetos ['PriceType'] ['id'] = "price_type_id";
		$strOrderBy = "price_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>