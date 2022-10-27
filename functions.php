<?php

require __DIR__ . '/vendor/autoload.php';

$servername = "localhost";
$dbname="trylotech_copyleaks";
$username = "trylotech_copyleaks";
$password = "copycopy@@!";
$token='';
$sandbox="false";

$website_url = "https://hospital.trylotech.com/";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


function data_base_get(){
    
    $sql="select value from meta where key_name='token' and time > DATE_SUB(NOW(), INTERVAL 45 HOUR) limit 1";
    // $result = $GLOBALS['conn']->query($sql);

if ($result = mysqli_query($GLOBALS['conn'], $sql)) {
  // Fetch one and one row
  $row = mysqli_fetch_row($result);
  if ($row){
  return $row[0];
  }else{
      return 0;
  }
  }
  mysqli_free_result($result);

    
    
    
}

function get_token($api,$email){
Requests::register_autoloader();

$headers = array(
	'Content-type' => 'application/json'
	
);


$data = '{
  "email": "'.$email.'",
  "key": "'.$api.'"
}';
$key= data_base_get();
if ($key and $key != 0){
    $GLOBALS['token'] = $key;
    return $key;
}else{

$response = Requests::post('https://id.copyleaks.com/v3/account/login/api', $headers, $data);
$raw=json_decode($response->body,true);
store_update_key($raw['access_token']);
$GLOBALS['token'] = $raw['access_token'];
return $raw['access_token']; 
}

}

function store_update_key($token){
    
    $sql = "DELETE FROM meta WHERE key_name='token'";
    $GLOBALS['conn']->query($sql);
    
    $sql = "INSERT INTO meta (key_name, value)
        VALUES ('token', '".$token."')";
    $GLOBALS['conn']->query($sql);

}

function store_session($filename,$link){
    
    $id=generateRandomString();

    $sql = "INSERT INTO session_id (id,file_name, status,link)
VALUES ('".$id."','".$filename."', 0 ,'".$link."')";

$GLOBALS['conn']->query($sql);
return $id;
    
}


function send_file($file_name,$link,$base64){
 
$id= store_session($file_name,$link);

$headers = array(
	'Content-type' => 'application/json',
	'Authorization' => 'Bearer '.$GLOBALS['token'],
);


$data = '{
   "id":"'.$id.'",
 "url": "'.$link.'",
 "base64":"'.$base64.'",
  "filename": "'.$file_name.'",
  "properties": {
    "webhooks": {
      "status": "'.$GLOBALS['website_url'].'webhook.php?/completed/'.$id.'"
    },
    "pdf":{"create":"true"},
    "sandbox":"'.$GLOBALS["sandbox"].'"
  }
}';


$response = Requests::put('https://api.copyleaks.com/v3/scans/submit/file/'.$id, $headers, $data);

$status=$response->status_code;
header("location:completed.php?status=".$status."&id=".$id);
}


function get_pdf($id){
    
    $token= get_token("84c2e15e-afc0-4b17-8f2e-38e8eae6ce91","yumnabali99@gmail.com");
    $headers = array(
	'Content-type' => 'application/json',
	'Authorization' => 'Bearer '.$token,
);



$data = '{
  "pdfReport": {
    "verb": "POST",
    "headers": [
      [
        "id",
        "'.$id.'"
      ]
    ],
    "endpoint": "'.$GLOBALS['website_url'].'webhooks.php?id=pdf"
  },
  "crawledVersion": {
    "verb": "POST",
    "headers": [
      [
      "id",
        "'.$id.'"
      ]
    ],
    "endpoint": "'.$GLOBALS['website_url'].'export/'.$id.'/crawled-version"
  },
  "completionWebhook": "'.$GLOBALS['website_url'].'webhooks.php?id=try",
  "maxRetries": 3
}';



$url='https://api.copyleaks.com/v3/downloads/'.$id.'/export/'.generateRandomString(4).'/';


$response = Requests::post($url, $headers, $data);

$status=$response->status_code;
echo $url;
echo $status;
}



function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>