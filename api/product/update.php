<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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

$product->nom = $data->nom;
$product->prix = $data->prix;
$product->quantite = $data->quantite;
$product->description = $data->description;

// update product
if ($product->update()) {
    echo json_encode(
        array('message' => 'Product Updated')
    );
} else {
    echo json_encode(
        array('message' => 'Product was Not Updated')
    );
}
