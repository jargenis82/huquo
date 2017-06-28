<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'clases/organisation.php';
include_once RUTA_SISTEMA . 'clases/contact.php';
include_once RUTA_SISTEMA . 'clases/user.php';
include_once RUTA_SISTEMA . 'clases/quote_line.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Quote extends ClaseBd {
	function declararTabla() {
		$tabla = "quote";
		$atributos ['quote_id'] ['esPk'] = true;
		$atributos ['quote_date'] ['esPk'] = false;
		$atributos ['quote_valid_until'] ['esPk'] = false;
		$atributos ['quote_discount'] ['esPk'] = false;
		$atributos ['quote_hst_rate'] ['esPk'] = false;
		$atributos ['quote_ship_to'] ['esPk'] = false;
		$atributos ['quote_number'] ['esPk'] = false;
		$atributos ['hubrox_id'] ['esPk'] = false;
		$atributos ['oppor_id'] ['esPk'] = false;
		$atributos ['quote_comment'] ['esPk'] = false;
		$objetos ['Organisation'] ['id'] = "org_id";
		$objetos ['Contact'] ['id'] = "contact_id";
		$objetos ['User'] ['id'] = "user_id";
		$strOrderBy = "quote_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
	function getQuoteTotal() {
		$quoteId = $this->getAtributo ( "quote_id" );
		if (isset ( $quoteId )) {
			$miConexionBd = $this->miConexionBd;
			$myQuoteLine = new QuoteLine ( $miConexionBd );
			$myQuoteLine->setObjeto ( "Quote", $quoteId );
			$arrQuoteLine = $myQuoteLine->consultar ();
			$total = doubleval ( 0 );
			foreach ( $arrQuoteLine as $aQuoteLine ) {
				$quoteLinePrice = doubleval ( $aQuoteLine->getAtributo ( 'quote_line_price' ) );
				$quoteLineQty = doubleval ( $aQuoteLine->getAtributo ( 'quote_line_qty' ) );
				$total += $quoteLinePrice * $quoteLineQty;
			}
			
			$myQuote = new Quote ( $miConexionBd, $quoteId );
			$quoteDiscount = doubleval($myQuote->getAtributo("quote_discount"));
			$quoteHstRate = doubleval($myQuote->getAtributo("quote_hst_rate"));
			$total -= $quoteDiscount;
			$total = ($total * ($quoteHstRate/100)) + $total;
			$total = number_format ( $total, 2, ".", "," );
			return $total;
		} else {
			return "0.00";
		}
	}
	function getNextQuoteNumber() {
		$miConexionBd = $this->miConexionBd;
		$date = intval ( str_replace ( "-", "", formatoFechaBd () ) );
		$strSelect = "MAX(quote_number) AS maximo";
		$strFrom = "quote";
		$r = $miConexionBd->hacerSelect ( $strSelect, $strFrom );
		if (! comprobarVar ( $r [0] ['maximo'] )) {
			return "$date-001";
		}
		$maximo = $r [0] ['maximo'];
		$dateMaximo = intval ( substr ( $maximo, 0, 8 ) );
		if ($date == $dateMaximo) {
			$nroMaximo = intval ( substr ( $maximo, - 3 ) );
			$nroMaximo ++;
			$nroMaximo = $nroMaximo < 10 ? "00$nroMaximo" : ($nroMaximo < 100 ? "0$nroMaximo" : $nroMaximo);
			return "$date-$nroMaximo";
		}
		return "$date-001";
	}
}

?>