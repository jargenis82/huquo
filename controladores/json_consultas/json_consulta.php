<?php
include_once '../../conf.inc.php';
include_once '../../inc/funciones.php';
// include_once '../../librerias/insightly.php';
include_once '../../clases/opportunity.php';
include_once '../../clases/quote.php';

// SE VERIFICA LA SESIÓN Y ACCESO DEL USUARIO
(session_id() == "") ? session_start () : null;
if (! comprobarVar ( $_SESSION ['user_id'] )) {
	exit ();
}

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

// if (comprobarVar ( $organizationId )) {
// 	$i = new Insightly ( APIKEY );
// 	$myOrganization = $i->getOrganization ( $organizationId );
// }

$unDato = array ();
// if (isset ( $myOrganization )) {
// 	$arrLinks = $myOrganization->LINKS;
// 	foreach ( $arrLinks as $aLink ) {
// 		$opportunityId = $aLink->OPPORTUNITY_ID;
if (comprobarVar($organizationId)) {
	$myOpportunity = new Opportunity();
	$myOpportunity->setAtributo("organizationid",$organizationId);
	$arrOpportunity = $myOpportunity->consultar();
	foreach ($arrOpportunity as $aOpportunity) {
		$opportunityId = $aOpportunity->getAtributo("recordid");
		// if (comprobarVar ( $opportunityId )) {
		if (isset($aOpportunity) and comprobarVar($aOpportunity->getAtributo("datecreated"))) {
			// $myOpportunity = $i->getOpportunity ( $opportunityId );
			// $unDato [0] = $myOpportunity->OPPORTUNITY_NAME;
			$unDato [0] = utf8_encode($aOpportunity->getAtributo("opportunityname"));
			$myDateTimeZone = new DateTimeZone ( "UTC" );
			// $myDateTime = DateTime::createFromFormat ( "Y-m-d H:i:s", $myOpportunity->DATE_CREATED_UTC, $myDateTimeZone );
			$myDateTime = DateTime::createFromFormat ( "n/j/Y", $aOpportunity->getAtributo("datecreated"), $myDateTimeZone );
			$myDateTimeZone = new DateTimeZone ( date_default_timezone_get () );
			$myDateTime->setTimezone ( $myDateTimeZone );
			$unDato [1] = "<span style='display: none;'>" . $myDateTime->format ( "Y-m-d" ) . "</span>" . $myDateTime->format ( "d-M-Y" );
			// Se coloca un caracter oculto adelante del estado de la oportunidad
			// de tal manera que siempre ordene de primero a las oportunidades OPEN
			// $opporState = $myOpportunity->OPPORTUNITY_STATE;
			$opporState = $aOpportunity->getAtributo("currentstate");
			if ($opporState == 'OPEN') {
				$dato = "1";
			} else {
				$dato = "2";
			}
			// $unDato [2] = "<span style='display: none;'>$dato</span>" . $myOpportunity->OPPORTUNITY_STATE;
			$unDato [2] = "<span style='display: none;'>$dato</span>" . $opporState;
			// $myPipelineStage = $i->getPipelineStage ( $myOpportunity->STAGE_ID );
			// $stageOrder = $myPipelineStage->STAGE_ORDER;
			// $stageName = $myPipelineStage->STAGE_NAME;
			$stageName = utf8_encode($aOpportunity->getAtributo("pipelinecurrentstage"));
			if ($stageName == "Interested") {
				$stageOrder = 1;
			} else if ($stageName == "Quote sent") {
				$stageOrder = 2;
			} else if ($stageName == "Negotiation") {
				$stageOrder = 3;
			} else if ($stageName == "Commitment") {
				$stageOrder = 4;
			} else {
				$stageOrder = 1;
			}
			if (comprobarVar ( $stageOrder )) {
				$unDato [3] = "$stageName<br><img alt='$stageName' src='../imagenes/pipeline_$stageOrder.png' style='width: 100px'>";
			} else {
				$unDato [3] = "undefined";
			}			
			$myQuote = new Quote ();
			$myQuote->setAtributo ( "oppor_id", $opportunityId );
			$cantidad = $myQuote->consultar ( true );
			($cantidad > 0) ? $unDato [4] = '<span id="btn_listQ" class="btn btn-default btn-xs glyphicon glyphicon-th-list" data-modulo="" data-accion="" onclick="dataTableQuote(' . $opportunityId . ',\'' . $opporState . '\')"></span>' : $unDato [4] = '<span></span>';
			$unDato [5] = ($opporState == "OPEN") ? "<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-file' data-modulo='' data-accion='' onclick='newQuote($opportunityId)'></span>" : '<span></span>';
			$arrJson ['aaData'] [] = $unDato;
		}
	}
}

echo json_encode ( $arrJson );
?>