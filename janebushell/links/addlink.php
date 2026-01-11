<?php

# PHP link manager (Linkman)
# Version: 1.02 from June 23, 2005
# File name: addlink.php
# File last modified: June 23, 2005
# Written 16th July 2004 by Klemen Stirn (info@phpjunkyard.com)
# http://www.PHPJunkYard.com

##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright 2004-2005 PHPJunkYard All Rights Reserved.                       #
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

require_once("settings.php");
require_once("header.txt");

if (empty($_REQUEST['name'])) {myerror("Please enter your name!");} else {$name=htmlspecialchars("$_REQUEST[name]");}
if (empty($_REQUEST['email'])) {myerror("Please enter your e-mail address!");} else {$email=htmlspecialchars("$_REQUEST[email]");}
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {myerror("Please enter a valid e-mail address!");}
if (empty($_REQUEST['title'])) {myerror("Please enter the title (name) of your website!");} else {$title=htmlspecialchars("$_REQUEST[title]");}

if (empty($_REQUEST['url'])) {myerror("Please enter the url of your website!");} else {$url=rtrim(htmlspecialchars("$_REQUEST[url]"));}
if (!(preg_match("/(http:\/\/+[\w\-]+\.[\w\-]+)/i",$url))) {myerror("The site URL is not valid!");}
if (empty($_REQUEST['recurl']) || $_REQUEST['recurl']=="http://") {myerror("Please enter the url where a reciprocal link to our site is placed!");} else {$recurl=rtrim(htmlspecialchars("$_REQUEST[recurl]"));}
if (!(preg_match("/(http:\/\/+[\w\-]+\.[\w\-]+)/i",$recurl))) {myerror("The reciprocal page URL is not valid!");}

preg_match("/^(http:\/\/)?([^\/]+)/i",$url, $matches);
preg_match("/^(http:\/\/)?([^\/]+)/i",$recurl, $recmatches);
if ($matches[2] != $recmatches[2]) {myerror("The reciprocal link must be placed under the same (sub)domain as your link is!");}

$url=str_replace("&amp;","&",$url);
$recurl=str_replace("&amp;","&",$recurl);
$site_url2 = str_replace("/","\\/",$settings['site_url']);

if (empty($_REQUEST['description'])) {myerror("Please enter description of your site!");} else {$description=htmlspecialchars("$_REQUEST[description]");}
if(strlen($description)>200) {myerror("Description is too long! Description of your website is limited to 200 chars.");}

$html = @file_get_contents($recurl, "r") or myerror("Can't open remote URL!");
if (!preg_match("/$site_url2/i",$html)) {
myerror("Our URL (<a href=\"$settings[site_url]\">$settings[site_url]</a>) wasn't found on your reciprocal links page (<a href=\"$recurl\">$recurl</a>)!<br><br>
Please make sure you place this exact URL on your page before adding your link!");
}

if($settings['system'] == 2) {$newline="\r\n";}
elseif($settings['system'] == 3) {$newline="\r";}
else {$newline="\n";}

$fp = fopen($settings['linkfile'],"rb") or die("Can't open the link file ($settings[linkfile]) for reading!");
$content=@fread($fp,filesize($settings['linkfile']));
fclose($fp);
$content = trim(chop($content));
$lines = explode($newline,$content);
if (count($lines)>$settings['max_links']) {myerror("We are not accepting any more links at the moment. We appologize for the inconvenience!");}

$replacement = stripslashes("$name$settings[delimiter]$email$settings[delimiter]$title$settings[delimiter]$url$settings[delimiter]$recurl$settings[delimiter]$description$newline");

if ($settings['add_to'] == 0) {
    $fp = fopen($settings['linkfile'],"rb");
	$links = @fread($fp,filesize($settings['linkfile']));
	fclose($fp);

	$replacement .= $links;

    $fp = fopen($settings['linkfile'],"wb") or myerror("Couldn't open links file for writing! Please CHMOD all txt files to 666 (rw-rw-rw)!");
	fputs($fp,$replacement);
	fclose($fp);
	}
else {
    $fp = fopen($settings['linkfile'],"ab") or myerror("Couldn't open links file for appending! Please CHMOD all txt files to 666 (rw-rw-rw)!");
	fputs($fp,$replacement);
	fclose($fp);
    }

if($settings['notify'] == 1) {
$message="Hello,

Someone just added a new link to your links page on $settings[site_url]

Link details:

Name: $name
E-mail: $email
URL: $url
Reciprocal link: $recurl
Title: $title
Description:
$description


End of message

";
$headers = "From: $name <$email>\n";
$headers .= "Reply-To: $name <$email>\n\n";
mail("$settings[admin_email]","New link submitted",$message,$headers);
}

?>
<p align="center"><b>Your link has been added!</b></p>
<p>&nbsp;</p>
<p align="center">Thank you, your link has been successfully added to our link exchange (try reloading our links page if you don't see your link there yet)!</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><a href="<?php echo("$settings[site_url]"); ?>">Back to the main page</a></p>
<?
require_once("footer.txt");
exit();
?>