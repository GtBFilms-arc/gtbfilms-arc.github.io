<?php
// >>> SETUP YOUR MESSAGE BOARD <<< //
// Detailed information found in the readme.htm file
// Settings file version: 1.3

// Password for admin area
$settings['apass']='oldboynonsense';

// Website title
$settings['website_title']="Jane's Webpage";

// Website URL
$settings['website_url']='http://www.janebushell.co.uk';

// Message board title
$settings['mboard_title']="Jane's Webpage";

// URL to folder where message board is installed
// DO NOT a trailing "/" !
$settings['mboard_url']='http://www.gonadovision.homeip.net/users/jane/messageboard';

/* Prevent automated submissions (recommended YES)? 0 = NO, 1 = YES, GRAPHICAL, 2 = YES, TEXT */  
$settings['autosubmit']=1;

/* Checksum - just type some digits and chars. Used to help prevent SPAM */
$settings['filter_sum']='d83hfdwg34';

/* Use JunkMark(tm) SPAM filter (recommended YES)? 1 = YES, 0 = NO */
$settings['junkmark_use']=1;

/* JunkMark(tm) score limit after which messages are marked as SPAM */
$settings['junkmark_limit']=60;

// Allow smileys? 1 = YES, 0 = NO
$settings['smileys']=1;

// Send you an e-mail when a new entry is added? 1 = YES, 0 = NO
$settings['notify']=0;

// Your e-mail. Only required if $settings['notify'] is set to 1.
$settings['admin_email']='you@yourdomain.com';

// Display IP number of members posting? 1 = YES, 0 = NO
$settings['display_IP']=1;

// Maximum number of posts displayed on the first page
$settings['maxposts']=50;

// Keep or delete old posts? 1 = KEEP, 0 = DELETE
$settings['keepoldmsg']=1;

// File exstention for message files
$settings['extension']='html';

/* Filter bad words? 1 = YES, 0 = NO */
$settings['filter']=1;

/* Filter language. Please refer to readme for info on how to add more bad words
to the list! */
$settings['filter_lang']='en';

/* DO NOT EDIT BELOW */
if (!defined('IN_SCRIPT')) {die('Invalid attempt!');}
?>
