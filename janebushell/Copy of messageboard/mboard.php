<?php
# PHP message board (MBoard)
# Version: 1.21
# File name: mboard.php
# Written 19th April 2005 by Klemen Stirn (info@phpjunkyard.com)
# http://www.PHPJunkYard.com

##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright 2004-2005 PHPJunkYard All Rights Reserved.                       #
#                                                                            #
# This script may be used and modified free of charge by anyone so long as   #
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

require_once('settings.php');
printTopHTML();

if($settings['system'] == 2) {$settings['newline']="\r\n";}
elseif($settings['system'] == 3) {$settings['newline']="\r";}
else {$settings['newline']="\n";}

if(!(empty($_REQUEST['a']))) {
$a=$_REQUEST['a'];

		if ($a=="delete") {confirmDelete("$_REQUEST[num]","$_REQUEST[up]");}
        if ($a=="confirmdelete") {doDelete("$_REQUEST[pass]","$_REQUEST[num]","$_REQUEST[up]");}

    $name=htmlspecialchars("$_REQUEST[name]");
    if(empty($name)) {problem("Please enter your name!");}
    $message=htmlspecialchars("$_REQUEST[message]");
    if(empty($message)) {problem("Please write a message");}
    if(!empty($_REQUEST['email']))
    {
    $email=htmlspecialchars("$_REQUEST[email]");
		if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
		{problem("Please enter a valid e-mail address!");}
	$char = array('.','@');
	$repl = array("&#46;","&#64;");
	$email=str_replace($char,$repl,$email);
    }
    else {$email="NO";}
        if ($a=="addnew")
            {
            $subject=htmlspecialchars("$_REQUEST[subject]");
   			if(empty($subject)) {problem("Please write a subject");}
            addNewTopic($name,$email,$subject,$message);
            }
        elseif ($a=="reply") {
        	$subject=htmlspecialchars("$_REQUEST[subject]");
   			if(empty($subject)) {problem("Please write a subject");}
            $_REQUEST['orig_id']=htmlspecialchars("$_REQUEST[orig_id]");
            $_REQUEST['orig_name']=htmlspecialchars("$_REQUEST[orig_name]");
            $_REQUEST['orig_subject']=htmlspecialchars("$_REQUEST[orig_subject]");
            $_REQUEST['orig_date']=htmlspecialchars("$_REQUEST[orig_date]");
            addNewReply($name,$email,$subject,$message,"$_REQUEST[orig_id]","$_REQUEST[orig_name]","$_REQUEST[orig_subject]","$_REQUEST[orig_date]");
            }
        else {problem("This is not a valid action.");}
}

?>
<h3 align="center"><?php echo("$settings[mboard_title]"); ?></h3>

<div align="center"><center>
<table border="0" width="95%"><tr>
<td>

<p><a href="#new"><b>New topic</b></a></p>
<hr>
<p align="center"><b>Recent topics</b></p>
<ul>
<?php
include_once "threads.txt";
?>
</ul>
<hr></td>
</tr></table>
</center></div>

<p align="center"><a name="new"></a><b>Add new topic</b></p>
<div align="center"><center>
<table border="0"><tr>
<td>
<form method=post action="mboard.php" name="form">
<p><input type="hidden" name="a" value="addnew"><b>Name:</b><br><input type=text name="name" size=30 maxlength=30><br>
E-mail (optional):<br><input type=text name="email" size=30 maxlength=50><br>
<b>Subject:</b><br><input type=text name="subject" size=30 maxlength=100><br><br>
<b>Message:</b><br><textarea cols=50 rows=9 name="message"></textarea><br>
Insert styled text: <a href="Javascript:insertspecial('B')"><b>Bold</b></a> |
<a href="Javascript:insertspecial('I')"><i>Italic</i></a> |
<a href="Javascript:insertspecial('U')"><u>Underlined</u></a><br>
<input type="checkbox" name="nostyled" value="Y"> Disable styled text</p>
<?php
if ($settings['smileys'] == 1) {
echo "<p><a href=\"javascript:openSmiley('$settings[mboard_url]/smileys.htm')\">Insert smileys</a>
(Opens a new window)<br>
<input type=\"checkbox\" name=\"nosmileys\" value=\"Y\"> Disable smileys";
}
?>
</p>
<p><input type=submit value="Add new topic">
</form>
</td>
</tr></table>
</center></div>
<?php
printCopyHTML();
printDownHTML();
exit();


// >>> START FUNCTIONS <<< //

function filter_bad_words($text) {
global $settings;
$file = 'badwords/'.$settings['filter_lang'].'.php';

	if (file_exists($file))
    {
    	include_once($file);
    }
    else
    {
    	problem("The bad words file ($file) can't be found! Please check the
        name of the file. On most servers names are CaSe SeNsiTiVe!");
    }

	foreach ($settings['badwords'] as $k => $v)
    {
    	$text = preg_replace("/$k/i",$v,$text);
    }

return $text;
} // END filter_bad_words

function addNewReply($name,$email,$subject,$comments,$orig_id,$orig_name,$orig_subject,$orig_date) {
global $settings;
$date=date ("d/M/Y H:m:s");

$comments = str_replace("\'","'",$comments);
$comments = str_replace("\&quot;","&quot;",$comments);
$comments = MakeUrl($comments);
$comments = str_replace("\r\n","<br>",$comments);
$comments = str_replace("\n","<br>",$comments);
$comments = str_replace("\r","<br>",$comments);

/* Let's strip those slashes */
$comments = stripslashes($comments);
$subject = stripslashes($subject);
$name = stripslashes($name);
$orig_name = stripslashes($orig_name);
$orig_subject = stripslashes($orig_subject);

/* Make text bold, italic and underlined text */
if ($_REQUEST['nostyled'] != "Y") {$comments=styledText($comments);}

if ($settings['smileys'] == 1 && $_REQUEST['nosmileys'] != "Y") {$comments = processsmileys($comments);}
if ($email != "NO") {$mail = "&lt;<a href=\"mailto:$email\">$email</a>&gt;";}
else {$mail=" ";}

if ($settings['filter']) {
$comments = filter_bad_words($comments);
$name = filter_bad_words($name);
$subject = filter_bad_words($subject);
}

$fp = fopen("count.txt","rb") or problem("Can't open the count file (count.txt) for reading!");
$count=fread($fp,6);
fclose($fp);
$count++;
$fp = fopen("count.txt","wb") or problem("Can't open the count file (count.txt) for writing! Please CHMOD this file to 666 (rw-rw-rw)");
fputs($fp,$count);
fclose($fp);

$threads = file("threads.txt");

for ($i=0;$i<=count($threads);$i++) {
	if(preg_match("/<!--o $orig_id-->/",$threads[$i]))
    	{
        preg_match("/<\!--(.*)-->\s\((.*)\)/",$threads[$i],$matches);
        $number_of_replies=$matches[2];$number_of_replies++;
        $threads[$i] = "<!--o $orig_id--> ($number_of_replies)$settings[newline]";
        $threads[$i] .= "<!--z $count-->$settings[newline]";
        $threads[$i] .= "<!--s $count--><ul><li><a href=\"msg/$count.$settings[extension]\">$subject</a> - <b>$name</b> <i>$date</i>$settings[newline]";
        $threads[$i] .= "<!--o $count--> (0)$settings[newline]";
        $threads[$i] .= "</li></ul><!--k $count-->$settings[newline]";
        break;
        }
}

$newthreads=implode('',$threads);

$fp = fopen("threads.txt","wb") or problem("Couldn't open links file (threads.txt) for writing! Please CHMOD it to 666 (rw-rw-rw)!");
fputs($fp,$newthreads);
fclose($fp);

$other = "in reply to <a href=\"$orig_id.$settings[extension]\">$orig_subject</a> posted by $orig_name on $orig_date";
createNewFile($name,$mail,$subject,$comments,$count,$date,$other,$orig_id);

$oldfile="msg/".$orig_id.".".$settings['extension'];

$filecontent = file($oldfile);

for ($i=0;$i<=count($filecontent);$i++) {
	if(preg_match("/<!-- zacni -->/",$filecontent[$i]))
    	{
        $filecontent[$i] = "<!-- zacni -->".$settings['newline']."<!--s $count--><li><a href=\"$count.$settings[extension]\">$subject</a> - <b>$name</b> <i>$date</i></li>".$settings['newline'];
        break;
        }
}

$rewritefile=implode('',$filecontent);

$fp = fopen($oldfile,"wb") or problem("Couldn't open file $oldfile for writing! Please CHMOD the &quot;msg&quot; folder to 777 (rwx-rwx-rwx)!");
fputs($fp,$rewritefile);
fclose($fp);

?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><b>Your message was successfully added!</b></p>
<p align="center"><a href="mboard.php">Click here to continue</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
printCopyHTML();
printDownHTML();
exit();
}

function createNewFile($name,$mail,$subject,$comments,$count,$date,$other="",$up="0") {
global $settings;
$header=implode('',file("header.txt"));
$footer=implode('',file("footer.txt"));
$content="<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
<title>$subject</title>
<meta content=\"text/html; charset=windows-1250\">
<link href=\"$settings[mboard_url]/style.css\" type=\"text/css\" rel=\"stylesheet\">
<META HTTP-EQUIV=\"Expires\" CONTENT=\"-1\">
<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">
<script language=\"Javascript\" src=\"$settings[mboard_url]/javascript.js\"><!--
//-->
</script>
</head>
<body>
";
$content.=$header;

$content.="
<h3 align=\"center\">$settings[mboard_title]</h3>

<div align=\"center\"><center>
<table border=\"0\" width=\"95%\"><tr>
<td>

<p align=\"center\"><a href=\"#new\">Post a reply</a> ||
<a href=\"$settings[mboard_url]/mboard.php\">Back to $settings[mboard_title]</a></p>
<hr>
<p align=\"center\"><b>$subject</b></p>

<p><a href=\"$settings[mboard_url]/mboard.php?a=delete&num=$count&up=$up\"><img
src=\"$settings[mboard_url]/images/delete.gif\" width=\"16\" height=\"14\" border=\"0\" alt=\"Delete this post\"></a>
Submitted by $name $mail on $date $other";

if ($settings['display_IP']==1) {$content .= "<br><font class=\"ip\">$_SERVER[REMOTE_ADDR]</font>";}

$content .= "</p>

<p><b>Message</b>:</p>

<p>$comments</p>

<hr>

<p align=\"center\"><b>Replies to this post</b></p>
<ul>
<!-- zacni --><p>No replies yet</p>
</ul>
<hr></td>
</tr></table>
</center></div>

<p align=\"center\"><a name=\"new\"></a><b>Reply to this post</b></p>
<div align=\"center\"><center>
<table border=\"0\"><tr>
<td>
<form method=post action=\"$settings[mboard_url]/mboard.php\" name=\"form\">
<p><input type=\"hidden\" name=\"a\" value=\"reply\"><b>Name:</b><br><input type=text name=\"name\" size=30 maxlength=30><br>
E-mail (optional):<br><input type=text name=\"email\" size=30 maxlength=50><br>
<b>Subject:</b><br><input type=text name=\"subject\" value=\"Re: $subject\" size=30 maxlength=100><br><br>
<b>Message:</b><br><textarea cols=50 rows=9 name=\"message\"></textarea>
<input type=\"hidden\" name=\"orig_id\" value=\"$count\">
<input type=\"hidden\" name=\"orig_name\" value=\"$name\">
<input type=\"hidden\" name=\"orig_subject\" value=\"$subject\">
<input type=\"hidden\" name=\"orig_date\" value=\"$date\"><br>
Insert styled text: <a href=\"Javascript:insertspecial('B')\"><b>Bold</b></a> |
<a href=\"Javascript:insertspecial('I')\"><i>Italic</i></a> |
<a href=\"Javascript:insertspecial('U')\"><u>Underlined</u></a><br>
<input type=\"checkbox\" name=\"nostyled\" value=\"Y\"> Disable styled text</p>
";

if ($settings['smileys'] == 1) {
$content.="<p><a href=\"javascript:openSmiley('$settings[mboard_url]/smileys.htm')\">Insert smileys</a>
(Opens a new window)<br>
<input type=\"checkbox\" name=\"nosmileys\" value=\"Y\"> Disable smileys";
}

$content.="
</p>
<p><input type=submit value=\"Submit reply\">
</form>
</td>
</tr></table>
</center></div>
";

$content.="
<!--
Changing the \"Powered by\" credit sentence without purchasing a licence is illegal!
Please visit http://www.phpjunkyard.com/copyright-removal.php for more information.
-->
<p align=\"center\"><font class=\"smaller\">Powered by
<a href=\"http://www.phpjunkyard.com/php-message-board.php\" class=\"smaller\" target=\"_blank\">Free
PHP message board</a> $settings[verzija] from
<a href=\"http://www.phpjunkyard.com/\" target=\"_blank\" class=\"smaller\">PHPJunkYard
- Free PHP scripts</a></font></p>
";
$content.=$footer;
$content.="
</body>
</html>";

$newfile="msg/".$count.".".$settings['extension'];
$fp = fopen($newfile,"wb") or problem("Couldn't create file &quot;$newfile&quot;! Please CHMOD the &quot;msg&quot; folder to 666 (rw-rw-rw)!");
fputs($fp,$content);
fclose($fp);
unset($content);
unset($header);
unset($footer);

/* Notify admin */
if ($settings['notify'] == 1)
	{
    $message = "Hello!

Someone has just posted a new message on your forum! Visit the below URL to view the message:

$settings[mboard_url]/$newfile

End of message
";

    mail("$settings[admin_email]","New forum post",$message);
    }

/* Delete old posts */
$count -= $settings['maxposts'];
$newfile="msg/".$count.".".$settings['extension'];
if (file_exists($newfile))
	{
    	deleteOld($count,$newfile);
    }

}

function addNewTopic($name,$email,$subject,$comments) {
global $settings;
$date=date ("d/M/Y H:m:s");

$comments = str_replace("\'","'",$comments);
$comments = str_replace("\&quot;","&quot;",$comments);
$comments = MakeUrl($comments);
$comments = str_replace("\r\n","<br>",$comments);
$comments = str_replace("\n","<br>",$comments);
$comments = str_replace("\r","<br>",$comments);

/* Let's strip those slashes */
$comments = stripslashes($comments);
$subject = stripslashes($subject);
$name = stripslashes($name);

/* Make text bold, italic and underlined */
if ($_REQUEST['nostyled'] != "Y") {$comments=styledText($comments);}

if ($settings['smileys'] == 1 && $_REQUEST['nosmileys'] != "Y") {$comments = processsmileys($comments);}
if ($email != "NO") {$mail = "&lt;<a href=\"mailto&#58;$email\">$email</a>&gt;";}
else {$mail=" ";}

if ($settings['filter']) {
$comments = filter_bad_words($comments);
$name = filter_bad_words($name);
$subject = filter_bad_words($subject);
}

$fp = fopen("count.txt","rb") or problem("Can't open the count file (count.txt) for reading!");
$count=fread($fp,6);
fclose($fp);
$count++;
$fp = fopen("count.txt","wb") or problem("Can't open the count file (count.txt) for writing! Please CHMOD this file to 666 (rw-rw-rw)");
fputs($fp,$count);
fclose($fp);

$addline = "<!--z $count-->$settings[newline]";
$addline .= "<!--s $count--><p><li><a href=\"msg/$count.$settings[extension]\">$subject</a> - <b>$name</b> <i>$date</i>$settings[newline]";
$addline .= "<!--o $count--> (0)$settings[newline]";
$addline .= "</li><!--k $count-->$settings[newline]";

$fp = @fopen("threads.txt","rb") or problem("Can't open the log file (threads.txt) for reading!");
$threads = @fread($fp,filesize("threads.txt"));
fclose($fp);
$addline .= $threads;
$fp = fopen("threads.txt","wb") or problem("Couldn't open links file (threads.txt) for writing! Please CHMOD it to 666 (rw-rw-rw)!");
fputs($fp,$addline);
fclose($fp);
createNewFile($name,$mail,$subject,$comments,$count,$date);

?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><b>Your message was successfully added!</b></p>
<p align="center"><a href="mboard.php">Click here to continue</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
printCopyHTML();
printDownHTML();
exit();
}

function deleteOld($num,$file) {
global $settings;

	if ($settings['keepoldmsg'] == 0) {unlink($file);}

// Delete input from threads.txt
$keep = "YES";
$threads = file("threads.txt");
for ($i=0;$i<=count($threads);$i++) {
	if(preg_match("/<!--z $num-->/",$threads[$i])) {unset($threads[$i]); $keep = "NO";}
    elseif(preg_match("/<!--k $num-->/",$threads[$i])) {unset($threads[$i]); break;}
    elseif($keep == "NO") {unset($threads[$i]);}
    else {continue;}
}
$newthreads=implode('',$threads);
$fp = fopen("threads.txt","wb") or problem("Couldn't open links file (threads.txt) for writing! Please CHMOD it to 666 (rw-rw-rw)!");
fputs($fp,$newthreads);
fclose($fp);

}

function doDelete($pass,$num,$up) {
global $settings;
if ($pass != $settings[apass]) {problem("Wrong password! The entry hasn't been deleted.");}

	if ($settings['keepoldmsg'] == 0)
    {
		unlink("msg/$num.$settings[extension]") or problem("Can't delete this post,
        access denied or post doesn't exist!");
    }

// Delete input from threads.txt
$keep = "YES";
$threads = file("threads.txt");
for ($i=0;$i<=count($threads);$i++) {

	if(!(empty($up)) && preg_match("/<!--o $up-->/",$threads[$i]))
    	{
        preg_match("/<\!--(.*)-->\s\((.*)\)/",$threads[$i],$matches);
        $number_of_replies=$matches[2];$number_of_replies--;
        $threads[$i] = "<!--o $up--> ($number_of_replies)$settings[newline]";
        }

	elseif(preg_match("/<!--z $num-->/",$threads[$i])) {unset($threads[$i]); $keep = "NO";}
    elseif(preg_match("/<!--k $num-->/",$threads[$i])) {unset($threads[$i]); break;}
    elseif($keep == "NO") {unset($threads[$i]);}
    else {continue;}
}
$newthreads=implode('',$threads);
$fp = fopen("threads.txt","wb") or problem("Couldn't open links file (threads.txt) for writing! Please CHMOD it to 666 (rw-rw-rw)!");
fputs($fp,$newthreads);
fclose($fp);

// Delete input from upper file if any
$upfile="msg/$up.$settings[extension]";
if(!(empty($up)) && file_exists($upfile)) {
$threads = file($upfile);
for ($i=0;$i<=count($threads);$i++) {
    if(preg_match("/<!--s $num-->/",$threads[$i])) {unset($threads[$i]); break;}
}
$newthreads=implode('',$threads);
$fp = fopen($upfile,"wb") or problem("Couldn't open file $upfile for writing! Please CHMOD it to 666 (rw-rw-rw)!");
fputs($fp,$newthreads);
fclose($fp);
}
?>
<hr>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><b>Selected post and all replies to it were successfully removed!</b></p>
<p align="center"><a href="<?php echo($settings[mboard_url]); ?>/mboard.php">Click here to continue</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
printCopyHTML();
printDownHTML();
exit();
}

function confirmDelete($num,$up) {
global $settings;
?>
<hr>
<p>&nbsp;</p>
<p>&nbsp;</p>
<form action="<?php echo($settings[mboard_url]); ?>/mboard.php" method="POST"><input type="hidden" name="a" value="confirmdelete">
<input type="hidden" name="num" value="<?php echo($num); ?>"><input type="hidden" name="up" value="<?php echo($up); ?>">
<p align="center"><b>Please enter your administration password:</b><br>
<input type="password" name="pass" size="20"></p>
<p align="center"><b>Are you sure you want to delete this post and all replies to it? This action cannot be undone!</b></p>
<p align="center"><input type="submit" value="YES, delete this entry and replies to it"> | <a href="<?php echo($settings[mboard_url]); ?>/mboard.php">NO, I changed my mind</a></p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
printCopyHTML();
printDownHTML();
exit();
}

function styledText($strText)
{
$strText = preg_replace("/\[B\](.*?)\[\/B\]/i","<B>$1</B>",$strText);
$strText = preg_replace("/\[I\](.*?)\[\/I\]/i","<I>$1</I>",$strText);
$strText = preg_replace("/\[U\](.*?)\[\/U\]/i","<U>$1</U>",$strText);
return($strText);
}

function MakeUrl($strUrl)
{
$strText = ' ' . $strUrl;
$strText = preg_replace("#(^|[\n ])([\w]+?://[^ \"\n\r\t<]*)#is", "$1<a href=\"$2\" target=\"_blank\" rel=\"nofollow\">$2</a>", $strText);
$strText = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#is", "$1<a href=\"http://$2\" target=\"_blank\" rel=\"nofollow\">$2</a>", $strText);
$strText = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "$1<a href=\"mailto&#58;$2&#64;$3\" rel=\"nofollow\">$2&#64;$3</a>", $strText);
$strText = substr($strText, 1);
return($strText);
}

function processsmileys($text) {
global $settings;
$text = preg_replace("/\:\)/","<img src=\"$settings[mboard_url]/images/icon_smile.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:\(/","<img src=\"$settings[mboard_url]/images/icon_frown.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:D/","<img src=\"$settings[mboard_url]/images/icon_biggrin.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\;\)/","<img src=\"$settings[mboard_url]/images/icon_wink.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:o/","<img src=\"$settings[mboard_url]/images/icon_redface.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:p/i","<img src=\"$settings[mboard_url]/images/icon_razz.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:cool\:/i","<img src=\"$settings[mboard_url]/images/icon_cool.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:rolleyes\:/i","<img src=\"$settings[mboard_url]/images/icon_rolleyes.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:mad\:/i","<img src=\"$settings[mboard_url]/images/icon_mad.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:eek\:/i","<img src=\"$settings[mboard_url]/images/icon_eek.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:clap\:/i","<img src=\"$settings[mboard_url]/images/yelclap.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:bonk\:/i","<img src=\"$settings[mboard_url]/images/bonk.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:chased\:/i","<img src=\"$settings[mboard_url]/images/chased.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:crazy\:/i","<img src=\"$settings[mboard_url]/images/crazy.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:cry\:/i","<img src=\"$settings[mboard_url]/images/cry.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:curse\:/i","<img src=\"$settings[mboard_url]/images/curse.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:err\:/i","<img src=\"$settings[mboard_url]/images/errr.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:livid\:/i","<img src=\"$settings[mboard_url]/images/livid.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:rotflol\:/i","<img src=\"$settings[mboard_url]/images/rotflol.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:love\:/i","<img src=\"$settings[mboard_url]/images/love.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:nerd\:/i","<img src=\"$settings[mboard_url]/images/nerd.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:nono\:/i","<img src=\"$settings[mboard_url]/images/nono.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:smash\:/i","<img src=\"$settings[mboard_url]/images/smash.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:thumbsup\:/i","<img src=\"$settings[mboard_url]/images/thumbup.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:toast\:/i","<img src=\"$settings[mboard_url]/images/toast.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:welcome\:/i","<img src=\"$settings[mboard_url]/images/welcome.gif\" border=\"0\" alt=\"\">",$text);
$text = preg_replace("/\:ylsuper\:/i","<img src=\"$settings[mboard_url]/images/ylsuper.gif\" border=\"0\" alt=\"\">",$text);
return "$text";
}

function problem($myproblem) {
echo"<p>&nbsp;</p>
<p>&nbsp;</p>
<p align=\"center\"><b>Error</b></p>
<p align=\"center\">$myproblem</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>";
printCopyHTML();
printDownHTML();
exit();
}

function printTopHTML() {
header("Expires: Mon, 26 Jul 2000 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
global $settings;
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
<title>$settings[mboard_title]</title>
<meta content=\"text/html; charset=windows-1250\">
<link href=\"style.css\" type=\"text/css\" rel=\"stylesheet\">
<script language=\"Javascript\" src=\"javascript.js\" type=\"text/javascript\"><!--
//-->
</script>
</head>
<body>
";
include_once "header.txt";
}

function printDownHTML() {
global $settings;
include_once "footer.txt";
echo "</body>
</html>";
}

function printCopyHTML() {
global $settings;

}
?>