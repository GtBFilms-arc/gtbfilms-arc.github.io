<?php 
  if(empty($_POST['usr'])) 
  {   ?> 
      <b>Fill All Details. <a href="chat.php">Back</a></b> 
                <?php 
  } 
  else 
  { 
      //Colllect the details and validate 
      $time = time(); 
      $usr = $_POST['usr']; 
      $folder = "users/";
      if (file_exists($folder . $usr)) {
      echo "That nickname is already being used by someone else! <br>Go back and try again with a different name...<br><br><a href=chat.php>Back</a>";
      exit;
      }
      
      //$pass = $_POST['pass']; 
      include("chatconfig.php"); 
  //if ($pass == $pass1){ 
  //$auth777 = "1"; 
  //}else{ 
  //$auth777 = "0"; 
  //} 
  //if ($usr == $usr1) { 
  //$auth778 = "1"; 
  //}else{ 
  //$auth778 = "0"; 
  //} 
  //if ($auth777 == "1") { 
  // if ($auth778 == "1") {
  $auth779 = "1"; 
  //} 
  //}
       
          $cookie_data = $usr;   
                   if($auth779 == "1") {
              if(setcookie ("cookie_dschat",$cookie_data, $time+3600)==TRUE) 
              {  
                  echo "Logged in. <br> <br><a href=chat.php>Go to the chat!</a>"; 
				  $folder = "users";
				  $file = $usr;
				  $filename = "$folder/$file";
$edit = $usr;

$fp = fopen($filename, 'w');
fwrite($fp, $edit);
fclose($fp);


$blf = "cdata.html";
$ctext = "*" . $usr . " has joined the room*<br>\n";
$file = fopen($blf, 'a+');
fwrite($file, $ctext);
fclose($file);
                  }else{ 

  echo "Authentication Failed."; 
          } 
   
                    }else{ 
  echo "Authentication Failed."; 
     }  
   
   }
   
  ?>