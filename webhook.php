<?php
require_once("functions.php");


if(isset($_GET['id'])){
    
    
   
   
   
$id=$_GET['id'];

header('Content-Type: application/json');
$request = file_get_contents('php://input');
$req_dump = print_r( $request, true );
$fp = file_put_contents( 'request.log', $req_dump );

// Updated Answer
if($json = json_decode(file_get_contents("php://input"), true)){
   $data = $json;
}

$id_t=$data['scannedDocument']['scanId'];


if($id=='try'){
    
    


$sql = "INSERT INTO webhooks (id,data, status)
VALUES ('".$id_t."','".file_get_contents("php://input")."','33')";

$GLOBALS['conn']->query($sql);
echo $id_t;


}else{
    
    file_put_contents('files/callback.'.$id_t.'.txt', print_r($data, true)); 
    echo "ok";
    
}


}else{



// Original Answer
header('Content-Type: application/json');
$request = file_get_contents('php://input');
$req_dump = print_r( $request, true );
$fp = file_put_contents( 'request.log', $req_dump );

// Updated Answer
if($json = json_decode(file_get_contents("php://input"), true)){
   $data = $json;
}

$id=$data['scannedDocument']['scanId'];

 $sql = "INSERT INTO webhooks (id,data, status)
VALUES ('".$id."','".file_get_contents("php://input")."','1')";

$GLOBALS['conn']->query($sql);
echo $id;

}

?>
