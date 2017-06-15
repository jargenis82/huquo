
<!DOCTYPE html>
<html lang="en">
<head>
<title>Bootstrap Example</title>
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

<script>
	var consultingCustomer = "";
	var organizationId = "[var.organizationId;noerr]";


  $( function() {
    var availableTags = [ [var.customers;htmlconv=no;noerr]  ];
      $( "#tags" ).autocomplete({
        source: availableTags        
      });
      
      $( "#tags" ).autocomplete({
          change: function( event, ui ) {
        	  if (consultingCustomer != this.value) {
				consultingCustomer = this.value;
        	  	xajax_getCustomer(this.value);
        	  }
        	  
          }
           });

     } );

  $(document).ready(function () {
	    $('#tags').on('autocompleteselect', function (e, ui) {
	    	consultingCustomer = ui.item.value;
	    	xajax_getCustomer(ui.item.value)	        
	    });
	});

  </script>


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
    //var availableTags = [   ];

      $( "#txt_decrip" ).autocomplete({
        source: availableDescrip
      });

     $(document).ready(function () {
	    $('#txt_decrip').on('autocompleteselect', function (e, ui) {
	    	 	xajax_getDescripProduct(ui.item.value)
	    });
		});  
     } );
 $(document).ready(function(){
    
    $('#remove').click(function(){
        $('#descripId tr:last').remove();
    })
	})
  </script>

</head>
<body onload="document.getElementById('tags').focus();">
	<div class="container">
		<div class="col-sm-8">
			<div class="panel panel-primary">
				<div class="panel-heading">Customer</div>
				<div class="panel-body" >
					<table class="table-responsive" border="0">
						<tr>
							<td  width="100px" ><label>Name</label></td>
							<td  width="200px"><input id="tags"></td>
							<td ><label>Ship to</label></td>
						</tr>
						<tr>
							<td><label>Address</label></td>
							<td><span id="span_address"></span></td>
							<td rowspan="3"><textarea  rows="2" cols="40"
									id="txt_address"> </textarea></td>
						</tr>
						<tr>
							<td><label>Web</label></td>
							<td><a href="" id="a_web"></a></td>
						</tr>
						<tr>
							<td><label>Phone</label></td>
							<td><span id="span_phone"></span></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
						</tr>
						<br>
					</table>


				</div>
			</div>

		</div>
		<div class="col-sm-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					</input>Quote
				</div>
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
								size="10"> </input></td>
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
				<div class="panel-body" >
					<table id="descripId" class="table table-striped" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="600">DESCRIPTION</th>
								<th>UNIT PRICE</th>[var.js;htmlconv=no;noerr]
								<th>QTY</th>
								<th>AMOUNT US$</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input id="txt_decrip" class="form-control"></td>
								<td align="center"><input id="txt_unit" size="5"></td>
								<td align="center"><input type="txt_qty" size="4"></td>
								<td align="center"><span id="span_amount" size="4"></span></td>
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
										<td><input type="text"size="3" ></input> $    <input type="text" size="2" ></input> % </td>

									</tr>
									<tr>

										<td>Subtotal (US$)</td>
										<td>$</td>

									</tr>
									<tr>

										<td>HST Rate</td>
										<td>0%</td>

									</tr>
									<tr>
										<td>HST</td>
										<td></td>
									</tr>
										<tr>
										<td><b>TOTAL (US$)</b></td>
										<td><b>$100.000<b></td>
									</tr>
								</tbody>
							</table>
							<divclass"row">
							<div class="col-sm-4"></div>
									<div class="cols-sm-8">
										<span
											class="btn btn-primary btn-sm glyphicon glyphicon-plus biselado"
											id="" data-accion="" onclick="addNewProduct()">ADD</span>
										<span
											class="btn btn-primary btn-sm glyphicon glyphicon-minus biselado"
											id="remove" data-accion="" onclick="">DELETE</span>
											<span
											class="btn btn-info btn-sm glyphicon glyphicon-floppy-save biselado"
											id="btn_Guardar" data-accion="">PDF</span>
									</div>

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="col-sm-4">

		</div>
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