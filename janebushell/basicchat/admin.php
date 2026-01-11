<html>
<head>
	<title>Admin Login</title>
<style type="text/css">
body {background-color: #DFDFEF;
font-family: Arial,Verdana, sans-serif;
color: #666676;
font-size: 80%;
margin: 0pt;}
h1 {
   margin-top: 8px;
   margin-bottom: 5px;
   padding: 3px;
   border: thin solid #9F9FAF;
   background: #BFBFcF;
   color: white;
   clear: both;
   }
p {background-color: transparent}
</style>
</head>
<?
include("chatconfig.php");
?>
<body>
<?
if (!isset($_COOKIE['cookie_dschata']))  
{
?>
<form method="POST" action="alogin.php">
    <h1>Admin Login</h1>
      <Center>
        <table border="0" width="auto">
        <tr>
          <td>Password</td>
          <td><input type="password" name="pass" size="20"></td>
          <td> </td>
        </tr>
		</table>
    <center>
    <p><input type="submit" value="Submit" name="sub">
    <input type="reset" value="Reset" name="res"></p>
    </center>
    </form>
	<?
}
else
{
  //Cookie is set and display the data
   $cookie_info = explode("-", $_COOKIE['cookie_dschata']);  //Extract the Data
   if ($_COOKIE['cookie_dschata'] == $pass1) { 
   $usrlvl = "admin";
   }
   if ($_COOKIE['cookie_dschata'] == $pass2) {
      $usrlvl = "moderator";
      }
   echo "<center>Logged in as $usrlvl.";  
   echo "<br><a href='alogout.php'>Logout</a>.<br><H1>Admin Controls</H1><br><a href=admin.php>REFRESH PAGE</a></center>";
?>
<form method="POST" action="edit.php"><input name="asdf999" type="hidden" value="qwerty777"><input type="submit" value="Edit Config"></form>
<br>
<form method="POST" action="dump.php"><input type="submit" value="Dump Data"></form>
<br>
<?
$folder = "users";
if ($handle = opendir($folder)) {
    while (false !== ($file = readdir($handle))) { 
        if (is_file("$folder/$file")) { 
            $size = filesize("$folder/$file");
            echo "$file<form method=POST action=kick.php><input type=submit value=Kick><input name=name type=hidden value=$file></form>"; 
        } 
    }
    closedir($handle); 
}

   }?>


</body>
</html>
