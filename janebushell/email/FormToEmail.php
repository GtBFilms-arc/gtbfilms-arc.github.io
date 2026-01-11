<?php

/*

Thank you for choosing FormToEmail by FormToEmail.com

Version 1.3 Created March 24th 2005

COPYRIGHT FormToEmail.com 2003 - 2005

You are not permitted to sell this script, but you can use it, copy it or distribute it, providing that you do not delete this copyright notice, and you do not remove any reference to FormToEmail.com

DESCRIPTION

FormToEmail allows you to place a form on your website which your visitors can fill out and send to you.  The contents of the form are sent to the email address which you specify below.  The form allows your visitors to enter their name, email address and comments.  If they try to send a blank form, they will be returned to the form.

Your visitors (and nasty spambots!) cannot see your email address!

When the form is sent, your visitor will get a confirmation of this on the screen, and will be given a link to continue to your homepage, or other page if you specify it.

Should you need the facility, you can add additional fields to your form, which this script will also process, without making any additional changes.

This is a PHP script.  In order for it to run, you must have PHP (version 4.1.0 or later) on your webhosting account.  If you are not sure about this, then ask your webhost about it.

SETUP INSTRUCTIONS

Step 1: Put the form on your webpage
Step 2: Enter your email address and continue link below
Step 3: Upload the files to your webspace

Step 1:

To put the form on your webpage, copy the code below as it is, and paste it into your webpage:

<form action="FormToEmail.php" method="post">
<table border="0" bgcolor="#ececec" cellspacing="5">
<tr><td><font face="arial" size="2">Name</font></td><td><input type="text" size="30" name="Name"></td></tr>
<tr><td><font face="arial" size="2">Email address</font></td><td><input type="text" size="30" name="Email"></td></tr>
<tr><td valign="top"><font face="arial" size="2">Comments</font></td><td><textarea name="Comments" rows="6" cols="30"></textarea></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" value="Send"><font face="arial" size="1">&nbsp;&nbsp;FormToEmail by <a href="http://FormToEmail.com">FormToEmail.com</a></font></td></tr>
</table>
</form>

Step 2:

Enter the email address below to send the form to:

*/

$my_email = "jane@janebushell.co.uk";

/*

Enter the continue link to offer the user after the form is sent.  If you do not change this, your visitor will be given a continue link to your homepage:

If you do change it, remove the "/" symbol below and replace with the name of the page to link to, eg: "mypage.htm" or "http://www.elsewhere.com/page.htm"

*/

$continue = "http://www.janebushell.co.uk";

/*

Step 3:

Save this file (FormToEmail.php) and upload it together with your webpage to your webspace.  IMPORTANT - The file name is case sensitive!  You must save it exactly as it is named above!  Do not put this script in your cgi-bin directory (folder) it may not work from there.

THAT'S IT, FINISHED!

You do not need to make any changes below this line.

*/

// This line prevents values being entered in a URL

if ($_SERVER['REQUEST_METHOD'] != "POST"){exit;}

$message = "";

// This line prevents a blank form being sent

while(list($key,$value) = each($_POST)){if(!(empty($value))){$set=1;}$message = $message . "$key: $value\n\n";} if($set!==1){header("location: $_SERVER[HTTP_REFERER]");exit;}

// Check email validity
if (empty($_POST['Email'])) {myerror("Please enter your e-mail address!");} else {$email=htmlspecialchars("$_POST[Email]");}
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {myerror("Please enter a valid e-mail address!");}
// ok

$message = $message . "-- \nEmail Sent from webpage http://www.janebushell.co.uk";
$message = stripslashes($message);

$subject = "Email from Webpage";
$headers = "From: " . $_POST['Email'] . "\n" . "Return-Path: " . $_POST['Email'] . "\n" . "Reply-To: " . $_POST['Email'] . "\n";

mail($my_email,$subject,$message,$headers);

function myerror($problem) {
require_once("header.txt");
echo "
<p align=\"center\"><b>ERROR</b></p>
<p>&nbsp;</p>
<p align=\"center\">$problem</p>
<p align=\"center\"><a href=\"javascript:history.go(-1)\">Click here to try again</a></p>
";
require_once("footer.txt");
exit();
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title>Form To Email PHP script from FormToEmail.com</title>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-uk">

<SCRIPT LANGUAGE="JavaScript">
<!-- hide from none JavaScript Browsers
Image5= new Image(100,30)
Image5.src = "../button_home.jpg"
Image6 = new Image(100,30)
Image6.src = "../button_home_on.jpg"


function SwapOut3() {
document.imageflip3.src = Image6.src; 
document.home.src = Image6.src;
return true;
}

function SwapBack3() {
document.imageflip3.src = Image5.src; 
document.home.src = Image5.src;
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

<body bgcolor="#ffffff" text="#000000">

<font face="arial">

<object><center>
<h1>Thank you <?php print stripslashes($_POST['Name']); ?>!</h1>
<h2>Your email has been sent successfully!</h2>
<p><a href="http://www.janebushell.co.uk" target="_top" onFocus="if(this.blur)this.blur()" onMouseOver="SwapOut3()" onMouseOut="SwapBack3()"><img NAME="imageflip3" SRC="../button_home.jpg" border=0></a>
</center></object>

</font>

</body>
</html>