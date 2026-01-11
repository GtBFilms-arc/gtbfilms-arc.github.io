<?php 
  if(empty($_POST['pass'])) 
  {   ?> 
      <b>Fill All Details </b> 
                <?php 
  } 
  else 
  { 
      //Colllect the details and validate 
      $time = time(); 
      //$usr = $_POST['usr']; 
      $pass = $_POST['pass']; 
      include("chatconfig.php"); 
  if ($pass == $pass1){ 
  $auth778 = "1"; 
  }else{ 
  $auth778 = "0";
  } 
  
    if ($pass == $pass2){ 
  $auth777 = "1"; 
  }else{ 
  $auth777 = "0";
  } 
  //if ($usr == $usr1) { 
  //$auth778 = "1"; 
  //}else{ 
  //$auth778 = "0"; 
  //} 
  //if ($auth777 == "1") { 
  // if ($auth778 == "1") {
  //$auth779 = "1"; 
  //} 
  //}
       
          $cookie_data = $pass;   
                   if($auth777 || $auth778 == "1") {
              if(setcookie ("cookie_dschata",$cookie_data, $time+3600)==TRUE) 
              {  
                  echo "Logged in. <br> <br><a href=admin.php>Do Stuff</a>"; 
                  }else{ 
  echo "Authentication Failed."; 
          } 
   
                    }else{  
  echo "Authentication Failed."; 
     }  
   
   }
   

  ?>
