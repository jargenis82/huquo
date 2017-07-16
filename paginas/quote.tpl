<!DOCTYPE html>
<html lang="en">
<head>
<title>Hubrox Technology Inc.</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Boostrap -->
<link rel="stylesheet"
	href="../librerias/bootstrap3.3.7/css/bootstrap.min.css">
<style type="text/css">
form input:focus:invalid {
	border: 2px solid red;
}
</style>
[var.js;htmlconv=no;noerr]
<script type="text/javascript"
	src="../librerias/jquery_actual/js/jquery.min.js"></script>
<script type="text/javascript"
	src="../librerias/bootstrap3.3.7/js/bootstrap.min.js"></script>

<!-- data table styles-->
<link rel="stylesheet" type="text/css"
	href="../librerias/jquery_actual/css/jquery-ui.css">
<link rel="stylesheet" type="text/css"
	href="../librerias/jquery-DataTables-1.10.7/media/css/dataTables.jqueryui.min.css">

<!-- SCRIPTS -->

<script type="text/javascript"
	src='../librerias/jquery-DataTables-1.10.7/media/js/jquery.dataTables.min.js'></script>
<script type="text/javascript"
	src='../librerias/jquery-DataTables-1.10.7/media/js/dataTables.jqueryui.min.js'></script>


<!--autoComplete-->
<script type="text/javascript"
	src='../librerias/jquery_actual/js/jquery-ui.js'></script>
<!-- js-->

<script type="text/javascript" src='../js/hubrox_op.js'></script>
<style type="text/css">
table, td, th {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	border-color: blue;
}
</style>

<script type="text/javascript">
	// Se declara el DataTable de la cotizaciones (filas de productos, precios, cantidades y montos)
	$(document).ready(function() {
		var table = $('#descripId').DataTable({
			searching: false,
			"scrollY": "auto",
			minDate: "today",
			"bInfo": false,
			"paging": false
	    });
	});
	
	// Variables generales
	var contactInsId = "";
	var idTxtDescrip = 0;
	var availableContact = [ [var.jsDataContact;htmlconv=no;noerr]  ];
	var availableContactId = [ [var.jsDataContactId;htmlconv=no;noerr]  ];
	var availableDescrip = [ [var.jsData;htmlconv=no;noerr]  ];
	var availableId = [ [var.jsDataId;htmlconv=no;noerr]  ];
	var opportunityId = "[var.opportunityId;noerr]";
	var organizationId = "[var.organizationId;noerr]";
	var customerRegionId = "[var.customerRegionId;noerr]";
	var priceTypeId = "[var.priceTypeId;noerr]";
	var arrProductSale = new Array();
	arrProductSale[0] = new Array();
	arrProductSale[0]['product_sale_id'] = "";
	arrProductSale[0]['quote_line_desc'] = "";
	arrProductSale[0]['quote_line_price'] = "";
	var subTotalProducts = 0.00;
	var discount = 0.00;
	
	// Funcion que prepara la cotizacion para su registro en XAJAX
	function saveQuote() {
		var quote = new Array();
		quote['contact_name'] = $('#txt_contact').val();
		quote['contact_email'] = $('#sel_email').val();
		quote['contact_ins_id'] = contactInsId;
		quote['quote_date'] = $('#span_date').text();
		quote['quote_valid_until'] = $('#txt_valid_until').val();
		quote['quote_discount'] = $('#txt_discount_val').val();
		quote['quote_hst_rate'] = $('#txt_hst_rate').val();
		quote['quote_ship_to'] = $('#txt_ship_to').val();
		quote['quote_number'] = $('#span_number').text();
		quote['oppor_id'] = opportunityId;
		quote['quote_comment'] = $('#txt_comment').val();
		quote['org_name'] = $('#span_name').text();
		quote['org_address'] = $('#span_address').text();
		quote['org_web'] = $('#href_web').html();
		quote['org_phone'] = $('#span_phone').text();
		quote['org_city'] = "[var.city;noerr]";
		quote['org_country'] = "[var.country;noerr]";
		quote['org_ins_id'] = organizationId;
		var arrProduct = new Array();
		for (var i=0;i <= idTxtDescrip;i++) {
			arrProduct[i] = new Array();
			arrProduct[i]['product_sale_id'] = arrProductSale[i]['product_sale_id'];
			arrProduct[i]['product_sale_desc'] = arrProductSale[i]['quote_line_desc'];
			arrProduct[i]['product_sale_price'] = arrProductSale[i]['quote_line_price'];
			arrProduct[i]['quote_line_desc'] = $('#txt_decrip'+i.toString()).val();
			arrProduct[i]['quote_line_price'] = $('#txt_unit'+i.toString()).val();
			arrProduct[i]['quote_line_qty'] = $('#txt_qty'+i.toString()).val();
		}
		xajax_saveQuote(quote,arrProduct);
	}
	
	// Calcula el monto de cada linea de la cotización después de la actualizacion (onChange) de algún valor
	function calculateAmount(id,getQuoteLine) {
		var unit = $('#txt_unit'+id).val();
		var qty = $('#txt_qty'+id).val();
		var amount = $('#span_amount'+id).text();
		var subtotal = $('#span_subtotal').text();
		var hstRate = $('#txt_hst_rate').val();
		var productSaleId = "";
		if (typeof arrProductSale[id] != "undefined") {
			if (typeof arrProductSale[id]['product_sale_id'] != "undefined"){
				productSaleId = arrProductSale[id]['product_sale_id'];
			}
		}
		xajax_calculateAmount(id,unit,qty,amount,subtotal,hstRate,productSaleId);
	}
	
	// Calcula el descuento y valor total de la cotización después de la actualizacion (onChange) de algún valor
	function calculateDiscount(objeto) {
		var id = objeto.id;
		var val = objeto.value;
		var subtotal = $('#span_subtotal').text();
		var hstRate = $('#txt_hst_rate').val();
		xajax_calculateDiscount(id,val,subTotalProducts,discount,subtotal,hstRate);
	}
	
	// Agrega una nueva fila despues de pulsar Intro en cualquier campor Unit
	function introQty(e) {
		tecla = (document.all) ? e.keyCode : e.which;
		if (tecla == 13){
			addNewProduct();			
		}
	}
	
	// Coloca el foco en el siguiente campo de texto al pulsar la tecla Enter
	function introTxt(e,objeto) {
		tecla = (document.all) ? e.keyCode : e.which;
		if (tecla == 13){
			if (objeto.id == 'txt_hst_rate') {
				$('#'+objeto.id).change();
			} else {
				cb = parseInt(objeto.tabIndex);
				$(':input[tabindex=\'' + (cb + 1) + '\']').focus();
			}
		}
	}
	
	// Permite pulsa Enter en el campo comment
	function introComment(e,objeto) {
		tecla = (document.all) ? e.keyCode : e.which;
		if (tecla == 13){
			objeto.value = objeto.value + String.fromCharCode(13);
		}
	}
	
	// Genera un DatePicker para el campo Valid Until
	$(function() {
		$( "#txt_valid_until" ).datepicker({
			minDate: 0,
			dateFormat: 'dd-M-yy',
			changeMonth: true,
			changeYear: true
		});
	});
	
	// Genera un campo de tipo AutoComplete para los nombres de los contactos
	$( function() {
		$( "#txt_contact" ).autocomplete({
			source: availableContact
		});
		
		$(document).ready(function () {
			$('#txt_contact').on('autocompleteselect', function (e, ui) {
				var i = availableContact.indexOf(ui.item.value);
				contactInsId = availableContactId[i];
				xajax_getContactInfos(contactInsId);
				document.getElementById('sel_email').focus();
			});
		});
	});
	
	// Genera un campo de tipo AutoComplete para la primera fila de productos de la cotización
	$( function() {
		$( "#txt_decrip0" ).autocomplete({
			source: availableDescrip
		});
		
		$(document).ready(function () {
			$('#txt_decrip0').on('autocompleteselect', function (e, ui) {
				var i = availableDescrip.indexOf(ui.item.value);
				var exchangeRate = $('#txt_exchange_rate').val();
				xajax_getDescripProduct(availableId[i],this.id,customerRegionId,priceTypeId,ui.item.value,exchangeRate);
			});
		});
	});
	
	// Función del evento click del botón Delete (Eliminar la última fila)
	$(document).ready(function(){
		$('#remove').click(function(){
			if (idTxtDescrip > 0) {
				var amount = $('#span_amount'+idTxtDescrip).text();
				var subtotal = $('#span_subtotal').text();
				var hstRate = $('#txt_hst_rate').val();
				xajax_calculateAmount(idTxtDescrip,0,0,amount,subtotal,hstRate);
				$('#descripId tr:last').remove();
				idTxtDescrip--;
				arrProductSale.pop();
			}
		})
	});

	// Función que inicializa el código a ejecutar en el evento keypress de la clase .validnumber
	// Es necesario ejecutarla cada vez que se crea un nuevo elemento con la clase .validnumber
	function validarKeyPress() {	
		//called when key is pressed in textbox
		$(".validNumber").keypress(function (e) {
			// Si no es punto, Intro, Backspace, Caracter nulo o números
			if (e.which != 46 && e.which != 13 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				mostrarMsjFadeOut("Digits Only", $(this));
				return false;
			}
			// Si es punto se valida que no sea en los campos qty o que no sea doble punto para los otros campos
			if (e.which == 46 && (this.id.indexOf('txt_qty') != -1 || this.value.indexOf('.') != -1)) {
				mostrarMsjFadeOut("Must be a valid number", $(this));
				return false;
			}
   		});
	}
	
	// Función que muestra un mensaje que de desvanece (FadeOut) dentro del span errorMsg
	// que se ubica cerca de la posicion del objeto especificado
 	function mostrarMsjFadeOut(msj,objeto) {
 		$('#errorMsg').show();
		$("#errorMsg").offset({ top: objeto.offset().top - 20, left: objeto.offset().left - 20});
		$("#errorMsg").html("<strong>"+msj+"</strong>").show().fadeOut(1000);
		window.parent.ajustarIframe();
 	}
	
	// Código que evita que se invoque el evento submit del formulario al pulsar la Tecla Enter en cualquier campo
	$(document).ready(function() {
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	});
</script>
</head>
<body
	onload="validarKeyPress();document.getElementById('txt_contact').focus();xajax_addNewProduct(0,'[var.quoteId;noerr]',customerRegionId,priceTypeId);window.parent.ajustarIframe();">
	<form action="javascript:saveQuote();" id="form_quote">
		<input type="submit" style="display: none;" id="submit_quote">
		<div class="container">
			<div class="col-sm-8">
				<div class="panel panel-primary">
					<div class="panel-heading">Customer</div>
					<div class="panel-body">
						<table class="table-responsive" border="0">
							<tr>
								<td width="100px"><label>Contact</label></td>
								<td width="200px"><input type="text" id="txt_contact"
									required="required" tabindex="1"></input></td>
								<td><label>Email</label></td>
								<td><select id="sel_email" tabindex="2"></select></td>
							</tr>
							<tr>
								<td width="100px"><label>Name</label></td>
								<td width="200px"><span id="span_name">[var.organizationName;noerr]</span></td>
								<td rowspan="2"><label>Ship to</label></td>
								<td>Shipping Address&nbsp;<input type="radio"
									name="rad_ship" id="rad_ship_1" tabindex="3">&nbsp;&nbsp;Billing
									Address<input type="radio" name="rad_ship" checked="checked"
									tabindex="3"></td>
							</tr>
							<tr>
								<td><label>Address</label></td>
								<td><span id="span_address">[var.address;noerr]</span></td>
								<td><textarea rows="2" cols="40" tabindex="4"
										id="txt_ship_to" required="required">[var.address;noerr]</textarea></td>
							</tr>
							<tr>
								<td><label>Web</label></td>
								<td><a href="[var.web;noerr]" id="href_web" target="_blank">[var.web;noerr]</a></td>
								<td><label>Region</label></td>
								<td><span id="span_region">[var.region;noerr]</span></td>
							</tr>
							<tr>
								<td><label>Phone</label></td>
								<td><span id="span_phone">[var.phone;noerr]</span></td>
								<td><label>Type</label></td>
								<td><span id="span_price_type">[var.customerType;noerr]</span></td>
							</tr>
							<tr style=''>
								<td nowrap="nowrap"><label>Exchange Rate</label></td>
								<td><input type="text" id="txt_exchange_rate" tabindex="4"
									size="8" dir="rtl" value="[var.exchangeRate;noerr]"
									onfocus="this.dir = 'ltr';" onblur="this.dir = 'rtl';">
									CA$ / US$</td>
								<td></td>
								<td></td>
							</tr>

						</table>
					</div>
				</div>

			</div>
			<div class="col-sm-4">
				<div class="panel panel-primary">
					<div class="panel-heading">Quote</div>
					<div class="panel-body">
						<table class="table-responsive" border="0">
							<tr>
								<td width="100px"><label>DATE</label></td>
								<td><span id="span_date">[var.fecha;noerr]</span></td>
							</tr>
							<tr>
								<td><label>QUOTE #</label></td>
								<td><span id="span_number">[var.quoteNumber;noerr]</span></td>
							</tr>
							<tr>
								<td><label>CUSTOMER ID</label></td>
								<td>[var.organizationId;noerr]</td>
							</tr>
							<tr>
								<td><label>VALID UNTIL</label></td>
								<td><input id="txt_valid_until" required="required"
									value="[var.quoteValidUntil;noerr]" tabindex="5" size="10"></td>
							</tr>
							<tr>
								<td><label>Prepared by</label></td>
								<td>[var.userName;noerr]</td>
							</tr>
						</table>
					</div>
				</div>
			</div>


		</div>
		<div class="container">
			<div class="col-sm-12">
				<div class="panel panel-primary">
					<div class="panel-heading">Description</div>
					<div class="panel-body">
						<table id="descripId" class="table table-striped" width="100%"
							cellspacing="0">
							<thead>
								<tr>
									<th width="600">DESCRIPTION</th>
									<th>UNIT PRICE</th>
									<th>QTY</th>
									<th width="100">AMOUNT ([var.currency;noerr])</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input id="txt_decrip0" class="form-control"
										required="required" tabindex="6"></td>
									<td align="center"><input id="txt_unit0" size="7"
										required="required" class="validNumber" tabindex="7"
										onchange="calculateAmount(0);" dir="rtl"
										onkeydown="javascript:return introTxt(event,this)"
										onfocus="this.dir = 'ltr';" onblur="this.dir = 'rtl';"></td>
									<td align="center"><input id="txt_qty0" size="4"
										required="required" class="validNumber" tabindex="8"
										onKeyDown="javascript:return introQty(event);"
										onchange="calculateAmount(0);" dir="rtl"
										onfocus="this.dir = 'ltr';" onblur="this.dir = 'rtl';"></td>
									<td align="right"><span id="span_amount0"></span></td>
								</tr>
							</tbody>
						</table>
						<br>
						<div class="row">
							<div class="col-sm-7">
								<label for="comment">Terms and Conditions:</label>
								<textarea class="form-control" rows="5" id="txt_comment"
									onkeydown="javascript:return introComment(event,this)"
									tabindex="1000">1. Customer will be billed after indicating acceptance of this quote.
2. Payment will be due prior to delivery of service and goods.
3. Please fax or mail the signed price quote to the address above.</textarea>
							</div>
							<div class="col-sm-5">
								<table class="table table-striped">
									<thead>
									</thead>
									<tbody>

										<tr>

											<td>Discount</td>
											<td><input type="text" id="txt_discount_val" size="8"
												tabindex="1001" class="validNumber" value="0"
												onkeydown="javascript:return introTxt(event,this)"
												onchange="calculateDiscount(this);" dir="rtl"
												onfocus="this.dir = 'ltr';" onblur="this.dir = 'rtl';"></input>
												$ <input type="text" id="txt_discount_per" size="4"
												tabindex="1002" class="validNumber" value="0" dir="rtl"
												onkeydown="javascript:return introTxt(event,this)"
												onchange="calculateDiscount(this);"
												onfocus="this.dir = 'ltr';" onblur="this.dir = 'rtl';"></input>
												%</td>

										</tr>
										<tr>

											<td>Subtotal ([var.currency;noerr])</td>
											<td><span id="span_subtotal">0.00</span></td>

										</tr>
										<tr>

											<td>HST Rate</td>
											<td><input type="text" id="txt_hst_rate" size="4"
												tabindex="1003" class="validNumber" value="0" dir="rtl"
												onkeydown="javascript:return introTxt(event,this)"
												onchange="calculateAmount(0)" onfocus="this.dir = 'ltr';"
												onblur="this.dir = 'rtl';"></input> %</td>

										</tr>
										<tr>
											<td>HST ([var.currency;noerr])</td>
											<td><span id="span_hst">0.00</span></td>
										</tr>
										<tr>
											<td><b>TOTAL ([var.currency;noerr])</b></td>
											<td><b><span id="span_total">0.00</span></b></td>
										</tr>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-3"></div>
									<div class="cols-sm-9">
										<span
											class="btn btn-primary btn-sm glyphicon glyphicon-plus biselado"
											id="" data-accion="" onclick="addNewProduct()">ADD</span> <span
											class="btn btn-primary btn-sm glyphicon glyphicon-minus biselado"
											id="remove" data-accion="" onclick="">DELETE</span> <span
											class="btn btn-primary btn-sm glyphicon glyphicon-floppy-disk biselado"
											id="" data-accion="" onclick="$('#submit_quote').click();">SAVE</span>
										<span
											class="btn btn-primary btn-sm glyphicon glyphicon-log-out biselado"
											id="" data-accion="" onclick="window.parent.close()">CLOSE</span><span
											class="btn btn-info btn-sm glyphicon glyphicon-floppy-save biselado"
											id="" data-accion="" onclick="" style="visibility: hidden">VIEW
											PDF</span>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="container-fluid">
		<div class="col-sm-4"></div>
		<div class="col-sm-8">
			<footer>
				<p>
					370 Magnetic Dr. North York, ON M3J 2C4,Toronto, Canada <label>
						Phone:+ 1-647-499-5741</label>Contact information: <a
						href="http://www.hubrox.com/">www.hubrox.com</a>.
				</p>

			</footer>
		</div>
	</div>
	<span id="errorMsg"></span>
</body>
</html>