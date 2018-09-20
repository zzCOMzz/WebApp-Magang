<?php
require __DIR__ . "/vendor/autoload.php";

use Bramus\Router\Router;
use Model\InitDb;


$init = new InitDb();
$router = new Router();

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/Project-Magang-App-Uploads"; 


$router->get("/hello",function(){
    echo $_SERVER['DOCUMENT_ROOT'];
});

$router->post("/api/tamu",function(){
    $cType = $_SERVER['CONTENT_TYPE'];

    if($cType == "application/json"){
        $reqBody = file_get_contents("php://input");
        $decodedJson = json_decode($reqBody);

        echo $decodedJson->name ."|". $decodedJson->id;
    } else if ($cType == "application/x-www-form-urlencoded") {
        
    } else {
        header("HTTP/1.0 405 Method Not Allowed");
        echo "Method Not Allowed";
    }
});

$router->run();


