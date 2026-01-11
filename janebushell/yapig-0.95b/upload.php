<?php

/**
 * File Uploads Based on Matt Thomsom patch 0.83b
 *
 *
 * @package admin
 */


session_start();
header("Cache-control: private");
include('config.php');
include('functions.php');
include('admin_func.php');

// Check admin login.  
if(!check_admin_login('','')) {
    header("Location: ./admin.php?action=error");
    die;
}

//Headings.
include($TEMPLATE_DIR . 'face_begin.php');
heading(_y("Upload Files"));
print_main_menubar();
print_admin_taskbar();

//First of all, check if upload function is available;


//change variable names (register globals)
$step=$_GET['step'];
//Dir can be set either using get or post
$dir=$_POST['dir'];
if (($dir=='')&& ($_GET['dir']!='')) $dir=$_GET['dir'];

if(strpos($dir,'.')!== false) {
    error(_y('Dots in directory names are not allowed'));   
}

if (!isset($step)) $step="1";

switch ($step) {


 case 'mkdir':
 case 'rmdir':
    
    if ($step=='mkdir') {

	if (mkdir($BASE_DIR . $dir, 0777)) {
	    msg(_y('Created directory: '). $dir);
	}
	else {
	    warning(_y('Could not create directory: '). $dir);
	    //Although we created the dir, we continue;
	}
    }
    else {
	if (rmdir($BASE_DIR . $dir)) 
	  msg(_y('Successfully removed directory: '). $dir);
	else 
	  warning(_y('Could not remove directory (not empty or failed permissions)'));
    }
    $step=1;
    $dir='';
    
 case 1: //First step is show the form where user has to select a dir
   //or create one.
   
   //Select or create dir.
   if (strlen($dir)!=0)
     msg(_y("Files will be uploaded to following directory: "). $dir);
   else {
     $I_CREATE_DIR=_y("Create directory");
     //Get al dirs
     echo <<<NEW_DIR_FORM
       <!-- NEW_DIR_FORM (upload.php) -->
       <h4>Create new directory:</h4>
       <form method="post" action="upload.php?step=mkdir">
       $BASE_DIR <input type="text" name="dir" value="">
       <input type="submit" value="$I_CREATE_DIR" class="formbutton" /> 
       </form>
       <!-- end NEW_DIR_FORM-->
NEW_DIR_FORM;
   }
   $I_RMDIR=_y("Remove dir <small>(if empty)</small>");
   if (!($all_dirs=get_all_dirs($BASE_DIR))) 
     error(_y("Could not get directory listing"));
   foreach ($all_dirs as $one_dir) 
     $D_DIR_LIST.=<<<DDL
       <li><input type="radio" name="dir" value="$one_dir" />$one_dir 
            [<a href="upload.php?step=rmdir&amp;dir=$one_dir">$I_RMDIR</a>]</li>
DDL;

   //Create HTMl
   for($i=0;$i<$MAX_UPLOADS;$i++){
     $D_UP_LIST.= <<<DUP
       <li><input type="file" name="uploaded_file$i" /></li>
DUP;

   }
   //i18n
   $I_SEL_DIR=_y('Select only <b>one</b> directory');
   $I_FILES=_y('Files to upload');
   $I_UPLOAD=_y('Upload files');
   echo <<<SEL_DIR_FORM
     <form method="post" enctype="multipart/form-data" 
     action="upload.php?step=2">
   $I_SEL_DIR:
     <ol>
     $D_DIR_LIST
     </ol>
     $I_FILES
     <ol>	 
     $D_UP_LIST
     </ol>
     <input type="submit" name="submit" value="$I_UPLOAD" class="formbutton">
     </form>
SEL_DIR_FORM;
     
   break;

 case 2: //Second and final step is 
 
  //echo "<pre>";print_r($_FILES);echo "</pre>"; //Debug-line
   //This check should be done using a JS ###
   if (!isset($dir) || (strlen($dir)==0)) 
     error("You must select a directory.");
 foreach ($_FILES as $upfile_info) {
     if (($file_name=$upfile_info['name'])!='') {
	 $file_name = stripslashes($file_name);
	 $file_name = str_replace("'","",$file_name);
	 $pi = pathinfo($file_name);
	 if (!in_array($pi['extension'],$IMAGE_EXT)) {
	     warning($file_name .  _y(" is not recognized as an image."));
	     continue;
	 }
     $dest_location=$BASE_DIR .$dir."/".$file_name;
     $copy = copy($upfile_info['tmp_name'], $dest_location);  
     
     if ($copy) {
       $type=$upfile_info['type'];
       $size=(int)($upfile_info['size']/1024);
       $MSG="<b>$dest_location</b> (mime: $type | size: $size KB)";
       msg(_y("Successfully uploaded file: ") . $MSG);
     }
     else {
       warning(_y("Could not copy file: "). "<b>$file_name</b>");
     }
   }//end if (filename..)
 }//end foreach
   

 
   break;
}
include($TEMPLATE_DIR . 'face_end.php');

?>
