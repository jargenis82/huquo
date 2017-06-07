<?php
include_once '../../conf.inc.php';
include_once '../../inc/funciones.php';
include_once '../../librerias/insightly.php';

// Se inicializa el arreglo a transformar en JSON con los resultados de la busqueda
$arrJson = array (
		"sEcho" => 3,
		"iTotalRecords" => 0, // Cantidad de registros totales
		"iTotalDisplayRecords" => 0, // Cantidad de registros de la busqueda
		"aaData" => array () 
);

// Busqueda de oportunidades de la organizacion
$i = new Insightly ( APIKEY );
$options ['filters'] [0] = "ORGANISATION_NAME = '" . $_GET ['customerName'] . "'";
$arrOrganizations = $i->getOrganizations ( $options );
$unDato = array ();
if (isset ( $arrOrganizations [0] )) {
	$arrLinks = $arrOrganizations [0]->LINKS;
	foreach ( $arrLinks as $aLink ) {
		$opportunityId = $aLink->OPPORTUNITY_ID;
		if (comprobarVar ( $opportunityId )) {
			$myOpportunity = $i->getOpportunity ( $opportunityId );
			$unDato [0] = $myOpportunity->OPPORTUNITY_NAME;
			$unDato [1] = substr($myOpportunity->DATE_CREATED_UTC,0,10);
			$unDato [2] = $myOpportunity->OPPORTUNITY_STATE;
			
			$myPipelineStage = $i->getPipelineStage($myOpportunity->STAGE_ID);
			$stageOrder = $myPipelineStage->STAGE_ORDER;
			$stageName = $myPipelineStage->STAGE_NAME;
			$unDato [3] = "$stageName<br><img alt='$stageName' src='../imagenes/pipeline_$stageOrder.png' style='width: 100px'>";
			$unDato [4] = '<span id="btn_listQ" class="btn btn-default btn-xs glyphicon glyphicon-th-list" data-modulo="" data-accion="" onclick="dataTableQuote('.$opportunityId.')"></span>';
			$arrJson ['aaData'] [] = $unDato;
		}
	}
}
/**
 * $unDato [0] [0] = 'Arkcom Grupo Khrislys - 150 HB-ID9 ';
 * $unDato [0] [1] = "Thu Apr 27 2017";
 * $unDato [0] [2] = "Open";
 * $unDato [0] [3] = '<span id="btn_listQ" class="btn btn-default btn-xs glyphicon glyphicon-th-list" data-modulo="" data-accion="" onclick="alert(this)"></span>';
 * $unDato [1] [0] = "Arkcom / Proser - 20 HB-9000 ";
 * $unDato [1] [1] = "Thu 17-Nov-2016";
 * $unDato [1] [2] = "Won";
 * $unDato [1] [3] = '<span id="btn_listQ" class="btn btn-default btn-xs glyphicon glyphicon-th-list" data-modulo="" data-accion="" onclick="eliminarRegistro(this)"></span>';
 * $arrJson ['aaData'] [] = $unDato [0];
 * $arrJson ['aaData'] [] = $unDato [1];
 */
echo json_encode ( $arrJson );

?>