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

a.disabled {
	pointer-events: none;
	visibility: hidden;
}
</style>

<script type="text/javascript">
	var pagAbiertas = new Array();

	function cerrarSesion() {
		var url = "./controladores/cerrar_sesion.php?newPage=[var.newPage;noerr]";
		cerrarPagAbiertas();
		window.location = url;
	}
	
	function cerrarPagAbiertas() {
		var tamano = pagAbiertas.length;
		for (var i=0;i < tamano;i++) {
			if (typeof(pagAbiertas[i]) != "undefined") {
				pagAbiertas[i].close();
			}
		}
	}
	
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
					<li class="active"><a href="#" class="[var.menuHid;noerr]">Quotes</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<li><a class="[var.menuHid;noerr]"><span
							class="glyphicon glyphicon-user"></span>&nbsp;[var.userName;noerr]
					</a></li>
					<li><a
						href="javascript:cerrarSesion();"
						class="[var.menuHid;noerr]"><span
							class="glyphicon glyphicon-log-out"></span>Log out</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="row">
		<iframe frameborder="0" id="ifrm"
			style="position: absolute; height: 100%; width: 100%"
			src="controladores/[var.page;noerr]"></iframe>
	</div>
</body>
</html>
