<?php
include '../conf.inc.php';
include_once RUTA_SISTEMA.'clases/product_type.php';

//$myProductType = new ProductType();
//$myProductType->setAtributo("product_type_desc","Device");
//$myProductType->registrar();

$myProductType = new ProductType();
$arrProductType = $myProductType->consultar();
echo $arrProductType[0]->getAtributo("product_type_id");
echo "<br>";
echo $arrProductType[0]->getAtributo("product_type_name");


?>