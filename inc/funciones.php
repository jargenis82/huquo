<?php
if (!defined("RUTA_SISTEMA")) {
	include '../conf.inc.php';
}

function devolverPermisos($miConexionBd,$miUsuario) {
	$miPerfil = new Perfil($miConexionBd);
	$miMenu = new Menu($miConexionBd);
	$miAcceso = new Acceso($miConexionBd);
	// Se contruye el arreglo para que no muestre los enlaces sin permiso
	$arrSinPermiso = array();
	$arrMenu = $miMenu->consultar();
	$arrAcceso = $miAcceso->consultar();
	foreach ($arrMenu as $unMenu) {
		$arrSinPermiso[$unMenu->getNom()]['inicio'] = "<!--";
		$arrSinPermiso[$unMenu->getNom()]['fin'] = "-->";
		foreach ($arrAcceso as $unAcceso) {
			if ($unAcceso->getTipo() != "TODO") {
				$arrSinPermiso[$unMenu->getNom()][$unAcceso->getTipo()]['inicio'] = "<!--";
				$arrSinPermiso[$unMenu->getNom()][$unAcceso->getTipo()]['fin'] = "-->";
			}
		}
	}
	// Se contruye el arreglo para que indique los permisos
	$arrUsuario = $miUsuario->consultar();
	if (isset($arrUsuario) and count($arrUsuario) == 1) {
		$miUsuarioAct = $arrUsuario[0];
		$usuarioId = $miUsuarioAct->getId();
		if (comprobarVar($usuarioId)) {
			$miPerfil->setUsuario($usuarioId);
			$arrPerfil = $miPerfil->consultar();
			foreach ($arrPerfil as $unPerfil) {
				$unMenu = $unPerfil->getMenu();
				$unAcceso = $unPerfil->getAcceso();
				$arrSinPermiso[$unMenu->getNom()]['inicio'] = "";
				$arrSinPermiso[$unMenu->getNom()]['fin'] = "";
				if ($unAcceso->getTipo() != "TODO") {
					$arrSinPermiso[$unMenu->getNom()][$unAcceso->getTipo()]['inicio'] = "";
					$arrSinPermiso[$unMenu->getNom()][$unAcceso->getTipo()]['fin'] = "";
				} else {
					foreach ($arrAcceso as $unAcceso2) {
						if ($unAcceso2->getTipo() != "TODO") {
							$arrSinPermiso[$unMenu->getNom()][$unAcceso2->getTipo()]['inicio'] = "";
							$arrSinPermiso[$unMenu->getNom()][$unAcceso2->getTipo()]['fin'] = "";
						}
					}
				}
			}
		}
	}
	// Se ajustan los valores del arreglo
	foreach ($arrSinPermiso as $i=>$permisos) {
		if (strcmp($permisos['inicio'],"") != 0) {
			foreach ($arrAcceso as $unAcceso3) {
				if ($unAcceso3->getTipo() != "TODO") {
					$arrSinPermiso[$i][$unAcceso3->getTipo()]['inicio'] = "nada";
					$arrSinPermiso[$i][$unAcceso3->getTipo()]['fin'] = "nada";
				}
			}
		}
	}
	// Se ajustan los indices del arreglo
	$arrSinPermiso2 = array();
	foreach ($arrSinPermiso as $i=>$permisos) {
		$j = str_replace(" ","_",$i);
		$arrSinPermiso2[$j] = $permisos;
	}
	return $arrSinPermiso2;
}

function devolverLoginSesion() {
	session_start();
	return isset($_SESSION['YY']) ? $_SESSION['YY'] : null;
}

function validarAcceso($miInstUsua) {
	// Se instancia la sesión actual del usuario
	session_start();
	$resultado = true;
	// Chequea el tiempo de expiración de la sesión
	if ((!isset($_SESSION['exp'])) or (strcmp(trim($_SESSION['exp']),"") == 0) or
		(time() >= $_SESSION['exp'])) {
		$resultado = false;
	}
	// Chequea el login y clave del usuario
	if ($resultado and isset($miInstUsua)) {
		$cantInsUsua = $miInstUsua->obtenerInstUsua($_SESSION['YY'],$_SESSION['XX'],formatoFechaBd());
		if ($cantInsUsua < 1) {
			$resultado = false;
		}
	}
	if (!$resultado) {
		header("Location: salir.php");
		exit;
	} else {
		// Renueva el tiempo de expiración de la sesión
		$_SESSION['exp'] = time()+EXP;
	}
}

function sinAcentos($cadena) {
	$cadena = str_replace("á","a",$cadena);
	$cadena = str_replace("é","e",$cadena);
	$cadena = str_replace("í","i",$cadena);
	$cadena = str_replace("ó","o",$cadena);
	$cadena = str_replace("ú","u",$cadena);
	$cadena = str_replace("Á","A",$cadena);
	$cadena = str_replace("É","E",$cadena);
	$cadena = str_replace("Í","I",$cadena);
	$cadena = str_replace("Ó","O",$cadena);
	$cadena = str_replace("Ú","U",$cadena);
	return $cadena;
}

function strMinus($cadena) {
	$cadena = aceptarComilla($cadena);
	return utf8_encode(strtolower((utf8_decode($cadena))));
}

function strMayus($cadena) {
	$cadena = aceptarComilla($cadena);
	return utf8_encode(strtoupper((utf8_decode(reemplazar($cadena)))));
}

function reemplazar($cadena){
	$cadena = str_replace("á","Á",$cadena);
	$cadena = str_replace("é","É",$cadena);
	$cadena = str_replace("í","Í",$cadena);
	$cadena = str_replace("ó","Ó",$cadena);
	$cadena = str_replace("ú","Ú",$cadena);
	return $cadena;
}

function comprobarVar($variable) {
	if (isset($variable) and strcmp(trim($variable),"") != 0)
	return true;
	else
	return false;
}

function aceptarComilla($cadena) {
	return str_replace("'","\'",$cadena);
}

function limpiarPalabra($palabra) {
	$resultado = trim($palabra);
	$valor = stripos($resultado," ");
	if ($valor)
	return "";
	else
	return $resultado;
}

function convertToDoubleval($number) {
	$number = str_replace ( ".", "", $number );
	$number = str_replace ( ",", ".", $number );
	return doubleval ( $number );
}

function listaFechas() {
	ini_set('date.timezone','UTC');
	$tiempoMod = time() - 14400;
	$tiempoLim = $tiempoMod - (86400*15);
	$fechas = array();
	while ($tiempoMod >= $tiempoLim) {
		$fecha = array();
		$fecha['id'] = $tiempoMod;
		$fecha['valor'] = date("d-m-Y",$tiempoMod);
		$fechas[] = $fecha;
		$tiempoMod = $tiempoMod - 86400;
	}
	return $fechas;
}

function formatoFechaHoraBd() {
	ini_set('date.timezone','UTC');
	$tiempoMod = time() - 14400;
	return date("Y-m-d H:i:s",$tiempoMod);
}

function fechaPasada($anos) {
	$fechaActual = formatoFecha();
	$dia = substr($fechaActual,0,2);
	$mes = substr($fechaActual,3,2);
	$ano = substr($fechaActual,6,4);
	$tiempo = mktime(0,0,0,$mes,$dia,($ano - $anos));
	return date("d/m/Y",$tiempo);
}

function restarFechas($fecha1,$fecha2,$anos) {
	$dia1 = substr($fecha1,0,2);
	$mes1 = substr($fecha1,3,2);
	$ano1 = substr($fecha1,6,4);
	$dia2 = substr($fecha2,0,2);
	$mes2 = substr($fecha2,3,2);
	$ano2 = substr($fecha2,6,4);
	$tiempo1 = mktime(0,0,0,$mes1,$dia1,$ano1);
	$tiempo2 = mktime(0,0,0,$mes2,$dia2,$ano2);
	$tiempoDif = $tiempo1 - $tiempo2;
	if ($tiempoDif < 0) {
		return false;
	} else {
		if ($anos === true) {
			return date("Y",$tiempoDif) - 1970;
		} else {
			$diasDif = $tiempoDif / (60 * 60 * 24);
			return $diasDif;
		}
	}
}

function formatoFecha($fecha,$mesDia) {
	if (isset($fecha) and strcmp($fecha,"") != 0) {
		if ($mesDia === true) {
			$fechaNueva = substr($fecha,3,2)."/";
			$fechaNueva .= substr($fecha,0,2)."/";
			$fechaNueva .= substr($fecha,6,4);
		} else {
			$fechaNueva = substr($fecha,8,2)."/";
			$fechaNueva .= substr($fecha,5,2)."/";
			$fechaNueva .= substr($fecha,0,4);
		}
		return $fechaNueva;
	} else {
		ini_set('date.timezone','UTC');
		$tiempoMod = time() - 14400;
		return date("d/m/Y",$tiempoMod);
	}
}

// Sumara dias (valor entero valido) a una fecha valida que este en formato m/d/Y.
// La funcion no valida el formato correcto de la fecha
function sumarFecha($fecha,$dias) {
	if (isset($fecha)) {
		$arregloFecha = split('[/]',$fecha);		
		// Se toma la marca de tiempo UNIX de la fecha
		$marcaTiempo = mktime(0,0,0,$arregloFecha[0],$arregloFecha[1],$arregloFecha[2]);
		// Se suman la cantidad de segundos correspondientes a los dias indicados
		$marcaTiempo += intval($dias) * 24 * 60 * 60;
		return date("m/d/Y",$marcaTiempo);
	}
}

function formatoFechaBd($fecha,$otroFormato) {
	if (isset($fecha)) {
		// Se chequea que la fecha este separada por "/" o "-" y que sean 3 valores
		$arregloFecha = split('[/]',$fecha);
		if ((!isset($arregloFecha)) or count($arregloFecha) != 3) {
			$arregloFecha = split('[-]',$fecha);
			if ((!isset($arregloFecha)) or count($arregloFecha) != 3) {
				return null;
			}
		}
		// Se chequea que sean valores enteros
		foreach ($arregloFecha as $i=>$valor) {
			if ((!is_numeric($valor)) or intval($valor) < 1) {
				return null;
			} else {
				if (intval($valor) < 10 and strlen(trim($valor)) == 1) {
					$arregloFecha[$i] = "0$valor";
				}
			}
		}
		// Se chequea que la fecha tenga formato correcto
		$fecha = $arregloFecha[0]."/".$arregloFecha[1]."/".$arregloFecha[2];
		if (!verificarFormatoFecha($fecha)) {
			return null;
		}
		if (strlen($arregloFecha[2]) == 4 and intval($arregloFecha[2]) > 2038 or intval($arregloFecha[2]) < 1970) {
			return $arregloFecha[2]."-".$arregloFecha[1]."-".$arregloFecha[0];
		} else {
			// Se toma la marca de tiempo UNIX de la fecha
			$marcaTiempo = mktime(0,0,0,$arregloFecha[1],$arregloFecha[0],$arregloFecha[2]);
			return date(((comprobarVar($otroFormato)) ? $otroFormato : "Y-m-d"),$marcaTiempo);
		}
	} else {
		ini_set('date.timezone','UTC');
		$tiempoMod = time() - 14400;
		return date(((comprobarVar($otroFormato)) ? $otroFormato : "Y-m-d"),$tiempoMod);
	}
}

function formatoFechaBdIfx($fecha,$otroFormato) {
	if (isset($fecha)) {
		// Se chequea que la fecha este separada por "/" o "-" y que sean 3 valores
		$arregloFecha = split('[/]',$fecha);
		if ((!isset($arregloFecha)) or count($arregloFecha) != 3) {
			$arregloFecha = split('[-]',$fecha);
			if ((!isset($arregloFecha)) or count($arregloFecha) != 3) {
				return null;
			}
		}
		// Se chequea que sean valores enteros
		foreach ($arregloFecha as $i=>$valor) {
			if ((!is_numeric($valor)) or intval($valor) < 1) {
				return null;
			} else {
				if (intval($valor) < 10 and strlen(trim($valor)) == 1) {
					$arregloFecha[$i] = "0$valor";
				}
			}
		}
		// Se chequea que la fecha tenga formato correcto
		$fecha = $arregloFecha[1]."/".$arregloFecha[0]."/".$arregloFecha[2];
		if (!verificarFormatoFecha($fecha)) {
			return null;
		}
		if (strlen($arregloFecha[2]) == 4 and intval($arregloFecha[2]) > 2038 or intval($arregloFecha[2]) < 1970) {
			return $arregloFecha[2]."-".$arregloFecha[0]."-".$arregloFecha[1];
		} else {
			// Se toma la marca de tiempo UNIX de la fecha
			$marcaTiempo = mktime(0,0,0,$arregloFecha[0],$arregloFecha[1],$arregloFecha[2]);
			return date(((comprobarVar($otroFormato)) ? $otroFormato : "Y-m-d"),$marcaTiempo);
		}
	} else {
		ini_set('date.timezone','UTC');
		$tiempoMod = time() - 14400;
		return date(((comprobarVar($otroFormato)) ? $otroFormato : "Y-m-d"),$tiempoMod);
	}
}

function verificarFormatoFecha($fecha,$ddmmyyyy) {
	$fecha = str_replace("-","/",trim($fecha));
	if (isset($fecha) and strcmp($fecha,"") != 0) {
		if (strlen($fecha) != 10 and ((!isset($ddmmyyyy)) or $ddmmyyyy === false))
		return false;
		$arregloFecha = split('[/]',$fecha);
		if (isset($arregloFecha) and count($arregloFecha) == 3) {
			$mesesDias[1] = 31;
			$mesesDias[3] = 31;
			$mesesDias[4] = 30;
			$mesesDias[5] = 31;
			$mesesDias[6] = 30;
			$mesesDias[7] = 31;
			$mesesDias[8] = 31;
			$mesesDias[9] = 30;
			$mesesDias[10] = 31;
			$mesesDias[11] = 30;
			$mesesDias[12] = 31;
			// Verifico el año
			if (!is_numeric($arregloFecha[2]) or intval($arregloFecha[2]) > 9999
			or intval($arregloFecha[2]) < 1900)
			return false;
			// Verifico los meses
			if (!is_numeric($arregloFecha[1]) or intval($arregloFecha[1]) > 12
			or intval($arregloFecha[1]) < 1)
			return false;
			// Verifico los dias
			if (!is_numeric($arregloFecha[0]) or intval($arregloFecha[0]) > 31
			or intval($arregloFecha[0]) < 1)
			return false;
			// Verifico la cantidad de dias por meses (excepto febrero)
			if (intval($arregloFecha[1]) != 2) {
				if (intval($arregloFecha[0]) > $mesesDias[intval($arregloFecha[1])])
				return false;
			} else {
				// Verifico la cantidad de d�as para febrero
				if (((intval($arregloFecha[2]) % 4) == 0) and (intval($arregloFecha[0]) > 29)) {
					return false;
				}
				if (((intval($arregloFecha[2]) % 4) != 0) and (intval($arregloFecha[0]) > 28)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}

function formatoDouble($valor,$formatoBd) {
	$valor = "$valor";
	$tamano = strlen($valor);
	$separador1 = $formatoBd ? "." : ",";
	$separador2 = $formatoBd ? "," : ".";
	for ($i=0;$i < $tamano;$i++) {
		if (strcmp($valor[$i],$separador2) == 0) {
			$valor[$i] = $separador1;
			return $valor;
		}
	}
	return $valor;
}

?>
