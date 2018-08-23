<?php
include_once '../conn/conexion.php';
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$mostrarRanking = $product->ranking();
$mostrarPalabras = $product->palabraUsada();

$stmt = $product->palabraUsada();
$num = $stmt->rowCount();

if($num>0){

    $products_arr = array();
    $products_arr["records"] = array();
           
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);

        $product_item = array(
            "titulo" => $titulo,
            "keyword" => $keyword
        );
        
        array_push($products_arr["records"], $product_item);
    }
    
    echo json_encode($products_arr);
} 
else {
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>
