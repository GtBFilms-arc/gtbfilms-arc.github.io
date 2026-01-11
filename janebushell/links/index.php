<?php
# PHP link manager (Linkman) - admin panel
# Version: 1.0.1
# File name: index.php
# Written 16th July 2004 by Klemen Stirn (info@phpjunkyard.com)
# http://www.PHPJunkYard.com

##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright 2004 PHPJunkYard All Rights Reserved.                            #
#                                                                            #
# The Linkman may be used and modified free of charge by anyone so long as   #
# this copyright notice and the comments above remain intact. By using this  #
# code you agree to indemnify Klemen Stirn from any liability that might     #
# arise from it's use.                                                       #
#                                                                            #
# Selling the code for this program without prior written consent is         #
# expressly forbidden. In other words, please ask first before you try and   #
# make money off this program.                                               #
#                                                                            #
# Obtain permission before redistributing this software over the Internet or #
# in any other medium. In all cases copyright and header must remain intact. #
# This Copyright is in full effect in any country that has International     #
# Trade Agreements with the United States of America or with                 #
# the European Union.                                                        #
##############################################################################

#############################
#     DO NOT EDIT BELOW     #
#############################

error_reporting(E_ALL ^ E_NOTICE);
require_once "settings.php";

header("Expires: Mon, 26 Jul 2000 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (empty($_REQUEST['action'])) {login();}
else {$action=$_REQUEST['action'];}

if($settings['system'] == 2) {$settings['newline']="\r\n";}
elseif($settings['system'] == 3) {$settings['newline']="\r";}
else {$settings['newline']="\n";}

if ($action == "login")
	{
    $pass=$_REQUEST['pass'];
	if(empty($pass)) {error("Please enter your admin password!");}
	checkpassword($pass);
    mainpage("welcome");
	}
elseif ($action == "remove")
	{
    $pass=$_REQUEST['pass'];
	if(empty($pass)) {error("You are not authorized to view this page!");}
    checkpassword($pass);
    $id=$_REQUEST['id'];
    if(empty($id) && $id != "0") {error("Please enter a link ID number!");}
    if (preg_match("/\D/",$id)) {error("This is not a valid link ID, use numbers (0-9) only!");}
    removelink($id);
	}
elseif ($action == "check")
	{
    $pass=$_REQUEST['pass'];
	if(empty($pass)) {error("Please enter your admin password!");}
    checkpassword($pass);
    check();
	}
elseif ($action == "add")
	{
    $pass=$_REQUEST['pass'];
	if(empty($pass)) {error("You are not authorized to view this page!");}
    checkpassword($pass);
    addlink();
	}
else {login();}
exit();

// START addlink()
function addlink() {
global $settings;
if (empty($_REQUEST['name'])) {error("Please enter owner's name!");} else {$name=htmlspecialchars("$_REQUEST[name]");}
if (empty($_REQUEST['email'])) {error("Please enter owner's e-mail address!");} else {$email=htmlspecialchars("$_REQUEST[email]");}
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {error("Please enter a valid e-mail address!");}
if (empty($_REQUEST['title'])) {error("Please enter the title (name) of the website!");} else {$title=htmlspecialchars("$_REQUEST[title]");}
if (empty($_REQUEST['url'])) {error("Please enter the url of the website!");} else {$url=rtrim(htmlspecialchars("$_REQUEST[url]"));}
if (!(preg_match("/(http:\/\/+[\w\-]+\.[\w\-]+)/i",$url))) {error("The site URL is not valid!");}
if (empty($_REQUEST['recurl']) || $_REQUEST['recurl']=="http://") {error("Please enter the url where a reciprocal link to our site is placed!");} else {$recurl=strtolower(rtrim(htmlspecialchars("$_REQUEST[recurl]")));}
if ($recurl != "http://nolink" && !(preg_match("/(http:\/\/+[\w\-]+\.[\w\-]+)/i",$recurl))) {error("The reciprocal page URL is not valid!");}
if (empty($_REQUEST['description'])) {error("Please enter description of your site!");} else {$description=htmlspecialchars("$_REQUEST[description]");}
if(strlen($description)>200) {error("Description is too long! Description of your website is limited to 200 chars.");}

$fp = fopen($settings['linkfile'],"rb") or die("Can't open the link file ($settings[linkfile]) for reading!");
$content=@fread($fp,filesize($settings['linkfile']));
fclose($fp);
$content = trim(chop($content));
$lines = @explode($newline,$content);
if (count($lines)>$settings['max_links']) {error("We are not accepting any more links at the moment. We appologize for the inconvenience!");}

$replacement = "$name$settings[delimiter]$email$settings[delimiter]$title$settings[delimiter]$url$settings[delimiter]$recurl$settings[delimiter]$description$settings[newline]";

if ($settings['add_to'] == 0) {
    $fp = fopen($settings['linkfile'],"rb");
	$links = @fread($fp,filesize($settings['linkfile']));
	fclose($fp);

	$replacement .= $links;

    $fp = fopen($settings['linkfile'],"wb") or error("Couldn't open links file for writing! Please CHMOD all txt files to 666 (rw-rw-rw)!");
	fputs($fp,$replacement);
	fclose($fp);
	}
else {
    $fp = fopen($settings['linkfile'],"ab") or error("Couldn't open links file for appending! Please CHMOD all txt files to 666 (rw-rw-rw)!");
	fputs($fp,$replacement);
	fclose($fp);
    }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
<link rel="STYLESHEET" type="text/css" href="style.css">
<title>PHP Link manager admin panel</title>
</head>
<body>
<div align="center"><center>
<table border="0" width="700">
<tr>
<td align="center" class="glava"><font class="header">PHP Link manager <?php echo($settings['verzija']); ?><br>-- Admin panel --</font></td>
</tr>
<tr>
<td class="vmes"><p>&nbsp;</p>
<div align="center"><center>
<table width="400"> <tr>
<td align="center" class="head">ERROR</td>
</tr>
<tr>
<td align="center" class="dol">
<form>
<p>&nbsp;</p>
<p><b>Link added</b></p>
<p>The URL <?php echo($url); ?> was successfully added to your links page.</p>
<p>&nbsp;</p>
<p><a href="index.php?action=login&pass=<?php echo($settings[apass]); ?>">Click to continue</a></p>
<p>&nbsp;</p>
</form>
</td>
</tr> </table>
</div></center>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
</tr>
<tr>
<!--
Changing the "Powered by" credit sentence without purchasing a licence is illegal!
Please visit http://www.phpjunkyard.com/copyright-removal.php for more information.
-->
<td align="center" class="copyright">Powered by <a href="http://www.phpjunkyard.com/php-link-manager.php" target="_new">PHP Link manager</a> <?php echo($settings['verzija']); ?><br>
(c) Copyright 2004 <a href="http://www.phpjunkyard.com/" target="_new">PHPjunkyard - Free PHP scripts</a></td>
</tr>
</table>
</div></center>
</body>
</html>
<?php
exit();
} // END addlink()

// START check()
function check() {
$lines=array();
global $settings;
$fp = fopen($settings['linkfile'],"rb") or die("Can't open the link file ($settings[linkfile]) for reading!");
$content=fread($fp,filesize($settings['linkfile']));
fclose($fp);
$content = trim(chop($content));
$lines = explode($settings['newline'],$content);
$site_url2 = preg_replace("/\//","\\\/",$settings['site_url']);

$i=1;
$found=0;
$rewrite=0;

echo <<<EOC
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
<link rel="STYLESHEET" type="text/css" href="style.css">
<title>Checking reciprocal links...</title>
</head>
<body>
EOC;

foreach($lines as $thisline) {
    list($name,$email,$title,$url,$recurl,$description)=explode($settings['delimiter'],$thisline);

    echo "<p>Checking link N. <b>$i</b>...<br>\n";
    echo "Link URL: $url<br>\n";
    	if ($recurl == "http://nolink")
        {
        	echo "<b>No reciprocal link required!</b><br><br>\n";
            echo "- - - - - - - - - - - - - - - - - - - - - - - - - - - -</p>\n";
            $i++;
		    $found=0;
 			flush();
            continue;
        }
        else
        {
        	echo "Reciprocal URL: $recurl<br>\n";
        }
    echo "Opening and reading reciprocal URL ";

	$remote = @fopen($recurl, "r") or $remote = "NO";
    	if ($remote == "NO") {echo "<br>\nERROR: CAN'T OPEN URL, PLEASE TRY LATER!<br><br>\n\n";}
        else
        {
			while ($html = fread($remote,1024)) {
		        if (preg_match("/$site_url2/i",$html)) {$found=1; break;}
        	echo ".";
	    	}

    		if ($found==1) {echo "<br>\nA link to $settings[site_url] was found!<br><br>\n\n";}
  			else {
       			echo "<br>\nLINK NOT FOUND!<br><br>\n\nRemoving link ...<br>";
                unset($lines[$i-1]);
                $rewrite=1;
    		}
        }
    $i++;
    echo "- - - - - - - - - - - - - - - - - - - - - - - - - - - -</p>\n";
    $found=0;
    flush();
}

echo <<<EOC
<p>&nbsp;</p>
<p><b>DONE!</b></p>
<p><a href="index.php?action=login&pass=$settings[apass]">Back to main page</a></p>
</body>
</html>
EOC;

exit();
}
// END check()

// START removelink()
function removelink($i) {
$lines=array();
global $settings;
$fp = fopen($settings['linkfile'],"rb") or die("Can't open the link file ($settings[linkfile]) for reading!");
$content=fread($fp,filesize($settings['linkfile']));
fclose($fp);

$content = trim(chop($content));
$lines = explode($settings['newline'],$content);
unset($lines[$i]);
$lines = array_values($lines);

$fp = fopen($settings['linkfile'],"wb") or die("Can't write to link file! Please Change the file permissions (CHMOD to 666 on UNIX machines!)");
	foreach ($lines as $thisline) {
    $thisline .= $settings['newline'];
	fputs($fp,$thisline);
	}
fclose($fp);

mainpage("The selected link was successfully removed!");
}
// END removelink()

// START mainpage()
function mainpage($notice) {
global $settings;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
<link rel="STYLESHEET" type="text/css" href="style.css">
<title>PHP Link manager admin panel</title>
<script language="Javascript" type="text/javascript"><!--
function doconfirm(message) {
	if (confirm(message)) {return true;}
    else {return false;}
}
//-->
</script>
</head>
<body>
<div align="center"><center>
<table border="0" width="700" cellpadding="5">
<tr>
<td align="center" class="glava"><font class="header">PHP Link manager <?php echo($settings['verzija']); ?><br>-- Admin panel --</font></td>
</tr>
<tr>
<td class="vmes">
<?php
if ($notice == "welcome") {
    echo "<p align=\"center\"><b>Welcome to admin panel!</b></p>";
	}
else {
	echo "<p align=\"center\"><b>Admin panel</b></p>
    <p align=\"center\"><font color=\"#FF0000\">$notice</font></p>";
    }

echo "<hr>\n";
$lines=array();

$fp = fopen($settings['linkfile'],"rb") or die("Can't open the link file ($settings[linkfile]) for reading!");
$content=@fread($fp,filesize($settings['linkfile']));
fclose($fp);
$content = trim(chop($content));
	if (strlen($content) == 0) {$noyet=1;}
$lines = @explode($settings['newline'],$content);

if ($noyet == 1)
	{
    ?>
<p>You don't have any links yet.</p>
    <?php
    }
else {
echo "<table border=\"0\">";
$i=0;
foreach ($lines as $thisline)
{
		if (strlen($thisline)<10) {$i++; continue;}
	list($name,$email,$title,$url,$recurl,$description)=explode($settings['delimiter'],$thisline);
    echo "<tr>
    <td valign=\"top\"><a href=\"index.php?action=remove&pass=$settings[apass]&id=$i\" onclick=\"return doconfirm('Are you sure you want to remove this link? This cannot be undone!');\"><img src=\"delete.gif\" height=\"14\" width=\"16\" border=\"0\" alt=\"Remove this link\"></a></td>
    <td valign=\"top\"><a href=\"$url\" target=\"_new\">$title</a> - $description</td>
    </tr>";
    $i++;
}

?>
</table>
<p>You can remove links  by clicking the <img src="delete.gif" height="14" width="16" border="0"> button.</p>
<?php
}
?>
<hr>
<form action="index.php" method="POST">
<p><b>Check reciprocal links</b></p>
<p>Click the below button and the script will check all submitted links to
see if your reciprocal link is still there. If the reciprocal link is not on the
reciprocal links page, submitted link will be removed!</p>
<p><b>This can take a while, please be patient!</b>
<input type="hidden" name="action" value="check">
<input type="hidden" name="pass" value="<?php echo($settings[apass]); ?>">
</p>
<p><input type="submit" value=" Check links "></p>
</form>
<hr>
<form action="index.php" method="POST">
<p><b>Add a link</b></p>
<p>Here you can manually add links to your links.php. LinkMan <b>will NOT</b>
check for reciprocal links if you submit using this form!</p>
<p>If you don't require a reciprocal link from this website please type
&quot;<b>http://nolink</b>&quot; (without the quotes) into the Reciprocal URL field!
<input type="hidden" name="action" value="add">
<input type="hidden" name="pass" value="<?php echo($settings[apass]); ?>">
</p>
<table border="0">
<tr>
<td><b>Owner name:</b></td>
<td><input type="text" name="name" maxlength="50"></td>
</tr>
<tr>
<td><b>Owner e-mail:</b></td>
<td><input type="text" name="email" maxlength="50"></td>
</tr>
<tr>
<td><b>Website title:</b></td>
<td><input type="text" name="title" maxlength="50"></td>
</tr>
<tr>
<td><b>Website URL:<b></td>
<td><input type="text" name="url" maxlength="100" value="http://" size="40"></td>
</tr>
<tr>
<td><b>URL with reciprocal link:</b></td>
<td><input type="text" name="recurl" maxlength="100" value="http://" size="40"></td>
</tr>
</table>

<p><b>Website description:</b><br>
<input type="text" name="description" maxlength="200" size="50"></p>

<p><input type="submit" value=" Add this link "></p>
</form>
<hr>
<p><b>Rate this script</b></p>
<p>If you like this script please rate it or even write a review at:</p>
<p><a href="http://www.hotscripts.com/Detailed/36875.html" target="_new">Rate
this Script @ Hot Scripts</a></p>
<hr>
<p><b>Stay updated</b></p>
<p>Join my FREE newsletter and you will be notified about new scripts, new versions of the existing scripts
and other important news from PHPJunkYard.<br>
<a href="http://www.phpjunkyard.com/newsletter.php"
target="_new">Click here for more info</a></p>
<hr>
<p>&nbsp;</p>
</td>
</tr>
<tr>
<!--
Changing the "Powered by" credit sentence without purchasing a licence is illegal!
Please visit http://www.phpjunkyard.com/copyright-removal.php for more information.
-->
<td align="center" class="copyright">Powered by <a href="http://www.phpjunkyard.com/php-link-manager.php" target="_new">PHP Link manager</a> <?php echo($settings['verzija']); ?><br>
(c) Copyright 2004 <a href="http://www.phpjunkyard.com/" target="_new">PHPjunkyard - Free PHP scripts</a></td>
</tr>
</table>
</div></center>
</body>
</html>
<?php
exit();
}
// END mainpage()

// START checkpassword()
function checkpassword($thepass) {
global $settings;
	if ($thepass != $settings['apass']) {error("Incorrect password!");}

}
// END checkpassword()

// START login()
function login() {
global $settings;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
<link rel="STYLESHEET" type="text/css" href="style.css">
<title>PHP Link manager admin panel</title>
</head>
<body>
<div align="center"><center>
<table border="0" width="700">
<tr>
<td align="center" class="glava"><font class="header">PHP Link manager <?php echo($settings['verzija']); ?><br>-- Admin panel --</font></td>
</tr>
<tr>
<td class="vmes"><p>&nbsp;</p>
<div align="center"><center>
<table width="400"> <tr>
<td align="center" class="head">Enter admin panel</td>
</tr>
<tr>
<td align="center" class="dol"><form method="POST" action="index.php"><p>&nbsp;<br><b>Please type in your admin password</b><br><br>
<input type="password" name="pass" size="20"><input type="hidden" name="action" value="login"></p>
<p><input type="submit" name="enter" value="Enter admin panel"></p>
</form>
</td>
</tr> </table>
</div></center>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
</tr>
<tr>
<!--
Changing the "Powered by" credit sentence without purchasing a licence is illegal!
Please visit http://www.phpjunkyard.com/copyright-removal.php for more information.
-->
<td align="center" class="copyright">Powered by <a href="http://www.phpjunkyard.com/php-link-manager.php" target="_new">PHP Link manager</a> <?php echo($settings['verzija']); ?><br>
(c) Copyright 2004 <a href="http://www.phpjunkyard.com/" target="_new">PHPjunkyard - Free PHP scripts</a></td>
</tr>
</table>
</div></center>
</body>
</html>
<?php
exit();
}
// END login()

// START error()
function error($myproblem) {
global $settings;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
<link rel="STYLESHEET" type="text/css" href="style.css">
<title>PHP Link manager admin panel</title>
</head>
<body>
<div align="center"><center>
<table border="0" width="700">
<tr>
<td align="center" class="glava"><font class="header">PHP Link manager <?php echo($settings['verzija']); ?><br>-- Admin panel --</font></td>
</tr>
<tr>
<td class="vmes"><p>&nbsp;</p>
<div align="center"><center>
<table width="400"> <tr>
<td align="center" class="head">ERROR</td>
</tr>
<tr>
<td align="center" class="dol">
<form>
<p>&nbsp;</p>
<p><b>An error occured:</b></p>
<p><?php echo($myproblem); ?></p>
<p>&nbsp;</p>
<p><a href="javascript:history.go(-1)">Back to the previous page</a></p>
<p>&nbsp;</p>
</form>
</td>
</tr> </table>
</div></center>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
</tr>
<tr>
<!--
Changing the "Powered by" credit sentence without purchasing a licence is illegal!
Please visit http://www.phpjunkyard.com/copyright-removal.php for more information.
-->
<td align="center" class="copyright">Powered by <a href="http://www.phpjunkyard.com/php-link-manager.php" target="_new">PHP Link manager</a> <?php echo($settings['verzija']); ?><br>
(c) Copyright 2004 <a href="http://www.phpjunkyard.com/" target="_new">PHPjunkyard - Free PHP scripts</a></td>
</tr>
</table>
</div></center>
</body>
</html>
<?php
exit();
}
// END error()

?>