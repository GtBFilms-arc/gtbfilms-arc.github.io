<?
$name = $_POST['name'];
unlink("users/$name");
echo "$name kicked out of the room! <a href=admin.php>Back</a>";

$usr = $_COOKIE['cookie_dschat'];
$blf = "cdata.html";
$ctext = "*" . $name . " has been kicked by the admin*<br>\n";
$file = fopen($blf, 'a+');
fwrite($file, $ctext);
fclose($file);
?>