<?php
/**
 * These functions are common to the different files: admin an user packages.
 * 
 * @package common
 */

/***/
require_once('config.php');
require_once('locale.php');
require_once('global.php');
require_once('datafile_func.php');
require_once('stats_func.php');

/**
 *Debug function.
 */
function debug($arr,$txt = '') {
    
    echo "<pre> -------- $txt\n";
    print_r($arr); 
    echo "\n------ </pre>";
    
}

/**
 * Autentifies user login
 * 
 * Checks if $user and $password are the same as the ones stored
 * in config.php. If true, sets a session variable or cookie variable
 * depending on the value of the global variable USE_COOKIE  
 * If no arguments are passed then it checks if user has that session var.
 *
 * @param string $user admin 
 * @param string $password 
 * @global string config.php admin user
 * @global string config.php admin password
 * @global array cookies
 * @global array sessions
 * @global bool true if cookies preferred. False if sessions preferred.
 * @return bool true if authentification OK. False if not OK.
 *
 */

function check_admin_login($user='',$password='') {
     global $USER, $PASSWORD, $HTTP_COOKIE_VARS,$HTTP_SESSION_VARS,$USE_COOKIES;

     //Convert USER and PASSWORD to arrays (support several admin accounts);
     if (!is_array($USER)) $USER=array($USER);
     if (!is_array($PASSWORD)) $PASSWORD=array($PASSWORD);

     //Check array lengths, USER  must be equal or smaller than PASSWORD.
     if (sizeof($USER) > sizeof($PASSWORD)) 
	 return(false);

    $end=sizeof($USER);

     for ($i=0;$i<$end;$i++) {
       if ($USE_COOKIES){ // Use cookies
	   $login_hash = md5($USER[$i] . $PASSWORD[$i]);
	   if (strcmp($login_hash, $_COOKIE['y_login']) == 0 ) {
	       setcookie('y_login', $login_hash, time()+3600);
	       return(true);
	   }
	   if ((strcmp($USER[$i],$user)==0)&&
	       (strcmp($PASSWORD[$i],$password)==0)) {
	       setcookie('y_login',$login_hash,time()+3600);
	       $_COOKIE['y_login'] = $login_hash;
	       return(true);
	   }	 
       } //end if cookies
	 else { //Use session variables
	     // echo "session i=$i U:" . $USER[$i]." P:". $PASSWORD[$i];
	     if ((strcmp($USER[$i],$HTTP_SESSION_VARS['y_user'])==0)&&
		 (strcmp($PASSWORD[$i],$HTTP_SESSION_VARS['y_password'])==0)){
		 $HTTP_SESSION_VARS['y_user']=$USER[$i]; //Refresh values 
		 $HTTP_SESSION_VARS['y_password']=$PASSWORD[$i];
		 return(true);
	     }
	     
	     if ((strcmp($USER[$i],$user)==0)&&
		 (strcmp($PASSWORD[$i],$password)==0)) {
		 $HTTP_SESSION_VARS['y_user']=$USER[$i];
		 $HTTP_SESSION_VARS['y_password']=$PASSWORD[$i];
		 return(true);
	     }	 
	 }  //end else
     } //end for 
    return(false);
}

/**
 * 
 * Includes the template file located in $TEMPLATE_DIR
 * Do not include the .php extension.
 * 
 * @global string $TEMPLATE_DIR template directory location
 * @param string $file filename without .php extension.
 * @return bool false if file does not exist
 */

function template($file) {
    global $TEMPLATE_DIR, $VERSION,$I_TITLE;
    
    $full_path= $TEMPLATE_DIR . $file . '.php';
    if (!file_exists($full_path)) return(false);
    include($full_path);
    return(true);
}

/**
 * decode HTML->entities.Opposite to htmlentities function
 */

function unhtmlentities ($string) {
    $trans_tbl = get_html_translation_table (HTML_ENTITIES);
    $trans_tbl = array_flip ($trans_tbl);
    return strtr ($string, $trans_tbl);
}



/*
 * Prints an error and dies.
 * 
 * @param string $message Error Message
 * @global string template directory.
 * @global string version of yapig
 * @return none
 */

function error ($message) {
    global $TEMPLATE_DIR, $VERSION;
    
  $I_ERROR=_y("Error");

    echo "<h3>$I_ERROR: $message</h3>";
    include($TEMPLATE_DIR . 'face_end.php');
    die;

}


/*
 * Outputs warning in a parragraf, but does not die.
 *
 * @param string $text output text
 *
 * @return none
 */

function warning($text) {
    $I_WARNING=_y('Warning');
    echo "<p class=\"msg\"><strong>$I_WARNING</strong>: $text</p>";
}

/*
 * outputs a message in a parragraf.
 *
 * @param string $text Output text
 * @return none
 */

function msg($text) {
    echo "<p class=\"msg\"><strong>Yapig:</strong> $text</p>";
}

/*
 * Outputs a heading <HX> in a parragraf. 
 *
 * @param string $text text of the heading
 * @param integer $size (Optional) biggest=1 smallest=6
 * @return none
 */

function heading($text, $size=1) {
    echo "<h{$size}>$text</h{$size}>";
}


/**
 * Prints main menu navigation bar
 * 
 * If gallery password is set, then it allows logout.
 * If admin password is set, then it allos admin logout.
 * 
 * @global config.php template directory
 * @global config.php home page
 * @global session global var 
 * @global cookie vars
 */

function print_main_menubar() {
    global $TEMPLATE_DIR, $HOME, $HTTP_SESSION_VARS,$USE_COOKIES,
      $HTTP_COOKIE_VARS;
    
    $I_MAIN_MENU=_y('Main menu');
    $I_GALLERY_INDEX=_y('Gallery index');
    $I_HOME_PAGE=_y('Homepage');
    $D_HOME_PAGE=$HOME;
    $I_ADMIN=_y('Admin');

    //Logout links
    $I_ADMIN_LOGOUT=_y('Admin logout');
    $I_GALLERY_LOGOUT=_y('Gallery logout');
   $admin_logout_str="<a href=\"admin.php?action=logout\">$I_ADMIN_LOGOUT</a> |";
 $gallery_logout_str="<a href=\"gallery.php?action=logout\">$I_GALLERY_LOGOUT</a>";

   if ($USE_COOKIES){

     if (strlen($HTTP_COOKIE_VARS['y_user'])>0)
     $D_ADMIN_LOGOUT=$admin_logout_str;
     
     if (strlen($HTTP_COOKIE_VARS['y_gallery_pwd'])>0){
       $D_GALLERY_LOGOUT=$gallery_logout_str;
     }
   }
   else{ //Using Session variables 
     if (strlen($HTTP_SESSION_VARS['y_user'])>0)
       $D_ADMIN_LOGOUT=$admin_logout_str;
     
     if (strlen($HTTP_SESSION_VARS['y_gallery_pwd'])>0){
       $D_GALLERY_LOGOUT=$gallery_logout_str;
     }
     //echo "y_gallery_pwd:" . $HTTP_SESSION_VARS['y_gallery_pwd'];
                            
   }
 //Print main menu bar.
 include($TEMPLATE_DIR . 'main_menu_bar.php');
 
}

/**
 * Checks if user has introduced a correct password in the gallery 
 * password form or if user was authentified previously. Sets a cookie
 * or a session cookie.
 * 
 * @global array session
 * @global array cookies
 * @global bool true if want to use cookies. False if want to use sessions
 * @return bool true if ok false if password is incorrect
 * 
 */
function check_gallery_password($gallery_password,$form_pw=''){
    global $HTTP_SESSION_VARS,$HTTP_COOKIE_VARS,$USE_COOKIES;
    
    if (strlen($gallery_password)==0) return(true);
    
    //Check if user already introduced the password
    if ($USE_COOKIES) {
	if (strcmp($gallery_password,$HTTP_COOKIE_VARS['y_gallery_pwd'])==0){
	    //setcookie('y_gallery_pwd',$gallery_password,time()+300);
	    return(true);
	}
	if (strcmp($gallery_password,$form_pw)==0) {
	  setcookie('y_gallery_pwd',$gallery_password,time()+450);
	  $HTTP_COOKIE_VARS['y_gallery_pwd']=$gallery_password;
	  return(true);
	}    
	return(false);	
    }
    else {
	if (strcmp($gallery_password,$HTTP_SESSION_VARS['y_gallery_pwd'])==0)
	    return(true);
	if (strcmp($gallery_password,$form_pw)==0) {
	    $HTTP_SESSION_VARS['y_gallery_pwd']=$gallery_password;
	    return(true);
	}    
	return(false);
    }
}

/**
 * returns the path to the thumbnail.
 * 
 * if phid is negative then returns path to password protected
 * gallery. 
 * 
 * @param string $gid_dir path to gid (ie: photos/mygallery/)
 * @param string $phid image identificator
 * @param bool $rawurlencode if true then apply rawurlencode function.
 * @return string with the filename. if not found returns not_available.jpg.
 * @global phid->filename correspondence
 * @global thumbnail prefix
 * @global localtion of template
 */
    
function get_thumb_path($gid_dir,$phid,$rawurlencode='false'){
  global $PHID_FILENAMES, $THUMB_PREFIX, $TEMPLATE_DIR;
  
  if ($phid<0) return($TEMPLATE_DIR . 'password.jpg');
  if(!($filename=get_data($gid_dir . $PHID_FILENAMES, $phid))){
    return($TEMPLATE_DIR . 'not_available.jpg');
  }
    $filename=$THUMB_PREFIX . $filename;
    
    if (!file_exists($gid_dir . $filename))
    return($TEMPLATE_DIR . 'not_available.jpg');
    if ($rawurlencode) $filename=$gid_dir . rawurlencode($filename); 
  return($filename);
}


/**
 * Filters some HTML tags on a string. 
 * 
 * 
 * @param string str String that will be filtered
 * @return string String filtered
 * 
 * Function coded by Marco Pohl (echopath)
 */

function safe_html($str){
    
       //nuke script and header tags and anything inbetween
        $str = preg_replace("'<script[^>]*?>.*?</script>'si", "", $str);
        $str = preg_replace("'<head[^>]*?>.*?</head>'si", "",  $str); 
        //listed of tags that will not be striped but whose  attributes will be
        //$allowed = "br|b|i|p|u|a|block|pre|center|hr";	  
        $allowed = "br|b|i|p|li|ul";   
        $str = preg_replace("/<((?!\/?($allowed)\b)[^>]*>)/xis", "", $str);    
        $str = preg_replace("/<($allowed).*?>/i", "<\\1>", $str);
    
        return $str;
    
}


/**
 * Contructs a commen line with the correct format of comments file. That is:
 *
 *     tit<|>aut<|>date<|>mail<|>web<|>msg
 *
 * Requires an array with this fields:
 *    + tit comments title
 *    + aut author
 *    + date
 *    + mail e-mail
 *    + web  web page
 *    + msg  comment text
 */

function construct_comment_line($data_array) {
  global $SEPARATOR;
  
  //Line Contruction
  $tit=str_replace("\\'","'",$data_array['tit']);
  $tit=htmlspecialchars($tit);
  $linea= $tit . $SEPARATOR;
  
  $aut=str_replace("\\'","'",$data_array['aut']);
  $aut=htmlspecialchars($aut);
  $linea=$linea . $aut . $SEPARATOR;
  
  $linea= $linea . htmlspecialchars($data_array['date']) . $SEPARATOR;
  $linea=$linea . htmlspecialchars($data_array['mail']) . $SEPARATOR;
  $linea=$linea . $data_array['web'] . $SEPARATOR;
  
  $msg=wordwrap($data_array['msg'],100,'\n',true);
  
   $msg=str_replace("\\'","'",$msg);
  $msg=addcslashes ($msg,"\0..\37");
  $msg=str_replace("\\n","<br />",$msg);
  $msg=str_replace("\\r","",$msg);
  $msg=str_replace("<?","",$msg);
  $msg=safe_html($msg); //filter some HTML tags.
  
  $linea= $linea . $msg;
  
  return($linea);
  
}


/**
 * gets an array list with all subdirs of basedir.
 * 
 * @param string basedir directory which is going to be opened
 * @return mixed false if problems, array with dirs if ok
 */

function get_all_dirs($basedir) {

    //echo "basedir:  $basedir";
    
    $dirlist=array();
    if (!is_dir($basedir)) return(false);
    if (!($dh = opendir($basedir))) return(false);
    while (($file = readdir($dh)) !== false) {
	if (is_dir($basedir . $file)) {
	    if ($file!='.' && $file!='..'  
		&& (strpos($file, "yapig_data")===false)) {
		$dirlist[]=$file;
	    }
	}
    }
    closedir($dh);
    
    //echo "<pre>get_all_dirs\n";print_r($dirlist);echo"</pre>"; //Debug line
    return($dirlist);
}




?>
