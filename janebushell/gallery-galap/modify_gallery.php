<?php

/**
 * This script does all tasks related to modification of a gallery.
 * 
 * @package admin
 */

session_start();
require_once('config.php');
require_once('functions.php');
require_once('admin_func.php');


/**
 *  Prints index of galleries with modify gallery menu.
 */

function print_all_modify_gids() {
  global $BASE_DIR,$GID_DIRS;
  
  echo _y('<p>Select a gallery:</p>');
  
  if(!($all_gids=get_all_data($GID_DIRS)))
    error(_y("There are no available galleries."));
  
  foreach ($all_gids as $gid => $dir){
    print_modify_gid($BASE_DIR . $dir, $gid);
  }
}

/**
 * prints a form for selecting the images of the gallery
 *
 */

function print_view_phids($gid_dir,$gid) {
  global $PHID_FILENAMES, $PHID_CAPTIONS;
  
  echo "<form method=\"post\" action=\"modify_gallery.php?action=update_phids&amp;gid=$gid\">\n";
  
  $I_SEND=_y("Update gallery imagelist");
  $I_CREATE_ALL=_y("Overwrite existing thumbnails");
  $I_CREATE_NEW=_y("Only create new thumbnails");
  $I_VIEW_INFO=_y("view/edit");
  $compare=true;
  if (!($all_phids=get_all_data($gid_dir . $PHID_FILENAMES))){
     warning(_y("Could not get imagelist. Gallery probably does not have images"));
     $compare=false;
   }
   else {
     echo _y('Current images in gallery are:');
     echo "<ul>\n";
     
     $I_CHECKED="checked=\"checked\"";
     foreach ($all_phids as $phid => $phid_filename ){
       if (!($D_DESC=get_data($gid_dir . $PHID_CAPTIONS,$phid ))){
	 $D_DESC=_y("<span class=\"red\">Image caption not set</span>");
       }

       echo <<<PHID_LIST2
	 <li><input type="checkbox" name="phid[$phid]" $I_CHECKED 
	 value="$phid_filename" /> 
	 $phid_filename (<i>$D_DESC</i>)
   [<a href="modify_phid.php?action=view_phid&amp;gid=$gid&amp;phid=$phid">$I_VIEW_INFO</a>]</li>

PHID_LIST2;
     }//end foreac
     echo "</ul>";
   }//end else
   //now get all imagenames
   if (!$all_images=get_all_image_filenames($gid_dir))
   warning(_y("Could not read gallery directory filenames"));
 else {
   echo _y("<p>Images that are in directory but not in gallery:</p><ul>");
   if ($compare) $all_phids=array_values($all_phids);
   else $all_phids=array();
   $new_ones=false; //only set to true if there are new images in directory.  
   foreach ($all_images as $phid => $image_filename ){
     if (!in_array($image_filename,$all_phids)){
       $new_ones=true;
       echo <<<PHID_LIST2
	 <li><input type="checkbox" name="phid[]" value="$image_filename" /> 
	 $image_filename</li>	        
PHID_LIST2;
     }//end if
   } //end foreach
   if (!$new_ones) {
     echo _y('<i>New images not found</i>');
   }
 }//end else
 echo <<<END_LIST
   </ul>
   <div>
   <input type="radio" name="create_images" value="all"/> $I_CREATE_ALL<br />
   <input type="radio" name="create_images" value="new" checked="checked" /> $I_CREATE_NEW 
   </div>
    <input type="submit" name="button" value="$I_SEND" class="formbutton" /><hr>
END_LIST;

}
// ################# MAIN PROGRAM ###################################

// Check login 
if(!check_admin_login('','')) {
  header("Location: ./admin.php?action=error");
  die;
}
//_GET['varname'] => varname
$action=$_GET['action'];
$gid=$_GET['gid'];
$phid=$_GET['phid'];


include($TEMPLATE_DIR . 'face_begin.php');

if (isset($action)){ 
  //Check if gid is set:
  if (!($dir=get_data($GID_DIRS,$gid))) {
    error(_y("Gallery does not exist or gid argument not set"));
  }
  $gid_dir=$BASE_DIR . $dir;
  include($gid_dir . $GID_INFO_FILE);  
}

heading(_y("Modify gallery: ") . $gid_info['title']);
print_main_menubar();
print_admin_taskbar();
if (isset($gid)&&($action!="move_up")&&($action!="move_down")) 
     print_modify_gid($gid_dir,$gid);

// Now lets see what do we have to do.
switch ($action) {
 case 'view_info': {
   
   //If we want to view a gallery - Gallery Info Modify.
   heading(_y("Modify gallery information:"),4);
   $action="modify_gallery.php?action=mod_info&amp;gid=$gid";
   print_gallery_form($action,$gid_info);
   break;
   
 }
 case 'mod_info': {
   //Check arguments	
   //_POST[varname] => varname; 
   $dir=$_POST['dir'];
   $title=$_POST['title'];
   $author=$_POST['author'];
   $desc=$_POST['desc'];
   $date=$_POST['date'];
   $gallery_password=$_POST['gallery_password'];
   $no_comments=$_POST['no_comments'];
   if (!isset($dir)) error(_y("Directory location with images required!"));
   
   if (!isset($title)||($title=="")||
       !isset($author)||($author=="") ||
       !isset($desc)|| ($desc==""))
     error(_y("Title, author and description are required!"));
   //Now update gid
   
   if (strlen($date)==0){
     $date=strftime("%A, %d/%B/%Y");
   }
     
     if ($no_comments!='on') $no_comments='off';
     
     msg(_y("Creating gallery information file with:"));
     $info= array('title'=> $title,
		  'author'=>$author,
		  'date'=> $date,
		  'gallery_password'=>$gallery_password,
		  'desc'=> $desc,
		'no_comments' => $no_comments
		);

   echo "<pre>";
   print_r($info);
   echo "</pre>";
   
   if(!create_info_file($gid_dir,$info))  
     error(_y("Creating gallery information file."));
   heading(_y('Gallery info successfully updated.'),3);
   break;
 } //end view gallery.
 
 case 'sel_thumb':{
   //modify GID_INFO_FILE.
     $change=$_GET['change'];
     $phid=$_GET['phid'];
     if (sizeof($phid)==0) $phid=$_POST['phid'];   
     include($gid_dir . $GID_INFO_FILE);
     if ($gid_info['gallery_password']!="") {  
	 msg(_y('Sorry, password protected galeries cannot change the thumbnail'));
	 break;
     }
   if($change=='yes'){
     $info['thumb_phid']=$phid['0'];
     if (!create_info_file($gid_dir, $info)) 
       error(_y("Could not update thumbnail"));
     heading(_y('Thumbnail updated. (Do not worry if the upper thumbnail is the previous one)'),4);
     break;//exit case.
   }

   //List all phids.
   heading(_y("Select thumbnail: "),4);
   if (!($all_phids=get_all_data($gid_dir . $PHID_FILENAMES)))
     error(_y("Could not get all imagelist."));
   
   //Construct Form.
   $I_SEND=_y('Update gallery thumbnail');
   $I_PW_PROT=_y('default password protected galleries thumbnail');
     $I_VIEW_INFO=_y('view info');
   if ($gid_info['thumb_phid']<0) $I_CHECKED="checked=\"checked\"";
   echo <<<FORM_BEGIN
     <form method="post" 
     action="modify_gallery.php?change=yes&amp;action=sel_thumb&amp;gid=$gid">
     <ul>
     <li><input type="radio" name="phid[]" $I_CHECKED value="-1" />
     password.jpg ($I_PW_PROT)</li>
     
FORM_BEGIN;

   foreach ($all_phids as $phid => $phid_filename ){
     if ($phid==$gid_info['thumb_phid']) $I_CHECKED="checked=\"checked\"";
     else $I_CHECKED="";
     echo <<<PHID_LIST
       <li><input type="radio" name="phid[]" $I_CHECKED value="$phid" /> 
     $phid_filename
   [<a href="modify_phid.php?action=view_phid&amp;gid=$gid&amp;phid=$phid">$I_VIEW_INFO</a>]</li>
       
PHID_LIST;
   }
   echo "</ul>&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"button\" class=\"formbutton\" value=\"$I_SEND\" />";


 break;

 }
 case 'view_phids':{ //Modify existent phids
   print_view_phids($gid_dir,$gid);
   break;
 }
 
 case 'update_phids':{
   $phid=$_POST['phid'];

   echo "<pre>" . _y('Selected imagelist:') . "\n"; 
   print_r($phid); echo "</pre>";
   if ($_POST['create_images']=='new'){
     msg(_y("Creating thumbnail for images that do not have one."));
     if(($image_list=get_images_without_thumb($gid_dir,$phid))!=false)
       create_all_thumbs($gid_dir,$gid_dir,$image_list);
   }
   else {
     msg(_y("Creating all thumbs"));
     create_all_thumbs($gid_dir,$gid_dir,$phid);
   }
   //Now create datafile with phid=> filenames. 
   if (!create_datafile($gid_dir. $PHID_FILENAMES,$phid))
     error(_y("Could not update gallery imagelist"));
   msg(_y("Imagelist successfully updated"));
   $updates['num_images']=count($phid);
   if (!create_info_file($gid_dir, $updates))
     warning(_y("Number of images not updated"));
   else 
     msg(_y('Number of images updated'));
   echo "<hr />";
   print_view_phids($gid_dir,$gid);
   break; 
 }
 case 'move_up':
   if (($result=move_up_data($GID_DIRS,$gid))<0) {
     warning(_y("Operation could not be done"));
   }
   print_all_modify_gids();
   break;
 case 'move_down':
   if (($result=move_down_data($GID_DIRS,$gid))<0) {
     warning(_y("Operation could not be done"));
   }
   print_all_modify_gids();
   break;

 default: {
        print_all_modify_gids();
    
 }
}

if (isset($_GET['gid'])) {
$I_GALLERY_INDEX=_y("Display this gallery");
$I_MODIFY=_y("Modify gallery index");

echo "<div><a href=\"view.php?gid=$gid\" title=\"$I_GALLERY_INDEX\">$I_GALLERY_INDEX</a> | <a href=\"modify_gallery.php\" title=\"$I_MODIFY\">$I_MODIFY</a></div>";
}

include ($TEMPLATE_DIR . 'face_end.php');
?>
