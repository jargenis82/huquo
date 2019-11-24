<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Organization extends ClaseBd {
	function declararTabla() {
		$tabla = "organization";
		$atributos ['organization_id'] ['esPk'] = true;
		$atributos ['recordid'] ['esPk'] = false;
		$atributos ['organizationname'] ['esPk'] = false;
		$atributos ['background'] ['esPk'] = false;
		$atributos ['billingaddressstreet'] ['esPk'] = false;
		$atributos ['billingaddresscity'] ['esPk'] = false;
		$atributos ['billingaddressstate'] ['esPk'] = false;
		$atributos ['billingaddresspostalcode'] ['esPk'] = false;
		$atributos ['billingaddresscountry'] ['esPk'] = false;
		$atributos ['shippingaddressstreet'] ['esPk'] = false;
		$atributos ['shippingaddresscity'] ['esPk'] = false;
		$atributos ['shippingaddressstate'] ['esPk'] = false;
		$atributos ['shippingaddresscountry'] ['esPk'] = false;
		$atributos ['shippingaddresspostalcode'] ['esPk'] = false;
		$atributos ['phone'] ['esPk'] = false;
		$atributos ['fax'] ['esPk'] = false;
		$atributos ['website'] ['esPk'] = false;
		$atributos ['emaildomain'] ['esPk'] = false;
		$atributos ['importantdate1name'] ['esPk'] = false;
		$atributos ['importantdate1'] ['esPk'] = false;
		$atributos ['importantdate2name'] ['esPk'] = false;
		$atributos ['importantdate2'] ['esPk'] = false;
		$atributos ['importantdate3name'] ['esPk'] = false;
		$atributos ['importantdate3'] ['esPk'] = false;
		$atributos ['tag1'] ['esPk'] = false;
		$atributos ['tag2'] ['esPk'] = false;
		$atributos ['tag3'] ['esPk'] = false;
		$atributos ['tag4'] ['esPk'] = false;
		$atributos ['tag5'] ['esPk'] = false;
		$atributos ['tag6'] ['esPk'] = false;
		$atributos ['tag7'] ['esPk'] = false;
		$atributos ['tag8'] ['esPk'] = false;
		$atributos ['tag9'] ['esPk'] = false;
		$atributos ['dateoflastactivity'] ['esPk'] = false;
		$atributos ['dateofnextactivity'] ['esPk'] = false;
		$atributos ['type'] ['esPk'] = false;
		$strOrderBy = "organizationname";
		$this->registrarTabla ( $tabla, $atributos, null, $strOrderBy );
		$this->dsn = "mysql";
	}
}
// Fin
?>