<?php
require_once("functions.php");

$token = get_token("84c2e15e-afc0-4b17-8f2e-38e8eae6ce91","yumnabali99@gmail.com");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_FILES['file']['name']; 
    $type = $_FILES['file']['type']; 
    $tmp_name = $_FILES['file']['tmp_name']; 
    $file_name = $name;
    
    if(isset($name)){ 
      if(!empty($name)){ 
          $temp = explode(".", $_FILES["file"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
          $target_path = "uploads/".basename( $newfilename);
          
          if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
            $im = file_get_contents($target_path);
            $base64 = base64_encode($im);
            $link=$GLOBALS['website_url'].$target_path;
            send_file($file_name,$link,$base64);
            // unlink($_FILES['file']['name']);
          }
        }
    }

    // $base64 = base64_encode($imagedata);
    
    
}
// echo $token;


?>