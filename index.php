<?php
require __DIR__ . "/vendor/autoload.php";

use Bramus\Router\Router;
use Model\InitDb;


$init = new InitDb();
$router = new Router();
$pdo = $init->getPdo();

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/Project-Magang-App-Uploads"; 




$router->post("/api/tamu",function() use ($pdo , $uploadDir){
    $cType = $_SERVER['CONTENT_TYPE'];

    if(strstr($cType ,  "multipart/form-data") != ""){
        $id = uniqid();
        $numberIdentity = $_POST['number_identity'];
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $phoneNo = $_POST['phone_no'];
        $purpose = $_POST['purpose'];
        $dateIn = $_POST['date_in'];
        $faceId = $_FILES['face_id'];

        
        $pSt = $pdo->prepare("insert into tamu values (?,?,?,?,?,?,?,?)");
        $pdo->beginTransaction();

        $pSt->bindValue(1,$id);
        $pSt->bindValue(2, $numberIdentity);
        $pSt->bindValue(3, $name);
        $pSt->bindValue(4, $gender);
        $pSt->bindValue(5, $address);
        $pSt->bindValue(6, $phoneNo);
        $pSt->bindValue(7, $purpose);
        $pSt->bindValue(8, $dateIn);
        $pSt->execute();

        $numberIdentityDir = "/" . $uploadDir . "/" . $numberIdentity;

        if(!file_exists($numberIdentityDir)){
            mkdir($numberIdentityDir);
        }

        $dateInDir = "/" . $numberIdentityDir . "/" . $dateIn;

        if(!file_exists($dateInDir)) {
            mkdir($dateInDir);
        }

        $finalDir = $dateInDir .  "/" . uniqid() . ".png";
        $isUploaded = move_uploaded_file($faceId['tmp_name'] , $finalDir);



        if($isUploaded){
            $pdo->commit();
            echo json_encode(
                array(
                    'code'=>200,
                    'data'=>'Process completed'
                )
            );
        }else{
            rmdir($dateInDir);
            rmdir($numberIdentityDir);

            echo json_encode(
                array(
                    'code'=>200,
                    'data'=>'Process not completed'
                )
            );
        }

    } else {
        header("HTTP/1.0 405 Method Not Allowed");
        echo("Method Not Allowed");
    }
});

$router->run();


