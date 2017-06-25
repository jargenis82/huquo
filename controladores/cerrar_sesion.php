<?php
include_once '../conf.inc.php';
include_once RUTA_SISTEMA.'inc/funciones.php';
session_start();



// Se borran y se destruyen todos los datos de la sesi�n
session_unset();
session_destroy();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script language="JavaScript" type="text/javascript">
		window.parent.parent.location = "../index.php";
	</script>
</head>
<body>
</body>
</html>