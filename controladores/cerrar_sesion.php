<?php
include_once '../conf.inc.php';
include_once RUTA_SISTEMA.'inc/funciones.php';
(session_id() == "") ? session_start () : null;

// Variable GET que indica si la página actual esta activa en una nueva pestaña o es la única página del sistema
$newPage = comprobarVar ( $_GET ['newPage'] ) ? trim ( $_GET ['newPage'] ) : "0";

// Se borran y se destruyen todos los datos de la sesi�n
session_unset();
session_destroy();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script language="JavaScript" type="text/javascript">
		var newPage = "<?php echo $newPage;?>";
		if (newPage == "1") {
			window.opener.parent.location = "..";
			window.opener.parent.cerrarPagAbiertas();
		} else {
			window.location = "..";
		}
	</script>
</head>
<body>
</body>
</html>