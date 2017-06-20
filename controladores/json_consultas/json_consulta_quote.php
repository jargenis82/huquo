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
		$quoteId = $myQuote->getAtributo("quote_id");
		$unDato [0] [0] = $myQuote->getAtributo("quote_number");
		$quoteTime = substr($myQuote->getAtributo("quote_date"),-8);
		$quoteDate = substr($myQuote->getAtributo("quote_date"),0,10);
		$quoteDate = formatoFecha($quoteDate);
		$quoteDate = formatoFechaBd($quoteDate, "m/d/Y");
		$quoteTotal = $myQuote->getQuoteTotal();
		$unDato [0] [1] = $quoteDate." ".$quoteTime;
		$unDato [0] [2] = "Annie Wang";
		$unDato [0] [3] = $quoteTotal;
		$unDato [0] [4] = "<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-file' data-modulo='' data-accion='' onclick=''></span>";
		$unDato [0] [5] = "<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-download-alt' data-modulo='' data-accion='' onclick='openPdfQuote($quoteId,true)'></span>";
		$arrJson ['aaData'] [] = $unDato [0];
	}
}

echo json_encode ( $arrJson );

?>