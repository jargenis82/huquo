<?php
if (! defined ( "RUTA_SISTEMA" )) {
	include '../conf.inc.php';
}
include_once RUTA_SISTEMA . 'inc/funciones.php';
include_once RUTA_SISTEMA . 'clases/product_type.php';
include_once RUTA_SISTEMA . 'librerias/clase_bd.php';
class Product extends ClaseBd {
	function declararTabla() {
		$tabla = "product";
		$atributos ['product_id'] ['esPk'] = true;
		$atributos ['product_name'] ['esPk'] = false;
		$atributos ['product_description'] ['esPk'] = false;
		$objetos ['ProductType'] ['id'] = "product_type_id";
		$strOrderBy = "product_id";
		$this->registrarTabla ( $tabla, $atributos, $objetos, $strOrderBy );
		$this->dsn = "mysql";
	}
	function getListaProducto() {
		$miConexionBd = $this->miConexionBd;
		// Se buscan todas las descripciones de los productos excepto de los features
		$strSelect = "product_sale_id,p1.product_id AS product_id,p1.product_description AS product_description,";
		$strSelect .= "product_master_id,product_secondary_id,p2.product_name AS product_master_name,p1.product_type_id AS product_type_id";
		$strFrom = "product AS p1,product AS p2,product_sale";
		$strWhere = "((p1.product_id = product_master_id AND product_secondary_id IS NULL) XOR ";
		$strWhere .= "(p1.product_id = product_secondary_id AND product_secondary_id IS NOT NULL)) AND ";
		$strWhere .= "p2.product_id = product_master_id";
		$strOrderBy = "p1.product_type_id,product_description";
		$r1 = $miConexionBd->hacerSelect ( $strSelect, $strFrom, $strWhere, null, $strOrderBy );
		$listaProducto = array ();
		foreach ( $r1 as $v1 ) {
			$productSaleId = $v1 ['product_sale_id'];
			$productId = $v1 ['product_id'];
			$descActual = $v1 ['product_description'];
			$productMasterId = $v1 ['product_master_id'];
			$productSecondaryId = $v1 ['product_secondary_id'];
			$productTypeId = $v1 ['product_type_id'];
			// Si el tipo de producto es Feature se le agrega ese dato a la descripción
			if ($productTypeId == 11) {
				$descActual .= " (Feature)";
			}
			// Se buscan los features que no estan incluidos en la descripcion base de cada producto para agregarlo en la descripcion base
			// en caso de ser un product_master_id
			if ($productId == $productMasterId) {
				$strFrom = "without_feature,product_w_feature,product_sale";
				$strWhere = "without_feature.without_feature_id = product_w_feature.without_feature_id AND ";
				$strWhere .= "product_w_feature.product_sale_id = product_sale.product_sale_id AND ";
				$strWhere .= "product_master_id = $productId";
				$r2 = $miConexionBd->hacerSelect ( "without_feature_name", $strFrom, $strWhere, "without_feature_name" );
				foreach ( $r2 as $v2 ) {
					$descActual .= ", without " . $v2 ['without_feature_name'];
				}
			}
			// Si el producto es de tipo secundario, se le agrega el producto master a la descripcion
			if ($productId == $productSecondaryId) {
				$descActual = $v1 ['product_master_name'] . " - $descActual";
			}
			$listaProducto [$productSaleId] = $descActual;
		}
		return $listaProducto;
	}
}
?>