<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Organisation extends ClaseBd {
	function declararTabla() {
		$tabla = "organisation";
		$atributos ['org_id'] ['esPk'] = true;
		$atributos ['org_name'] ['esPk'] = false;
		$atributos ['org_address'] ['esPk'] = false;
		$atributos ['org_web'] ['esPk'] = false;
		$atributos ['org_phone'] ['esPk'] = false;
		$atributos ['org_city'] ['esPk'] = false;
		$atributos ['org_country'] ['esPk'] = false;
		$atributos ['org_ins_id'] ['esPk'] = false;
		$strOrderBy = "org_id";
		$this->registrarTabla ( $tabla, $atributos, null, $strOrderBy );
		$this->dsn = "mysql";
	}
}

?>