<?php
include_once '../../conf.inc.php';
include_once '../../inc/funciones.php';
include_once '../../clases/quote.php';

// Se inicializa el arreglo a transformar en JSON con los resultados de la busqueda
$arrJson = array (
		"sEcho" => 3,
		"iTotalRecords" => 0, // Cantidad de registros totales
		"iTotalDisplayRecords" => 0, // Cantidad de registros de la busqueda
		"aaData" => array () 
);

$opportunityId = comprobarVar ( $_GET ['opportunityId'] ) ? $_GET ['opportunityId'] : null;
$arrJson ['aaData'] = array ();
if (comprobarVar ( $opportunityId )) {
	$myQuote = new Quote ();
	$myQuote->setAtributo ( "oppor_id", $opportunityId );
	$arrQuote = $myQuote->consultar ();
	foreach ( $arrQuote as $myQuote ) {
		$quoteId = $myQuote->getAtributo ( "quote_id" );
		$unDato [0] [0] = $myQuote->getAtributo ( "quote_number" );
		$myDateTime = DateTime::createFromFormat ( "Y-m-d H:i:s", $myQuote->getAtributo ( "quote_date" ) );
		$quoteDate = "<span style='display: none;'>" . $myDateTime->format ( "Y-m-d H:i:s" ) . "</span>" . $myDateTime->format ( "d-M-Y H:i:s" );
		$quoteTotal = $myQuote->getQuoteTotal ();
		$unDato [0] [1] = $quoteDate;
		$unDato [0] [2] = $myQuote->getObjeto ( "User" )->getAtributo ( "user_name" );
		$unDato [0] [3] = $quoteTotal;
		$unDato [0] [4] = "<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-file' data-modulo='' data-accion='' onclick='viewQuote($quoteId,$opportunityId)'></span>";
		$unDato [0] [5] = "<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-download-alt' data-modulo='' data-accion='' onclick='openPdfQuote($quoteId,true)'></span>";
		$arrJson ['aaData'] [] = $unDato [0];
	}
}

echo json_encode ( $arrJson );

?>