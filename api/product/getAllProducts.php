<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

include_once '../../Database.php';
include_once '../../Product.php';

//Instantiate Db
$database = new Database();
$db = $database->connect();

//Initaite products
$product = new Product($db);

//Query all products
$result = $product->getAllProducts();

$count = $result->rowCount();

if ($count > 0) {
    $products_arr = array();
    // $products_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $product_item = array(
            'productId' => $productId,
            'nom' => $nom,
            'prix' => $prix,
            'quantite' => $quantite,
            'description' => $description,
        );

        // Push data
        array_push($products_arr, $product_item);
    }

    echo json_encode($products_arr);
} else {
    // No product found
    http_response_code(404);
    echo json_encode(
        array('message' => 'No product found')
    );
}
