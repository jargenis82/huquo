<!DOCTYPE html>
<html lang="en">
<head>
<title>Bootstrap Example</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Boostrap -->

<link rel="stylesheet"
	href="../librerias/bootstrap3.3.7/css/bootstrap.min.css">
<script type="text/javascript"
	src="../librerias/jquery_actual/js/jquery.min.js"></script>
<script type="text/javascript"
	src="../librerias/bootstrap3.3.7/js/bootstrap.min.js"></script>

<!-- data table styles-->

<link rel="stylesheet" type="text/css"
	href="../librerias/jquery_actual/css/jquery-ui.css">
<link rel="stylesheet" type="text/css"
	href="../librerias/jquery-DataTables-1.10.7/media/css/dataTables.jqueryui.min.css">

<!-- XAJAX scripts -->
[var.js;htmlconv=no;noerr]

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
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	border-color: blue;
}
</style>
<script>
	var consultingCustomer = "";

	$(function() {
		var availableTags = [ [var.customers;htmlconv=no;noerr]  ];
		//var availableTags = [ "Arkcom", "Wellscom", "Asp", "BASIC", "C" ];
		$("#tags").autocomplete({
			source : availableTags
		});

		$("#tags").autocomplete({
			change : function(event, ui) {
				if (consultingCustomer != this.value) {
					consultingCustomer = this.value;
					xajax_getCustomer(this.value,1);
				}
			}
		});

	});
	$(document).ready(function() {
		$('#tags').on('autocompleteselect', function(e, ui) {
			consultingCustomer = ui.item.value;
			xajax_getCustomer(ui.item.value,1);
		});
	});
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
		<div class="row">
			<div class="col-sm-3">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<label>Name</label><input id="tags" placeholder="Customer Name."
							class="form-control">
					</div>
				</div>
			</div>
			<div class="col-sm-9">
				<table class="table-responsive" border="0">
					<tr>
						<td width="100"><label>Address </label></td>
						<td width="300"><span id="span_address"></span></td>
					</tr>
					<tr>
						<td><label>Web</label></td>
						<td><a href="" id="a_web"></a></td>
					</tr>
					<tr>
						<td><label>Phone</label></td>
						<td><span id="span_phone"></span></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-primary">
					<div class="panel-heading">Opportunities List</div>
					<div class="panel-body">
						<table id="tOpportunity" class="display" width="100%"
							cellspacing="0">
							<thead>
								<tr>
									<th>Opportunity Name</th>
									<th>Opportunity Created</th>
									<th>Current State</th>
									<th>Pipeline</th>
									<th>Quote List</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						<br>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-primary">
							<div class="panel-heading">Quotes</div>
							<div class="panel-body">
								<table id="tQuote" class="display" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Quote Number</th>
											<th>Quote Date</th>
											<th>Total ($)</th>
											<th>View / Edit</th>
											<th>PDF</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>