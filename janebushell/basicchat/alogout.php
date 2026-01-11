<?php
$time = time();

if (isset($_COOKIE['cookie_dschata']))
{
  setcookie ("cookie_dschata", "", $time - 3600);
echo "Logged Out<br><br><a href=admin.php>Back</a>";

}
?> 
