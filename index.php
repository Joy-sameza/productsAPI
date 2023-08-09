<?php

declare(strict_types=1);

// Auto load classes
spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

// Handle errors and exceptions
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$part = explode("/", $request);

if ($part[1] != 'products' OR $part[1] != 'doc')
    return http_response_code(404);

$id = null; 
$name = null;
if (array_key_exists(2, $part)) {
    if (is_numeric($part[2])) {
        $id = $part[2];
    } else {
        if (is_string($part[2])) {
            $name = $part[2];
        }
    }
}

$database = new Database("localhost", "root", "12345", "mcs_db");

$product = new Product($database);

$controller = new Controller($product);

$controller->processRequest($method, $id, $name);
