<?php
include_once '../conf.inc.php';
include_once '../inc/funciones.php';
include_once '../librerias/conexion_bd.php';
include_once '../librerias/xajax_0.2.4/xajax.inc.php';
include_once '../librerias/insightly.php';
include_once '../clases/price.php';
include_once '../clases/product.php';
include_once '../clases/product_sale.php';

$xajax = new xajax ( "ajax_funciones.php" );
$xajax->registerFunction ( "getCustomer" );
$xajax->registerFunction ( "getDescripProduct" );
$xajax->registerFunction ( "addNewProduct" );
$xajax->registerFunction ( "calculateAmount" );
$xajax->registerFunction ( "saveQuote" );
function saveQuote($quote, $arrProductSale, $arrProduct) {
	$objResponse = new xajaxResponse ();
	
	$objResponse->addAlert(var_export($quote,true));
	$objResponse->addAlert(var_export($arrProductSale,true));
	$objResponse->addAlert(var_export($arrProduct,true));
	
	return $objResponse;
}
function calculateAmount($id, $unit, $qty, $amountAct, $subtotal, $hstRate) {
	$objResponse = new xajaxResponse ();
	$unit = str_replace ( ".", "", $unit );
	$unit = str_replace ( ",", ".", $unit );
	$unit = doubleval ( $unit );
	$qty = doubleval ( $qty );
	$amountAct = str_replace ( ".", "", $amountAct );
	$amountAct = str_replace ( ",", ".", $amountAct );
	$amountAct = doubleval ( $amountAct );
	$subtotal = str_replace ( ".", "", $subtotal );
	$subtotal = str_replace ( ",", ".", $subtotal );
	$subtotal = doubleval ( $subtotal );
	$amount = $unit * $qty;
	$subtotal = $subtotal - $amountAct + $amount;
	$hstRate = doubleval ( "0.0$hstRate" );
	$hst = $subtotal * $hstRate;
	$total = $subtotal + $hst;
	$unit = number_format ( $unit, 2, ",", "." );
	$amount = number_format ( $amount, 2, ",", "." );
	$subtotal = number_format ( $subtotal, 2, ",", "." );
	$hst = number_format ( $hst, 2, ",", "." );
	$total = number_format ( $total, 2, ",", "." );
	if (comprobarVar ( $id )) {
		$objResponse->addAssign ( "txt_unit$id", "value", $unit );
		$objResponse->addAssign ( "span_amount$id", "innerHTML", $amount );
	}
	$objResponse->addAssign ( "span_subtotal", "innerHTML", $subtotal );
	$objResponse->addAssign ( "span_hst", "innerHTML", $hst );
	$objResponse->addAssign ( "span_total", "innerHTML", $total );
	return $objResponse;
}
function getDescripProduct($productSaleId, $txtDecrip, $customerRegionId, $priceTypeId, $quoteLineDesc) {
	$objResponse = new xajaxResponse ();
	$idTxtDescrip = str_replace ( "txt_decrip", "", $txtDecrip );
	$miConexionBd = new ConexionBd ( "mysql" );
	$myPrice = new Price ( $miConexionBd );
	$myPrice->setObjeto ( "ProductSale", $productSaleId );
	$myPrice->setObjeto ( "CustomerRegion", $customerRegionId );
	$myPrice->setObjeto ( "PriceType", $priceTypeId );
	$arrPrice = $myPrice->consultar ();
	if (count ( $arrPrice ) == 1) {
		$priceValue = $arrPrice [0]->getAtributo ( "price_value" );
		$priceValue = number_format ( $priceValue, 2, ",", "." );
		$objResponse->addAssign ( "txt_unit$idTxtDescrip", "value", $priceValue );
		$objResponse->addAssign ( "txt_qty$idTxtDescrip", "value", "" );
		$objResponse->addScript ( "calculateAmount('$idTxtDescrip');" );
		$objResponse->addScript ( "document.getElementById('txt_qty$idTxtDescrip').focus();" );
		$objResponse->addScript ( "arrProductSale[$idTxtDescrip]['product_sale_id'] = '$productSaleId';" );
		$objResponse->addScript ( "arrProductSale[$idTxtDescrip]['quote_line_desc'] = '$quoteLineDesc';" );
		$objResponse->addScript ( "arrProductSale[$idTxtDescrip]['quote_line_price'] = '$priceValue';" );
	}
	return $objResponse;
}
function addNewProduct($idTxtDescrip) {
	$objResponse = new xajaxResponse ();
	// $objResponse->addAlert("newRow");
	$_SESSION ['trId'] = $_SESSION ['trId'] + 1;
	$textoHtml = '<tr id="' . $_SESSION ['trId'] . '">';
	$textoHtml .= '<td><input id="txt_decrip' . $idTxtDescrip . '" class="form-control"></td>';
	$textoHtml .= '<td align="center"><input id="txt_unit' . $idTxtDescrip . '" size="7"  onchange="calculateAmount(' . $idTxtDescrip . ');" dir="rtl" onfocus="this.dir = ' . "\'ltr\'" . ';" onblur="this.dir = ' . "\'rtl\'" . ';"></td>';
	$textoHtml .= '<td align="center"><input id="txt_qty' . $idTxtDescrip . '" size="2" onKeyDown="javascript:return introQty(event);"  onchange="calculateAmount(' . $idTxtDescrip . ');" dir="rtl" onfocus="this.dir = ' . "\'ltr\'" . ';" onblur="this.dir = ' . "\'rtl\'" . ';"></td>';
	$textoHtml .= '<td align="right"><span id="span_amount' . $idTxtDescrip . '" size="4"></span></td>';
	$jq = "
     		var tr='$textoHtml';
    		$('#descripId').append(tr);
";
	$js = "
	$( function() {

      $( '#txt_decrip$idTxtDescrip' ).autocomplete({
        source: availableDescrip
      });

     $(document).ready(function () {
	    $('#txt_decrip$idTxtDescrip').on('autocompleteselect', function (e, ui) {
	    	 	var i = availableDescrip.indexOf(ui.item.value);
	    	 	xajax_getDescripProduct(availableId[i],this.id,customerRegionId,priceTypeId,ui.item.value);
	    });
		});
     } )";
	
	$objResponse->addScript ( $jq );
	$objResponse->addScript ( $js );
	$objResponse->addScript ( "document.getElementById('txt_decrip$idTxtDescrip').focus();" );
	$objResponse->addScript ( "window.parent.ajustarIframe();" );
	return $objResponse;
}
function getCustomer($customerName, $getOpportunities) {
	$objResponse = new xajaxResponse ();
	$objResponse->addAssign ( "span_address", "innerHTML", "" );
	$objResponse->addAssign ( "a_web", "href", "" );
	$objResponse->addAssign ( "a_web", "innerHTML", "" );
	$objResponse->addAssign ( "span_phone", "innerHTML", "" );
	
	// Se consulta una copia local para mejorar el desempeño en máquinas de desarrollo (provisional)
	if (defined ( "CUSTOMERS" )) {
		$getCustomer = file_get_contents ( "../log/getCustomer" );
		$getCustomer = explode ( ";", $getCustomer );
		$organizationId = $getCustomer [0];
		$address = $getCustomer [1];
		$web = $getCustomer [2];
		$phone = $getCustomer [3];
		$customerName = $getCustomer [4];
		$objResponse->addScript ( "organizationId = '$organizationId';" );
		$objResponse->addAssign ( "span_address", "innerHTML", $address );
		$objResponse->addAssign ( "a_web", "href", $web );
		$objResponse->addAssign ( "a_web", "innerHTML", $web );
		$objResponse->addAssign ( "span_phone", "innerHTML", $phone );
		$objResponse->addScript ( "dataTable('$customerName');" );
		return $objResponse;
	}
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
