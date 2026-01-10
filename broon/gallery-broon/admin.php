<?php

/** 
 *  Main admin module. This module presents login form and main
 *  admin page. Most of admin tasks are implemented in other admin modules.
 *  such as modify_gallery, delete_gallery, etc..
 *
 * Actions:
 *
 * + While STOP in config.php is set as true, do nothing.No admin login
 *   is possible.
 * + Show admin login form if admin is not logged in.
 * + In admin form  $username and $userpass are set. If they are correct
 *   then send a session cookie or a cookie (check_admin_login)
 * + Admin actions are selected using $action variable (on QUERY_STING)
 *    - logout: remove set cookies
 *    - add: add a gallery. Prints add gallery form. (default action)
 *    - change: change admin config. Not implemented
 *
 * @package admin
 */

session_start();
header("Cache-control: private");

/***/
include('config.php');
include('functions.php');
include('admin_func.php');

////////////////////////// MAIN PROGRAM ///////////////////


//First of all, check if user read all the config.php file 
if (isset($STOP)&&($STOP==true)){
    template('face_begin');
    heading(_y("<strong>Yapig</strong> administration"));
    echo<<<MODIFY
    
    <p>Thank you for using <strong>Yapig</strong>!</p>
    <p>Please before running this admin tool edit the
    <i>config.php</i> file!</p>
    <p>Reload this page after modifications.</p>
     <hr />
    <p><strong>Note:</strong> Do not forget to set
     <b>\$STOP=false</b> or comment
    that line in <i>config.php</i></p>

MODIFY;
    template('face_end');
    die;
}


$action=$_GET['action'];

//Now if admin wants to logout
if (strcmp($action,'logout')==0) {
  if ($USE_COOKIES) {
    setcookie('y_user',' ',time()-3600); //Expire cookie
    setcookie('y_password', ' ',time()-3600);
    $HTTP_COOKIE_VARS['y_user']='';
    $HTTP_COOKIE_VARS['y_password']='';
  }
  else {
    $HTTP_SESSION_VARS['y_user']='';
    $HTTP_SESSION_VARS['y_password']='';
  }
}
//echo "username=".$_POST['username'];
//echo   " | userpass: ". $_POST['userpass']."<br />";
//echo "cookie username: " . $HTTP_COOKIE_VARS['y_user']; 

//check login
if(!check_admin_login($_POST['username'],$_POST['userpass'])) {
  include($TEMPLATE_DIR . 'face_begin.php');
  heading(_y("<strong>Yapig</strong> administration"));
  print_main_menubar();
  
  //User didnt logged in -> Show Form.
  login_form();
  include($TEMPLATE_DIR . 'face_end.php');
  die;
}


include($TEMPLATE_DIR . 'face_begin.php');
heading(_y("<strong>Yapig</strong> administration"));
print_main_menubar();

//Show Admin Task bar
if (strcmp($action,'logout')!=0) print_admin_taskbar();

//check Action;

if (strlen($action)==0) $action="add";


// ------------------------------ ACTIONS -----------------------------

switch($action) {
 case 'change':{
     echo _y("Not available.");
     break;
 }
    //----------------------------------
 case 'add': {
     //_include('admin_add_steps.php');
     heading(_y("Add a new gallery"));
     print_gallery_form('add_gallery.php');
     break;
 }
 //-------------------------------------------
}
include($TEMPLATE_DIR . 'face_end.php');

?>
