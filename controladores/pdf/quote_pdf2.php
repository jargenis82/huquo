<?php
include_once '../../conf.inc.php';
include_once '../../inc/funciones.php';
include_once '../../librerias/conexion_bd.php';
include_once '../../librerias/tbs_class/tbs_class.php';
include_once '../../clases/organisation.php';
include_once '../../clases/quote.php';

// Require composer autoload
require_once '../../vendor/autoload.php';
/*
// Se capturan 
$quoteId = comprobarVar ( $_GET ['quoteId'] ) ? limpiarPalabra ( aceptarComilla ( $_GET ['quoteId'] ) ) : null;
$pdf = comprobarVar ( $_GET ['pdf'] ) ? limpiarPalabra ( aceptarComilla ( $_GET ['pdf'] ) ) : null;

// Valida si existe el id de la cotización y el hash de búsqueda de la cotización
if ((comprobarVar ( $quoteId ) and ! comprobarVar ( $_SESSION ['user_id'] )) or (! comprobarVar ( $quoteId ) and ! comprobarVar ( $pdf ))) {
	exit ();
}

// Se genera la instancia de conexión con la BD
$miConexionBd = new ConexionBd ( "mysql" );

// Se genera la instancia con Quote
$myQuote = new Quote ( $miConexionBd );
if (comprobarVar ( $quoteId )) {
	$myQuote->setAtributo ( "quote_id", $quoteId );
} else {
	$myQuote->setAtributo ( "quote_hash", $pdf );
	$arrQuote = $myQuote->consultar ();
	if (count ( $arrQuote ) == 1) {
		$myQuote = $arrQuote [0];
	} else {
		exit ();
	}
}

// Se capturan los valores a mostrar en el PDF
$quoteId = $myQuote->getAtributo ( "quote_id" );
*/

// Variables de configuración del MPDF
$config['mode'] = 'utf-8';
$config['format'] = 'Letter';
$config['orientation'] = 'P';
$config['margin_top'] = '18.7';
$config['margin_left'] = '15.9';
$config['margin_right'] = '30.3';
$config['margin_bottom'] = '4.9';

// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf($config);



// Write some HTML code:
$mpdf->WriteHTML(file_get_contents('../../paginas/quote_pdf_body.tpl'));

// Output a PDF file directly to the browser
$mpdf->Output();
exit;
