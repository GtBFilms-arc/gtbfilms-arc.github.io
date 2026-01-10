<?php
/**
 * @package admin
 * 
 * This module does all necessary actions to add a new gallery
 * Receives information from a module. These are the received via form 
 * variable names:
 * 
 * $title : Gallery Title
 * $author: Who created the gallery
 * $dir   : Directory where image files are stored
 * $desc  : Description 
 * $gallery_password: only exits this variable if gallery is private.
 * 
 * User must have been logged in as admin (admin.php) before calling
 * this script.
 *
 */


/** */
require_once('config.php');
require_once('functions.php');
require_once('admin_func.php');	    


// ################# MAIN PROGRAM ###################################


session_start();
//found in php.net session_start() function comments. This will allow
//to keep submitted info after pressing back
//http://es2.php.net/manual/es/function.session-start.php
header("Cache-control: private");

// Check admin login.  
if(!check_admin_login('','')) {
    header("Location: ./admin.php?action=error");
    die;
}

include($TEMPLATE_DIR . 'face_begin.php');

//Convert: _POST[varname] => varname
$dir=$_POST['dir'];
$title=$_POST['title'];
$author=$_POST['author'];
$date=$_POST['date'];
$desc=$_POST['desc'];
$gallery_password=$_POST['gallery_password'];
$no_comments=$_POST['no_comments'];

heading(_y("Adding new gallery: ") . $title);
print_main_menubar();
print_admin_taskbar();

//First of all, check if GD library is already loaded 
//Check if can create the gallery.
if(!load_gd()) {
  error(_y("GD library not loaded. Please, read installation documentation"));
}



//Check arguments	    
if (!isset($dir)) error(_y("Image storage directory required!"));

if (!isset($title)||($title=="")||
    !isset($author)||($author=="") ||
    !isset($desc)|| ($desc==""))
  error(_y("Title, author and description are required!"));

//Append '/' if required 
if (!preg_match("/\/\$/",$dir)){
      $dir.='/';
}

// Some checks about selected dir.
$gid_dir= $BASE_DIR . $dir;

if (!is_dir($gid_dir)) 
  error (_y("Location directory does not exist:"). "($gid_dir)" );
if (gid_dir_being_used($dir)) 
    error(_y("Selected gallery directory is being used by another gallery. Delete that gallery first."));

//first of all secure the dir.
// This is for avoiding automatic server listing.
msg(_y('Creating index.html for avoiding server listing.'));

if (!new_index_file($gid_dir, "../../gallery.php")) {
  warning(_y("Permissions. Read FAQ documentation."));
}

//Load all imagenames
msg(_y("Loading image names of: ") . $gid_dir);


if (!($imagenames = get_all_image_filenames($gid_dir))) {
    error(_y("Failed to read image names."));
}

//Make phid-filenames;
msg(_y("Creating correspondence photo filenames <-> photo identificator (phid)"));
if(!create_datafile($gid_dir . $PHID_FILENAMES,$imagenames)){
     error(_y("Creating phid correspondence."));   
}
if (strlen($date)==0){
     $date=strftime("%A, %d/%B/%Y");
}
//Create Info File	     
msg(_y("Creating gallery information file with:"));
$info= array('root_gid'=>$root_gid,
	     'title'=> $title,
	     'author'=>$author,
	     'gallery_password'=>$gallery_password,
	     'num_images'=> count($imagenames),
	     'num_subgids'=> "0",
	     'thumb_phid'=> "0",
	     'no_comments'=> $no_comments,
	     'date'=> $date,
	     'desc'=> $desc
	     );
echo "<pre>";
print_r($info);
echo "</pre>";

if(!create_info_file($gid_dir,$info,false))  
  error(_y("Error creating gallery information file."));


//Make thumbnails
msg(_y("Creating thumbnails"));
if (!create_all_thumbs($gid_dir,$gid_dir,$imagenames)) 
  error(_y("Could not create all thumbnails."));

//Now add gallery to $GID_DIRS file.
msg(_y("Adding this gallery to gallery index"));
if (!add_to_gid_dirs($dir)) 
  error(_y("Adding gallery to file: ") . $GID_DIRS);
msg(_y("Gallery <b>successfully</b> created"));

//Links to most possible destinations
$I_GALLERY_INDEX=_y("Gallery index");
$I_BACK_TO_ADMIN_PAGE=_y("Back to admin page");
echo "<div><a href=\"gallery.php\" title=\"$I_GALLERY_INDEX\">$I_GALLERY_INDEX</a> | 
      <a href=\"admin.php\" title=\"$I_BACK_TO_ADMIN_PAGE\">$I_BACK_TO_ADMIN_PAGE</a></div>";

include ($TEMPLATE_DIR . 'face_end.php');
?>
