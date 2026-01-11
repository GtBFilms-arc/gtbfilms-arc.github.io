<?
	 $secure = $_POST['asdf999'];
	 if($secure == "qwerty777"){
$edited1 = $_POST['edited'];
$filename = "chatconfig.php";
 $edited2 = stripslashes($edited1);

$fp = fopen($filename, 'w');
fwrite($fp, $edited2);
fclose($fp);
 echo "You have successfully edited the config.php.\n\n<a href=admin.php>Back</a>";
?>
