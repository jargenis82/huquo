<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Opportunity extends ClaseBd {
	function declararTabla() {
		$tabla = "opportunity";
		$atributos ['opportunity_id'] ['esPk'] = true;
		$atributos ['recordid'] ['esPk'] = false;
		$atributos ['opportunityname'] ['esPk'] = false;
		$atributos ['details'] ['esPk'] = false;
		$atributos ['organizationname'] ['esPk'] = false;
		$atributos ['organizationid'] ['esPk'] = false;
		$atributos ['probability'] ['esPk'] = false;
		$atributos ['bidcurrency'] ['esPk'] = false;
		$atributos ['bidamount'] ['esPk'] = false;
		$atributos ['bidtype'] ['esPk'] = false;
		$atributos ['bidduration'] ['esPk'] = false;
		$atributos ['forecastclosedate'] ['esPk'] = false;
		$atributos ['opportunitycategory'] ['esPk'] = false;
		$atributos ['currentstate'] ['esPk'] = false;
		$atributos ['laststatechangereason'] ['esPk'] = false;
		$atributos ['pipelinename'] ['esPk'] = false;
		$atributos ['pipelinecurrentstage'] ['esPk'] = false;
		$atributos ['userresponsibleemailaddress'] ['esPk'] = false;
		$atributos ['datecreated'] ['esPk'] = false;
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
		$atributos ['actualclosedate'] ['esPk'] = false;
		$strOrderBy = "opportunity_id";
		$this->registrarTabla ( $tabla, $atributos, null, $strOrderBy );
		$this->dsn = "mysql";
	}
}
// Fin
?>