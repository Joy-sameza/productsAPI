<?php
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    
    include_once '../../Database.php';
    include_once '../../Product.php';
    
    //Instantiate Db and connect
    $database = new Database();
    $db = $database->connect();
    
    //Initaite products
    $product = new Product($db);

    $product->productId = isset($_GET['productId']) ? $_GET['productId'] : die();
    

    // get product
    $product->getProduct();

    //create array
    $product_arr = array(
        'productId'   => $product->productId,
        'nom'         => $product->nom,
        'prix'        => $product->prix,
        'quantite'    => $product->quantite,
        'description' => $product->description,
    );

    // Display JSON
    echo json_encode($product_arr);