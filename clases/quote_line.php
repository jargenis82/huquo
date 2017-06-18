<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'clases/quote.php';
include_once RUTA_SISTEMA . 'clases/price.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class QuoteLine extends ClaseBd {
	function declararTabla() {
		$tabla = "quote_line";
		$atributos ['quote_line_id'] ['esPk'] = true;
		$atributos ['quote_line_desc'] ['esPk'] = false;
		$atributos ['quote_line_price'] ['esPk'] = false;
		$atributos ['quote_line_discount'] ['esPk'] = false;
		$atributos ['quote_line_qty'] ['esPk'] = false;
		$atributos ['quote_line_edit_price'] ['esPk'] = false;
		$atributos ['quote_line_edit_desc'] ['esPk'] = false;
		$objetos ['Quote'] ['id'] = "quote_id";
		$objetos ['ProductSale'] ['id'] = "product_sale_id";
		$strOrderBy = "quote_line_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>