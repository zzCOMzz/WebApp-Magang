<?php
require __DIR__ . "/vendor/autoload.php";

use Bramus\Router\Router;
use Model\InitDb;
use Util\HeaderWriter;


$init = new InitDb();
$hWriter = new HeaderWriter();
$router = new Router();
$pdo = $init->getPdo();

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/Project-Magang-App-Uploads"; 


$router->delete("/api/tamu/(\w+)",function($id) use ($pdo , $uploadDir , $hWriter){ 
    $pPst = $pdo->prepare("select * from tamu where id = ?");
    $pPst->bindValue(1 , $id);
    $pPst->execute();
    $pResult = $pPst->fetch(PDO::FETCH_OBJ);

    if(isset($pResult)) {
        $numberIdentity = $pResult->number_identity;
        $dateIn = $pResult->date_in;

        try {
            $pPreDelete = $pdo->prepare("delete from tamu where id = ?");
            $pPreDelete->bindValue(1,$id);
            $pPreDelete->execute();

            $filePath = $uploadDir .  "/" . $numberIdentity . "/" . $dateIn . "/" . $id . ".png";
            $isDeleted = unlink($filePath);
    
            if($isDeleted) {
                $fsIterator = new FilesystemIterator($uploadDir . "/" . $numberIdentity . "/" . $dateIn);
                if (iterator_count($fsIterator) == 0) {
                    rmdir($uploadDir . "/" . $numberIdentity . "/" . $dateIn);
                }
                echo json_encode(
                    array(
                        'code'=>200,
                        'data'=>'Delete success'
                    )
                );
            }
        }catch(PDOException $pdoEx){
            echo json_encode(
                array(
                    'code'=>200,
                    'data'=>'Delete failed'
                )
            );
        }



    }else {
        $hWriter->writeHeader(404,"Not Found");
        echo json_encode(
            array(
                'code'=>404,
                'data'=>'Data not found'
            )
        );
    }

});

$router->post("/api/tamu",function() use ($pdo , $uploadDir , $hWriter){
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

        $finalDir = $dateInDir .  "/" . $id . ".png";
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
        $hWriter->writeHeader(405,"Method Not Allowed");
        echo("Method Not Allowed");
    }
});

$router->run();


