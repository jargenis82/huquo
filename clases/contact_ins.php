<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class ContactIns extends ClaseBd {
	function declararTabla() {
		$tabla = "contact_ins";
		$atributos ['contact_ins_id'] ['esPk'] = true;
		$atributos ['recordid'] ['esPk'] = false;
		$atributos ['salutation'] ['esPk'] = false;
		$atributos ['firstname'] ['esPk'] = false;
		$atributos ['lastname'] ['esPk'] = false;
		$atributos ['organization'] ['esPk'] = false;
		$atributos ['role'] ['esPk'] = false;
		$atributos ['background'] ['esPk'] = false;
		$atributos ['mailaddressstreet'] ['esPk'] = false;
		$atributos ['mailaddresscity'] ['esPk'] = false;
		$atributos ['mailaddressstate'] ['esPk'] = false;
		$atributos ['mailaddresscountry'] ['esPk'] = false;
		$atributos ['mailaddresspostalcode'] ['esPk'] = false;
		$atributos ['otheraddressstreet'] ['esPk'] = false;
		$atributos ['otheraddresscity'] ['esPk'] = false;
		$atributos ['otheraddressstate'] ['esPk'] = false;
		$atributos ['otheraddresspostalcode'] ['esPk'] = false;
		$atributos ['otheraddresscountry'] ['esPk'] = false;
		$atributos ['businessphone'] ['esPk'] = false;
		$atributos ['homephone'] ['esPk'] = false;
		$atributos ['mobilephone'] ['esPk'] = false;
		$atributos ['fax'] ['esPk'] = false;
		$atributos ['assistantphone'] ['esPk'] = false;
		$atributos ['assistantname'] ['esPk'] = false;
		$atributos ['otherphone'] ['esPk'] = false;
		$atributos ['emailaddress'] ['esPk'] = false;
		$atributos ['dateofbirth'] ['esPk'] = false;
		$atributos ['importantdate1name'] ['esPk'] = false;
		$atributos ['importantdate1'] ['esPk'] = false;
		$atributos ['importantdate2name'] ['esPk'] = false;
		$atributos ['importantdate2'] ['esPk'] = false;
		$atributos ['importantdate3name'] ['esPk'] = false;
		$atributos ['importantdate3'] ['esPk'] = false;
		$atributos ['contacttag1'] ['esPk'] = false;
		$atributos ['contacttag2'] ['esPk'] = false;
		$atributos ['contacttag3'] ['esPk'] = false;
		$atributos ['contacttag4'] ['esPk'] = false;
		$atributos ['contacttag5'] ['esPk'] = false;
		$atributos ['contacttag6'] ['esPk'] = false;
		$atributos ['contacttag7'] ['esPk'] = false;
		$atributos ['contacttag8'] ['esPk'] = false;
		$atributos ['contacttag9'] ['esPk'] = false;
		$atributos ['datecreated'] ['esPk'] = false;
		$atributos ['dateupdated'] ['esPk'] = false;
		$atributos ['linkedorganizationbillingstreet'] ['esPk'] = false;
		$atributos ['linkedorganizationbillingcity'] ['esPk'] = false;
		$atributos ['linkedorganizationbillingstate'] ['esPk'] = false;
		$atributos ['linkedorganizationbillingpostalcode'] ['esPk'] = false;
		$atributos ['linkedorganizationbillingcountry'] ['esPk'] = false;
		$atributos ['linkedorganizationshippingstreet'] ['esPk'] = false;
		$atributos ['linkedorganizationshippingcity'] ['esPk'] = false;
		$atributos ['linkedorganizationshippingstate'] ['esPk'] = false;
		$atributos ['linkedorganizationshippingpostalcode'] ['esPk'] = false;
		$atributos ['linkedorganizationshippingcountry'] ['esPk'] = false;
		$atributos ['dateoflastactivity'] ['esPk'] = false;
		$atributos ['dateofnextactivity'] ['esPk'] = false;
		$atributos ['organizationrecordid'] ['esPk'] = false;
		$strOrderBy = "contact_ins_id";
		$this->registrarTabla ( $tabla, $atributos, null, $strOrderBy );
		$this->dsn = "mysql";
	}
}
// Fin
?>