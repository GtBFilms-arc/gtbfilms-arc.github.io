<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Admin Login/Control Panel</title>
	<?
	include("chatconfig.php");
	 $secure = $_POST['asdf999'];
	 $rpass = $_COOKIE['cookie_dschata'];
	 ?>
</head>
<body>
<?
if($secure == "qwerty777"){
 if($rpass == $pass1){
?>
<form action="editprog.php" method="POST">
<textarea name="edited" rows=25 cols=60><?
$str = file_get_contents("chatconfig.php");
echo $str;
 ?></textarea><br><input name="asdf999" type="hidden" value="qwerty777">
<input type="submit" value="Edit"><br><a href=admin.php>Back</a><br>
<?
} else {
echo "You are a moderator, not an admin! You cant edit people's passwords as a moderator!!!<br><br><a href=admin.php>Back</a>";
}
} else {
echo "SECURITY ERROR. GET OUT, NEWB.";
}
?>
</form>
</body>
</html>


