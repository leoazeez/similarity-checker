

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plagrism </title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<style>

</style>
  </head>
  <body>
   
   
  <div class="container">
       
         <?php

require_once("functions.php");
// $token=get_token("84c2e15e-afc0-4b17-8f2e-38e8eae6ce91","yumnabali99@gmail.com");
// $GLOBALS['token'] = $token;
$status =$_GET['status'];
$id= $_GET['id'];


if ($status){
    
    ?>
    
<div class="position-fixed bottom-0 start-0 p-3" style="z-index: 11">
  <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-square-dots-fill" viewBox="0 0 16 16">
  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.5a1 1 0 0 0-.8.4l-1.9 2.533a1 1 0 0 1-1.6 0L5.3 12.4a1 1 0 0 0-.8-.4H2a2 2 0 0 1-2-2V2zm5 4a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
</svg>
      <strong class="me-auto">Message</strong>
      
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body submit-body">
      Submitted Successfully
    </div>
  </div>
</div>


<?php
    echo "<h2 id='text_change'>Waiting for Completion</h2>";
   
    
?>
<script>
    $(document).ready(function() {
        $("#liveToast").toast('show');
    });
    
</script>
    
    <?php
}else{
    header("location:index.html");
}



?>
      <div class="mt-4 row">
          
          <div id="main_table" class="col">
           
          </div>
          
          <div id="links" style="border-left:2px solid;display:none"class="col">
       
          </div>
      </div>
      
      
  </div>
   
   
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>-->



<script>
$stat=true;
$(document).ready(function() {
    
setInterval(function(){
    if($stat){
        // $(".submit-body").text("Checking status of <?php echo $id;?>");
        $("#liveToast").toast('show');
  check_status();
    }
},3000);

});     
   
  function check_status(){
           $.post("apis.php", {"action":"check_status","id":"<?php echo $id;?>"}, function(response) {
    if(response != "" && response !='No File') {
        $(".submit-body").text("Processed Completed");
        $("#liveToast").toast('show');
        $stat=false;
        response = JSON.parse(response);
        $('#text_change').text("Check details below");
        score = response['results']['score'];
        
        console.log(response['results']['score']);
        html_results = `<h2>Score Results </h2>
        <table class="table results">
  <thead>
    <tr>
      <th scope="col">Aggregated Score</th>
      <th scope="col">Identical Words</th>
      <th scope="col">Minor Changed Words</th>
      <th scope="col">Related Meaning Words</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">${score['aggregatedScore']}</th>
      <th>${score['identicalWords']}</th>
      <th>${score['minorChangedWords']}</th>
      <th>${score['relatedMeaningWords']}</th>
    </tr>
   
  </tbody>
</table>`;

$("#main_table").html(html_results);
internet = response['results']['internet'];
internet_length=internet.length;

html_links = `<h2>Score Results </h2>
        <table class="table html_links table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Title</th>
      <th scope="col">Matched Words</th>
      <th scope="col">url</th>
    </tr>
  </thead>
  <tbody>`;
  internet.forEach(function (value, index) {
    html_links=html_links+`<tr>
      <th scope="col">${index+1}</th>
      <th scope="col">${value['title']}</th>
      <th scope="col">${value['matchedWords']}</th>
      <th scope="col"><a href='${value['url']}' target='_blank'> Visit </a></th>
    </tr>`;
});
  
  html_links = html_links+ `</tbody></table>`;

$("#links").css("display","").html(html_links);

    }else if(response =='No File'){
     $(".submit-body").html("<p>No Such File Exist</p> <p>Redirecting to Home Page</p>");
        $("#liveToast").toast('show');
        $stat=false;
        setTimeout(function(){
            window.location.href = '/index.html';
         }, 3000);
    }else{
        $(".submit-body").text("Under Processing...");
        $("#liveToast").toast('show');
        // alert('Error, Please try again.');
    }
});

}    

        
    </script>
 
 
 
  </body>
</html>


