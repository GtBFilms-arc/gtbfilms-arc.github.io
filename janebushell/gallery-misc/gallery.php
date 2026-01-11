<?php

/**
 * This script generates the index of all galleries.
 *
 * It does not receive any argument via query string except when
 * user wants to logout of a password protected gallery. It receives
 * 
 *   action=logout
 * 
 * 
 * If file $GID_DIRS does not exist, then shows a message telling
 * the admin to modify config.php and log in as admin.
 *
 * 
 * 
 * @package user
 */

session_start();
require_once('config.php');
require_once('view_func.php');
require_once('functions.php');

// Log out of the gallery. This is for password protected galleries
if ($_GET['action']=="logout"){
  if ($USE_COOKIES) {
    setcookie('y_gallery_pwd','',time()-3600);
    $HTTP_COOKIE_VARS['y_gallery_pwd']='';
  }
  else { //use sessions;
    $HTTP_SESSION_VARS['y_gallery_pwd']='';
  }
}


include($TEMPLATE_DIR . 'face_begin.php');
heading($I_TITLE);
print_main_menubar();




// First Time we run YAPIG ---------------
if (!file_exists($GID_DIRS)) {
    heading(_y("Congratulations Yapig is up and running!"),3);

//welcome message    
    echo _y('
   <p>Welcome to <a href="http://yapig.sf.net">Yapig.</a></p>
   <p>There are <strong>NO</strong> defined  galleries.<p>
   <p>If this is the first time you run:</p>
   <ol>
   <li>Edit the <i>config.php</i> file.</li>
   <li><a href="admin.php">Login as admin</a> and add a new gallery.</li>
   </ol>
    ');
    include($TEMPLATE_DIR . 'face_end.php');
    die;
}
//------------------------------------------------------------

//  If the file exists then user already created any gallery.
//echo "<br />PW:fwform $fwform -gp: $gallery_password -ses: ". $HTTP_SESSION_VARS['y_gallery_pwd'];
// So we get list of gallery ids and directorys;

$base_url="view.php?hit=yes&amp;gid=";
if (!index_of_galleries($base_url,$GID_DIRS)) 
     warning(_y("There are no galleries. Login as admin to create new galleries"));
include($TEMPLATE_DIR . 'face_end.php');
?>
