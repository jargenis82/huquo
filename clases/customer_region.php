<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class CustomerRegion extends ClaseBd {
	function declararTabla() {
		$tabla = "customer_region";
		$atributos ['customer_region_id'] ['esPk'] = true;
		$atributos ['customer_region_name'] ['esPk'] = false;
		$strOrderBy = "customer_region_name";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>