<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'clases/organisation.php';
include_once RUTA_SISTEMA . 'clases/contact.php';
include_once RUTA_SISTEMA . 'clases/user.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Quote extends ClaseBd {
	function declararTabla() {
		$tabla = "quote";
		$atributos ['quote_id'] ['esPk'] = true;
		$atributos ['quote_date'] ['esPk'] = false;
		$atributos ['quote_valid_until'] ['esPk'] = false;
		$atributos ['quote_discount'] ['esPk'] = false;
		$atributos ['quote_hst_rate'] ['esPk'] = false;
		$atributos ['quote_ship_to'] ['esPk'] = false;
		$atributos ['quote_number'] ['esPk'] = false;
		$atributos ['hubrox_id'] ['esPk'] = false;
		$objetos ['Organisation'] ['id'] = "org_id";
		$objetos ['Contact'] ['id'] = "contact_id";
		$objetos ['User'] ['id'] = "user_id";
		$strOrderBy = "quote_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>