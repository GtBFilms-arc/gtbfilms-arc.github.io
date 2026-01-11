<?
include("chatconfig.php");
?>
<meta http-equiv="Refresh" content="<? echo $refresh; ?>">
<!--<body bgcolor="<? echo $bg; ?>">-->
<body bgcolor="#DFDFEF">

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
           echo "Nobody"; 
      } else { 
           return;  
 }
 }
?>
</body>
