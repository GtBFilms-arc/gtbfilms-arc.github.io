<?
if (!file_exists("users/" . $_COOKIE['cookie_dschat'] . "")){
  setcookie ("cookie_dschat", "", $time - 3600);
echo "Logged Out<br><br><a href=chat.php>Back</a>";
exit;

}


$time = time();

if (isset($_COOKIE['cookie_dschat']))
{
  setcookie ("cookie_dschat", "", $time - 3600);
echo "Logged Out<br><br><a href=chat.php>Back</a>";

				  $folder = "users";
				  $file = $_COOKIE["cookie_dschat"];
				  $filename = "$folder/$file";
$usr = $_COOKIE['cookie_dschat'];
$blf = "cdata.html";
$ctext = "*" . $usr . " has left the room*<br>\n";
$file = fopen($blf, 'a+');
fwrite($file, $ctext);
fclose($file);

unlink("$filename");

} else {
echo "You're already logged out!! <a href=chat.php>Back</a>";
}
?>