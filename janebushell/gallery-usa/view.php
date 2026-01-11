<?php

/**
 * Here we have de code that shows a gallery index or an image with its
 * comments
 * 
 * On query string receives: $gid [ $phid || $page ] 
 * 
 * Actions: 
 *
 * if only is set gid => print gallery index.
 * if is set gid and phid => show image, image info and comments
 * if gid and page are set => show that page
 *
 * if gallery has password then check if user filled gallery password form
 * variable, $form_pw, is the same as the stored in the $GID_INFO_FILE
 * of the gallery. 
 *   
 * @package user
 */

session_start();
require_once('functions.php');
require_once('locale.php');
require_once('config.php');
require_once('view_func.php');

//_GET[varname] => varname
$gid=$_GET['gid'];
$phid=$_GET['phid'];
$page=$_GET['page'];
$hit=$_GET['hit'];
$form_pw=$_POST['form_pw'];


$case=0;
// 0 -> View gallery 
if (!isset($gid)) {
  header("Location: ./gallery.php");
  die;
};
// 1 -> View photo;


if (isset($phid)) { 
    $case=1;
}
if (!is_int((int)$phid)) {
    error(_y("Incorrect Arguments"));
}
//Default page 0;
if ( !isset($page) ) $page='0';
    
	  
//Obtain gallery directory and information about this gallery.
if (!($dir=get_data($GID_DIRS,$gid))) 
    error(_y('Retrieving gallery directory'));
    

$gid_dir= $BASE_DIR . $dir;
include($gid_dir . $GID_INFO_FILE);
//echo "<br>form:$form_pw-{$gid_info['gallery_password']}-ses:". $HTTP_SESSION_VARS['y_gallery_pwd'];

//Check if gallery has an access password and user inserted it.
//form_pw: password introduced by user in a form.
//gallery_password: password stored in $GID_INFO_FILE
if (!check_admin_login()) { //If user is admin, allow.
  if (strlen($gid_info['gallery_password'])>0) {
    //if gallery_has password => check
    if (!check_gallery_password($gid_info['gallery_password'],$form_pw)){ //if password
      include($TEMPLATE_DIR . 'face_begin.php');
      error(_y("Password incorrect."));
    }
  }
}

switch($case) {

 case 0: { //View Gallery
   //Only count a visit of the gallery when hit is set.
     if ($hit=="yes") {     //Stats
	 add_listed_visit($GID_STATS,$gid);
	 header("Location: ./view.php?gid=$gid");
	 die;
     }
     include($TEMPLATE_DIR . 'face_begin.php');
     heading($gid_info['title']);
     print_main_menubar();
     print_gallery_navigation_bar($gid);
     print_page_navigation_bar($gid_dir,$gid,$page);
     //Now show Gallery thumbnails
     if (!gallery_thumbs($gid,$page))
       error(_y("Gallery data not found. Please re-create this gallery"));
     print_page_navigation_bar($gid_dir,$gid,$page);
     print_gallery_navigation_bar($gid);
     break;
 }
 case 1: { //View Image

     $phid_filename= get_data($gid_dir . $PHID_FILENAMES, $phid);
     $phid_visits=get_data($gid_dir . $PHID_STATS, $phid);
     $phid_comments=get_data($gid_dir . $PHID_COMMENTS, $phid);
     $img_path=$gid_dir . $phid_filename;
     if (!is_file($img_path)) {
	 include($TEMPLATE_DIR . 'face_begin.php');
	 error(_y('Image not found'));
     }
     $img_url=$gid_dir . rawurlencode($phid_filename);

     $I_TITLE= $gid_info['title'] . " - $phid_filename";
     include($TEMPLATE_DIR . 'face_begin.php');
     heading($gid_info['title'] . "- <small>$phid_filename</small>");
     print_main_menubar();
     print_navigation_bar($gid,$phid,$gid_dir);
     
     //Setting MAX_IMG_SIZE (view config.php for doc)
     if ($MAX_IMG_SIZE > 0) {
	 $sz_orig = getimagesize($img_path); 
	   $ratio=$sz_orig[1]/$sz_orig[0];
	 if ($sz_orig[0]> $MAX_IMG_SIZE || $sz_orig[0]> $MAX_IMG_SIZE) {	    
	     if ($ratio>1) {
		 $height=$MAX_IMG_SIZE;
		 $width=(int)($MAX_IMG_SIZE/$ratio);
	     }
	     else {
		 $width=$MAX_IMG_SIZE;
		 $height=(int) ($MAX_IMG_SIZE*$ratio);
	     }
	     //Now construct img_size html code
	     $img_size= "style=\"width:$width;height=$height\" ";	     
	 }
     }
     echo "<div class=\"viewimage\"><a name=\"img\"></a><img src=\"".$img_url. "\" ". $img_size . "alt=\"$phid_filename\" id=\"myimage\" class=\"image\" onload=\"preload('$img_url')\" /></div>";
     print_zoom_bar($img_path);
     print_navigation_bar($gid,$phid,$gid_dir);
     print_exif_info($img_path,$phid);
     if ($gid_info['no_comments']!="on") {
	 print_all_comments($gid_dir . $gid ."_" . $phid, $gid, $phid);
	 print_navigation_bar($gid,$phid,$gid_dir);
     }
     //print_navigation_bar($gid,$phid, $gid_dir);
     //Stats
     add_listed_visit( $gid_dir . $PHID_STATS, $phid);
 }
}

include($TEMPLATE_DIR . 'face_end.php');
?>
