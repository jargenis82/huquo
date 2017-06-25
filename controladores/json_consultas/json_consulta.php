<?php
include_once '../../conf.inc.php';
include_once '../../inc/funciones.php';
include_once '../../librerias/insightly.php';
include_once '../../clases/quote.php';

// Se consulta una copia local para mejorar el desempeño en máquinas de desarrollo (provisional)
if (defined ( "CUSTOMERS" )) {
	include_once '../../log/arrJson.php';
	if (! comprobarVar ( $_GET ['organizationId'] )) {
		$arrJson ['aaData'] = array ();
	}
	echo json_encode ( $arrJson );
	exit ();
}

// Se inicializa el arreglo a transformar en JSON con los resultados de la busqueda
$arrJson = array (
		"sEcho" => 3,
		"iTotalRecords" => 0, // Cantidad de registros totales
		"iTotalDisplayRecords" => 0, // Cantidad de registros de la busqueda
		"aaData" => array () 
);

// Busqueda de oportunidades de la organizacion
$organizationId = $_GET ['organizationId'];
if (comprobarVar ( $organizationId )) {
	$i = new Insightly ( APIKEY );
	$myOrganization = $i->getOrganization ( $organizationId );
}

$unDato = array ();
if (isset ( $myOrganization )) {
	$arrLinks = $myOrganization->LINKS;
	foreach ( $arrLinks as $aLink ) {
		$opportunityId = $aLink->OPPORTUNITY_ID;
		if (comprobarVar ( $opportunityId )) {
			$myOpportunity = $i->getOpportunity ( $opportunityId );
			$unDato [0] = $myOpportunity->OPPORTUNITY_NAME;
			$myDateTimeZone = new DateTimeZone ( "UTC" );
			$myDateTime = DateTime::createFromFormat ( "Y-m-d H:i:s", $myOpportunity->DATE_CREATED_UTC, $myDateTimeZone );
			$myDateTimeZone = new DateTimeZone ( date_default_timezone_get ());
			$myDateTime->setTimezone($myDateTimeZone);
			$unDato [1] = $myDateTime->format("Y-m-d");
			$unDato [2] = $myOpportunity->OPPORTUNITY_STATE;
			$myPipelineStage = $i->getPipelineStage ( $myOpportunity->STAGE_ID );
			$stageOrder = $myPipelineStage->STAGE_ORDER;
			$stageName = $myPipelineStage->STAGE_NAME;
			$unDato [3] = "$stageName<br><img alt='$stageName' src='../imagenes/pipeline_$stageOrder.png' style='width: 100px'>";
			$myQuote = new Quote ();
			$myQuote->setAtributo ( "oppor_id", $opportunityId );
			$cantidad = $myQuote->consultar ( true );
			($cantidad > 0) ? $unDato [4] = '<span id="btn_listQ" class="btn btn-default btn-xs glyphicon glyphicon-th-list" data-modulo="" data-accion="" onclick="dataTableQuote(' . $opportunityId . ')"></span>' : $unDato [4] = '<span></span>';
			$unDato [5] = ($unDato [2] == "OPEN") ? "<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-file' data-modulo='' data-accion='' onclick='newQuote($opportunityId)'></span>" : '<span></span>';
			$arrJson ['aaData'] [] = $unDato;
		}
	}
}

echo json_encode ( $arrJson );
?>