<!DOCTYPE html>
<html lang="en">
<head>
<title>Hubrox Technology Inc.</title>
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
</style>
<script>



function validarUser(){

xajax_validateUser($('#userName').val(),$('#password').val());


}
</script>
</head>
<body>

<div class="container">
   	<div class="row">
	 		<div class="col-sm-4 col-sm-offset-3 text-left">
	   		<form  id="idForm" action="javascript:validarUser();">
			    <h4 class="form-signin-heading">Welcome Huquo Please Sign In</h4>
				  <hr class="colorgraph"><br>
				  <label><b>Username</b></label>
				  <input type="text" class="form-control" id="userName" name="userName" placeholder="Username" required  />
				  <label><b>Password</b></label>
				  <input type="password" class="form-control" id="password" name="password" placeholder="Password" required/>
				  <hr class="colorgraph"><br>
				  <button class="btn btn-lg btn-primary btn-block"   value="Login" type="submit" >Login</button>
			<div class="col-sm-8" ></div>
		</div>
	</div>
</div>

</body>
	
</body>

</html>