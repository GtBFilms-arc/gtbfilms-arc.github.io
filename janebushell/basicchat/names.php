<?
include("chatconfig.php");
?>
<meta http-equiv="Refresh" content="<? echo $refresh; ?>">
<?
if (!file_exists("users/" . $_COOKIE['cookie_dschat'] . "")){
echo "You've been kicked from the chat by the admin!";
exit;
}
?>
<body bgcolor="<? echo $col2; ?>">

<?php
 echo "<u><b><font color=$col3>Users in the room</font></u></b><br>";  
 $folder = "users";  
 $filecnt = 0; 
 if ($handle = opendir($folder)) {  
      while (false !== ($file = readdir($handle))) {   
           if (is_file("$folder/$file")) {   
                $size = filesize("$folder/$file");  
                echo "<font color=$col3>$file</font><br>"; $filecnt++; 
           }   
      }  
      closedir($handle); 
      if ($filecnt == "0") {
           echo "<font color=$col3>Nobody</font>"; 
      } else { 
           return;  
 }
 }
?>
</body>
