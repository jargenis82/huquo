<?php
(!defined("X")) ? define ("X","") : null;
(!defined("Y")) ? define ("Y","") : null;
(!defined("Z")) ? define ("Z","") : null;
(!defined("W")) ? define ("W","") : null;
(!defined("V")) ? define ("V","") : null;
(!defined("XI")) ? define ("XI","") : null;
(!defined("YI")) ? define ("YI","") : null;
(!defined("ZI")) ? define ("ZI","") : null;
(!defined("WI")) ? define ("WI","") : null;
(!defined("VI")) ? define ("VI","") : null;
(!defined("UI")) ? define ("UI","") : null;

// Clase para realizar la conexion con la BD
class ConexionBd {
	private $enlace;
	private $ejecutar;
	private $consultaPendiente;

	// Conecta con la Base de Datos
	function __construct($dsn=null,$enlace=null,$ejecutar=null) {
		(session_id() == "") ? session_start () : null;
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
	function hacerSelect($strSelect=null,$strFrom=null,$strWhere=null,$strGroupBy=null,$strOrderBy=null) {
		$consulta = isset($strSelect) ? "SELECT $strSelect" : "";
		$consulta .= isset($strFrom) ? " FROM $strFrom" : "";
		$consulta .= (isset($strWhere) and (strcmp($strWhere,"") != 0)) ? " WHERE $strWhere" : "";
		$consulta .= isset($strGroupBy) ? " GROUP BY $strGroupBy" : "";
		$consulta .= isset($strOrderBy) ? " ORDER BY $strOrderBy" : "";
		$consulta .= ";";
		return $this->hacerConsulta($consulta,true);
	}
	
	// Realiza los ingreso a la Base de Datos
	function hacerInsert($strInsertInto=null,$strValues=null,$idRetorno=null) {
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
	function hacerDelete($strDeleteFrom=null,$strWhere=null) {
		$consulta = isset($strDeleteFrom) ? "DELETE FROM $strDeleteFrom" : "";
		$consulta .= isset($strWhere) ? " WHERE $strWhere" : "";
		$consulta .= ";";
		return $this->hacerConsulta($consulta);
	}

	// Realiza las actualizaciones en la Base de Datos
	function hacerUpdate($strUpdate=null,$strSet=null,$strWhere=null) {
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
	function hacerConsulta($consulta=null,$devolver=null) {
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
