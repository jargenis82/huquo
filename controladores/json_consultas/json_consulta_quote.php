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


 $unDato [0] [0] = '20171702a1';
 $unDato [0] [1] = "02/17/2017";
 $unDato [0] [2] = "Annie Wang";
 $unDato [0] [3] = '50.000';
 $unDato [0] [4] = "<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-file' data-modulo='' data-accion='' onclick='newQuote()'></span>";
 $unDato [0] [5] = "<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-download-alt' data-modulo='' data-accion='' onclick='quote()'></span>";

 $arrJson ['aaData'] [] = $unDato [0];

 



echo json_encode ( $arrJson );

?>