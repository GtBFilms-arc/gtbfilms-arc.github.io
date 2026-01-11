<?php


 /**
 * This script does all tasks related to modification of a gallery.
 * 
 * 
 * Receives on query string:
 *
 * + phid: Photo identificator.If not set then redirect to 
 *         modify_gallery.php?gid=$gid
 *
 * + gid: Gallery identificator.If not set then redirects to modify_gallery.php
 *
 * + action: following values: 
 *   - view_phid : View Image Info.
 *   - del_phid : Delete Image 
 *   - del_comments: Delete all comments of the image
 *   - del_visits: Delete visit stats of the image
 *   - mod_caption: Modify caption
 *   - del_comment: Delete comment 
 *        - comment_id
 *   - mod_comment: Modify comment
 *        -comment_id
 *   - rotate: Rotates the image +90 or -90 degrees
 *     - angle
 *
 *
 * @package admin
 */


session_start();
require_once('config.php');
require_once('functions.php');
require_once('admin_func.php');
require_once('view_func.php');

/**
 * Prints phid comments with two links for admin tasks: modify and delete 
 * this comment.
 * 
 * @param string gid_dir gallery directory $BASE_DIR + image_dir
 * @param int gid gallery identificator
 * @param int phid image identificator
 * @returns boolean  true if ok, false if no comments show.
 *
 */

function admin_comments($gid_dir, $gid, $phid) {
  
  $comments_file=$gid_dir . $gid . "_" . $phid;
  if (!file_exists($comments_file)) {
    heading(_y("Image has no comments"),2);
    return(false);
  }
  if (!($all=file($comments_file))) {
    heading(_y("Image has no comments"),2);
    return(false);
  }
  $I_MOD=_y("Modify");
  $I_DEL=_y("Delete");
  heading(_y("Image Comments"),2);

  foreach ($all as $comment_id => $line) {
    $D_DEL_URL="modify_phid.php?action=del_comment&amp;gid=$gid&amp;phid=$phid&amp;comment_id=$comment_id";
    $D_MOD_URL="modify_phid.php?action=mod_comment&amp;gid=$gid&amp;phid=$phid&amp;comment_id=$comment_id";

    $admin_html=<<<ADMIN_HTML
      &nbsp;&nbsp;<b><span><a href="$D_DEL_URL">$I_DEL</a> | 
      <a href="$D_MOD_URL">$I_MOD</a></span></b>      
ADMIN_HTML;
    print_comment($line,$admin_html);
  } //End foreach;
  return(true);
}

/**
 * prints all the stuff that allows modifying an image
 * 
 * @param string gid_dir gallery directory (BASE_DIR + image_dir)
 * @param int gid gallery identifier;
 * @param int phid image identifier.
 *
 */

function print_modify_phid ($gid_dir, $gid, $phid){
  global $PHID_FILENAMES,$PHID_STATS,$PHID_COMMENTS,$TEMPLATE_DIR,
    $THUMB_PREFIX, $PHID_CAPTIONS;
  
  //Get info
  if(!($D_PHID_FILENAME= get_data($gid_dir . $PHID_FILENAMES, $phid)))
    error(_y("Image identificator (phid) not found."));
  
  if (!($D_CAPTION=get_data($gid_dir . $PHID_CAPTIONS, $phid))){
    $D_CAPTION="";
  }
  
      
 //Use Standard Yapig template naming.
  $D_PHID_VISITS=get_data($gid_dir . $PHID_STATS, $phid);
  $D_PHID_COMMENTS=get_data($gid_dir . $PHID_COMMENTS, $phid);
  $D_IMG_PATH=$gid_dir . $D_PHID_FILENAME;  
  $D_THUMB_PATH=get_thumb_path($gid_dir, $phid);
  $D_PHID=$phid;
  $D_GID=$gid;
  
  $D_URL_REMOVE="modify_phid.php?action=del_phid&amp;gid=$gid&amp;phid=$phid";
  $D_URL_CLEAR_COMMENTS="modify_phid.php?action=del_comments&amp;gid=$gid&amp;phid=$phid";
  $D_URL_CLEAR_VISITS="modify_phid.php?action=del_visits&amp;gid=$gid&amp;phid=$phid";
  $D_URL_SET_THUMB="modify_gallery.php?action=sel_thumb&amp;change=yes&amp;gid=$gid&amp;phid[0]=$phid";
  $D_MOD_CAPTION_URL="modify_phid.php?action=mod_caption&amp;gid=$gid&amp;phid=$phid";
  $D_ROTATE_90_URL="modify_phid.php?action=rotate&amp;gid=$gid&amp;phid=$phid&amp;angle=90";
  $D_ROTATE_N90_URL="modify_phid.php?action=rotate&amp;gid=$gid&amp;phid=$phid&amp;angle=-90";

  //I18n
  $I_IMG_NAME=_y('Image name');
  $I_NUM_COMMENTS=_y('Comments');
  $I_NUM_VISITS=_y('Visits');
  $I_REMOVE=_y('Remove from gallery');
  $I_CLEAR=_y('Clear');
  $I_CLEAR=_y('Delete All');
  $I_PHID=_y('Image id');
  $I_MOD_IMG=_y('Modify Image');
  $I_SET_THUMB=_y('Set as gallery thumbnail');
  $I_CAPTION=_y("Image caption");
  $I_CHANGE_CAPTION=_y("Change caption");
  $I_ROTATE=_y("Rotate image");
  $base_url='modify_phid.php?action=view_phid&amp;';
  print_navigation_bar ($gid,$phid,$gid_dir,$base_url);
  include($TEMPLATE_DIR . 'modify_phid_info.php');
  admin_comments($gid_dir,$gid,$phid);
  print_navigation_bar ($gid,$phid,$gid_dir,$base_url);
}

/**
 * deletes
 *
 */
function delete_phid_filenames($gid_dir, $phid){
  global $PHID_FILENAMES;

  if (!delete_data($gid_dir . $PHID_FILENAMES,$phid))
    error(_y('Could not remove image from gallery imagelist'));
  else msg(_y('Image will not be displayed in list.'));

  
    
}
/**
 * does all actions to delete phid comments, that is:
 *
 *  - deletes comments file 
 *  - decrease image and gallery comments counter.
 *
 * @param string $gid_dir gallery directory.
 * @param int $gid gallery identifier
 * @param int $phid image identifier
 *
 */

function delete_phid_comments($gid_dir, $gid,$phid) {
  global $PHID_COMMENTS;
  $warn=false;
  
  $comments_file=$gid_dir . $gid . '_' . $phid; 
  if (!@unlink($comments_file))
    warning(_y('Could not delete image comments file: ') . $comments_file);
  else msg(_y('Image comments deleted'));

  if ((!$num_comments=get_data($gid_dir . $PHID_COMMENTS, $phid))) 
    $warn=true; //If could not get data.
  else {
    if (!decrease_counter($gid_dir . $PHID_COMMENTS, $phid,$num_comments)) 
      $warn=true; //Could not decrease_counters
  }
  if ($warn) warning(_y('Could not delete image comments counter: '));
  else msg(_y('Image comments counter deleted'));

}

function delete_phid_visits($gid_dir,$phid){
  global $PHID_STATS;

  if (!delete_data($gid_dir . $PHID_STATS,$phid))
    warning(_y('Image visits counter not found or could not be removed.'));
  else msg(_y('Image visits counter deleted'));
  
}

////////////////////////////////////////////////////////////////////////////
// MAIN PROGRAM
////////////////////////////////////////////////////////////////////////////

// Check login 
if(!check_admin_login('','')) {
  header("Location: ./admin.php?action=error");
  die;
}

//Avoid cache.
header('Cache-Control: no-cache');


//Register Globals
$phid=$_GET['phid'];
$gid=$_GET['gid'];
$action=$_GET['action'];


if (!isset($gid)){
  header("Location: ./modify_gallery.php");
  die;
}
if (!isset($phid)) {
  header("Location: ./modify_gallery.php?action=view_phids&gid=$gid");
  die;
} 



if (isset($action)){ 
  
  if (!($dir=get_data($GID_DIRS,$gid))) 
    header("Location: ./modify_gallery.php");
  
  $gid_dir=$BASE_DIR . $dir;
  include($gid_dir . $GID_INFO_FILE);  
}

//if (action!='rotate') {
include($TEMPLATE_DIR . 'face_begin.php');
heading(_y("Modify gallery: ") . $gid_info['title']);
print_main_menubar();
print_admin_taskbar();
//}

if (isset($gid)) {
  print_modify_gid($gid_dir,$gid, true);
}

switch($action) {
    
  case 'view_phid': {
    print_modify_phid($gid_dir, $gid, $phid); 
    break;
  }
  case 'del_phid': {
    //Delete phid from gallery listing,comments and visits
    $base_url='modify_phid.php?action=view_phid&amp;';
    print_navigation_bar ($gid,$phid,$gid_dir,$base_url);    
    delete_phid_filenames($gid_dir, $phid);
    delete_phid_comments($gid_dir,$gid,$phid);
    delete_phid_visits($gid_dir,$phid);

    break;
  }
 case 'del_comments': {
   delete_phid_comments($gid_dir, $gid,$phid);
   print_modify_phid($gid_dir,$gid,$phid);
   break;
 }

 case 'del_visits': {
   delete_phid_visits($gid_dir,$phid);
   print_modify_phid($gid_dir,$gid,$phid);
   break;
 }

 case 'mod_caption': {
     $caption=$_POST['caption'];
     $caption=str_replace("\\'","'",$caption);
     $caption=str_replace('\"','&quot;',$caption);
     
     delete_data($gid_dir . $PHID_CAPTIONS, $phid);
   if (add_data($gid_dir . $PHID_CAPTIONS, $phid, $caption))
     msg(_y("Set new caption to: " . $caption));
   else 
     warning(_y("Could not update caption."));
   
   print_modify_phid($gid_dir,$gid,$phid);
   break;
 }

 case 'del_comment': { //Delete a comment (comment_id= line on comments file)
   $comment_id=$_GET['comment_id'];
   if (!isset($comment_id)&&(strlen($comment_id)==0)) 
     warning(_y("Comment not selected"));  

   $comments_file=$gid_dir . $gid ."_".$phid;
   if (!($all=file($comments_file)))
     warning(_y("Image has no comments"));
   if (!($fd=fopen($comments_file,"w+"))){
     warning(_y("Coul not open comments file"));
   }else {
     $i=0;
     while($i<sizeof($all)){
       if ($i!=$comment_id) fputs($fd,$all[$i]);
       $i++;
     }
     fclose($fd);
     msg(_y("Comment successfully deleted"));
     //Now decrease gallery comments;
     decrease_counter($gid_dir . $PHID_COMMENTS, $phid);
     print_modify_phid($gid_dir, $gid,$phid);
   }
   break;
 }
 case 'mod_comment': {
   $comment_id=$_GET['comment_id'];

   $comments_file=$gid_dir . $gid ."_".$phid;

   if (!($all=file($comments_file)))
     error(_y("Could not read comments file"));
   //echo "<pre>all:\n"; print_r($all);echo "</pre>"; //debug line
   if (isset($_GET['step'])) { //Then => new values are already set
     if (!($fd=fopen($comments_file,"w+")))
       error(_y("Opening comments file."));
     //Create a line with the file format from posted data
     $line=construct_comment_line($_POST);
     //Replace the old data;
     $all[$comment_id]=$line;
     //save the comments file
     foreach($all as $line)
       fputs($fd,$line . "\r\n");
     fclose($fd);
     msg(_y("Comment successfully updated"));
   }
   else { //Allow edit comment.
     
   list($field['tit'],$field['aut'],$field['date'],$field['mail'],
	$field['web'],$field['msg'])=array_values(explode($SEPARATOR,$all[$comment_id]));
   //echo "<pre>all:\n"; print_r($field);echo "</pre>";
   $url="modify_phid.php?action=mod_comment&amp;gid=$gid&amp;phid=$phid&amp;".
     "comment_id=$comment_id&amp;step=2";
   print_form($url,$field);
  
   }
   print_modify_phid($gid_dir, $gid, $phid);
   break;
 }

 case 'rotate': //rotate the image and and thumbnail
   
   $angle=$_GET['angle'];

   //Set default angle.
   if (($angle!=90) && ($angle!= -90)) $angle=90;

   if (($phid_name=get_data($gid_dir . $PHID_FILENAMES, $phid))===false){
     error(_y("Image not found."));
   }
   
   $phid_path= $gid_dir . $phid_name;
   $thumb_path = $gid_dir . $THUMB_PREFIX . $phid_name;

   //echo "phid_path= $phid_path <br />thumb_path= $thumb_path";//Debug Line

   if (!rotate_image($phid_path,$angle)) {
     warning(_y("Could not rotate the image or the thumbnail"));
   }
 
   if (!rotate_image($thumb_path,$angle)) {
     warning(_y("Could not rotate the image or the thumbnail"));
   }
   
    //header("Location: modify_phid?action=view_phid&gid=$gid&phid=$phid");
    msg(_y("Image has successfully been rotated, although your browser might be displaying the old one because it is on cache."));
   
     
    print_modify_phid($gid_dir, $gid,$phid);
   

}//end switch

?>
