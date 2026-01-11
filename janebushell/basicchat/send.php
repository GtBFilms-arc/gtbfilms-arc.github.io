<?
header('location: center.php');
include("chatconfig.php");
$ctext2 = $_POST['ctext'];

$usr = $_COOKIE["cookie_dschat"];

if (!file_exists("users/" . $_COOKIE['cookie_dschat'] . "")){
echo "You've been kicked from the chat by the admin, you cant talk!!";
exit;
}

$blf = "cdata.html";
$ctext = $usr . ": " . $ctext2 . "<br>\n";
$edited = stripslashes($ctext);
$file = fopen($blf, 'a+');
fwrite($file, $edited);
fclose($file);

$lines = file('cdata.html');
foreach ($lines as $line_num => $line) {
echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
   if ($line_num == $ad) {
    $blf = "cdata.html";
$ctext = "*Chat window refreshed automatically*<br>\n";
$file = fopen($blf, 'w');
fwrite($file, $ctext);
fclose($file);

$blf = "cdata.html";
$ctext = $usr . ": " . $ctext2 . "<br>\n";
$edited = stripslashes($ctext);
$file = fopen($blf, 'a+');
fwrite($file, $edited);
fclose($file);
}
}

?>