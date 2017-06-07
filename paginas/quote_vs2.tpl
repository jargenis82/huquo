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
<style type="text/css">
table, td, th {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	border-color: blue;
}
</style>

<script>
	var consultingCustomer = "";


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
    var table = $('#example').DataTable( {
        searching: false,
        "scrollY": "auto",
        "bInfo": false,
        "paging": false
    } );
 
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );
 var counter = 2;
  $('#addRow').on( 'click', function () {
        table.row.add( [
            counter +'.1',
            counter +'.2',
            counter +'.3',
            counter +'.4',
            counter +'.5'
        ] ).draw();
 
        counter++;
    } )


} );



   
  </script>

</head>
<body onload="document.getElementById('tags').focus()">

	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="#myNavbar">
					<span class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><p class="text-primary">
						<strong>Hubrox Technology Inc. </strong>
					</p></a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Home</a></li>
					<li class="dropdown"><a class="dropdown-toggle"
						data-toggle="dropdown" href="#">Page 1 <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#">Page 1-1</a></li>
							<li><a href="#">Page 1-2</a></li>
							<li><a href="#">Page 1-3</a></li>
						</ul></li>
					<li><a href="#">Page 1</a></li>
					<li><a href="#">Page 2</a></li>
					<li><a href="#">Page 3</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#"><span class="glyphicon glyphicon-user"></span>
							Sign Up</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-log-in"></span>
							Login</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<p></p>
	<div class="container">
		<div class="col-sm-8">
			<div class="panel panel-primary">
				<div class="panel-heading">Customer</div>
				<div class="panel-body">
					<table class="table-responsive" border="0">
						<tr>
							<td width="200px"><label>Name</label></td>
							<td width="700px"><input id="tags"></td>
							<td><label>Ship to</label></td>
						</tr>
						<tr>
							<td><label>Address</label></td>
							<td><span id="span_address"></span></td>
							<td rowspan="3"><textarea rows="2" cols="40"
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
					<table class="table-responsive" width="200">
						<tr>
							<td><label>DATE</label></td>
							<td>[var.fecha;noerr]</td>
						</tr>
						<tr>
							<td><label>QUOTE #</label></td>
							<td>20170606-012</td>
						</tr>
						<tr>
							<td><label>CUSTOMER ID</label></td>
							<td>73793570</td>
						</tr>
						<tr>
							<td><label>VALID UNTIL</label></td>
							<td>30/01/2017</td>
						</tr>
						<tr>
							<td><label>Prepared by</label></td>
							<td>Annie Wang</td>
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
					<table id="example" class="display" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="600">DESCRIPTION</th>
								<th>UNIT PRICE</th>[var.js;htmlconv=no;noerr]
								<th>QTY</th>
								<th>AMOUNT US</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><label for="tags">Tags: </label><input id=""></td>
								<td id="">Row 1 Data 2</td>
								<td>Row 1 Data 1</td>
								<td>Row 1 Data 2</td>
							</tr>
						</tbody>
					</table>
					<br>
					<div class="row">
						<div class="col-sm-7"></div>
						<div class="col-sm-5">
							<table class="table table-striped">
								<thead>
								</thead>
								<tbody>
									<tr>

										<td>Subtotal (US$)</td>
										<td><label>$</label></td>

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
							<div class="col-sm-8"></div>
							<div class="cols-sm-4">
								<span
									class="btn btn-primary btn-sm glyphicon glyphicon-plus biselado"
									id="addRow" data-accion='add'>ADD</span> <span
									class="btn btn-info btn-sm glyphicon glyphicon-floppy-save biselado"
									id="btn_Guardar" data-accion='save'">PDF</span>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="col-sm-2"></div>
		<div class="col-sm-10">
			<footer>
				<p>
					370 Magnetic Dr. North York, ON M3J 2C4,Toronto, Canadá <label>
						Phone:+ 1-647-499-5741</label>Contact information: <a
						href="http://www.hubrox.com/">www.hubrox.com</a>.
				</p>

			</footer>
		</div>
	</div>
</body>
</html>