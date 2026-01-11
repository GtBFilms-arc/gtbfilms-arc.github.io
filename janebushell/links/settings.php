<?php
// SETUP YOUR LINK MANAGER
// Detailed information found in the readme.htm file

// What type of server is your website on?
// 1 = UNIX (Linux), 2 = Windows, 3 = Machintos
$settings['system']=2;

// Password for admin area
$settings['apass']="oldboynonsense";

// Your website URL
$settings['site_url']="http://www.janebushell.co.uk";

// Admin e-mail
$settings['admin_email']="alan@broon.co.uk";

// Send you an e-mail everytime someone adds a link? 1=YES, 0=NO
$settings['notify']=0;

// Maximum number of links
$settings['max_links']=50;

// Use "clean" URLs or redirects? 1=clean, 0=redirects
$settings['clean']=1;

// Where to add new links? 0 = top of list, 1 = end of list
$settings['add_to']=1;

// Name of the file where link URLs and other info is stored
$settings['linkfile']="linkinfo.txt";

// DO NOT EDIT BELOW
$settings['verzija']="1.02";
$settings['delimiter']="\t";

function myerror($problem) {
require_once("header.txt");
echo "
<p align=\"center\"><b>ERROR</b></p>
<p>&nbsp;</p>
<p align=\"center\">$problem</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align=\"center\"><a href=\"javascript:history.go(-1)\">Back to the previous page</a></p>
";
require_once("footer.txt");
exit();
}
?>