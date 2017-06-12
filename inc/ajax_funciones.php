<?php
include_once '../conf.inc.php';
include_once '../inc/funciones.php';
include_once '../librerias/conexion_bd.php';
include_once '../librerias/xajax_0.2.4/xajax.inc.php';
include_once '../librerias/insightly.php';

$xajax = new xajax ( "ajax_funciones.php" );
$xajax->registerFunction ( "getCustomer" );
$xajax->registerFunction ( "getDescripProduct");
$xajax->registerFunction ( "addNewProduct");

function getDescripProduct($productName){
	$objResponse = new xajaxResponse ();
	$unitPrice=5000;
	$qty="12";
	$amount=$unitPrice*$qty;
	$objResponse->addAssign("span_price","innerHTML",$unitPrice);
	
	return $objResponse;
}
function addNewProduct(){
	$objResponse = new xajaxResponse ();
	//$objResponse->addAlert("newRow");
	$_SESSION['trId'] = $_SESSION['trId']+1;
	$textoHtml        = '<tr id="'.$_SESSION['trId'].'">';
	$textoHtml .= '<td><input id="txt_decrip" ></td>';
	$textoHtml .='<td align="center"><input id="txt_discunt" size="4"></td>';
	$textoHtml.='<td align="center"><span id="span_price"></span></td>';
	$textoHtml.='<td align="center"><input type="txt_qty" size="4"></td>';
	$textoHtml.='<td align="center"><span id="span_amount" size="4"></span></td>';
	$textoHtml.='<td align="center"><span id="" class="btn btn-default btn-xs glyphicon glyphicon-remove"  onclick=""></span></td>';
	$jq = "
     		var tr='$textoHtml';
    		$('#descripId').append(tr);
	";
  $js ="
	$( function() {
    var availableDescrip = ['''Arkcom'''];       
      $( '#txt_decrip' ).autocomplete({
        source: availableDescrip        
      });
      
     $(document).ready(function () {
	    $('#txt_decrip').on('autocompleteselect', function (e, ui) {
	    	 	xajax_getDescripProduct(ui.item.value)	        
	    });'
		});  
     } )";
	

	$objResponse->addScript($jq);
	$objResponse->addScript($js);
	return $objResponse;
}


function getCustomer($customerName, $getOpportunities) {
	$objResponse = new xajaxResponse ();
	$objResponse->addAssign ( "span_address", "innerHTML", "" );
	$objResponse->addAssign ( "a_web", "href", "" );
	$objResponse->addAssign ( "a_web", "innerHTML", "" );
	$objResponse->addAssign ( "span_phone", "innerHTML", "" );
	$i = new Insightly ( APIKEY );
	$options ['filters'] [0] = "ORGANISATION_NAME = '$customerName'";
	$arrOrganization = $i->getOrganizations ( $options );
	if (isset ( $arrOrganization [0] )) {
		$organizationId = $arrOrganization [0]->ORGANISATION_ID;
		$objResponse->addScript ( "organizationId = '$organizationId';" );
		$arrAddresses = $arrOrganization [0]->ADDRESSES;
		if (isset ( $arrAddresses [0] )) {
			$address = $arrAddresses [0]->STREET . ", " . $arrAddresses [0]->CITY . ", " . $arrAddresses [0]->COUNTRY . ".";
			$address = str_replace ( "\n", " ", $address );
			$address = str_replace ( "\r", " ", $address );
			$objResponse->addAssign ( "span_address", "innerHTML", $address );
		}
		$arrContactInfos = $arrOrganization [0]->CONTACTINFOS;
		$contadorW = 0;
		$contadorP = 0;
		foreach ( $arrContactInfos as $myContactInfos ) {
			if ($myContactInfos->TYPE == "WEBSITE" and $contadorW == 0) {
				$web = $myContactInfos->DETAIL;
				$objResponse->addAssign ( "a_web", "href", $web );
				$objResponse->addAssign ( "a_web", "innerHTML", $web );
				$contadorW ++;
			}
			if ($myContactInfos->TYPE == "PHONE" and $contadorP == 0) {
				$phone = $myContactInfos->DETAIL;
				$objResponse->addAssign ( "span_phone", "innerHTML", $phone );
				$contadorP ++;
			}
			if (($contadorP + $contadorW) == 2) {
				break 1;
			}
		}
		if (comprobarVar ( $getOpportunities ) and $getOpportunities == "1") {
			$objResponse->addScript ( "dataTable('$customerName');" );
		}
	}
	return $objResponse;
}

$xajax->processRequests ();
?>
