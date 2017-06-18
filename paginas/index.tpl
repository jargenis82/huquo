<!DOCTYPE html>
<html lang="en">
<head>
<title>Hubrox Technology Inc.</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Boostrap -->
<link rel="stylesheet"
	href="librerias/bootstrap3.3.7/css/bootstrap.min.css">

[var.js;htmlconv=no;noerr]

<script type="text/javascript"
	src="librerias/jquery_actual/js/jquery.min.js"></script>
<script type="text/javascript"
	src="librerias/bootstrap3.3.7/js/bootstrap.min.js"></script>

<!-- data table styles-->
<link rel="stylesheet" type="text/css"
	href="librerias/jquery_actual/css/jquery-ui.css">
<link rel="stylesheet" type="text/css"
	href="librerias/jquery-DataTables-1.10.7/media/css/dataTables.jqueryui.min.css">

<!-- SCRIPTS -->

<script type="text/javascript"
	src='librerias/jquery-DataTables-1.10.7/media/js/jquery.dataTables.min.js'></script>
<script type="text/javascript"
	src='librerias/jquery-DataTables-1.10.7/media/js/dataTables.jqueryui.min.js'></script>


<!--autoComplete-->
<script type="text/javascript"
	src='librerias/jquery_actual/js/jquery-ui.js'></script>
<style type="text/css">
table, td, th {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	border-color: blue;
}
</style>

<script type="text/javascript">
	function ajustarIframe() {
		document.getElementById('ifrm').style.height = document
				.getElementById('ifrm').contentWindow.document.body.scrollHeight
				+ 'px';
	}
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
	<div class="row">
		<iframe frameborder="0"
			style="position: absolute; height: 100%; width: 100%"
			src="controladores/[var.page;noerr]" id="ifrm"></iframe>
	</div>
</body>
</html>