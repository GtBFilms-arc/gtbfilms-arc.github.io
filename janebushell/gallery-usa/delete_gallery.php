<?php

/**
 * This script does all tasks related to deletion of a gallery.
 * 
 * @package admin
 */

session_start();
require_once('config.php');
require_once('functions.php');
require_once('admin_func.php');

// ################# MAIN PROGRAM ###################################

// Check login 
if(!check_admin_login('','')) {
  header("Location: ./admin.php?action=error");
  die;
}

//Print headings.
include($TEMPLATE_DIR . 'face_begin.php');
heading(_y("Delete gallery"));
print_main_menubar();
print_admin_taskbar();

$gid=$_GET['gid'];
$action=$_GET['action'];

//Check if gid is set:
if (!($dir=get_data($GID_DIRS,$gid))) {
  error(_y("Gallery does not exist or gid argument not set"));
}
$gid_dir= $BASE_DIR . $dir;

if (isset($gid)) print_modify_gid($gid_dir,$gid);

//Print delete form (so user can choose what to delete.)
if ($action!='delete') {
  print_delete_gallery_form($gid);
  include($TEMPLATE_DIR . 'face_end.php');
  die;
}

//Delete gid and phid Stats;
if ($_POST['d_gid_stats']){
  if (!delete_data($GID_STATS,$gid)) 
    warning(_y("Gallery stats do not exist or could not be deleted"));
  else 
    msg(_y("Gallery stats deleted."));
}

if ($_POST['d_phid_stats']){
  if (!@unlink($gid_dir . $PHID_STATS))
    warning(_y("Image stats of this gallery do not exist or could not be deleted."));
  else 
    msg(_y("Image stats of this gallery deleted.")); 
}
 
if($_POST['d_comments']){
  //Delete comments counters
  if (!@unlink($gid_dir . $PHID_COMMENTS)) 
    warning(_y("Comments counter of this gallery does not exist or could not be deleted"));
  else 
    msg(_y("Comments counter of this gallery deleted"));
  //Delete comments
  if (!($phids=get_all_data($gid_dir . $PHID_FILENAMES)))
    warning(_y("Could not load this file: ") . $PHID_FILENAMES);
  else {
    foreach ($phids as $key => $value){ 
      //Delete each comment file $gid_$phid
      $comments_file= $gid_dir . $gid . "_". $key;	       
      if (@file_exists($comments_file)) {
	if (!@unlink($comments_file)) 
	  warning(_y('Could not delete: ' . $comments_file ));	          
	else msg(_y('Deleted: ') . $comments_file);
      }
    }//end foreach
  }
}

if ($_POST['d_captions']) {
    $captions_file = $gid_dir . $PHID_CAPTIONS;
    if (!@unlink($captions_file)) 
      warning(_y('Could not delete: ' . $captions_file ));	          
    else msg(_y('Deleted: ') . $captions_file);
}

if (!$_POST['d_all'] && $_POST['d_thumbs']) {

  if (!($all_images=get_all_data($gid_dir . $PHID_FILENAMES)))
    error(_y('Could not get image list from file: ') . $PHID_FILENAMES);

  foreach ($all_images as $imagename) {
    $thumb_path=$gid_dir . $THUMB_PREFIX . $imagename;
    if (!@unlink($thumb_path))
      warning(_y('Could not delete thumbnail image: ') . $thumb_path);
    else msg(_y('Deleted thumbnail: ') . $thumb_path);   
  }//end foreach
}


//Deletes gid and $PHID_FILENAMES
if ($_POST['d_gid']){
  if (!delete_data($GID_DIRS,$gid)) 
    error(_y("Gallery could not be removed from index"));
  else 
    msg(_y("Gallery will not be displayed anymore in the gallery index"));
  if (!@unlink($gid_dir . $PHID_FILENAMES)){
    warning(_y('Could not delete: ') . $PHID_FILENAMES);	 
  }
}

if($_POST['d_all']) {
  
  if (!is_dir($gid_dir)) 
    error($gid_dir . _y(' is not a valid directory'));

  if (!($dh = opendir($gid_dir))) 
    error($gid_dir . _y(' could not be opened'));

  while (($file = readdir($dh)) !== false) {
    if ($file!='.' && $file!='..') {
      if (!@unlink($gid_dir . $file))
	warning(_y('Could not delete: ') . $file);	 
      else  
	msg(_y('Deleted file: ') . $file);   
    }
  }//end while
}//end if d_all
    
  heading(_y("Gallery successfully deleted."),4);

//Show some useful links
$I_ADMIN_INDEX=_y('Admin index');
$I_DELETE_INDEX=_y('Delete additional galleries'); 
echo "<div><a href=\"admin.php\">$I_ADMIN_INDEX</a> | 
   <a href=\"modify_gallery.php\">$I_DELETE_INDEX</a></div>";

include($TEMPLATE_DIR . 'face_end.php');
?>
