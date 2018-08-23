<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../conn/conexion.php';
include_once '../objects/product.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$product = new Product($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{   
    if(isset($_GET["keywords"])) {
        $keywords = $_GET["keywords"];
    } else {        
        $json = file_get_contents("php://input");
        $data = json_decode($json);  
        $array = json_decode(json_encode($data), true);  
        $keywords = $array["keywords"];
    }
    // query products
    $stmt = $product->search($keywords);
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
    
        // products array
        $products_arr = array();
        $products_arr["records"] = array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            extract($row);
    
            $product_item = array(
                "titulo" => $titulo,
                "descripcion" => html_entity_decode($descripcion),
                "fecha_inicio" => $fecha_inicio,
                "fecha_termino" => $fecha_termino,
                "precio" => $precio,
                "imagen" => $imagen,
                "vendidos" => $vendidos,
                "tags" => $tags
            );
            
            $registro = $product->registro($keywords, $titulo);

            array_push($products_arr["records"], $product_item);
        }
        
        echo json_encode($products_arr);
    } 
    else {
        echo json_encode(
            array("message" => "No products found.")
        );
    }
}
else {
    echo json_encode(
        array("message" => "Use POST request.")
    );
}
?>