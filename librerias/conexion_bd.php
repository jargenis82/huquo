<?php
if ((!defined("X")) or (!defined("Y")) or (!defined("Z")) or (!defined("W")) or (!defined("V")) or
(!defined("XI")) or (!defined("YI")) or (!defined("ZI")) or (!defined("WI")) or (!defined("VI")) or (!defined("UI"))) {
	define ("X","");
	define ("Y","");
	define ("Z","");
	define ("W","");
	define ("V","");	
	define ("XI","");
	define ("YI","");
	define ("ZI","");
	define ("WI","");
	define ("VI","");
	define ("UI","");
}

// Clase para realizar la conexion con la BD
class ConexionBd {
	private $enlace;
	private $ejecutar;
	private $consultaPendiente;

	// Conecta con la Base de Datos
	function __construct($dsn,$enlace,$ejecutar) {
		session_start();
		$this->consultaPendiente = "";
		$this->ejecutar = isset($ejecutar) ? $ejecutar : true;

		if (isset($enlace))
			$this->enlace = $enlace;
		else {
			if (isset($dsn) and strcmp($dsn,"informix") == 0) { 
				$this->enlace = new PDO("informix:host=".WI.";server=".VI.";protocol=onsoctcp;database=".ZI.";service=".UI,YI,XI);
			} else if (isset($dsn) and strcmp($dsn,"pgsql") == 0) { 
				$this->enlace = new PDO("pgsql:host=".W.";port=".V.";dbname=".Z.";user=".X.";password=".Y);
			} else if (isset($dsn) and strcmp($dsn,"mysql") == 0) { 
				$this->enlace = new PDO("mysql:host=".W.";port=".V.";dbname=".Z,X,Y);
			} else {
				$this->enlace = new PDO("pgsql:host=".W.";port=".V.";dbname=".Z.";user=".X.";password=".Y);
			}
		}		
	}
	
	// Realiza las consultas a la Base de Datos
	function hacerSelect($strSelect,$strFrom,$strWhere,$strGroupBy,$strOrderBy) {
		$consulta = isset($strSelect) ? "SELECT $strSelect" : "";
		$consulta .= isset($strFrom) ? " FROM $strFrom" : "";
		$consulta .= (isset($strWhere) and (strcmp($strWhere,"") != 0)) ? " WHERE $strWhere" : "";
		$consulta .= isset($strGroupBy) ? " GROUP BY $strGroupBy" : "";
		$consulta .= isset($strOrderBy) ? " ORDER BY $strOrderBy" : "";
		$consulta .= ";";
		return $this->hacerConsulta($consulta,true);
	}
	
	// Realiza los ingreso a la Base de Datos
	function hacerInsert($strInsertInto,$strValues,$idRetorno) {
		$consulta = isset($strInsertInto) ? "INSERT INTO ".$strInsertInto : "";
		$consulta .= isset($strValues) ? " VALUES ($strValues)" : "";

		if(isset($idRetorno)){
			$consulta .= isset($idRetorno) ? " RETURNING $idRetorno;" : ";";
			$resultado = $this->hacerConsulta($consulta,true);
			(isset($resultado)&&count($resultado)==1) ? $retorno = $resultado[0][$idRetorno] : $retorno = false;
		}else{
			$consulta .= ";";
			$retorno = $this->hacerConsulta($consulta);
		}
		return $retorno;
	}
	// Realiza la eliminacion en la Base de Datos
	function hacerDelete($strDeleteFrom,$strWhere) {
		$consulta = isset($strDeleteFrom) ? "DELETE FROM $strDeleteFrom" : "";
		$consulta .= isset($strWhere) ? " WHERE $strWhere" : "";
		$consulta .= ";";
		return $this->hacerConsulta($consulta);
	}

	// Realiza las actualizaciones en la Base de Datos
	function hacerUpdate($strUpdate,$strSet,$strWhere) {
		$consulta = isset($strUpdate) ? "UPDATE $strUpdate" : "";
		$consulta .= isset($strSet) ? " SET $strSet" : "";
		$consulta .= isset($strWhere) ? " WHERE $strWhere" : "";
		$consulta .= ";";
		return $this->hacerConsulta($consulta);
	}
	
	// Realiza la ejecuci�n de las consultas pendientes
	function hacerConsultasPendientes() {
		if (!($this->ejecutar)) {
			$this->ejecutar = true;
			$consulta = $this->consultaPendiente;
			$this->consultaPendiente = "";
			return $this->hacerConsulta($consulta);
		} else
		return false;		
	}
	// Realiza la ejecuci�n de una consulta
	function hacerConsulta($consulta,$devolver) {
		if ($this->ejecutar) {
			if (isset($devolver) and $devolver) {
				$resultado = $this->enlace->query($consulta);
				if (!($resultado === false)) {
					$arreglo = $resultado->fetchAll();
					return (count($arreglo) == 0 ? null : $arreglo);
				}
			} else {
				$resultado = $this->enlace->exec($consulta);
				if (!($resultado === false)) {
					return true;
				}
			}
			if (defined("RUTA_SISTEMA")) {
				ini_set('date.timezone','UTC');
				$tiempoMod = time() - 14400;
				$archivo = date("Ymd-His",$tiempoMod);
				$error = $this->devolverError();
				$error .= chr(13).$consulta;
				file_put_contents(RUTA_SISTEMA."log/$archivo.txt",$error);
			}
			return false;
		} else {
			$this->consultaPendiente .= $consulta;
			return true;
		}
	}

	// Devuelve el ultimo error en las consultas
	function devolverError() {
		return var_export($this->enlace->errorInfo(),true);
	}

	function noEjecutar() {
		$this->ejecutar = false;
		$this->consultaPendiente = "";
	}

	function siEjecutar() {
		$this->ejecutar = true;
		$this->consultaPendiente = "";
	}
	
}
?>
