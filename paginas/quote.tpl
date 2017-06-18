
<!DOCTYPE html>
<html lang="en">
<head>
<title>Hubrox Technology Inc.</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Boostrap -->
<link rel="stylesheet"
	href="../librerias/bootstrap3.3.7/css/bootstrap.min.css">
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
 $(document).ready(function() {
    var table = $('#descripId').DataTable( {
        searching: false,
        "scrollY": "auto",
      	 minDate: "today",
        "bInfo": false,
        "paging": false
    } );
} );

 var idTxtDescrip = 0;
 
 //var availableDescrip = [ "Arkcom","Wellscom","Asp","BASIC","C"  ];
 var availableDescrip = [ [var.jsData;htmlconv=no;noerr]  ];
 var availableId = [ [var.jsDataId;htmlconv=no;noerr]  ];
 
 function calculateAmount(id) {
	var unit = $('#txt_unit'+id).val();
	var qty = $('#txt_qty'+id).val();
	var amount = $('#span_amount'+id).text();
	var subtotal = $('#span_subtotal').text();
	var hstRate = $('#span_hst_rate').text();
	xajax_calculateAmount(id,unit,qty,amount,subtotal,hstRate);
 }
 
 function introQty(e) {
		tecla = (document.all) ? e.keyCode : e.which;
		if (tecla == 13){
			window.event.keyCode=0; 
			addNewProduct();
		}
	}
 
 $(function() {
        $( "#fechaVal" ).datepicker
        ({
        	 minDate: 0,
        	dateFormat: 'mm/dd/yy',
        	changeMonth: true,
          changeYear: true
 
        });
  
    });
  $( function() {
     $( "#txt_decrip" ).autocomplete({
        source: availableDescrip
      });

     $(document).ready(function () {
	    $('#txt_decrip').on('autocompleteselect', function (e, ui) {	    	
	    		var i = availableDescrip.indexOf(ui.item.value);
	    		// CustomerRegion 1 PriceType 1 **** PROVISIONAL
	    		xajax_getDescripProduct(availableId[i],this.id,1,1);
	    });
		});  
     } );
 $(document).ready(function(){
    
    $('#remove').click(function(){
    	if (idTxtDescrip > 0) {
	    	var amount = $('#span_amount'+idTxtDescrip).text();
	    	var subtotal = $('#span_subtotal').text();
	    	var hstRate = $('#span_hst_rate').text();
	    	xajax_calculateAmount(idTxtDescrip,0,0,amount,subtotal,hstRate);
	    	$('#descripId tr:last').remove();
	    	idTxtDescrip--;
    	}
    })
	})
  </script>

</head>
<body onload="document.getElementById('tags').focus();">
	<div class="container">
		<div class="col-sm-8">
			<div class="panel panel-primary">
				<div class="panel-heading">Customer</div>
				<div class="panel-body">
					<table class="table-responsive" border="0">
						<tr>
							<td width="100px"><label>Name</label></td>
							<td width="200px"><span id="span_name">[var.organizationName;noerr]</span></td>
							<td rowspan="2"><label>Ship to</label></td>
							<td rowspan="2"><textarea rows="2" cols="40"
									id="txt_address"> </textarea></td>
						</tr>
						<tr>
							<td><label>Address</label></td>
							<td><span id="span_address">[var.address;noerr]</span></td>
						</tr>
						<tr>
							<td><label>Web</label></td>
							<td><a href="[var.web;noerr]" id="a_web">[var.web;noerr]</a></td>
							<td><label>Region</label></td>
							<td><span id="span_region">Outside US & Canadá</span></td>
						</tr>
						<tr>
							<td><label>Phone</label></td>
							<td><span id="span_phone">[var.phone;noerr]</span></td>
							<td><label>Type</label></td>
							<td><span id="span_price_type">Reseller</span></td>
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
							<td>[var.fecha;noerr]</td>
						</tr>
						<tr>
							<td><label>QUOTE #</label></td>
							<td>[var.quoteNumber;noerr]</td>
						</tr>
						<tr>
							<td><label>CUSTOMER ID</label></td>
							<td>[var.organizationId;noerr]</td>
						</tr>
						<tr>
							<td><label>VALID UNTIL</label></td>
							<td><input id="fechaVal" value="[var.fechaValidez;noerr]"
								size="10"></td>
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
								<th width="100">AMOUNT (US$)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input id="txt_decrip" class="form-control"></td>
								<td align="center"><input id="txt_unit" size="5"
									onchange="calculateAmount('');" dir="rtl"></td>
								<td align="center"><input id="txt_qty" size="4"
									onKeyDown="javascript:return introQty(event);"
									onchange="calculateAmount('');"></td>
								<td align="right"><span id="span_amount"></span></td>
							</tr>
						</tbody>
					</table>
					<br>
					<div class="row">
						<div class="col-sm-7">
							<label for="comment">Comment:</label>
							<textarea class="form-control" rows="5" id="comment"></textarea>
						</div>
						<div class="col-sm-5">
							<table class="table table-striped">
								<thead>
								</thead>
								<tbody>

									<tr>

										<td>Discount</td>
										<td><input type="text" size="3"></input> $ <input
											type="text" size="2"></input> %</td>

									</tr>
									<tr>

										<td>Subtotal (US$)</td>
										<td><span id="span_subtotal">0,00</span></td>

									</tr>
									<tr>

										<td>HST Rate</td>
										<td><span id="span_hst_rate">0</span>%</td>

									</tr>
									<tr>
										<td>HST (US$)</td>
										<td><span id="span_hst">0,00</span></td>
									</tr>
									<tr>
										<td><b>TOTAL (US$)</b></td>
										<td><b><span id="span_total">0,00</span></b></td>
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
										id="remove" data-accion="" onclick="">DELETE</span> 
										<span class="btn btn-primary btn-sm glyphicon glyphicon-floppy-disk biselado"
										id="" data-accion="" onclick="">SAVE</span> <span
										class="btn btn-info btn-sm glyphicon glyphicon-floppy-save biselado"
										id="" data-accion="" onclick="openPdfQuote()">VIEW PDF</span>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
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
</body>
</html>