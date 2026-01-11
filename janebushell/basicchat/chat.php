<?
include("chatconfig.php");
?>
<html>
<head>
	<title><? echo $name; ?></title>

<SCRIPT LANGUAGE="JavaScript">
<!-- hide from none JavaScript Browsers
Image1= new Image(100,30)
Image1.src = "../button_home.jpg"
Image2 = new Image(100,30)
Image2.src = "../button_home_on.jpg"



function SwapOut1() {
document.imageflip1.src = Image2.src; 
document.home.src = Image2.src;
return true;
}

function SwapBack1() {
document.imageflip1.src = Image1.src; 
document.home.src = Image1.src;
return true;
}
// - stop hiding -->
</SCRIPT>

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
if (!isset($_COOKIE['cookie_dschat']))  
{
?>
<h1><? echo $name ?></h1>
<form method="POST" action="login.php">
    <center><h1>Login</h1>
      <Center>
        <table border="0" width="auto">
        <tr>
          <td>Nickname</td>
          <td><input type="text" name="usr" size="20"></td>
          <td> </td>
        </tr>
		</table>
    <center>
    <p><input type="submit" value="Submit" name="sub">
    <input type="reset" value="Reset" name="res"></p>
    </center>
    </form>
    
    </body>

<IFRAME SRC="name2.php" TITLE="names" frameborder=0 scrolling="auto" width="145">
<!-- Alternate content for non-supporting browsers -->
<h3>WARNING: <BR>
YOUR BROWSER DOES NOT SUPPORT IFRAMES,
<BR>
AND WILL NOT ALLOW DSCHAT TO FUNCTION CORRECTLY.<br><br>
TURN FRAMES ON TO VIEW DSCHAT CORRECTLY.
</IFRAME>
	<?
	echo "<br><br><br><br>				<hr width=35%>";
}
else
{
  //Cookie is set and display the data
   $cookie_info = explode("-", $_COOKIE['cookie_dschat']);  //Extract the Data
   $user = $cookie_info[0];
   echo "<body>";
   echo "<center>Logged in as $user.";  
   echo "<br><a href='logout.php'>Logout</a>.</center><br>";
?>
<strong><u><font face="arial" color="<? echo $col3; ?>" size="4"><? echo $name ?></font></strong></u>
<table>
<tr>
       <td bgcolor="<? echo $col1; ?>" width="500" height="500">
<IFRAME SRC="center.php" TITLE="center" frameborder=0 scrolling="no" width="499" height="495">
<!-- Alternate content for non-supporting browsers -->
<h3>WARNING: <BR>
YOUR BROWSER DOES NOT SUPPORT IFRAMES,
<BR>
AND WILL NOT ALLOW DSCHAT TO FUNCTION CORRECTLY.<br><br>
TURN FRAMES ON TO VIEW DSCHAT CORRECTLY.
</IFRAME>
	   </td>
       <td bgcolor="#DFDFEF" width="150" height="500">
<IFRAME SRC="names.php" TITLE="names" frameborder=0 scrolling="no" width="145" height="495">
<!-- Alternate content for non-supporting browsers -->
<h3>WARNING: <BR>
YOUR BROWSER DOES NOT SUPPORT IFRAMES,
<BR>
AND WILL NOT ALLOW DSCHAT TO FUNCTION CORRECTLY.<br><br>
TURN FRAMES ON TO VIEW DSCHAT CORRECTLY.
</IFRAME>
	   </td>
</tr>
</table>
	   <center>
	   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='logout.php'>Logout</a> <a href="javascript:;" onClick="window.open('admin.php','newwin','width=505,height=500'); return false;">Admin Panel</a>
	   </center>
	   			<?
				}
				?>
<a href="http://www.janebushell.co.uk" target="_top" onFocus="if(this.blur)this.blur()" onMouseOver="SwapOut1()" onMouseOut="SwapBack1()"><img NAME="imageflip1" SRC="../button_home.jpg" border=0></a>
				
</body>
</html>
