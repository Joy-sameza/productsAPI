<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../Database.php';
include_once '../../Product.php';

//Instantiate Db and connect
$database = new Database();
$db = $database->connect();

//Initaite products
$product = new Product($db);

$data = json_decode(file_get_contents('php://input'));

$product->productId = $data->productId;

// delete product
if ($product->delete()) {
    echo json_encode(
        array('message' => 'Product Deleted')
    );
} else {
    http_response_code(500);
    echo json_encode(
        array('message' => 'Product was Not Deleted')
    );
}