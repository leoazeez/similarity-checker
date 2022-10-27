<?php

require_once("functions.php");

  if(isset($_POST)){
        if ($_POST['action'] == "check_status") { check_status(); }
      
    }

    function file_exist($id){
        $sql = "select * from session_id where id='".$id."'";
       if ($result = mysqli_query($GLOBALS['conn'], $sql)) {



    $row = mysqli_fetch_row($result);
      if ($row){
          
          
            return 1;
    
      }else{
          
          return "No File";
      }
  }
  mysqli_free_result($result);
    
    
    
    }

    function check_status(){
       
       $id = $_POST['id'];
       
       $exist=file_exist($id);
       
       if($exist ==1){
       $sql = "select * from webhooks where id='".$id."' and status='1'";
       if ($result = mysqli_query($GLOBALS['conn'], $sql)) {



  $row = mysqli_fetch_row($result);
      if ($row){
          
          
            echo $row[1];
    
      }else{
          
          echo "";
      }
  }
  mysqli_free_result($result);
       }else{
           
           echo $exist;
       }
    
    }


?>