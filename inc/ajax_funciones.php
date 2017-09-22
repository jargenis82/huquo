<?php
include_once '../conf.inc.php';
include_once '../inc/funciones.php';
include_once '../librerias/conexion_bd.php';
include_once '../librerias/xajax_0.2.4/xajax.inc.php';
include_once '../librerias/insightly.php';
include_once '../librerias/class-phpass.php';
include_once '../clases/contact.php';
include_once '../clases/organisation.php';
include_once '../clases/price.php';
include_once '../clases/product.php';
include_once '../clases/product_sale.php';
include_once '../clases/quote.php';
include_once '../clases/quote_line.php';
include_once '../clases/user.php';

$xajax = new xajax ( "ajax_funciones.php" );
$xajax->registerFunction ( "getCustomer" );
$xajax->registerFunction ( "getDescripProduct" );
$xajax->registerFunction ( "addNewProduct" );
$xajax->registerFunction ( "calculateAmount" );
$xajax->registerFunction ( "calculateDiscount" );
$xajax->registerFunction ( "saveQuote" );
$xajax->registerFunction ( "getContactInfos" );
$xajax->registerFunction ( "validateUser" );
function getContactInfos($contactInsId, $contactEmail) {
	$objResponse = new xajaxResponse ();
	$i = new Insightly ( APIKEY );
	$myContact = $i->getContact ( $contactInsId );
	$arrContactInfos = $myContact->CONTACTINFOS;
	$htmlSelect = '';
	foreach ( $arrContactInfos as $i => $myContactinfo ) {
		if ($myContactinfo->TYPE == "EMAIL") {
			$selected = '';
			if (comprobarVar ( $contactEmail )) {
				if (trim ( $myContactinfo->DETAIL ) == $contactEmail) {
					$selected = 'selected="selected"';
				}
			}
			$htmlSelect .= '<option value="' . $myContactinfo->DETAIL . '" ' . $selected . '>' . $myContactinfo->DETAIL . '</option>';
		}
	}
	$htmlSelect = $htmlSelect == '' ? '<option value="0">Any...</option>' : $htmlSelect;
	$objResponse->addAssign ( "sel_email", "innerHTML", $htmlSelect );
	return $objResponse;
}
function validateUser($user, $password) {
	$objResponse = new xajaxResponse ();
	// Se ajusta el nombre de usuario de tal manera que no tenga espacios en blanco y permita las comillas
	// Se evita la inyección de código
	$user = aceptarComilla ( limpiarPalabra ( $user ) );
	// Se crea enlace con base de datos a wordpress (FTP)
	$enlace = new PDO ( "mysql:host=" . W . ";port=" . V . ";dbname=" . ZZ, XX, YY );
	$miConexionBd = new ConexionBd ( null, $enlace );
	// Se consulta el usuario en Wordpress para obtener el HASH del password
	$r = $miConexionBd->hacerSelect ( "user_pass,user_email,display_name", "wp_users", "user_login = '$user'" );
	if (comprobarVar ( $r [0] ['user_pass'] )) {
		// Se instancia la clase para validar el password con el HASH
		$myPasswordHash = new PasswordHash ();
		if ($myPasswordHash->CheckPassword ( aceptarComilla ( $password ), $r [0] ['user_pass'] )) {
			// Se busca el usuario en la BD de Huquo
			$miConexionBd = new ConexionBd ( "mysql" );
			$miConexionBd->hacerConsulta ( "BEGIN;" );
			$myUser = new User ( $miConexionBd );
			$myUser->setAtributo ( "user_login_ftp", $user );
			$myUser->setAtributo ( "user_name", utf8_encode ( $r [0] ['display_name'] ) );
			$myUser->setAtributo ( "user_email", utf8_encode ( $r [0] ['user_email'] ) );
			$arrUser = $myUser->consultar ();
			// Si existe el usuario obtengo los datos a mantener en la variable de sesión
			if (count ( $arrUser ) == 1) {
				$myUser = $arrUser [0];
			} else {
				$miConexionBd->hacerConsulta ( "ROLLBACK;" );
				$objResponse->addAlert ( "Restricted access. Please contact your administrator." );
				return $objResponse;
				/**
				$myUser->setAtributo ( "user_creation_date", date ( "Y-m-d H:i:s" ) );
				if (! $myUser->registrar ()) {
					$miConexionBd->hacerConsulta ( "ROLLBACK;" );
					$objResponse->addAlert ( "Error (VU-001). Please contact your administrator." );
					return $objResponse;
				}
				*/
			}
			$_SESSION ['user_id'] = $myUser->getAtributo ( "user_id" );
			$_SESSION ['user_login_ftp'] = $myUser->getAtributo ( "user_login_ftp" );
			$_SESSION ['user_name'] = $myUser->getAtributo ( "user_name" );
			$_SESSION ['user_email'] = $myUser->getAtributo ( "user_email" );
			$miConexionBd->hacerConsulta ( "COMMIT;" );
			$objResponse->addScript ( "window.top.location.reload(true);" );
		} else {
			$objResponse->addAlert ( "Datos incorrectos. Intente nuevamente." );
		}
	} else { // Si no existe el usuario en el FTP termina
		$objResponse->addAlert ( "Datos incorrectos. Intente nuevamente." );
	}
	return $objResponse;
}
function saveQuote($quote, $arrProduct) {
	$objResponse = new xajaxResponse ();
	// Se crea una instancia de conexión con la BD para todas las clases y transacciones
	$miConexionBd = new ConexionBd ( "mysql" );
	$miConexionBd->hacerConsulta ( "BEGIN;" );
	// Se verifica si los datos de la organización ya existe en la Base de Datos o si se han modificado desde la última carga
	// en caso afirmativo se crea un nuevo registro en la tabla organisation
	$myOrganisation = new Organisation ( $miConexionBd );
	$myOrganisation->setAtributo ( "org_name", $quote ['org_name'] );
	$myOrganisation->setAtributo ( "org_address", $quote ['org_address'] );
	$myOrganisation->setAtributo ( "org_web", $quote ['org_web'] );
	$myOrganisation->setAtributo ( "org_phone", $quote ['org_phone'] );
	$myOrganisation->setAtributo ( "org_city", $quote ['org_city'] );
	$myOrganisation->setAtributo ( "org_country", $quote ['org_country'] );
	$myOrganisation->setAtributo ( "org_ins_id", $quote ['org_ins_id'] );
	$arrOrganisation = $myOrganisation->consultar ();
	if (count ( $arrOrganisation ) > 1) {
		$miConexionBd->hacerConsulta ( "ROLLBACK;" );
		$objResponse->addAlert ( "Error (SQ-001). Please contact your administrator." );
		return $objResponse;
	}
	if (count ( $arrOrganisation ) == 0) {
		if (! $myOrganisation->registrar ()) {
			$miConexionBd->hacerConsulta ( "ROLLBACK;" );
			$objResponse->addAlert ( "Error (SQ-002). Please contact your administrator." );
			return $objResponse;
		}
	} else {
		$myOrganisation = $arrOrganisation [0];
	}
	$orgId = $myOrganisation->getAtributo ( "org_id" );
	// Se verifica si los datos del contacto ya existe en la Base de Datos o si se han modificado desde la última carga
	// en caso afirmativo se crea un nuevo registro en la tabla contact
	$myContact = new Contact ( $miConexionBd );
	$myContact->setAtributo ( "contact_name", $quote ['contact_name'] );
	$myContact->setAtributo ( "contact_email", $quote ['contact_email'] );
	$myContact->setAtributo ( "contact_ins_id", $quote ['contact_ins_id'] );
	$arrContact = $myContact->consultar ();
	if (count ( $arrContact ) > 1) {
		$miConexionBd->hacerConsulta ( "ROLLBACK;" );
		$objResponse->addAlert ( "Error (SQ-0021). Please contact your administrator." );
		return $objResponse;
	}
	if (count ( $arrContact ) == 0) {
		if (! $myContact->registrar ()) {
			$miConexionBd->hacerConsulta ( "ROLLBACK;" );
			$objResponse->addAlert ( "Error (SQ-0022). Please contact your administrator." );
			return $objResponse;
		}
	} else {
		$myContact = $arrContact [0];
	}
	$contactId = $myContact->getAtributo ( "contact_id" );
	// Se carga una nueva instancia de cotización
	$myQuote = new Quote ( $miConexionBd );
	// Se valida si la fecha ha cambiado con respecto a la que se muestra por pantalla
	$quoteDate = date ( "Y-m-d H:i:s" );
	$myQuote->setAtributo ( "quote_date", $quoteDate );
	$quoteDatePage = date ( "Y-m-d", strtotime ( $quote ['quote_date'] ) );
	if (substr ( $quoteDate, 0, 10 ) != $quoteDatePage) {
		$newDate = formatoFechaBd ( formatoFecha ( substr ( $quoteDate, 0, 10 ) ), "d-M-Y" );
		$objResponse->addAlert ( "The quote date has changed to $newDate." );
	}
	$myQuote->setAtributo ( "quote_valid_until", date ( "Y-m-d", strtotime ( $quote ['quote_valid_until'] ) ) );
	$myQuote->setAtributo ( "quote_discount", convertToDoubleval ( $quote ['quote_discount'] ) );
	$myQuote->setAtributo ( "quote_hst_rate", convertToDoubleval ( $quote ['quote_hst_rate'] ) );
	$myQuote->setAtributo ( "quote_ship_to", $quote ['quote_ship_to'] );
	$quoteNumber = $myQuote->getNextQuoteNumber ();
	$myQuote->setAtributo ( "quote_number", $quoteNumber );
	$hash = sha1 ( rand ( 0, 1000 ) . time () );
	$myQuote->setAtributo ( "quote_hash", $hash );
	if ($quoteNumber != $quote ['quote_number']) {
		$objResponse->addAlert ( "The quote number has changed to $quoteNumber." );
	}
	$myQuote->setObjeto ( "Organisation", $orgId );
	$myQuote->setObjeto ( "Contact", $contactId );
	$myQuote->setObjeto ( "User", $_SESSION ['user_id'] );
	$myQuote->setAtributo ( "oppor_id", $quote ['oppor_id'] );
	$myQuote->setAtributo ( "quote_comment", $quote ['quote_comment'] );
	// Se registra la cotización y se obtiene el quote_id
	if (! $myQuote->registrar ()) {
		$miConexionBd->hacerConsulta ( "ROLLBACK;" );
		$objResponse->addAlert ( "Error (SQ-003). Please contact your administrator." );
		return $objResponse;
	}
	$quoteId = $myQuote->getAtributo ( "quote_id" );
	// Se cargan las instancias de quote_line
	foreach ( $arrProduct as $i => $aProduct ) {
		if (comprobarVar ( $aProduct ['quote_line_desc'] ) or comprobarVar ( $aProduct ['quote_line_price'] ) or comprobarVar ( $aProduct ['quote_line_qty'] )) {
			$myQuoteLine = new QuoteLine ( $miConexionBd );
			$myQuoteLine->setAtributo ( 'quote_line_desc', $aProduct ['quote_line_desc'] );
			$quoteLinePrice = convertToDoubleval ( $aProduct ['quote_line_price'] );
			$myQuoteLine->setAtributo ( 'quote_line_price', $quoteLinePrice );
			$myQuoteLine->setAtributo ( 'quote_line_qty', $aProduct ['quote_line_qty'] );
			$myQuoteLine->setObjeto ( "Quote", $quoteId );
			if (comprobarVar ( $aProduct ['product_sale_id'] )) {
				$myQuoteLine->setObjeto ( "ProductSale", $aProduct ['product_sale_id'] );
				// Si el precio cambió se activa una bandera en la tabla quote_line para identificarlo
				$quoteLineEditPrice = "0";
				if ($quoteLinePrice != $aProduct ['product_sale_price']) {
					$quoteLineEditPrice = "1";
				}
				$myQuoteLine->setAtributo ( 'quote_line_edit_price', $quoteLineEditPrice );
				// Si la descripción cambió se activa una bandera en la tabla quote_line para identificarlo
				$quoteLineEditDesc = "0";
				if ($aProduct ['quote_line_desc'] != $aProduct ['product_sale_desc']) {
					$quoteLineEditDesc = "1";
				}
				$myQuoteLine->setAtributo ( 'quote_line_edit_desc', $quoteLineEditDesc );
			}
			if (! $myQuoteLine->registrar ()) {
				$miConexionBd->hacerConsulta ( "ROLLBACK;" );
				$objResponse->addAlert ( "Error (SQ-004-$i). Please contact your administrator." );
				return $objResponse;
			}
		}
	}
	if (! $miConexionBd->hacerConsulta ( "COMMIT;" )) {
		$miConexionBd->hacerConsulta ( "ROLLBACK;" );
		$objResponse->addAlert ( "Error (SQ-005). Please contact your administrator." );
		return $objResponse;
	}
	if (comprobarVar ( strstr ( $_SERVER ['HTTP_REFERER'], "hubrox.com" ) )) {
		// Se busca el usuario de la cotizacion para obtener el API key de Insightly
		$myUser = $myQuote->getObjeto('User');
		$userApiKey = $myUser->getAtributo('user_api_key');
		if (!comprobarVar($userApiKey)) {
			$userApiKey = APIKEY;
		}
		// Se agrega un enlace de la cotización en Insighly
		$i = new Insightly ( $userApiKey );
		$objeto = new stdClass ();
		$objeto->TITLE = 'Quote of HUQUO - '.$quoteNumber;
		$objeto->LINK_SUBJECT_ID = $quote ['oppor_id'];
		$objeto->LINK_SUBJECT_TYPE = 'Opportunity';
		$objeto->BODY = '<a href="http://www.hubrox.com/huquo_pro/controladores/pdf/quote_pdf.php?pdf=' . $hash . '" target="_blank">Quote ' . $quoteNumber . '</a>';
		$noteLinks = new stdClass ();
		$noteLinks->ORGANISATION_ID = $quote ['org_ins_id'];
		$arrNoteLinks [0] = $noteLinks;
		$objeto->NOTELINKS = $arrNoteLinks;
		$objeto = $i->addNote ( $objeto );
	}
	$objResponse->addScript ( "openPdfQuote($quoteId);" );
	$objResponse->addScript ( "window.parent.opener.dataTable('" . $quote ['org_name'] . "');" );
	$objResponse->addScript ( "window.parent.opener.dataTableQuote(" . $quote ['oppor_id'] . ",'OPEN');" );
	return $objResponse;
}
function calculateAmount($id, $unit, $qty, $amountAct, $subtotal, $hstRate, $productSaleId) {
	$objResponse = new xajaxResponse ();
	$unit = convertToDoubleval ( $unit );
	$qty = doubleval ( $qty );
	$amountAct = convertToDoubleval ( $amountAct );
	$subtotal = convertToDoubleval ( $subtotal );
	$amount = $unit * $qty;
	$subtotal = $subtotal - $amountAct + $amount;
	if (comprobarVar ( $productSaleId )) {
		$objResponse->addScript ( "subTotalProducts = subTotalProducts - $amountAct + $amount;" );
	}
	$unit = number_format ( $unit, 2, ".", "," );
	$amount = number_format ( $amount, 2, ".", "," );
	$subtotal = number_format ( $subtotal, 2, ".", "," );
	if (comprobarVar ( $id )) {
		$objResponse->addAssign ( "txt_unit$id", "value", $unit );
		$objResponse->addAssign ( "span_amount$id", "innerHTML", $amount );
	}
	$objResponse->addScript ( "xajax_calculateDiscount('txt_discount_val',discount,subTotalProducts,discount,'$subtotal','$hstRate');" );
	return $objResponse;
}
function calculateDiscount($id, $val, $subTotalProducts, $discountAct, $subtotal, $hstRate) {
	$objResponse = new xajaxResponse ();
	$subTotalProducts = doubleval ( $subTotalProducts );
	if ($id == "txt_discount_val") {
		$val = convertToDoubleval ( $val );
		if ($subTotalProducts > 0) {
			$per = ($val / $subTotalProducts) * 100;
		} else {
			$per = 0;
		}
	} else if ($id == "txt_discount_per") {
		$per = convertToDoubleval ( $val );
		$val = ($per / 100) * $subTotalProducts;
	}
	$discountAct = doubleval ( $discountAct );
	$subtotal = convertToDoubleval ( $subtotal );
	$subtotal = $subtotal + $discountAct - $val;
	$hstRate = doubleval ( $hstRate );
	$hstRate = $hstRate / 100;
	$hst = $subtotal * $hstRate;
	$hstRate = $hstRate * 100;
	$total = $subtotal + $hst;
	$objResponse->addScript ( "discount = $val;" );
	$val = number_format ( $val, 2, ".", "," );
	$per = number_format ( $per, 2, ".", "," );
	$subtotal = number_format ( $subtotal, 2, ".", "," );
	$hst = number_format ( $hst, 2, ".", "," );
	$total = number_format ( $total, 2, ".", "," );
	$objResponse->addAssign ( "txt_discount_val", "value", $val );
	$objResponse->addAssign ( "txt_discount_per", "value", $per );
	$objResponse->addAssign ( "span_subtotal", "innerHTML", $subtotal );
	$objResponse->addAssign ( "txt_hst_rate", "value", $hstRate );
	$objResponse->addAssign ( "span_hst", "innerHTML", $hst );
	$objResponse->addAssign ( "span_total", "innerHTML", $total );
	return $objResponse;
}
function getDescripProduct($productSaleId, $txtDecrip, $customerRegionId, $priceTypeId, $quoteLineDesc, $exchangeRate) {
	$objResponse = new xajaxResponse ();
	$idTxtDescrip = str_replace ( "txt_decrip", "", $txtDecrip );
	$miConexionBd = new ConexionBd ( "mysql" );
	$myPrice = new Price ( $miConexionBd );
	$myPrice->setObjeto ( "ProductSale", $productSaleId );
	$myPrice->setObjeto ( "CustomerRegion", $customerRegionId );
	$myPrice->setObjeto ( "PriceType", $priceTypeId );
	$arrPrice = $myPrice->consultar ();
	if (count ( $arrPrice ) == 1) {
		$priceValue = doubleval ( $arrPrice [0]->getAtributo ( "price_value" ) );
		$priceValue = $priceValue * doubleval ( $exchangeRate );
		$priceValue = number_format ( $priceValue, 2, ".", "," );
		$objResponse->addAssign ( "txt_unit$idTxtDescrip", "value", $priceValue );
		$objResponse->addAssign ( "txt_qty$idTxtDescrip", "value", "" );
		$objResponse->addScript ( "arrProductSale[$idTxtDescrip]['product_sale_id'] = '$productSaleId';" );
		$objResponse->addScript ( "arrProductSale[$idTxtDescrip]['quote_line_desc'] = '$quoteLineDesc';" );
		$objResponse->addScript ( "arrProductSale[$idTxtDescrip]['quote_line_price'] = '$priceValue';" );
		$objResponse->addScript ( "calculateAmount('$idTxtDescrip');" );
		$objResponse->addScript ( "document.getElementById('txt_qty$idTxtDescrip').focus();" );
	}
	return $objResponse;
}
function addNewProduct($idTxtDescrip, $quoteId, $customerRegionId, $priceTypeId) {
	$objResponse = new xajaxResponse ();
	if (comprobarVar ( $idTxtDescrip ) and intval ( $idTxtDescrip ) != 0) {
		$tabIndex = (intval ( $idTxtDescrip ) - 1) * 3 + 9;
		$_SESSION ['trId'] = $_SESSION ['trId'] + 1;
		$textoHtml = '<tr id="' . $_SESSION ['trId'] . '">';
		$textoHtml .= '<td><input id="txt_decrip' . $idTxtDescrip . '" tabindex="' . $tabIndex . '" required="required" class="form-control" ></td>';
		$textoHtml .= '<td align="center"><input id="txt_unit' . $idTxtDescrip . '" tabindex="' . ($tabIndex + 1) . '" required="required" onkeydown="javascript:return introTxt(event,this)" size="7" class="validNumber" onchange="calculateAmount(' . $idTxtDescrip . ');" dir="rtl" onfocus="this.dir = ' . "\'ltr\'" . ';" onblur="this.dir = ' . "\'rtl\'" . ';"></td>';
		$textoHtml .= '<td align="center"><input id="txt_qty' . $idTxtDescrip . '" tabindex="' . ($tabIndex + 2) . '" required="required" size="4" class="validNumber" onKeyDown="javascript:return introQty(event);"  onchange="calculateAmount(' . $idTxtDescrip . ');" dir="rtl" onfocus="this.dir = ' . "\'ltr\'" . ';" onblur="this.dir = ' . "\'rtl\'" . ';"></td>';
		$textoHtml .= '<td align="right"><span id="span_amount' . $idTxtDescrip . '"></span></td>';
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
	    	 	var exchangeRate = $('#txt_exchange_rate').val();
	    	 	xajax_getDescripProduct(availableId[i],this.id,customerRegionId,priceTypeId,ui.item.value,exchangeRate);
	    });
		});
     } )";
		
		$objResponse->addScript ( $jq );
		$objResponse->addScript ( $js );
		$objResponse->addScript ( "window.parent.ajustarIframe();" );
		$objResponse->addScript ( "document.getElementById('txt_decrip$idTxtDescrip').focus();" );
		$objResponse->addScript ( "validarKeyPress();" );
	}
	if (comprobarVar ( $quoteId ) and comprobarVar ( $customerRegionId ) and comprobarVar ( $priceTypeId )) {
		$miConexionBd = new ConexionBd ( "mysql" );
		$myQuoteLine = new QuoteLine ( $miConexionBd );
		$myQuoteLine->setObjeto ( "Quote", $quoteId );
		$cantidad = $myQuoteLine->consultar ( true );
		$totalNewProduct = intval ( $idTxtDescrip ) + 1;
		if ($totalNewProduct == $cantidad) {
			$arrQuoteLine = $myQuoteLine->consultar ();
			$j = 0;
			$subTotal = doubleval ( 0 );
			$subTotalProducts = doubleval ( 0 );
			foreach ( $arrQuoteLine as $i => $aQuoteLine ) {
				$quoteLineDesc = $aQuoteLine->getAtributo ( "quote_line_desc" );
				$quoteLinePrice = convertToDoubleval ( $aQuoteLine->getAtributo ( "quote_line_price" ) );
				$quoteLineQty = intval ( $aQuoteLine->getAtributo ( "quote_line_qty" ) );
				$quoteLineAmount = $quoteLinePrice * $quoteLineQty;
				$subTotal += $quoteLineAmount;
				$myProductSale = $aQuoteLine->getObjeto ( "ProductSale" );
				if (isset ( $myProductSale )) {
					$productSaleId = $myProductSale->getAtributo ( "product_sale_id" );
					$myPrice = new Price ( $miConexionBd );
					$myPrice->setObjeto ( "ProductSale", $productSaleId );
					$myPrice->setObjeto ( "CustomerRegion", $customerRegionId );
					$myPrice->setObjeto ( "PriceType", $priceTypeId );
					$arrPrice = $myPrice->consultar ();
					if (count ( $arrPrice ) == 1) {
						$priceValue = $arrPrice [0]->getAtributo ( "price_value" );
						$priceValue = number_format ( $priceValue, 2, ".", "," );
						$objResponse->addScript ( "arrProductSale[$i]['product_sale_id'] = '$productSaleId';" );
						$objResponse->addScript ( "arrProductSale[$i]['quote_line_desc'] = availableDescrip[availableId.indexOf('$productSaleId')];" );
						$objResponse->addScript ( "arrProductSale[$i]['quote_line_price'] = '$priceValue';" );
						$objResponse->addScript ( "subTotalProducts = subTotalProducts + $quoteLineAmount;" );
						$subTotalProducts += $quoteLineAmount;
					}
				}
				$quoteLinePrice = number_format ( $quoteLinePrice, 2, ".", "," );
				$quoteLineAmount = number_format ( $quoteLineAmount, 2, ".", "," );
				$objResponse->addAssign ( "txt_decrip$i", "value", $quoteLineDesc );
				$objResponse->addAssign ( "txt_unit$i", "value", $quoteLinePrice );
				$objResponse->addAssign ( "txt_qty$i", "value", $quoteLineQty );
				$objResponse->addAssign ( "span_amount$i", "innerHTML", $quoteLineAmount );
			}
			$myQuote = new Quote ( $miConexionBd, $quoteId );
			$myContact = $myQuote->getObjeto ( "Contact" );
			if (isset ( $myContact )) {
				$contactName = $myContact->getAtributo ( "contact_name" );
				$contactEmail = $myContact->getAtributo ( "contact_email" );
				$contactInsId = $myContact->getAtributo ( "contact_ins_id" );
				$objResponse->addAssign ( "txt_contact", "value", $contactName );
				$objResponse->addScript ( "contactInsId = '$contactInsId';" );
				$objResponse->addScript ( "xajax_getContactInfos($contactInsId,'$contactEmail');" );
			}
			$quoteComment = $myQuote->getAtributo ( "quote_comment" );
			$quoteDiscount = convertToDoubleval ( $myQuote->getAtributo ( "quote_discount" ) );
			$objResponse->addScript ( "discount = $quoteDiscount;" );
			if ($subTotalProducts > 0) {
				$quoteDiscountPer = $quoteDiscount * 100 / $subTotalProducts;
			} else {
				$quoteDiscountPer = 0;
			}
			$subTotal = $subTotal - $quoteDiscount;
			$quoteHstRate = doubleval ( $myQuote->getAtributo ( "quote_hst_rate" ) );
			$quoteHst = $subTotal * $quoteHstRate / 100;
			$total = $subTotal + $quoteHst;
			$quoteDiscount = number_format ( $quoteDiscount, 2, ".", "," );
			$quoteDiscountPer = number_format ( $quoteDiscountPer, 2, ".", "," );
			$subTotal = number_format ( $subTotal, 2, ".", "," );
			$quoteHst = number_format ( $quoteHst, 2, ".", "," );
			$total = number_format ( $total, 2, ".", "," );
			$objResponse->addAssign ( "txt_comment", "value", $quoteComment );
			$objResponse->addAssign ( "txt_discount_val", "value", $quoteDiscount );
			$objResponse->addAssign ( "txt_discount_per", "value", $quoteDiscountPer );
			$objResponse->addAssign ( "span_subtotal", "innerHTML", $subTotal );
			$objResponse->addAssign ( "txt_hst_rate", "value", $quoteHstRate );
			$objResponse->addAssign ( "span_hst", "innerHTML", $quoteHst );
			$objResponse->addAssign ( "span_total", "innerHTML", $total );
			$objResponse->addScript ( "document.getElementById('txt_contact').focus();" );
		} else {
			$objResponse->addScript ( "addNewProduct($quoteId);" );
		}
	}
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
