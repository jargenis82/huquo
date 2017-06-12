<?php
include '../conf.inc.php';
include_once '../librerias/conexion_bd.php';

$miConexionBd = new ConexionBd ( "mysql" );
// Se buscan todas las descripciones de los productos excepto de los features
$r1 = $miConexionBd->hacerSelect ( "*", "product" , "product_type_id <> 11");
$listaProducto = array ();
foreach ( $r1 as $v1 ) {
	$productId = $v1 ['product_id'];
	$descActual = $v1 ['product_description'];
	// Se buscan los features que no estan incluidos en la descripcion base de cada producto para agregarlo en la descripcion base
	$strFrom = "without_feature,product_w_feature,product_sale";
	$strWhere = "without_feature.without_feature_id = product_w_feature.without_feature_id AND ";
	$strWhere .= "product_w_feature.product_sale_id = product_sale.product_sale_id AND ";
	$strWhere .= "product_master_id = $productId";
	$r2 = $miConexionBd->hacerSelect ( "without_feature_name", $strFrom, $strWhere, "without_feature_name" );
	foreach ( $r2 as $v2 ) {
		$descActual .= ", without " . $v2 ['without_feature_name'];
	}
	// Si el producto es de tipo secundario, se le agrega el producto master a la descripcion
	$strWhere = "product_master_id = product_id AND product_secondary_id = $productId";
	$r3 = $miConexionBd->hacerSelect ( "product_name", "product_sale,product", $strWhere );
	if (isset($r3[0])) {
		$descActual = $r3[0]['product_name']." - $descActual";
	}
	$listaProducto [$productId] = $descActual;
}

echo "<pre>";
print_r ( $listaProducto );
echo "</pre>";

?>